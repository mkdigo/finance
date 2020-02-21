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
    $userId = $_SESSION['user'];

    $json['error'] = false;
    $json['errorMsg'] = "Sem Erros";

    $action = $_POST['action'];


    function loadData(){
        global $json, $pdo;

        try{
            $json['load'] = "
                <ul>
                    <li>Data</li>
                    <li>Conta</li>
                    <li>Débito</li>
                    <li>Crédito</li>
                    <li>Obs</li>
                    <li></li>
                </ul>
            ";

            $sql = "SELECT * FROM entries ORDER BY `date` DESC, bind DESC, credit, account_id LIMIT 500";
            $con = $pdo->prepare($sql);

            if(!$con->execute()){
                throw new Exception("Entries query error");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);
        
            foreach($list as $rows){
                $sql2 = "SELECT account FROM accounts WHERE id = $rows->account_id";
                $con2 = $pdo->prepare($sql2);
                if(!$con2->execute()){
                    $json['error'] = $error[1];
                }
                $rows2 = $con2->fetch(PDO::FETCH_ASSOC);

                $json['load'] .= "
                    <ul>
                        <li>$rows->date</li>
                        <li>".$rows2['account']."</li>
                        <li>".number($rows->debit)."</li>
                        <li>".number($rows->credit)."</li>
                        <li>$rows->comments</li>
                        <li>
                            <button onclick='del($rows->bind)'>
                                <img src='templates/trash.png' />
                            </button>
                        </li>
                    </ul>
                ";
            }
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
    }

    
    function loadAccounts(){
        global $json, $pdo;

        try{
            //ALL ACCOUNTS
            $sql = "SELECT * FROM accounts ORDER BY account";
            $con = $pdo->prepare($sql);
            if(!$con->execute()){
                throw new Exception("accounts query error");
            }else{
                $list = $con->fetchAll(PDO::FETCH_OBJ);
                $accounts = "<option value=''></option>";
                foreach($list as $rows){
                    $accounts .= "<option value='$rows->id'>$rows->account</option>";
                }
                $json['accounts'] = $accounts;
            }

            //EXPENSES
            $sql = "SELECT * FROM accounts WHERE subgroup = 'Despesas' ORDER BY account";
            $con = $pdo->prepare($sql);
            if(!$con->execute()){
                throw new Exception("Expenses query error");
            }else{
                $list = $con->fetchAll(PDO::FETCH_OBJ);
                $expenses = "<option value=''></option>";
                foreach($list as $rows){
                    $expenses .= "<option value='$rows->id'>$rows->account</option>";
                }
                $json['expenses'] = $expenses;
            }

            //CURRENT ASSETS
            $sql = "SELECT * FROM accounts WHERE `group` = 'Ativo' AND subgroup = 'Circulante' ORDER BY account";
            $con = $pdo->prepare($sql);
            if(!$con->execute()){
                throw new Exception("Current Assets query error");
            }else{
                $list = $con->fetchAll(PDO::FETCH_OBJ);
                $currentAssets = "<option value=''></option>";
                foreach($list as $rows){
                    $selected = ($rows->id == 25) ? "selected" : "";
                    $currentAssets .= "<option value='$rows->id' $selected>$rows->account</option>";
                }
                $json["currentAssets"] = $currentAssets;
            }

            //BANKS
            $sql = "SELECT * FROM accounts WHERE account LIKE '%banco%' ORDER BY account";
            $con = $pdo->prepare($sql);
            if(!$con->execute()){
                throw new Exception("Banks query error");
            }else{
                $list = $con->fetchAll(PDO::FETCH_OBJ);
                $banks = "<option value=''></option>";
                foreach($list as $rows){
                    $banks .= "<option value='$rows->id'>$rows->account</option>";
                }
                $json['banks'] = $banks;
            }

            //REVENUES
            $sql = "SELECT * FROM accounts WHERE subgroup = 'Receitas'  ORDER BY account";
            $con = $pdo->prepare($sql);
            if(!$con->execute()){
                throw new Exception("Revenues query error");
            }else{
                $list = $con->fetchAll(PDO::FETCH_OBJ);
                $revenues = "<option value=''></option>";
                foreach($list as $rows){
                    $revenues .= "<option value='$rows->id'>$rows->account</option>";
                }
                $json['revenues'] = $revenues;
            }
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
        $value = $_POST['value'];
        $comments = $_POST['comments'];

        $entry = new Entry($date, $debitId, $creditId, $value, $comments, $userId);
        
        if($entry->getError()){
            $json['error'] = true;
            $json['errorMsg'] = "Add error";
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

    loadData();
    loadAccounts();

    $json = json_encode($json);
    echo $json;

}

?>