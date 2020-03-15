<nav class="menu">
	<ul>
		<li id="menuAccounts" onClick="openPage('accounts')">Contas</li>
		<li id="menuAdjustCash">Ajustar Caixa</li>
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
		<li id="menuBillsToPay" onClick="openPage('billsToPay')">Contas a Pagar</li>
		<li id="menuEntries" onClick="openPage('entries')">Lançamentos</li>
		<li id="menuShoppingList" onClick="openPage('shoppingList')">Lista de Compras</li>
		<li id="menuRevenue" onClick="openPage('revenue')">Receitas</li>
		<li id="menu_logout" onClick="openPage('logout')">Sair</li>
	</ul>
</nav>

<div id="mobileMenu">Menu</div>

<div class="box" id="boxAdjustCash">
	<h1>Ajustar Caixa</h1>
	<form action="adjustCash.php" method="post" id="adjustCashForm" onsubmit="return false">
		<ul>
			<li>
				<label for="adjustCashDate">Data:</label>
				<input type="date" name="date" id="adjustCashDate"/>
			</li>
			<li>
				<label for="adjustCashCreditId">Conta:</label>
				<select name="creditId" id="adjustCashCreditId">
					<?php
						$sql = "SELECT * FROM accounts WHERE account LIKE '%Caixa%' ORDER BY account ";
						$con = $pdo->prepare($sql);
						$con->execute();
						$list = $con->fetchAll(PDO::FETCH_OBJ);
						foreach($list as $rows){
							echo"<option value='$rows->id'>$rows->account</option>";
						}
					?>
				</select>
			</li>
			<li>
				<label for="adjustCashValue">Valor:</label>
				<input type="text" name="value" id="adjustCashValue" onkeypress="return num()" onkeyup="maskVal('adjustCashValue'); keyEnter(event, '#adjustCashConfirm')"/>
			</li>
			<li>
				<button id="adjustCashConfirm">Confirmar</button>
				<button class="close">Cancelar</button>
			</li>
		</ul>
	</form>
</div>

<div id="load">
    <div></div>
    <span>Loading</span>
</div>
