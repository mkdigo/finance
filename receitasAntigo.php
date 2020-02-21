<?php
include("connect.php");
session_start();
 if(!isset($_SESSION['usuario'])){session_destroy();header("location:index.html");}else{
 $usuario = $_SESSION['usuario'];
 $usuario_id = $_SESSION['id'];
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet"/>
<link href="css/receitas.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script lang="javascript">

page="receitas";
page2="Receitas(Previsão)";

data = new Date();
dia = data.getDate();
mes = data.getMonth()+1;
ano = data.getFullYear();
hora= data.getHours();

<?php
if(isset($_POST['mes'])){
	$c_mes=$_POST['mes'];
	$c_ano=$_POST['ano'];
	echo"
		c_mes=$c_mes;
		c_ano=$c_ano;
	";
}
else{
	$c_dia=date('d');	
	$c_mes=date('m');
	$c_ano=date('Y');
	$hora=date('H');
	$min=date('i');
	
	echo"
		c_mes=mes;
		c_ano=ano;
	";

}
?>

$(document).ready(function(){
	
	$("#c_mes").val(c_mes);
	$("#c_ano").val(c_ano);
	$("#add_ano").val(ano);
	$("#add_mes").val(mes);
	
	
	$("#buscar").click(function(){
		$("#buscar").css("border","inset 2px #999");
		$("#form_buscar").submit();
	});
	
	add=0;
	$("#add_botao").click(function(){
		if(add==0){
			$("#add_botao").css("border","inset 2px #999");
			$("#cx_add").slideDown(500);
			add++;
		}
		else{
			$("#add_botao").css("border","outset 2px #999");
			$("#cx_add").slideUp(500);
			add=0;
		}
	});
	
	$("#add").click(function(){
		$("#add").css("border","inset 2px #999");
		$("#form_add").submit();
	});
	
	$("#add_cancelar").click(function(){
		$("#add_cancelar").css("border","inset 2px #999");
		$("#add_botao").css("border","outset 2px #999");
		$("#cx_add").slideUp(500);
		setTimeout(function(){$("#add_cancelar").css("border","outset 2px #999");},200);
		add=0;
	});
	
	if(hora<12){$("#add_turno").val(2);$("#add_dia").val(dia-1);}else{$("#add_turno").val(1);$("#add_dia").val(dia);}
	
	
	
});


</script>
<title>Receitas</title>
</head>

<body>

<?php include("menu.php"); ?>

<div class="top2" align="center">
	<form method="post" action="receitas.php" id="form_buscar">
	Mês:<select name="mes" class="campos" id="c_mes">
    		<script>
			for(x=1;x<13;x++){
				document.write("<option value='"+x+"'>"+x+"</option>");
			}
			</script>
    	</select>
    
    Ano:<input type="number" name="ano" class="campos" id="c_ano" />
	<div class="add_botao" id="buscar" align="center" style="margin-top:0px;">Buscar</div>
    <div class="add_botao" id="add_botao" align="center" style="margin-top:0px;">Add</div>
    </form>
    
</div>

<div id="cx_add" align="center">
	<div class="top_add" align="center">Add</div>
    <form action="sql.php" method="post" id="form_add">
    <table border="0">
    	<tr>
        	<td width="30">Data:</td>
            <td>
            	<input type="number" name="ano" id="add_ano" class="campos" />/
                <select name="mes" class="campos" id="add_mes" style="width:45px;">
                <script>
				for(x=1;x<=12;x++){
					document.write("<option value='"+x+"'>"+x+"</option>");
				}
				</script>
                </select>/
                <select name="dia" class="campos" id="add_dia" style="width:45px;">
                <script>
				for(x=1;x<=31;x++){
					document.write("<option value='"+x+"'>"+x+"</option>");
				}
				</script>
                </select>
            </td>
        </tr>
        <tr>
        	<td>
            	Turno:
            </td>
            <td>
            	<select name="turno" class="campos" style="width:55px; margin-right:12px;" id="add_turno">
                	<option value="1">Dia</option>
                    <option value="2">Noite</option>
                </select>
                H Extras:
                <input type="number" name="he" class="campos" step="0.25"/>
            </td>
        </tr>
        <tr>
        	<td colspan="2" align="right">
            <div class="add_botao" id="add" align="center">Add</div>
            <div class="add_botao" id="add_cancelar" align="center">Cancelar</div>
            <!--<input type="submit" class="campos" value="Add" />
            <input type="button" class="campos" value="Cancelar" id="add_cancelar" />-->
            </td>
        </tr>
    </table>
    <input type="hidden" name="acao" value="receita" />
    </form>
</div>

<div class="conteudo" align="center">
	<table border="0">
    	<tr align="center">
        	<td width="40" class="destaque">Dia</td>
            <td width="70" class="destaque">Turno</td>
            <td width="70" class="destaque">H Extras</td>
            <td width="100" class="destaque">Valor Bruto</td>
        </tr>
        <?php
		$sql="select * from receitas where mes = $c_mes and ano = $c_ano and usuario = $usuario_id order by dia";
		$exec=mysqli_query($connect,$sql);
		$num_rows=mysqli_num_rows($exec);
		if($num_rows>0){
			$TT_dias=0;
			$TT_he=0;
			$TT_noite=0;
			while($rows=mysqli_fetch_assoc($exec)){
				$r_dia=$rows['dia'];
				$r_he=$rows['he'];
				$r_turno=$rows['turno'];
				$r_id=$rows['id'];
				
				if($r_turno==1){$v_bruto=($r_he*1420*1.3)+((7+50/60)*1420);}
				else{
					$v_bruto=($r_he*1420*1.3)+((7+50/60)*1420)+(6*1420*0.3);
					$TT_noite++;
				}
				
				if($r_turno==1){$r_turno="Dia";}else{$r_turno="Noite";}
				$TT_dias++;
				$TT_he=$TT_he+$r_he;
				
				
				echo"
					<tr align='center'>
						<td onClick='aparecer($r_id);'>$r_dia</td>
						<td>$r_turno</td>
						<td>$r_he</td>
						<td>".number_format($v_bruto,0,",",".")."</td>
						<td id='$r_id' class='img' style='display:none; border:none;'><img src='templates/excluir.png' width='20' onClick='excluir($r_id);'/></td>
					</tr>
				";
			}
		}
		else{
			$TT_dias=0;
			$TT_he=0;
			$TT_noite=0;
		}
		?>
    </table>
    
    <br />
    
    <table border="0" width="78%">
    	<tr>
        	<td>Dias Trabalhados:</td>
            <td align="center"><?php echo $TT_dias; ?></td>
            <td align="center">
            	<?php
				$v_dias=$TT_dias*(7+(50/60))*1420;
				echo number_format($v_dias,0,",",".");;
				?>
            </td>
        </tr>
        <tr>
        	<td>Horas Extras:</td>
            <td align="center"><?php echo $TT_he; ?></td>
            <td align="center">
            	<?php
				$v_he=$TT_he*1420*1.3;
				echo number_format($v_he,0,",",".");;
				?>
            </td>
        </tr>
        <tr>
        	<td>Adicional Noturno:</td>
            <td align="center"><?php echo $TT_noite*6; ?></td>
            <td align="center">
				<?php
                $v_noite=$TT_noite*6*1420*0.3;
                echo number_format($v_noite,0,",",".");;
                ?>
            </td>
        </tr>
        <tr>
        	<td class="destaque">Total Bruto:</td>
            <td></td>
            <td align="center" class="destaque">
				<?php
                $total_bruto=$v_dias+$v_he+$v_noite;
				echo number_format($total_bruto,0,",",".");;
                ?>
            </td>
		</tr>
        <tr>
            <td>Seguro Saúde:</td>
            <td></td>
            <td align="center">
				<?php
                $saude=9900;
				echo number_format($saude,0,",",".");;
                ?>
            </td>
		</tr>
        <tr>
            <td>Aposentadoria:</td>
            <td></td>
            <td align="center">
				<?php
                $aposenta=27450;
				echo number_format($aposenta,0,",",".");;
                ?>
            </td>
		</tr>
        <tr>
            <td>Seguro Desemprego:</td>
            <td></td>
            <td align="center">
				<?php
                $desemprego=$total_bruto*0.003;
				echo number_format($desemprego,0,",",".");;
                ?>
            </td>
        </tr>
        <tr>
            <td>Imposto Renda:</td>
            <td></td>
            <td align="center">
				<?php
                $renda=$total_bruto*0.025;
				echo number_format($renda,0,",",".");;
                ?>
            </td>
        </tr>
        <tr>
            <td class="destaque">Total Descontos:</td>
            <td></td>
            <td align="center" class="destaque">
				<?php
                $descontos=$saude+$aposenta+$desemprego+$renda;
				echo number_format($descontos,0,",",".");;
                ?>
            </td>
        </tr>
        <tr>
            <td class="destaque">Total Líquido:</td>
            <td></td>
            <td align="center" class="destaque">
				<?php
                $total_liq=$total_bruto-$descontos;
				echo number_format($total_liq,0,",",".");;
                ?>
            </td>
        </tr>
    </table>
</div>

<script>
x=0;
function aparecer(id){
	if(x==0){
		$("#"+id).css("display","block");
		x++;
	}
	else{
		$(".img").css("display","none");
		x=0;
	}
}

function excluir(id){
	window.location.href="del.php?acao=del_receita&id="+id;
}
</script>

</body>
</html>
<?php
 }
 ?>