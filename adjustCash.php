<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
    session_destroy();
    header("location:index.php");
}else{
    $userId = $_SESSION['userId'];

    require_once("class/connectPDO.php");
    require_once("class/functions.php");
    require_once("class/entry.php");

    $json['error'] = false;
    $json['errorMsg'] = "Sem Erros";
   
    try{
        $date = $_POST['date'];
        $debitId = 31;
        $creditId = $_POST['creditId'];
        $value = numberClearFormat($_POST['value']);
        $comments = "Ajuste de caixa";
        
        //DEBIT
        $sql = "SELECT SUM(debit) AS amount FROM entries WHERE account_id = $creditId";
        $con = $pdo->prepare($sql);
        
        if(!$con->execute()){
            throw new Exception("Debit sum error");
        }

        $row = $con->fetch(PDO::FETCH_ASSOC);
        $debit = $row['amount'];

        //CREDIT
        $sql = "SELECT SUM(credit) AS amount FROM entries WHERE account_id = $creditId";
        $con = $pdo->prepare($sql);
        
        if(!$con->execute()){
            throw new Exception("Credit sum error");
        }

        $row = $con->fetch(PDO::FETCH_ASSOC);
        $credit = $row['amount'];

        $amount = $debit - $credit;

        $difference = $amount - $value;
        
        $entry = new Entry($date, $debitId, $creditId, $difference, $comments, $userId);

        if($entry->getError()){
            throw new Exception($entry->getErrorMsg());
        }

    }catch(Exception $e){
        $json['error'] = true;
        $json['errorMsg'] = $e->getMessage();
    }

    $json = json_encode($json);
    echo $json;
    
}
?>