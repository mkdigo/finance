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
    <link href="css/pagar.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="js/global.js"></script>
	<script src="js/pagar.js"></script>
	
	<title>Contas a Pagar</title>
</head>

<body>

<?php
require_once("connectPDO.php");
require_once("class/functions.php");
require_once("notifica.php");
require_once("menu.php");

?>

<header class="header">
    Contas a Pagar
</header>

<nav class="tools">
    <ul>
		<li>Total: <span id="tt">0</span></li>
        <li onClick="cx('cxAdd', 'addValor')">Adicionar</li>
    </ul>
</nav>

<div class="cx" id="cxAdd">
    <h1>Adicionar Conta</h1>
    <ul>
		<li>
			<label for="addContaId">Conta:</label>
			<select id="addContaId">
				<?php
					$sql = "SELECT id, conta FROM contas WHERE subgrupo LIKE 'Despesas' ORDER BY conta";
					$con = $pdo->prepare($sql);
					$con->execute();
					$list = $con->fetchAll(PDO::FETCH_OBJ);
					foreach($list as $rows){
						echo"
							<option value='$rows->id'>$rows->conta</option>
						";
					}
				?>
			</select>
		</li>
		<li>
			<label for="addValor">Valor:</label>
			<input type="text" id="addValor"onkeypress="return soNum()" onkeyup="mascValor('addValor')" />
		</li>
		<li>
			<label for="addVenc">Vencimento:</label>
			<input type="date" id="addVenc" class="now" />
		</li>
		<li>
			<label for="addObs">Obs:</label>
			<input type="text" id="addObs">
		</li>
        <li>
            <div class="bt" id="addConfirmar">Confirmar</div>
            <div class="bt cancelar">Cancelar</div>
        </li>
    </ul>
</div>

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

<div class="cx" id="cxBaixa">
    <h1>Baixa</h1>
    <ul>
		<li><label for="">Conta:</label><span id="baixaConta"></span></li>
		<li><label for="">Valor:</label><span id="baixaValor"></span></li>
		<li><label for="">Vencimento:</label><span id="baixaVenc"></span></li>
		<li>
			<label for="baixaPag">Data Pgto:</label>
			<input type="date" id="baixaPag" class="now" />
		</li>
		<li>
			<label for="baixaForma">Forma Pgto:</label>
			<select id="baixaForma">
				<?php
					$sql = "SELECT id, conta FROM contas WHERE grupo LIKE 'Ativo' AND subgrupo LIKE 'Circulante' ORDER BY conta";
					$con = $pdo->prepare($sql);
					$con->execute();
					$list = $con->fetchAll(PDO::FETCH_OBJ);
					foreach($list as $rows){
						echo"
							<option value='$rows->id'>$rows->conta</option>
						";
					}
				?>
			</select>
		</li>
        <li>
            <div class="bt" id="baixaConfirmar">Confirmar</div>
            <div class="bt cancelar">Cancelar</div>
        </li>
    </ul>
</div>

<div class="camada"></div>

<section class="conteudo">
	<div>
		<ul>
			<li>Conta</li>
			<li>Valor</li>
			<li>Vencimento</li>
			<li>Obs</li>
		</ul>
		<?php
			$sql = "SELECT * FROM pagar WHERE pagamento IS NULL ORDER BY vencimento";
			$con = $pdo->prepare($sql);
			$con->execute();
			$list = $con->fetchAll(PDO::FETCH_OBJ);
			$tt = 0;
			foreach($list as $rows){
				$sql2 = "SELECT conta FROM contas WHERE id = $rows->conta";
				$con2 = $pdo->prepare($sql2);
				$con2->execute();
				$list2 = $con2->fetch(PDO::FETCH_ASSOC);
				$conta = $list2['conta'];
				$valor = number($rows->valor);
				$venc = $rows->vencimento;
				$obs = $rows->obs;

				$baixa = "onclick=\"baixa($rows->id, $rows->conta, '$conta', '$valor', '$venc', '$obs')\"";
				echo"
					<ul>
						<li $baixa>$conta</li>
						<li $baixa>$valor</li>
						<li $baixa>$venc</li>
						<li>$obs<img src='templates/excluir.png' onclick='cxDel($rows->id)'/></li>
					</ul>
				";
				$tt += $rows->valor;
			}
			echo"
				<script>
					document.getElementById('tt').innerHTML = '".number($tt)."'
				</script>
			";
		?>
	</div>
</section>

</body>

<?php
}
?>