<?php

session_start();

if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
}else{
	require_once("connect.php");
	require_once("connectPDO.php");
	$usuario = $_SESSION['userId'];

    if(!isset($_POST['acao'])){echo"<script>history.go(-1);</script>";}else{

        $acao = $_POST['acao'];

        if ($acao == "addConta"){
            $conta = $_POST['conta'];
            $grupo = $_POST['grupo'];
            $subgrupo = $_POST['subgrupo'];
            $sql = "INSERT INTO contas(conta,grupo,subgrupo) VALUES(:conta,:grupo,:subgrupo);";
            $con = $pdo->prepare($sql);
            $con->bindValue(":conta",$conta,PDO::PARAM_STR);
            $con->bindValue(":grupo",$grupo,PDO::PARAM_STR);
            $con->bindValue(":subgrupo",$subgrupo,PDO::PARAM_STR);
            $con->execute();
            header("location:contas.php");
        }

    }
    
}
?>