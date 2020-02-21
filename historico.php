<?php
include("connect.php");
session_start();
if(!isset($_SESSION['usuario'])){session_destroy();header("location:index.html");}else{
$usuario = $_SESSION['usuario'];

$ano=$_GET['ano'];
$mes=$_GET['mes'];
$contaid=$_GET['conta'];
$sql="select * from contas where id = $contaid";
$exec=mysqli_query($connect,$sql);
$rows=mysqli_fetch_assoc($exec);
$conta=$rows['conta'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet"/>
<link href="css/historico.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script lang="javascript">

page2='Histórico <?php echo"$mes/$ano"; ?>';

$(document).ready(function(){
	$("#voltar").click(function(){
		$("#voltar").css("border","inset 3px #999");
		history.go(-1);
	});
});

</script>
<title>Histórico</title>
</head>

<body>

<?php include("menu.php"); ?>

<div class="conta" align="center"><?php echo"$conta"; ?></div>

<div align="center" class="conteudo">
	<table width="90%">
    	<tr>
        	<td class="destaque" width="30">Dia</td><td class="destaque" width="70">Débido</td><td class="destaque" width="70">Crédito</td><td class="destaque">Obs</td>
        </tr>
        <?php
		$sql="select * from lancamentos where conta = $contaid and ano = $ano and mes = $mes order by dia";
		$exec=mysqli_query($connect,$sql);
		$TTdebito=0;
		$TTcredito=0;
		while($rows=mysqli_fetch_assoc($exec)){
			$dia=$rows['dia'];
			$debito=$rows['debito'];
			$credito=$rows['credito'];
			$obs=$rows['obs'];
			$TTdebito=$TTdebito+$debito;
			$TTcredito=$TTcredito+$credito;
			
			echo"
				<tr>
					<td>$dia</td>
					<td>".number_format($debito,0,',','.')."</td>
					<td>".number_format($credito,0,',','.')."</td>
					<td>$obs</td>
				</tr>
			";
		}
		echo"
			<tr>
				<td class='destaque'>TT</td>
				<td class='destaque'>".number_format($TTdebito,0,',','.')."</td>
				<td class='destaque'>".number_format($TTcredito,0,',','.')."</td>
				<td class='destaque'></td>
			</tr>
		";
		?>
    </table>
</div>

<div id="voltar">Voltar</div>

</body>
</html>
<?php
}
?>