<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
}else{
	$userId = $_SESSION['userId'];

	if(isset($_POST['month']) && isset($_POST['year'])){
		$month = $_POST['month'];
		$year = $_POST['year'];
	}
	else{
		$month = date('m');
		$year = date('Y');
	}

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
    <link href="css/receitas.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="js/global.js"></script>
	<script src="js/receitas.js"></script>
	
	<title>Receitas</title>
</head>

<body>

<?php
require_once("connectPDO.php");
require_once("class/functions.php");
require_once("notifica.php");
require_once("menu.php");

?>

<header class="header">
    Receitas
</header>

<nav class="tools">
    <ul>
	<li>Mês: 
		<select id="month">
			<?php
				for($x=1; $x<=12; $x++){
					$x = ($x < 10) ? '0'.$x : $x;
					$selected = ($x == $month) ? 'selected' : '';
					echo"
						<option value='$x' $selected>$x</option>
					";
				}
			?>
		</select>
		Ano: <input type="number" id='year' value="<?php echo $year; ?>"/>
	</li>
        <li onClick="cx('cxAdd', 'addValor')">Adicionar</li>
    </ul>
</nav>

<section class="conteudo">
	<div>
		<ul>
			<li>Dia</li>
			<li>Turno</li>
			<li>Hrs Extras</li>
			<li>Valor Bruto</li>
		</ul>
		<?php
			$month = '02';
			$year = '2019';

			$sql = "SELECT * FROM receitas WHERE MONTH(dat) = :m AND YEAR(dat) = :y AND usuario = $userId";
			//$sql = "SELECT * FROM receitas WHERE MONTH(dat) = '$month' AND YEAR(dat) = '$year' AND usuario = $userId";
			$con = $pdo->prepare($sql);
			$con->bindValue(":m", $month, PDO::PARAM_STR);
			$con->bindValue(":y", $year, PDO::PARAM_INT);
			$con->execute();
			$list = $con->fetchAll(PDO::FETCH_OBJ);

			$totalDias = 0;
			$totalNoites = 0;
			$totalHExtras = 0;

			foreach($list as $rows){
				if($rows->turno == 1){
					$valorBruto=($rows->he*1420*1.3)+((7+50/60)*1420);
				}elseif($rows->turno == 2){
					$valorBruto=($rows->he*1420*1.3)+((7+50/60)*1420)+(6*1420*0.3);
					$totalNoites++;
				}

				$totalDias++;
				$totalHExtras += $rows->he;

				$turno = ($rows->turno == 1) ? "Dia" : "Noite";

				echo"
					<ul>
						<li>$rows->dat</li>
						<li>$turno</li>
						<li>$rows->he</li>
						<li>".number($valorBruto)."</li>
					</ul>
				";
			}
		?>
	</div>

	<div>
		<ul>
			<li>Dias Trabalhados:</li>
			<li>Horas Extras:</li>
			<li>Adicional Noturno:</li>
			<li>Total Bruto:</li>
			<li>Seguro Saúde</li>
			<li>Aposentadoria:</li>
			<li>Seguro Desemprego:</li>
			<li>Imposto Renda:</li>
			<li>Total Descontos:</li>
			<li>Total Líquido:</li>
		</ul>
	</div>
</section>

</body>

<?php
}
?>