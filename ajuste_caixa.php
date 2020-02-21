<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
    session_destroy();
    header("location:index.php");
}else{
    $userId = $_SESSION['userId'];

    require_once("connectPDO.php");
    require_once("class/functions.php");
    
    $dat = $_POST['dat'];
    $contaId = $_POST['contaId'];
    $valor = $_POST['valor'];
    
    //DEBITO
    $sql = "SELECT SUM(debito) AS saldo FROM lancamentos WHERE conta = $contaId";
    $con = $pdo->prepare($sql);
    $con->execute();
    $row = $con->fetch(PDO::FETCH_ASSOC);
    $debito = $row['saldo'];

    //CREDITO
    $sql = "SELECT SUM(credito) AS saldo FROM lancamentos WHERE conta = $contaId";
    $con = $pdo->prepare($sql);
    $con->execute();
    $row = $con->fetch(PDO::FETCH_ASSOC);
    $credito = $row['saldo'];
    $saldo = $debito - $credito;

    $diferenca = $saldo - $valor;
    
    lancar($dat, 31, $contaId, $diferenca, 'Ajuste de Caixa', $pdo, $userId);
}
?>