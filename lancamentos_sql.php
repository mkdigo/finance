<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a" || !isset($_POST['acao'])){
    session_destroy();
    header("location:index.php");
}else{

    $userId = $_SESSION['userId'];
    require_once("connectPDO.php");
    require_once("class/functions.php");
    
    $acao = $_POST['acao'];

    if($acao == "excluir"){
        $amarracao = $_POST['amarracao'];
        deleteLancamento($amarracao, $pdo);
    }else{
        $dat = $_POST['dat'];
        $debitoId = $_POST['debitoId'];
        $creditoId = $_POST['creditId'];
        $valor = $_POST['valor'];
        $obs = $_POST['obs'];
        lancar($dat, $debitoId, $creditoId, $valor, $obs, $pdo, $userId);
    }

    echo"
        <h1>Lançamentos</h1>

        <ul>
            <li>Data</li>
            <li>Conta</li>
            <li>Débito</li>
            <li>Crédito</li>
            <li>Obs</li>
        </ul>
    ";

    //LISTAR TABELA
    $sql = "SELECT * FROM lancamentos ORDER BY dat DESC, amarracao DESC, credito, conta";
    $con = $pdo->prepare($sql);
    $con->execute();
    $list = $con->fetchAll(PDO::FETCH_OBJ);

    foreach($list as $rows){
        $sql2 = "SELECT conta FROM contas WHERE id = $rows->conta";
        $con2 = $pdo->prepare($sql2);
        $con2->execute();
        $list2 = $con2->fetch(PDO::FETCH_ASSOC);
        echo"
            <ul>
                <li>$rows->dat</li>
                <li>".$list2['conta']."</li>
                <li>".number($rows->debito)."</li>
                <li>".number($rows->credito)."</li>
                <li>$rows->obs <img src='templates/excluir.png' onclick='cxExcluir($rows->amarracao)'/></li>
            </ul>
        ";
    }

}
        
?>