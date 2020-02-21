<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
}else{
	$usuario = $_SESSION['user'];


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
    <link href="css/contas.css?version=1" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="js/global.js"></script>
	

<script lang="javascrip">

$(document).ready(function(){
	$("#add").click(function(){
		$("#cxAdd").slideDown(500)
		$(".camada").fadeIn()
	})

	$("#addConfirmar").click(function(){
		var conta = $("#conta").val()
		if(conta == ""){
			$("#conta").css("background-color","#CC3");
			$("#conta").focus();
		}
		else{$("#formAdd").submit();}
	});
	
});

const page="add_conta"

</script>

<title>Contas</title>
</head>

<body>

<?php
	require_once("connectPDO.php");
	require_once("notifica.php");
	require_once("menu.php");	
?>

<header class="header">
	Adicionar Conta
</header>

<nav class="tools">
	<ul>
		<li id="add">Adicionar</li>
	</ul>
</nav>

<div class="cx" id="cxAdd">
	<h1>Adicionar conta</h1>

	<form action="sql.php" method="post" id="formAdd">
	<ul>
		<li><label for="conta">Conta:</label><input type="text" name="conta" id="conta" /></li>
		<li><label for="grupo">Grupo:</label>
			<select name="grupo" id="grupo">
            	<option value="Ativo">Ativo</option>
                <option value="Passivo">Passivo</option>
                <option value="Patrimônio Líquido">Patrimônio Líquido</option>
                <option value="Contas de Resultado">Contas de Resultado</option>
            </select>
		</li>
		<li><label for="subgrupo">Sub-Grupo:</label>
			<select name="subgrupo" id="subgrupo">
             	<option value="Capital Social">Capital Social</option>
                <option value="Circulante">Circulante</option>
                <option value="Despesas">Despesas</option>
                <option value="Exigível a longo prazo">Exigível a longo prazo</option>
                <option value="Lucros/Prejuizos acumulado">Lucros/Prejuizos acumulado</option>
                <option value="Permanente">Permanente</option>
                <option value="Realizável a longo prazo">"Realizável a longo prazo</option>
                <option value="Receitas">Receitas</option>
            </select>
		</li>
		<li>
			<div class="bt" id="addConfirmar">Confirmar</div>
			<div class="bt cancelar">Cancelar</div>
		</li>
	</ul>
		<input type="hidden" name="acao" value="addConta"/>
	</form>
</div>

<div class="camada"></div>

<section class="conteudo">
	<div>
		<ul>
			<li>Conta</li>
			<li>Grupo</li>
			<li>Sub-Grupo</li>
		</ul>
		<?php
			$sql = "SELECT * FROM contas ORDER BY conta";
			$con = $pdo->prepare($sql);
			$con->execute();
			$list = $con->fetchAll(PDO::FETCH_OBJ);
			foreach($list as $rows){
				echo"
					<ul>
						<li>$rows->conta</li>
						<li>$rows->grupo</li>
						<li>$rows->subgrupo</li>
					</ul>
				";
			}
		?>
	</div>
</section>



</body>
</html>

<?php
 }
 ?>