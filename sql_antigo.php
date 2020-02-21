<?php
session_start();

if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
}
 else{
	require_once("connect.php");
	require_once("connectPDO.php");
	$usuario = $_SESSION['userId'];

if(!isset($_POST['acao'])){echo"<script>history.go(-1);</script>";}else{

	$acao = $_POST['acao'];

if($acao == "saque"){
	$ano = $_POST['ano'];
	$mes = $_POST['mes'];
	$dia = $_POST['dia'];
	$conta = $_POST['conta'];
	$destino = $_POST['destino'];
	$saque = $_POST['valor'];
	$tarifa = $_POST['tarifa'];
	$valor= $saque + $tarifa;
	if($tarifa != 0){$obs = "Saque - Tarifa: $tarifa";}else{$obs = "Saque";}
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$conta','0','$valor','$obs',NULL,'$usuario');";//conta bancaria
	$exec=mysqli_query($connect,$sql);
	$ultimo=mysqli_insert_id($connect);
	
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$destino','$saque','0','$obs','$ultimo','$usuario');";//caixa
	$exec=mysqli_query($connect,$sql);
	
	if($tarifa != 0){
		$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','38','$tarifa','0','$obs','$ultimo','$usuario');";//tarifa
		$exec=mysqli_query($connect,$sql);
	}
	
	$sql = "UPDATE `lancamentos` SET `amarracao` = '$ultimo' WHERE `lancamentos`.`id` = $ultimo;";
	$exec=mysqli_query($connect,$sql);
	
	$sql="select * from contas where id = $conta";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_fetch_assoc($exec);
	$total=$rows['total'];
	$total=$total-$valor;
	$sql="UPDATE `contas` SET `total` = '$total' WHERE `contas`.`id` = $conta;";
	$exec=mysqli_query($connect,$sql);
	
	$sql="select * from contas where id = $destino";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_fetch_assoc($exec);
	$total=$rows['total'];
	$total=$total+$saque;
	$sql="UPDATE `contas` SET `total` = '$total' WHERE `contas`.`id` = $destino;";
	$exec=mysqli_query($connect,$sql);
	
	header("location:extrato.php?conta=$conta");

}

if($acao == "cartao"){
	$ano = $_POST['ano'];
	$mes = $_POST['mes'];
	$dia = $_POST['dia'];
	$conta = $_POST['conta'];
	$destino=$_POST['destino'];
	$cartao = $_POST['valor'];
	$obs = "Cartão - ".$_POST['obs'];
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$conta','0','$cartao','$obs',NULL,'$usuario');";//conta bancaria
	$exec=mysqli_query($connect,$sql);
	$ultimo=mysqli_insert_id($connect);
	
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$destino','$cartao','0','$obs','$ultimo','$usuario');";//despesas diversas
	$exec=mysqli_query($connect,$sql);
		
	$sql = "UPDATE `lancamentos` SET `amarracao` = '$ultimo' WHERE `lancamentos`.`id` = $ultimo;";
	$exec=mysqli_query($connect,$sql);
	
	$sql="select * from contas where id = $conta";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_fetch_assoc($exec);
	$total=$rows['total'];
	$total=$total-$cartao;
	$sql="UPDATE `contas` SET `total` = '$total' WHERE `contas`.`id` = $conta;";
	$exec=mysqli_query($connect,$sql);
	
	header("location:extrato.php?conta=$conta");

}

if($acao == "deposito"){
	$ano = $_POST['ano'];
	$mes = $_POST['mes'];
	$dia = $_POST['dia'];
	$conta = $_POST['conta'];
	$origem=$_POST['origem'];
	$deposito = $_POST['valor'];
	$obs = $_POST['obs'];
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$conta','$deposito','0','$obs',NULL,'$usuario');";//conta bancaria
	$exec=mysqli_query($connect,$sql);
	$ultimo=mysqli_insert_id($connect);
	
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$origem','0','$deposito','$obs','$ultimo','$usuario');";//caixa
	$exec=mysqli_query($connect,$sql);
		
	$sql = "UPDATE `lancamentos` SET `amarracao` = '$ultimo' WHERE `lancamentos`.`id` = $ultimo;";
	$exec=mysqli_query($connect,$sql);
	
	$sql="select * from contas where id = $conta";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_fetch_assoc($exec);
	$total=$rows['total'];
	$total=$total+$deposito;
	$sql="UPDATE `contas` SET `total` = '$total' WHERE `contas`.`id` = $conta;";
	$exec=mysqli_query($connect,$sql);
	
	if($origem==25 || $origem==26){
		$sql="select * from contas where id = $origem";
		$exec=mysqli_query($connect,$sql);
		$rows=mysqli_fetch_assoc($exec);
		$total=$rows['total'];
		$total=$total-$deposito;
		$sql="UPDATE `contas` SET `total` = '$total' WHERE `contas`.`id` = $origem;";
		$exec=mysqli_query($connect,$sql);
	}
	
	header("location:extrato.php?conta=$conta");

}

if($acao == "lanc"){
	$ano = $_POST['ano'];
	$mes = $_POST['mes'];
	$dia = $_POST['dia'];
	$debito = $_POST['debito'];
	$credito = $_POST['credito'];
	$valor = $_POST['valor'];
	$obs = $_POST['obs'];
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$debito','$valor','0','$obs',NULL,'$usuario');";//debito
	$exec=mysqli_query($connect,$sql);
	$ultimo=mysqli_insert_id($connect);
	
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$credito','0','$valor','$obs','$ultimo','$usuario');";//credito
	$exec=mysqli_query($connect,$sql);
	
	$sql = "UPDATE `lancamentos` SET `amarracao` = '$ultimo' WHERE `lancamentos`.`id` = $ultimo;";
	$exec=mysqli_query($connect,$sql);
	echo'<div align="center" style="font-family:verdana; font-size:36px;">Lançamento efetuado com sucesso!</div>';
	
	$sql="select * from contas where id = $debito";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_fetch_assoc($exec);
	$contaD=$rows['grupo'];
	$totalD=$rows['total'];
	if($contaD=="Ativo"){$totalD=$totalD+$valor;} elseif($contaD=="Passivo" || $contaD=="Patrimônio Líquido"){$totalD=$totalD-$valor;}else{}
	$sql="UPDATE `contas` SET `total` = '$totalD' WHERE `contas`.`id` = $debito;";
	$exec=mysqli_query($connect,$sql);
	
	$sql="select * from contas where id = $credito";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_fetch_assoc($exec);
	$contaC=$rows['grupo'];
	$totalC=$rows['total'];
	if($contaC=="Passivo" || $contaC=="Patrimônio Líquido"){$totalC=$totalC+$valor;} elseif($contaC=="Ativo"){$totalC=$totalC-$valor;}else{}
	$sql="UPDATE `contas` SET `total` = '$totalC' WHERE `contas`.`id` = $credito";
	$exec=mysqli_query($connect,$sql);
	
	
	header("location:lancamentos.php");
}

if ($acao == "add_conta"){
	$conta = $_POST['conta'];
	$grupo = $_POST['grupo'];
	$subgrupo = $_POST['subgrupo'];
	$total = 0;
	$sql = "INSERT INTO contas(conta,grupo,subgrupo,total) VALUES(:conta,:grupo,:subgrupo,:total);";
	$con = $pdo->prepare($sql);
	$con->bindValue(":conta",$conta);
	$con->bindValue(":grupo",$grupo);
	$con->bindValue(":subgrupo",$subgrupo);
	$con->bindValue(":total",$total);
	$con->execute();
	header("location:contas.php");
}

if($acao == "pagar"){
	$conta=$_POST['conta'];
	$valor=$_POST['valor'];
	$vencimento=$_POST['vencimento'];
	$obs=$_POST['obs'];
	$sql="INSERT INTO pagar(`conta`,`valor`,`vencimento`,`obs`) values('$conta','$valor','$vencimento','$obs');";
	$exec=mysqli_query($connect,$sql);
	header("location:pagar.php");
}

if($acao=="fechamento"){
	$mes=$_POST['mes'];
	$ano=$_POST['ano'];
	
	$sql="select * from fechamento where ano = $ano and mes =$mes";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_num_rows($exec);
	
	if($rows>0){echo"<script>history.go(-1);alert('Fechamento já efetuado! Confira o mês e tente novamente.');</script>";}
	else{
		//receitas
		$sql="select * from contas where grupo = 'Contas de Resultado' and subgrupo = 'Receitas'";
		$exec=mysqli_query($connect,$sql);
		
		$receitaTT=0;
		while($rows=mysqli_fetch_assoc($exec)){
			$contaid=$rows['id'];;
			$sql2="select * from lancamentos where conta = $contaid and ano = $ano and mes = $mes";
			$exec2=mysqli_query($connect,$sql2);
			
			$receita=0;
			
			while($rows2=mysqli_fetch_assoc($exec2)){
				$valor=$rows2['credito'];
				$receita=$receita+$valor;
			}
			$receitaTT=$receitaTT+$receita;
			$sql2="insert into fechamento(ano,mes,conta,valor)values('$ano','$mes','$contaid','$receita');";
			$exec2=mysqli_query($connect,$sql2);
		}
		//despesas
		$sql="select * from contas where subgrupo = 'Despesas'";
		$exec=mysqli_query($connect,$sql);
		
		$despesaTT=0;
		while($rows=mysqli_fetch_assoc($exec)){
			$contaid=$rows['id'];
			$sql2="select * from lancamentos where conta = $contaid and ano = $ano and mes = $mes";
			$exec2=mysqli_query($connect,$sql2);
			
			$despesas=0;
			
			while($rows2=mysqli_fetch_assoc($exec2)){
				$valor=$rows2['debito'];
				$despesas=$despesas+$valor;
			}
			$despesaTT=$despesaTT+$despesas;
			if($despesas > 0){
				$sql2="insert into fechamento(ano,mes,conta,valor)values('$ano','$mes','$contaid','$despesas');";
				$exec2=mysqli_query($connect,$sql2);
			}
		}
		
		//lucro ou prejuizo
		$lucro=$receitaTT-$despesaTT;
		$sql="select * from contas where id = 36";
		$exec=mysqli_query($connect,$sql);
		$rows=mysqli_fetch_assoc($exec);
		$total=$rows['total'];
		$total=$total+$lucro;
		$sql="update contas set total = $total where id = 36";
		$exec=mysqli_query($connect,$sql);
		
		
		//balanco patrimonial
		$sql="select * from contas where grupo != 'Contas de Resultado'";
		$exec=mysqli_query($connect,$sql);
		
		while($rows=mysqli_fetch_assoc($exec)){
			$contaid=$rows['id'];
			$total=$rows['total'];
			$sql2="insert into fechamento(ano,mes,conta,valor)values('$ano','$mes','$contaid','$total');";
			$exec2=mysqli_query($connect,$sql2);
			
		}
		header("location:balanco.php");
	}
}

if($acao=="ajustecx"){
	$dia=$_POST['dia'];
	$mes=$_POST['mes'];
	$ano=$_POST['ano'];
	$credito=$_POST['conta'];
	$debito="31";
	$atual=$_POST['valor'];
	
	$sql="select * from contas where id = $credito";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_fetch_assoc($exec);
	$total=$rows['total'];
	$valor=$total-$atual;
	
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$debito','$valor','0','Ajuste caixa',NULL,'$usuario');";//debito
	$exec=mysqli_query($connect,$sql);
	$ultimo=mysqli_insert_id($connect);
	
	$sql = "INSERT INTO `$banco`.`lancamentos` (`id`,`ano`,`mes`,`dia`,`conta`,`debito`,`credito`,`obs`,`amarracao`,`usuario`) VALUES(NULL,'$ano','$mes','$dia','$credito','0','$valor','Ajuste caixa','$ultimo','$usuario');";//credito
	$exec=mysqli_query($connect,$sql);
		
	$sql = "UPDATE `lancamentos` SET `amarracao` = '$ultimo' WHERE `lancamentos`.`id` = $ultimo;";
	$exec=mysqli_query($connect,$sql);
	
	
	$sql="UPDATE `contas` SET `total` = '$atual' WHERE `contas`.`id` = $credito;";
	$exec=mysqli_query($connect,$sql);
	
	echo"<script>history.go(-1);self.location.reload();</script>";
	
}

if($acao=="compras"){
	$produto=$_POST['produto'];
	$qtde=$_POST['qtde'];
	$obs=$_POST['obs'];
	$sql="insert into compras (`produto`,`qtde`,`obs`) values('$produto','$qtde','$obs');";
	$exec=mysqli_query($connect,$sql);
	header("location:compras.php");
}

if($acao=="receita"){
	$dia=$_POST['dia'];
	$mes=$_POST['mes'];
	$ano=$_POST['ano'];
	$turno=$_POST['turno'];
	$he=$_POST['he'];
	
	$sql="insert into receitas (`ano`,`mes`,`dia`,`turno`,`he`,`usuario`) values('$ano','$mes','$dia','$turno','$he','$usuario');";
	$exec=mysqli_query($connect,$sql);
	header("location:receitas.php");
}


//ADD USUARIO

if($acao=='add_usuario'){
	$nome=$_POST['nome'];
	$usuario=$_POST['usuario'];
	$senha1=$_POST['senha'];
	$senha2=$_POST['senha2'];
	
	$sql="select * from usuarios where usuario='$usuario'";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_num_rows($exec);
	
	if($senha1!=$senha2){
		echo"
			<script>alert('As senhas devem ser iguais, tente novamente.');history.back();</script>
		";
	}
	elseif($rows>0){
		echo"
			<script>alert('Nome de usuário em uso, tente outro.');history.back();</script>
		";
	}
	else{
		function gerar_salt($tamanho = 22) {
			return substr(sha1(mt_rand()), 0, $tamanho); 
		}
		 
		$salt = gerar_salt();
		$senha=md5($senha1.$salt);
		$sql="insert into usuarios (`nome_completo`,`usuario`,`senha`,`salt`,`alt`) values('$nome','$usuario','$senha','$salt',1);";
		$exec=mysqli_query($connect,$sql);
		header("location:usuarios.php");
	}
}

//ALTERAR SENHA

if($acao=='alt_senha'){
	$usuario_id=$_POST['usuario_id'];
	$senha1=$_POST['senha'];
	$senha2=$_POST['senha2'];
	
	$sql="select * from usuarios where id=$usuario_id";
	$exec=mysqli_query($connect,$sql);
	$rows=mysqli_fetch_assoc($exec);
	$senhaAntiga=$rows['senha'];
	$saltAntigo=$rows['salt'];
	
	$senha1=md5($senha1.$saltAntigo);
	
	if($senha1==$senhaAntiga){
		function gerar_salt($tamanho = 22) {
			return substr(sha1(mt_rand()), 0, $tamanho); 
		}
		
		$salt = gerar_salt();
		$senha2=md5($senha2.$salt);
		$sql="update usuarios set senha='$senha2', salt='$salt', alt=1 where id=$usuario_id";
		$exec=mysqli_query($connect,$sql);
		
		echo"
			<script>
			alert('Senha alterada com sucesso!');
			document.location.href='usuarios.php';
			</script>
		";
	}
	else{
		$_SESSION['alt']=0;
		echo"
			<script>
			alert('A senha antiga não confere com a digitada, tente novamente.');
			history.back();
			</script>
		";
	}
	
	
}



//2 chaves de fechamento abaixo
}
}
?>