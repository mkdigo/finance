<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
}else{
	if(!isset($_GET['id'])){
        header("location:balance.php");
    }else{
        $userId = $_SESSION['userId'];
        $accountId = $_GET['id'];


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
    <link href="css/bank.css" rel="stylesheet" type="text/css"/>
	<script src="js/global.js"></script>
	<script src="js/bank.js" defer></script>

<title>Banco</title>
</head>

<body>

<?php
require_once("class/connectPDO.php");
require_once("class/functions.php");
require_once("notification.php");
require_once("menu.php");

?>

<header class="header">
    Extrato Bancário
</header>

<section class="layer">

    <div class="box" id="boxError"></div>

    <div class="box" id="withdraw">
        <form action="" method="post" id="formWithdraw" onsubmit="return false">
            <h1>Saque</h1>
            <ul>
                <li>
                    <label for="withdrawDate">Data:</label>
                    <input type="date" name="date" id="withdrawDate"/>
                </li>
                <li>
                    <label for="withdrawDebitId">Destino:</label>
                    <select name="debitId" id="withdrawDebitId">
                    
                    </select>
                </li>
                <li>
                    <label for="withdrawValue">Valor:</label>
                    <input type="text" name="value" id="withdrawValue" onkeypress="return num(event);" onkeyup="maskVal('withdrawValue')"/>
                </li>
                <li>
                    <label for="withdrawTariff">Tarifa:</label>
                    <input type="text" name="tariff" id="withdrawTariff" onkeypress="return num(event);" onkeyup="maskVal('withdrawTariff')"/>
                </li>
                <li>
                    <label for="withdrawComments">Obs:</label>
                    <input type="text" name="comments" id="withdrawComments"/>
                </li>
                <li>
                    <button type="button" id="withdrawConfirm">Confirmar</button>
                    <button type="button" class="close">Cancelar</button>
                </li>
            </ul>
        </form>
    </div>

    <div class="box" id="card">
        <form action="" method="post" id="formCard" onsubmit="return false">
            <h1>Cartão</h1>
            <ul>
                <li>
                    <label for="cardDate">Data:</label>
                    <input type="date" name="date" id="cardDate"/>
                </li>
                <li>
                    <label for="cardDebitId">Destino:</label>
                    <select name="debitId" id="cardDebitId">
                    
                    </select>
                </li>
                <li>
                    <label for="cardValue">Valor:</label>
                    <input type="text" name="value" id="cardValue" onkeypress="return num(event);" onkeyup="maskVal('cardValue')"/>
                </li>
                <li>
                    <label for="cardTariff">Tarifa:</label>
                    <input type="text" name="tariff" id="cardTariff" onkeypress="return num(event);" onkeyup="maskVal('cardTariff')"/>
                </li>
                <li>
                    <label for="cardComments">Obs:</label>
                    <input type="text" name="comments" id="cardComments"/>
                </li>
                <li>
                    <button type="button" id="cardConfirm">Confirmar</button>
                    <button type="button" class="close">Cancelar</button>
                </li>
            </ul>
        </form>
    </div>

    <div class="box" id="deposit">
        <form action="" method="post" id="formDeposit" onsubmit="return false">
            <h1>Depósito</h1>
            <ul>
                <li>
                    <label for="depositDate">Data:</label>
                    <input type="date" name="date" id="depositDate"/>
                </li>
                <li>
                    <label for="depositCreditId">Destino:</label>
                    <select name="creditId" id="depositCreditId">
                    
                    </select>
                </li>
                <li>
                    <label for="depositValue">Valor:</label>
                    <input type="text" name="value" id="depositValue" onkeypress="return num(event);" onkeyup="maskVal('depositValue')"/>
                </li>
                <li>
                    <label for="depositTariff">Tarifa:</label>
                    <input type="text" name="tariff" id="depositTariff" onkeypress="return num(event);" onkeyup="maskVal('depositTariff')"/>
                </li>
                <li>
                    <label for="depositComments">Obs:</label>
                    <input type="text" name="comments" id="depositComments"/>
                </li>
                <li>
                    <button type="button" id="depositConfirm">Confirmar</button>
                    <button type="button" class="close">Cancelar</button>
                </li>
            </ul>
        </form>
    </div>

</section>


 <nav class="tools">
    <ul>
        <li class="amount">Saldo: <span id="amount"></span></li>
        <li onClick="box('withdraw', 'withdrawValue');"><img src="templates/saque.png" width="35"/></li>
        <li onClick="box('card', 'cardValue');"><img src="templates/cartao.png" width="30"/></li>
        <li onClick="box('deposit', 'depositValue');"><img src="templates/deposito.png" width="35"/></li>
    </ul>
</nav>


<section class="container flexUl">
    
</section>

</body>
</html>

<?php
	}
}
?>