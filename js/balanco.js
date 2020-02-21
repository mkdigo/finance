const page = "balanco"

$(document).ready(function(){
	
	
	$("#ac").html(ac)
	$("#ar").html(ar)
	$("#ap").html(ap)
	$("#ativo").html(ativo)
	$("#pc").html(pc)
	$("#pe").html(pe)
	$("#passivo").html(passivo)
	$("#pl").html(pl)
	$("#receitas").html(receitas)
	$("#despesas").html(despesas)
	$("#lucro").html(lucro)
	
	$("#month, #year").change(function(){
		$("#formBalanco").submit()
	})
	
});

function historico (m,a,conta){
	window.location.href = "historico.php?mes=" + m + "&ano=" + a + "&conta=" + conta
}