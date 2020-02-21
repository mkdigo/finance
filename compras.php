<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
}else{
	$userId = $_SESSION['userId'];

	
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
    <link href="css/compras.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="js/global.js"></script>
	<script src="js/compras.js"></script>
	
	<title>Lista de Compras</title>
</head>

<body>

<?php
require_once("connectPDO.php");
require_once("class/functions.php");
require_once("notifica.php");
require_once("menu.php");

?>

<header class="header">
    Lista de Compras
</header>

<nav class="tools">
    <ul>
        <li onClick="cx('cxAdd', 'addProduto')">Adicionar</li>
    </ul>
</nav>

<div class="cx" id="cxDel">
    <h1>Confirmar Exclusão</h1>
    <ul>
        <li style="text-align: center;">Tem certeza que deseja excluir?</li>
        <li>
            <div class="bt" onclick="del()">Confirmar</div>
            <div class="bt cancelar">Cancelar</div>
        </li>
    </ul>
</div>

<div class="cx" id="cxAdd">
	<h1>Adicionar Produto</h1>
	<ul>
		<li>
			<label for="addProduto">Produto:</label>
			<input type="text" id="addProduto" />
		</li>
		<li>
			<label for="addQtde">Quantidade:</label>
			<input type="text" id="addQtde" onkeypress="return soNum(event)" />
		</li>
		<li>
			<label for="addObs">Observação:</label>
			<input type="text" id="addObs" />
		</li>
		<li>
            <div class="bt" id="addConfirmar">Confirmar</div>
            <div class="bt cancelar">Cancelar</div>
        </li>
	</ul>
</div>

<div class="camada"></div>

<section class="conteudo">
	<div>
		<ul>
			<li>Produto</li>
			<li>Qtde</li>
			<li>Obs</li>
		</ul>
		<?php
			$sql = "SELECT * FROM compras ORDER BY produto";
			$con = $pdo->prepare($sql);
			$con->execute();
			$list = $con->fetchAll(PDO::FETCH_OBJ);
			foreach($list as $rows){
				echo"
				<ul>
					<li>$rows->produto</li>
					<li>$rows->qtde</li>
					<li>$rows->obs<img src='templates/excluir.png' onclick='cxDel($rows->id)'/></li>
				</ul>
				";
			}
		?>
	</div>
</section>


</body>

<?php
}
?>