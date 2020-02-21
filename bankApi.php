<?php

session_start();
require_once("class/connectPDO.php");
require_once("class/functions.php");
require_once("class/entry.php");
require_once("class/deleteEntry.php");

if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
} else{
    $userId = $_SESSION['userId'];

    $json['error'] = false;
    $json['errorMsg'] = "Sem Erros";

    $action = $_POST['action'];
    $accountId = $_POST['accountId'];

    
    function load(){
        global $pdo, $json, $accountId;

        try{
            $sql = "SELECT account FROM accounts WHERE id = :id";
            $con = $pdo->prepare($sql);
            $con->bindValue("id", $accountId, PDO::PARAM_INT);
            
            if(!$con->execute()){
                throw new Exception("Account query error");
            }

            $row = $con->fetchObject();

            $json['account'] = $row->account;


            $json['list'] = "
                <ul>
                    <li>Data</li>
                    <li>Débito</li>
                    <li>Crédito</li>
                    <li>Saldo</li>
                    <li>Obs</li>
                    <li></li>
                </ul>
            ";

            $sql = "SELECT SUM(`debit`) as amount FROM `entries` WHERE account_id = $accountId";
            $con = $pdo->prepare($sql);
            
            if(!$con->execute()){
                throw new Exception("Debit query error");
            }

            $result = $con->fetchObject();
            $debit = $result->amount;
            
            $sql = "SELECT SUM(`credit`) as amount FROM `entries` WHERE account_id = $accountId";
            $con = $pdo->prepare($sql);
            
            if(!$con->execute()){
                throw new Exception("Credit query error");
            }

            $result = $con->fetchObject();
            $credit = $result->amount;

            $amount = $debit - $credit;

            $sql = "SELECT * FROM entries WHERE account_id = $accountId ORDER BY `date` DESC, id DESC";
            $con = $pdo->prepare($sql);
            
            if(!$con->execute()){
                throw new Exception("Entries query error");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);

            $debitTT = 0;
            $creditTT = 0;
            $data = "";
            foreach($list as $rows){
                $data .= "
                    <ul>
                        <li>$rows->date</li>
                        <li>".number($rows->debit)."</li>
                        <li>".number($rows->credit)."</li>
                        <li>".number($amount)."</li>
                        <li>$rows->comments</li>
                        <li>
                            <button onclick='del($rows->bind)'>
                                <img src='templates/trash.png' />
                            </button>
                        </li>
                    </ul>
                ";

                $amount = $amount - $rows->debit + $rows->credit;
                $debitTT += $rows->debit;
                $creditTT += $rows->credit;
                
            }

            $amount2 = number($debitTT - $creditTT);

            $json['list'] .= $data;
            $json['amount'] = $amount2;


            withdrawDebit();
            cardDebit();
            depositCredit();
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }


    }


    function withdrawDebit(){
        global $pdo, $json, $accountId, $userId;

        try{
            $sql = "SELECT * FROM accounts WHERE `group` = 'Ativo' AND `subgroup` = 'Circulante' AND account LIKE '%Caixa%' ORDER BY account";
            $con = $pdo->prepare($sql);
            
            if(!$con->execute()){
                throw new Exception("Withdraw Debit query error");
            }
            
            $list = $con->fetchAll(PDO::FETCH_OBJ);
            $data = "";

            foreach($list as $rows){
                if($userId == 1){
                    $selected = ($rows->id == 25) ? "selected" : "";
                }elseif($userId == 2){
                    $selected = ($rows->id == 26) ? "selected" : "";
                }else{
                    $selected = "";
                }
                
                $data .= "
                    <option value='$rows->id' $selected>$rows->account</option>
                ";
            }

            $json['withdrawDebit'] = $data;
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
    }


    function cardDebit(){
        global $pdo, $json, $accountId;

        try{
            $sql = "SELECT * FROM accounts WHERE `group` = 'Contas de Resultado' AND `subGroup` = 'Despesas' ORDER BY account";
            $con = $pdo->prepare($sql);
            
            if(!$con->execute()){
                throw new Exception("Card Debit query error");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);
            $data = "";

            foreach($list as $rows){
                $selected = ($rows->account == "Restaurante") ? "selected" : "";
                $data .= "
                    <option value='$rows->id' $selected>$rows->account</option>
                ";
            }

            $json['cardDebit'] = $data;
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
    }
    
    
    function depositCredit(){
        global $pdo, $json, $accountId;

        try{
            $sql = "SELECT * FROM accounts WHERE `group` = 'Contas de Resultado' AND `subGroup` = 'Receitas' ORDER BY account";
            $con = $pdo->prepare($sql);
            
            if(!$con->execute()){
                throw new Exception("Deposit Credit query error");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);
            $data = "";

            foreach($list as $rows){
                $selected = ($accountId == 21 && $rows->id == 27 || $accountId == 23 && $rows->id == 27) ? "selected" : "";
                $data .= "
                    <option value='$rows->id' $selected>$rows->account</option>
                ";
            }

            $json['depositCredit'] = $data;
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
    }
    

    function delete(){
        global $json;

        $bind = $_POST['bind'];

        $del = new DeleteEntry($bind);

        if($del->getError()){
            $json['error'] = true;
            $json['errorMsg'] = "Delete error";
        }
    }

    
    function add(){
        global $json, $userId;

        $date = $_POST['date'];
        $debitId = $_POST['debitId'];
        $creditId = $_POST['creditId'];
        $value = numberClearFormat($_POST['value']);

        $tariffId = 38;
        $tariff = ($_POST['tariff'] == "") ? 0 : numberClearFormat($_POST['tariff']);
        $comments = $_POST['comments'];
        
        try{
            $entry = new Entry($date, $debitId, $creditId, $value, $comments, $userId);
            if($entry->getError()){
                throw new Exception("Add error");
            }

            if($tariff > 0){
                $entry = new Entry($date, $tariffId, $creditId, $tariff, $comments, $userId);
                if($entry->getError()){
                    throw new Exception("Add tariff error");
                }
            }
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
    }


    switch($action){
        case "del":
            delete();
        break;

        case "add":
            add();
        break;
    }


    load();


    $json = json_encode($json);
    echo $json;

}
