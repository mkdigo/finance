<?php

require_once("connectPDO.php");
$table = "receitas";

$sql = "SELECT * FROM $table";
$con = $pdo->prepare($sql);
$con->execute();
$list = $con->fetchAll(PDO::FETCH_OBJ);

foreach($list as $rows){
    $id = $rows->id;
    $ano = $rows->ano;
    $mes = $rows->mes;
    $mes = ($mes < 10) ? "0".$mes : $mes;
    $dia = $rows->dia;
    $dia = ($dia < 10) ? "0".$dia : $dia;
    $data = "$ano-$mes-$dia";
    echo "$data </br>";
    $sql2 = "UPDATE $table SET dat = '$data' WHERE id = $id";
    $con2 = $pdo->prepare($sql2);
    $con2->execute();
}

?>