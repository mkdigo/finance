<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
} else{
    $user = $_SESSION['user'];


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
    <link href="css/entries.css" rel="stylesheet" type="text/css"/>
	<script src="js/global.js"></script>
	<script src="js/entries.js" defer></script>

<title>Lançamentos</title>
</head>

<body>

<?php
require_once("notification.php");
require_once("menu.php");
?>

<header class="header">
    Lançamentos
</header>

<section class="layer">

    <div class="box" id="boxError"></div>

    <div class="box" id="boxDelete">
        <h1>Confirmar Exclusão</h1>
        <ul>
            <li>Tem certeza que deseja excluir?</li>
            <li>
                <button id="deleteConfirm">Confirmar</button>
                <button class="close">Cancelar</button>
            </li>
        </ul>
    </div>

    <div class="box" id="boxExpenses">
        <h1>Lançar Despesas</h1>
        <form action="" onsubmit="return false" id="expensesForm">
            <ul>
                <li>
                    <label for="expensesDate">Data:</label>
                    <input type="date" name="date" id="expensesDate"/>
                </li>
                <li>
                    <label for="expensesDebitId">Despesa:</label>
                    <select name="debitId" id="expensesDebitId">
                        
                    </select>
                </li>
                <li>
                    <label for="expensesCreditId">Forma Pag:</label>
                    <select name="creditId" id="expensesCreditId">
    
                    </select>
                </li>
                <li>
                    <label for="expensesValue">Valor:</label>
                    <input type="text" name="value" id="expensesValue" onkeypress="return num()" onkeyup="maskVal('expensesValue')"/>
                </li>
                <li>
                    <label for="expensesComments">Obs:</label>
                    <input type="text" name="comments" id="expensesComments">
                </li>
                <li>
                    <button type="button" id="expensesConfirm">Confirmar</button>
                    <button type="button" class="close">Cancelar</button>
                </li>
            </ul>
        </form>
    </div>

    <div class="box" id="boxEntry">
        <h1>Lançar</h1>
        <form action="" onsubmit="return false" id="entryForm">
        <ul>
            <li>
                <label for="entryDate">Data:</label>
                <input type="date" name="date" id="entryDate"/>
            </li>
            <li>
                <label for="entryDebitId">Debito:</label>
                <select name="debitId" id="entryDebitId">
                    
                </select>
            </li>
            <li>
                <label for="entryCreditId">Crédito:</label>
                <select name="creditId" id="entryCreditId">

                </select>
            </li>
            <li>
                <label for="entryValue">Valor:</label>
                <input type="text" name="value" id="entryValue" onkeypress="return num()" onkeyup="maskVal('entryValue')"/>
            </li>
            <li>
                <label for="entryComments">Obs:</label>
                <input type="text" name="comments" id="entryComments">
            </li>
            <li>
                <button type="button" id="entryConfirm">Confirmar</button>
                <button type="button" class="close">Cancelar</button>
            </li>
        </ul>
        </form>
    </div>

    <div class="box" id="boxRevenues">
        <h1>Lançar Receitas</h1>
        <form action="" onsubmit="return false" id="revenuesForm">
        <ul>
            <li>
                <label for="revenuesDate">Data:</label>
                <input type="date" name="date" id="revenuesDate"/>
            </li>
            <li>
                <label for="revenuesDebitId">Entrada:</label>
                <select name="debitId" id="revenuesDebitId">
                    
                </select>
            </li>
            <li>
                <label for="revenuesCreditId">Receita:</label>
                <select name="creditId" id="revenuesCreditId">

                </select>
            </li>
            <li>
                <label for="revenuesValue">Valor:</label>
                <input type="text" name="value" id="revenuesValue" onkeypress="return num()" onkeyup="maskVal('revenuesValue')"/>
            </li>
            <li>
                <label for="revenuesComments">Obs:</label>
                <input type="text" name="comments" id="revenuesComments">
            </li>
            <li>
                <button type="button" id="revenuesConfirm">Confirmar</button>
                <button type="button" class="close">Cancelar</button>
            </li>
        </ul>
        </form>
    </div>

</section>

<nav class="tools">
    <ul>
        <li id="toolsExpenses">Despesas</li>
        <li id="toolsEntries">Geral</li>
        <li id="toolsRevenues">Receitas</li>
    </ul>
</nav>

<section class="container flexUl">

</section>

</body>
<?php
 }
 ?>