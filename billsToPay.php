<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
}else{

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="apple-touch-icon" sizes="114x114" href="templates/logo.png" />
    <link rel="icon" type="imagem/jpeg" href="templates/logo.png" />
    <link href="css/global.css" rel="stylesheet" type="text/css"/>
    <link href="css/billsToPay.css" rel="stylesheet" type="text/css"/>
	<script src="js/global.js"></script>
	<script src="js/billsToPay.js" defer></script>

<title>Contas a Pagar</title>
</head>

<body>

<?php
require_once("notification.php");
require_once("menu.php");
?>

<header class="header">
    Contas a Pagar
</header>

<section class="layer">

    <div class="box" id="boxError"></div>
    
    <div class="box" id="boxDel">
        <h1>Confirmar exclusão</h1>
        <ul>
            <li>
                <label for="">Conta:</label>
                <span id="delAccount"></span>
            </li>
            <li>
                <label for="">Valor:</label>
                <span id="delValue"></span>
            </li>
            <li>
                <label for="">Vencimento:</label>
                <span id="delDueDate"></span>
            </li>
            <li>
                <button type="button" id="delConfirm">Confirmar</button>
                <button type="button" class="close">Cancelar</button>
            </li>
        </ul>
    </div>
    
    <div class="box" id="boxPay">
        <h1>Baixa</h1>
        <form action="" id="payForm" onsubmit="return false">
            <ul>
                <li>
                    <label for="">Conta:</label>
                    <span id="payAccount"></span>
                </li>
                <li>
                    <label for="">Valor:</label>
                    <span id="payValue"></span>
                </li>
                <li>
                    <label for="">Vencimento:</label>
                    <span id="payDueDate"></span>
                </li>
                <li>
                    <label for="payDate">Pagamento:</label>
                    <input type="date" id="payDate" name="date" required>
                </li>
                <li>
                    <button type="button" id="payConfirm">Confirmar</button>
                    <button type="button" class="close">Cancelar</button>
                </li>
            </ul>
            <input type="hidden" id="payId" name="id" value="">
        </form>
    </div>

    <div class="box" id="boxAdd">
        <h1>Adicionar Conta</h1>

        <form action="" id="addForm" onsubmit="return false">
            <ul>
                <li>
                    <label for="addAccountId">Conta:</label>
                    <select id="addAccountId" name="accountId">
                        
                    </select>
                </li>
                <li>
                    <label for="addValue">Valor:</label>
                    <input type="text" id="addValue" name="value" onkeypress="return num()" onkeyup="maskVal('addValue')" />
                </li>
                <li>
                    <label for="addDueDate">Vencimento:</label>
                    <input type="date" id="addDueDate" name="dueDate" />
                </li>
                <li>
                    <label for="addComments">Obs:</label>
                    <input type="text" id="addComments" name="comments" />
                </li>
                <li>
                    <button type="button" id="addConfirm">Confirmar</button>
                    <button type="button" class="close">Cancelar</button>
                </li>
            </ul>
        </form>
	</div>

</section>

<nav class="tools">
    <ul>
		<li class="amount">Total: <span>0</span></li>
        <li onClick="box('boxAdd', 'addValue')">Adicionar</li>
    </ul>
</nav>

<section class="container flexUl">

</section>

<?php
}
?>