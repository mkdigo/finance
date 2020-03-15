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
    <link href="css/accounts.css?version=1" rel="stylesheet" type="text/css"/>
	<script src="js/global.js"></script>
	<script src="js/accounts.js" defer></script>
	
<title>Contas</title>
</head>

<body>

<?php
	require_once("notification.php");
	require_once("menu.php");	
?>

<header class="header">
	Adicionar Conta
</header>

<section class="layer">

	<div class="box" id="boxError"></div>

	<div class="box" id="boxAdd">
		<h1>Adicionar conta</h1>

		<form action="" method="post" id="addForm" onsubmit="return false">
			<ul>
				<li>
					<label for="addAccount">Conta:</label>
					<input type="text" name="account" id="addAccount" />
				</li>
				<li>
					<label for="addGroup">Grupo:</label>
					<select name="group" id="addGroup">
						<option value="Ativo">Ativo</option>
						<option value="Passivo">Passivo</option>
						<option value="Patrimônio Líquido">Patrimônio Líquido</option>
						<option value="Contas de Resultado">Contas de Resultado</option>
					</select>
				</li>
				<li><label for="addSubGroup">Sub-Grupo:</label>
					<select name="subGroup" id="addSubGroup">
						<option value='Circulante'>Circulante</option>
						<option value='Permanente'>Permanente</option>
						<option value='Realizável a longo prazo'>Realizável a longo prazo</option>
					</select>
				</li>
				<li>
					<button type="button" id="addConfirm">Confirmar</button>
					<button type="button" class="close">Cancelar</button>
				</li>
			</ul>
		</form>
	</div>

	<div class="box" id="boxEdit">
		<h1>Editar conta</h1>

		<form action="" method="post" id="editForm" onsubmit="return false">
			<ul>
				<li>
					<label for="editAccount">Conta:</label>
					<input type="text" name="account" id="editAccount" />
				</li>
				<li>
					<label for="editGroup">Grupo:</label>
					<select name="group" id="editGroup">
						<option value="Ativo">Ativo</option>
						<option value="Passivo">Passivo</option>
						<option value="Patrimônio Líquido">Patrimônio Líquido</option>
						<option value="Contas de Resultado">Contas de Resultado</option>
					</select>
				</li>
				<li><label for="editSubGroup">Sub-Grupo:</label>
					<select name="subGroup" id="editSubGroup">
						<option value='Circulante'>Circulante</option>
						<option value='Permanente'>Permanente</option>
						<option value='Realizável a longo prazo'>Realizável a longo prazo</option>
					</select>
				</li>
				<li>
					<button type="button" id="editConfirm">Confirmar</button>
					<button type="button" class="close">Cancelar</button>
				</li>
			</ul>
			<input type="hidden" name="accountId" value="" id="editAccountId" />
		</form>
	</div>

</section>


<nav class="tools">
	<ul>
		<li id="toolsAdd">Adicionar</li>
	</ul>
</nav>


<section class="container flexUl">
	
</section>



</body>
</html>

<?php
 }
 ?>