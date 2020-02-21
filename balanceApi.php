<?php

session_start();
require_once("class/connectPDO.php");
require_once("class/functions.php");
require_once("class/balance.php");

if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
} else{
    $userId = $_SESSION['user'];

    $error[0] = array("number" => 0, "msg" => "Sem erros.");
    $error[1] = array("number" => 1, "msg" => "Erro!");

    $json['error'] = $error[0];


    function load(){
        global $json;

        $month = $_POST['month'];
        $year = $_POST['year'];

        //INCOME STATEMENT

        //REVENUES
        $balance = new Dre('Receitas', $month, $year);
        $json['revenuesData'] = $balance->getPrint();
        $revenuesAmount = $balance->getAmount();
        $json['revenuesAmount'] = number($revenuesAmount);

        //EXPENSES
        $balance = new Dre('Despesas', $month, $year);
        $json['expensesData'] = $balance->getPrint();
        $expensesAmount = $balance->getAmount();
        $json['expensesAmount'] = number($expensesAmount);


        $netIncome = $revenuesAmount - $expensesAmount;
        $json['netIncome'] = number($netIncome);


        //BALANCE SHEET

        //CURRENT ASSETS
        $balance = new Balance('Ativo', 'Circulante', $month, $year);
        $json['currentAssetsData'] = $balance->getPrint();
        $currentAssetsAmount = $balance->getAmount();
        $json['currentAssetsAmount'] = number($currentAssetsAmount);

        //LONG TERM ASSETS
        $balance = new Balance('Ativo', 'Realizável a longo prazo', $month, $year);
        $json['longTermData'] = $balance->getPrint();
        $longTermAmount = $balance->getAmount();
        $json['longTermAmount'] = number($longTermAmount);

        //PERMANENT
        $balance = new Balance('Ativo', 'Permanente', $month, $year);
        $json['permanentData'] = $balance->getPrint();
        $permanentAmount = $balance->getAmount();
        $json['permanentAmount'] = number($permanentAmount);

        $assetsAmount = $currentAssetsAmount + $longTermAmount + $permanentAmount;
        $json['assetsAmount'] = number($assetsAmount);


        //CURRENT LIABILITIES
        $balance = new Balance('Passivo', 'Circulante', $month, $year);
        $json['currentLiabilitiesData'] = $balance->getPrint();
        $currentLiabilitiesAmount = $balance->getAmount();
        $json['currentLiabilitiesAmount'] = number($currentLiabilitiesAmount);
        
        //LONG TERM LIABILITIES
        $balance = new Balance('Passivo', 'Exigível a longo prazo', $month, $year);
        $json['longTermLiabilitiesData'] = $balance->getPrint();
        $longTermLiabilitiesAmount = $balance->getAmount();
        $json['longTermLiabilitiesAmount'] = number($longTermLiabilitiesAmount);
        
        //EQUITY
        $balance = new Balance('Patrimônio Líquido', 'Patrimônio Líquido', $month, $year);
        $json['equityData'] = $balance->getPrint();

        $revenue = new Balance('Contas de Resultado', 'Receitas', $month, $year);
        $revenueValue = $revenue->getAmount();

        $expense = new Balance('Contas de Resultado', 'Despesas', $month, $year);
        $expenseValue = $expense->getAmount();

        $balanceNetIncome = $revenueValue - $expenseValue;

        $json['equityData'] .= "<li>Lucro/Prejuizo</li><li>".number($balanceNetIncome)."</li>";
        $equityAmount = $balance->getAmount() + $balanceNetIncome;
        $json['equityAmount'] = number($equityAmount);

        //LIABILITIES AMOUNT
        $liabilitiesAmount = $currentLiabilitiesAmount + $longTermLiabilitiesAmount + $equityAmount;
        $json['liabilitiesAmount'] = number($liabilitiesAmount);
    }


    load();

    $json = json_encode($json);
    echo $json;
}

?>