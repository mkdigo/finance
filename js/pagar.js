const page = "pagar"

$(document).ready(function(){

    $("#addConfirmar").click(function(){
        confirmarAdd()
    })

    $("#cxAdd input").keyup(function(){
		teclaEnter(event, 'addConfirmar')
    })

    $("#baixaConfirmar").click(function(){
        confirmarBaixa()
    })

    $("#cxBaixa input").keyup(function(){
		teclaEnter(event, 'baixaConfirmar')
    })
    
})

function confirmarAdd(){
    let contaId = $("#addContaId").val()
    let valor = $("#addValor").val()
    let venc = $("#addVenc").val()
    let obs = $("#addObs").val()

    console.log(venc)

    if(isNaN(valor) ||  valor == ""){
        $("#addValor").css("background-color","#CC3")
        $("#addValor").focus()
    }else if(venc == ""){
        $("#addVenc").css("background-color","#CC3")
        $("#addVenc").focus()
    }else{
        $("#addValor").css("background-color","#FFF")
        $("#addVenc").css("background-color","#FFF")

        valor = mascValorClear(valor)

        $.ajax({
            url:'pagar_sql.php',
            type:'POST',
            cache:false,
            dataType:"html",
            data:{
                acao:'add',
                contaId:contaId,
                obs:obs,
                valor:valor,
                venc:venc
            },
            success: function(data){
                $(".conteudo div").empty().html(data)
                $("#addValor").val("")
                $("#addObs").val("")
                cancelar()
            }
        })
    }
}

var delId = null

function cxDel(id){
    delId = id;
    cx('cxDel')
}

function del(){
    $.ajax({
        url:'pagar_sql.php',
        type:'POST',
        cache:false,
        dataType:"html",
        data:{
            acao:'del',
            id:delId
        },
        success: function(data){
            $(".conteudo div").empty().html(data)
            cancelar()
        }
    })
}

var baixaId = null
var baixaDebitoId = null
var baixaValor = null
var baixaObs = null

function baixa(id, contaId, conta, valor, venc, obs){
    baixaId = id
    baixaDebitoId = contaId
    baixaValor = valor
    baixaObs = obs
    $("#baixaConta").html(conta)
    $("#baixaValor").html(valor)
    $("#baixaVenc").html(venc)
    cx('cxBaixa', 'baixaPag')

    baixaValor = mascValorClear(valor)
    
}

function confirmarBaixa(){
    let baixaCreditoId = $("#baixaForma").val()
    let baixaPagamento = $("#baixaPag").val()
    console.log(baixaValor)
    $.ajax({
        url:'pagar_sql.php',
        type:'POST',
        cache:false,
        dataType:"html",
        data:{
            acao:'baixa',
            id:baixaId,
            debitoId:baixaDebitoId,
            creditoId:baixaCreditoId,
            valor:baixaValor,
            pagamento:baixaPagamento,
            obs:baixaObs
        },
        success: function(data){
            $(".conteudo div").empty().html(data)
            cancelar()
        }
    })
}
