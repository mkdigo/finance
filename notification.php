<?php
require_once("class/connectPDO.php");

$pagar_ano = date('Y');
$pagar_mes = date('m');
$pagar_dia = date('d');

//vencidas
$sql = "SELECT * FROM payments WHERE due_date < CURRENT_DATE() AND payment IS NULL";
$con = $pdo->prepare($sql);
$con->execute();
$venc = $con->rowCount();


//venc proximo
$sql = "SELECT * FROM payments WHERE due_date BETWEEN CURRENT_DATE() AND CURRENT_DATE()+7 AND payment IS NULL";
$con = $pdo->prepare($sql);
$con->execute();
$prox = $con->rowCount();

$qtde = $venc + $prox;

if($venc == 1){$msg="$venc conta vencida e";}elseif($venc > 1){$msg="$venc contas vencidas e";}else{$msg="";}
if($prox == 1){$msg="Há $msg $prox conta com vencimento próximo!";}else{$msg="Há $msg $prox contas com vencimento próximo!";}

if($qtde > 0){
	echo"
	<div id='bell' onClick=\"notification('$msg')\">
		<div>$qtde</div>
		<img src='templates/bell.png'/>
	</div>
	";
}
?>