<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
}else{
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
    <link href="css/balance.css" rel="stylesheet" type="text/css"/>
	<script src="js/global.js"></script>
	<script src="js/balance.js" defer></script>
	
	<title>Balanço</title>
</head>

<body>

<?php
require_once("notification.php");
require_once("menu.php");
?>


<section class="layer">

    <div class="box" id="boxError"></div>

</section>

<header class="header">
	Balanço Patrimonial
</header>

<nav class="tools">
	<form action="" method="post" id="formBalance" onsubmit="return false">
	<ul>
		<li>Mês: 
			<select id="month" name="month">
				<option value="1">01</option>
				<option value="2">02</option>
				<option value="3">03</option>
				<option value="4">04</option>
				<option value="5">05</option>
				<option value="6">06</option>
				<option value="7">07</option>
				<option value="8">08</option>
				<option value="9">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
			</select>
			Ano: <input type="number" id='year' name="year" value=""/>
		</li>
	</ul>
	</form>
</nav>


<section class="container">
	<h1>Balanço Patrimonial</h1>

	<div>
		<header>
			<ul>
				<li class="red">Ativo</li>
				<li class="red" id="assetsAmount">0</li>
				<li>Circulante</li>
				<li id="currentAssetsAmount">0</li>
			</ul>
		</header>
		<ul class="data" id="currentAssetsData">

		</ul>
		
		<header>
			<ul>
				<li>Realizável a Longo Prazo</li>
				<li id="longTermAmount">0</li>
			</ul>
		</header>
		<ul class="data" id="longTermData">
			
		</ul>
		
		<header>
			<ul>
				<li>Permanente</li>
				<li id="permanentAmount">0</li>
			</ul>
		</header>
		<ul class="data" id="permanentData">

		</ul>
	</div>


	<div>
		<header>
			<ul>
				<li class="red">Passivo</li>
				<li class="red" id="liabilitiesAmount">0</li>
				<li>Circulante</li>
				<li id="currentLiabilitiesAmount">0</li>
			</ul>
		</header>

		<ul class="data" id="currentLiabilitiesData">
			
		</ul>
		
		<header>
			<ul>
				<li>Exigível a Longo Prazo</li>
				<li id="longTermLiabilitiesAmount">0</li>
			</ul>
		</header>

		<ul class="data" id="longTermLiabilitiesData">
			
		</ul>
		
		<header>
			<ul>
				<li class="red">Patrimônio Líquido</li>
				<li class="red" id="equityAmount">0</li>
			</ul>
		</header>

		<ul class="data" id="equityData">
			
		</ul>
	</div>
	
    <h1 style="margin-top: 30px;">Demonstração do Resultado do Exercício</h1>
    <div>
		
		<header>
			<ul>
				<li class="red">Receitas</li>
				<li class="red" id="revenuesAmount">0</li>
			</ul>
		</header>

		<ul class="data" id="revenuesData">
			
		</ul>

		<header>
			<ul>
				<li class="red">Despesas</li>
				<li class="red" id="expensesAmount">0</li>
			</ul>
		</header>
		<ul class="data" id="expensesData">

		</ul>
		
		<header>
			<ul>
				<li class="red">Lucro/Prejuizo</li>
				<li class="red" id="netIncome">0</li>
			</ul>
		</header>
	
	</div>
    
</section>

</body>
</html>
<?php
}
?>