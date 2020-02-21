<?php
require_once("class/userAdd.php");
require_once("connectPDO.php");

$nome = "Larissa Honda";
$usuario = "larissa";
$senha = "1104";

$user = new UserAdd($nome, $usuario, $senha, $pdo);

echo $user->getStatus();
?>