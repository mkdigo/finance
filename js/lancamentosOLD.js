page = "lancamentos"

$(document).ready(function(){

    function confirmar(acao){
        var dat = $("#" + acao + "Dat").val()
		var debitoId = $("#" + acao + "DebitoId").val()
		var creditoId = $("#" + acao + "CreditoId").val()
        var obs = $("#" + acao + "Obs").val()
        var valor = $("#" + acao + "Valor").val()
        valor = mascValorClear(valor)

        $(".cx input, .cx select").css("background-color","#FFF")
        
        if(isNaN(valor) ||  valor == ""){
			$("#" + acao + "Valor").css("background-color","#CC3")
            $("#" + acao + "Valor").focus()
        }else if(debitoId == ""){
            $("#" + acao + "DebitoId").css("background-color","#CC3")
            $("#" + acao + "DebitoId").focus()
        }else if(creditoId == ""){
            $("#" + acao + "CreditoId").css("background-color","#CC3")
            $("#" + acao + "CreditoId").focus()
        }else if(dat == ""){
            $("#" + acao + "Dat").css("background-color","#CC3")
            $("#" + acao + "Dat").focus()
		}else{
			$.ajax({
				url:'lancamentos_sql.php',
				type:'POST',
				cache:false,
				dataType:"html",
				data:{
					acao:acao,
					dat:dat,
					debitoId:debitoId,
					creditId:creditoId,
					obs:obs,
					valor:valor
				},
				success: function(data){
                    $(".conteudo div").empty().html(data)
                    $("#" + acao + "DebitoId").val("")
                    $("#" + acao + "CreditoId").val("")
					$("#" + acao + "Valor").val("")
					$("#" + acao + "Obs").val("")
					cancelar()
				}
			})
		}
    }

    $("#despesasConfirmar").click(function(){
        confirmar('despesas')
    })

    $("#cxDespesas input").keyup(function(){
        teclaEnter(event, 'despesasConfirmar')
    })
    
    $("#geralConfirmar").click(function(){
        confirmar('geral')
    })

    $("#cxGeral input").keyup(function(){
        teclaEnter(event, 'geralConfirmar')
    })

    $("#receitasConfirmar").click(function(){
        confirmar('receitas')
    })

    $("#cxReceitas input").keyup(function(){
        teclaEnter(event, 'receitasConfirmar')
    })
})

function cxExcluir(n){
	amarracao = n
	cx('excluirLancamento')
}

function confirmarExclusao(){
	$.ajax({
		url:'lancamentos_sql.php',
		type:'POST',
		cache:false,
		dataType:"html",
		data:{
			acao:"excluir",
			amarracao:amarracao
		},
		success: function(data){
			$(".conteudo div").empty().html(data)
			cancelar()
		}
	});
}
