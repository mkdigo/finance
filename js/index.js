$(document).ready(function(){



	$("#loginButton").click(function(){
		usuario = $("#usuario").val()
		senha = $("#senha").val()
		if(usuario == ""){
			$("#usuario").css('background-color','#CC3');
			$("#senha").css('background-color','#FFF');
			$("#usuario").focus();
		}
		else if(senha == ""){
			$("#senha").css('background-color','#CC3');
			$("#usuario").css('background-color','#FFF');
			$("#senha").focus();
		}
		else{
			$("#usuario").css('background-color','#FFF');
			$("#senha").css('background-color','#FFF');
			$("#loginForm").submit()
		}
	});

	//PRESSIONAR TECLA ENTER
	$("input").keyup(function(e){
		if(e.which == 13){
			$("#loginButton").click()
		}
	})
	
});
