<nav class="menu">
	<ul>
		<li id="menu_add_conta" onClick="openPage('contas')">Contas</li>
		<li id="menuAdjustment">Ajustar Caixa</li>
		<li id="menuBalance" onClick="openPage('balance')">Balanço</li>
		<li id="menuBank">Banco</li>
			<div id="menuBankList">
				<ul>
				<?php
					$sql = "SELECT * FROM accounts WHERE account LIKE '%banco%' ORDER BY account";
					$con = $pdo->prepare($sql);
					$con->execute();
					$list = $con->fetchAll(PDO::FETCH_OBJ);
					foreach($list as $rows){
						echo"
							<li onClick='openBank($rows->id)'>$rows->account</li>
						";
					}
				?>
				</ul>
			</div>
		<li id="menu_pagar" onClick="openPage('pagar')">Contas a Pagar</li>
		<li id="menuEntries" onClick="openPage('entries')">Lançamentos</li>
		<li id="menu_compras" onClick="openPage('compras')">Lista de Compras</li>
		<li id="menu_receitas" onClick="openPage('receitas')">Receitas</li>
		<li id="menu_logout" onClick="openPage('logout')">Sair</li>
	</ul>
</nav>

<div id="mobileMenu">Menu</div>

<div class="box" id="boxAdjustment">
	<h1>Ajustar Caixa</h1>
	<form action="" id="formAjustment" onsubmit="return false">
		<ul>
			<li>
				<label for="adjustmentDate">Data:</label>
				<input type="date" id="adjustmentDate"/>
			</li>
			<li>
				<label for="adjustmentContaId">Conta:</label>
				<select id="adjustmentContaId">
				<?php
					$sql = "SELECT * FROM contas WHERE conta LIKE '%Caixa%' ORDER BY conta ";
					$con = $pdo->prepare($sql);
					$con->execute();
					$list = $con->fetchAll(PDO::FETCH_OBJ);
					foreach($list as $rows){
						echo"<option value='$rows->id'>$rows->conta</option>";
					}
				?>
				</select>
			</li>
			<li>
				<label for="adjustmentValue">Valor:</label>
				<input type="text" id="adjustmentValue" onkeypress="return num()" onkeyup="maskVal('adjustmentValue'); keyEnter(event, '#adjustmentConfirm')"/>
			</li>
			<li>
				<button id="adjustmentConfirm">Confirmar</button>
				<button class="close">Cancelar</button>
			</li>
		</ul>
	</form>
</div>

<div id="load">
    <div></div>
    <span>Loading</span>
</div>
