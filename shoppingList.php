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
    <link href="css/shoppingList.css" rel="stylesheet" type="text/css"/>
	<script src="js/global.js"></script>
	<script src="js/shoppingList.js" defer></script>

<title>Lista de Compras</title>
</head>

<body>

<?php
require_once("notification.php");
require_once("menu.php");

?>

<header class="header">
    Extrato Bancário
</header>

<section class="layer">

    <div class="box" id="boxError"></div>

    <div class="box" id="boxDel">
		<h1>Confirmar Exclusão</h1>
		<ul>
			<li>
				Tem certeza que deseja excluir?
			</li>
			<li>
				<button type="button" id="delConfirm">Confirmar</button>
				<button type="button" class="close">Cancelar</button>
			</li>
		</ul>
	</div>

	<div class="box" id="boxAdd">
        <h1>Adicionar Produto</h1>
        <form action="" id="addForm" onsubmit="return false">
            <ul>
                <li>
                    <label for="addProduct">Produto:</label>
                    <input type="text" id="addProduct" name="product" />
                </li>
                <li>
                    <label for="addQuantity">Quantidade:</label>
                    <input type="text" id="addQuantity" name="quantity" onkeypress="return num(event)" />
                </li>
                <li>
                    <label for="addComments">Observação:</label>
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