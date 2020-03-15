<?php

session_start();
require_once("class/connectPDO.php");
require_once("class/functions.php");

if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
} else{
    $userId = $_SESSION['userId'];

    $json['error'] = false;
    $json['errorMsg'] = "Sem Erros";

    $action = $_POST['action'];


    function load(){
        global $pdo, $json;

        try{
            $json['list'] = "
                <ul>
                    <li>Conta</li>
                    <li>Valor</li>
                    <li>Vencimento</li>
                    <li>Obs</li>
                    <li></li>
                </ul>
            ";
    
            $sql = "SELECT * FROM payments WHERE payment IS NULL ORDER BY due_date";
            $con = $pdo->prepare($sql);

            if(!$con->execute()){
                throw new Exception("Payments query error");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);
            
            $amount = 0;

            foreach($list as $rows){
                $sql2 = "SELECT account FROM accounts WHERE id = $rows->account_id";
                $con2 = $pdo->prepare($sql2);
                
                if(!$con2->execute()){
                    throw new Exception("Account query error");
                }

                $rows2 = $con2->fetchObject();

                $val = number($rows->value);

                $f = "onclick=\"pay($rows->id, '$rows2->account', '$val', '$rows->due_date')\"";

                $json['list'] .= "
                    <ul>
                        <li $f>$rows2->account</li>
                        <li $f>$val</li>
                        <li $f>$rows->due_date</li>
                        <li $f>$rows->comments</li>
                        <li>
                            <button type='button' onclick=\"del($rows->id, '$rows2->account', '$val', '$rows->due_date')\">
                                <img src='templates/trash.png'/>
                            </button>
                        </li>
                    </ul>
                ";
                
                $amount += $rows->value;
            }

            $json['amount'] = number($amount);

            accounts();

        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
    }


    function accounts(){
        global $pdo, $json;

        try{
            $sql = "SELECT id, account FROM accounts ORDER BY account";
            $con = $pdo->prepare($sql);
            
            if(!$con->execute()){
                throw new Exception("Acconts query error");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);

            $json['accounts'] = "";
            foreach($list as $rows){
                $json['accounts'] .= "
                    <option value='$rows->id'>$rows->account</option>
                ";
            }
            
        }catch(Exception $d){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage;
        }
    }

    
    function add(){
        global $pdo, $json;

        $accountId = $_POST['accountId'];
        $value = numberClearFormat($_POST['value']);
        $dueDate = $_POST['dueDate'];
        $comments = $_POST['comments'];

        $sql = "INSERT INTO payments (account_id, `value`, due_date, comments) VALUES(:accountId, :val, :dueDate, :comments)";
        $con = $pdo->prepare($sql);
        $con->bindValue(":accountId", $accountId, PDO::PARAM_INT);
        $con->bindValue(":val", $value, PDO::PARAM_INT);
        $con->bindValue(":dueDate", $dueDate, PDO::PARAM_STR);
        $con->bindValue(":comments", $comments, PDO::PARAM_STR);
        
        if(!$con->execute()){
            $json['error'] = true;
            $json['errorMsg'] = "Insert payment error";
        }
    }


    function pay(){
        global $pdo, $json;

        $id = $_POST['id'];
        $date = $_POST['date'];

        $sql = "UPDATE payments SET payment = :date WHERE id = :id";
        $con = $pdo->prepare($sql);
        $con->bindValue(":date", $date, PDO::PARAM_STR);
        $con->bindValue(":id", $id, PDO::PARAM_INT);
        
        if(!$con->execute()){
            $json['error'] = true;
            $json['errorMsg'] = "Payment error";
        }
        
    }


    function del(){
        global $pdo, $json;

        $id = $_POST['id'];
        
        $sql = "DELETE FROM payments WHERE id = :id";
        $con = $pdo->prepare($sql);
        $con->bindValue(":id", $id, PDO::PARAM_INT);

        if(!$con->execute()){
            $json['error'] = true;
            $json['errorMsg'] = "Delete error";
        }
    }
    

    switch($action){
        case "add":
            add();
        break;
        case "pay":
            pay();
        break;
        case "del":
            del();
        break;
    }

    load();

    $json = json_encode($json);
    echo $json;

}
?>