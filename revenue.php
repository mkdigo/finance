<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	// session_destroy();
	// header("location:index.php");
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
    <link href="css/revenue.css" rel="stylesheet" type="text/css"/>
	<script src="js/global.js"></script>
	<script src="js/revenue.js" defer></script>

<title>Receitas</title>
</head>

<body>

<?php
require_once("notification.php");
require_once("menu.php");

?>

<header class="header">
    Receitas
</header>

<section class="layer">

    <div class="box" id="boxError"></div>

    <div class="box" id="boxAdd">
        <form action="" method="post" id="addForm" onsubmit="return false">
            <h1>Saque</h1>
            <ul>
                <li>
                    <label for="addDate">Data:</label>
                    <input type="date" name="date" id="addDate"/>
                </li>
                <li>
                    <label for="addShift">Turno:</label>
                    <select name="shift" id="addShift">
                        <option value="1">Dia</option>
                        <option value="2">Noite</option>
                        <option value="3">Dia (Folga)</option>
                        <option value="4">Noite (Folga)</option>
                    </select>
                </li>
                <li>
                    <label for="addOvertime">Horas Extras:</label>
                    <input type="number" name="overtime" id="addOvertime" step=".1" />
                </li>
                <li>
                    <button type="button" id="addConfirm">Confirmar</button>
                    <button type="button" class="close">Cancelar</button>
                </li>
            </ul>
        </form>
    </div>

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

</section>


 <nav class="tools">
    <ul>
        <li>Mês: 
            <select id="month">
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
            Ano: <input type="number" id='year' />
        </li>
        <li onClick="box('boxAdd', 'addOvertime')">Adicionar</li>
    </ul>
</nav>


<section class="container">
    <div class="flexUl">
        
    </div>

    <div class="amounts">
		<ul>
			<li>
                <label for="">Dias Trabalhados:</label>
                <span id="ttDays">0</span>
            </li>
			<li>
                <label for="">Horas Extras:</label>
                <span id="ttOvertime">0</span>
            </li>
			<li>
                <label for="">Adicional Noturno:</label>
                <span id="ttNights">0</span>
            </li>
			<li>
                <label for="">Total Bruto:</label>
                <span id="grossAmount">0</span>
            </li>
			<li>
                <label for="">Seguro Saúde</label>
                <span id="healthInsurance">0</span>
            </li>
			<li>
                <label for="">Aposentadoria:</label>
                <span id="retirement">0</span>
            </li>
			<li>
                <label for="">Seguro Desemprego:</label>
                <span id="unemploymentInsurance">0</span>
            </li>
			<li>
                <label for="">Imposto Renda:</label>
                <span id="incomeTax">0</span>
            </li>
			<li style="color: #B00">
                <label for="">Total Descontos:</label>
                <span id="ttDiscounts">0</span>
            </li>
			<li>
                <label for="">Total Líquido:</label>
                <span id="netValue">0</span>
            </li>
		</ul>
	</div>
</section>

</body>
</html>

<?php
}
?>