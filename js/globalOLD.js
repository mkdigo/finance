$(document).ready(function(){
    //TECLA ESC
    $(document).keyup(function(e) {
		if(e.which == 27){
			$(".camada").fadeOut()
            $(".cx").fadeOut()
		}
    });
    
    //ANIMAÇÃO BOTAO
    $(".bt").click(function(){
        bt = $(this)
        bt.addClass("btClick")
        setTimeout(function(){bt.removeClass("btClick")},500)
    })

    //CANCELA TODAS AS CXS
    $(".cancelar").click(function(){
        cancelar()
    })

    //MENU
    $("#menu_"+page).css("background","rgba(255, 255, 255, 0.2)")

    var bancoclick=  0
    $("#menu_banco").click(function(){
        if(bancoclick == 0){
            $(this).css("background","rgba(255, 255, 255, 0.2)")
            $("nav.menu div").animate({"max-height":"400px"},500)
            bancoclick++
        }
        else{
            $(this).css("background-color","rgba(38, 30, 52, 1)")
            $("nav.menu div").animate({"max-height":"0px"},500)
            bancoclick=0
        }
    });

    $("nav.tools li").click(function(){
        let bt = $(this)
        bt.css("background","rgba(120, 120 , 120, 1)")
        setTimeout(function(){bt.css("background","rgb(180, 180, 180)")}, 500)
    })

    var mobileMenu = 0
    $("div#mobileMenu").click(function(){
        let mobi = $(this)
        mobi.css("border","inset 3px rgb(71, 56, 97)")
        setTimeout(function(){mobi.css("border","outset 3px rgb(71, 56, 97)")}, 300)
        if(mobileMenu == 0){
            $("nav.menu").animate({"left":"0"},300)
            mobileMenu++
        }
        else{
            $("nav.menu").animate({"left":"-100%"},300)
            mobileMenu = 0
        }
    })

    $(".cx").css("margin-left","-"+$(".cx").width()/2+"px")

    $("#menu_ajustecx").click(function(){
        cx('cxAjuste', 'ajusteValor')
    })

    $("#ajusteConfirmar").click(function(){
        let dat = $("#ajusteDat").val()
        let contaId = $("#ajusteContaId").val()
        let valor = mascValorClear($("#ajusteValor").val())
        
        $.ajax({
            url:'ajuste_caixa.php',
            type:'POST',
            cache:false,
            dataType:"html",
            data:{
                dat:dat,
                contaId:contaId,
                valor:valor
            },
            success: function(){
                $("#ajusteValor").val("")
                cancelar()
                location.reload()
            }
        })
    })
    
})

//MENU
function menu(caminho){
    $("#menu_"+caminho).css("background","rgba(255, 255, 255, 0.2)")
    window.location.href = caminho + ".php"
}

function menuBanco(id){
    window.location.href="extrato.php?conta="+id
}

//NOTIFICAÇÃO
function notificacao(msg){
    alert(msg)
}

//onkeypress="return soNum(event)" SÓ PERMITE NÚMEROS NO INPUT
function soNum(e){
    let tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla==8 || tecla==0) return true;
	else  return false;
    }
}

//onkeyup
function mascValor(campo){
    let v = $("#" + campo).val()
	if(v!=""){
		if(v.indexOf('.')!=-1){
			while(v.indexOf('.')!=-1){
				v=v.replace(".","");
			}
		}
		v=parseInt(v).toLocaleString('de-DE');
        $("#"+campo).val(v);
	}
}

function mascValorClear(n){
	if(n.indexOf('.')!=-1){
		while(n.indexOf('.')!=-1){
			n=n.replace(".","");
		}
    }
	return n
}

//PARA ABRIR AS CXS
function cx(cxId, focus=null){
	$(".camada").fadeIn()
	$(".now").val(now())
    $("#"+cxId).slideDown(500)
    
    if(focus != null){
        $("#"+focus).focus()
    }
}

//PARA PREENCHER DATA ATUAL NO INPUT DATE
function now(){
    let data = new Date()
    let dia = data.getDate()
        dia = (dia>=10) ? dia : "0" + dia
    let mes = data.getMonth()+1
        mes = (mes>=10) ? mes : "0" + mes
    let ano = data.getFullYear()

    return ano + "-" + mes + "-" + dia
}

function cancelar(){
    $(".camada").fadeOut()
    $(".cx").fadeOut()
}

//ENTER
function teclaEnter(e, botao){
    if(e.which == 13){
        $("#" + botao).click()
    }
}

/*
KEY_DOWN = 40; 
KEY_UP = 38; 
KEY_LEFT = 37; 
KEY_RIGHT = 39; 

KEY_END = 35; 
KEY_BEGIN = 36; 

KEY_BACK_TAB = 8; 
KEY_TAB = 9; 
KEY_SH_TAB = 16; 
KEY_ENTER = 13; 
KEY_ESC = 27; 
KEY_SPACE = 32; 
KEY_DEL = 46; 

KEY_A = 65; 
KEY_B = 66; 
KEY_C = 67; 
KEY_D = 68; 
KEY_E = 69; 
KEY_F = 70; 
KEY_G = 71; 
KEY_H = 72; 
KEY_I = 73; 
KEY_J = 74; 
KEY_K = 75; 
KEY_L = 76; 
KEY_M = 77; 
KEY_N = 78; 
KEY_O = 79; 
KEY_P = 80; 
KEY_Q = 81; 
KEY_R = 82; 
KEY_S = 83; 
KEY_T = 84; 
KEY_U = 85; 
KEY_V = 86; 
KEY_W = 87; 
KEY_X = 88; 
KEY_Y = 89; 
KEY_Z = 90; 

KEY_PF1 = 112; 
KEY_PF2 = 113; 
KEY_PF3 = 114; 
KEY_PF4 = 115; 
KEY_PF5 = 116; 
KEY_PF6 = 117; 
KEY_PF7 = 118; 
KEY_PF8 = 119;
*/