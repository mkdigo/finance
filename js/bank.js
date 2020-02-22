const page = "Bank";


function request(action, formulary = null){
	let load = new Load();
	load.loadOn();

	let urlVar = getUrlVar();
	let accountId = urlVar['id'];

	let form;

	switch(action){
        case "withdraw":
			form = new FormData(formulary);
			form.append("creditId", accountId);
			break;
		case "card":
			form = new FormData(formulary);
			form.append("creditId", accountId);
			break;
		case "deposit":
			form = new FormData(formulary);
			form.append("debitId", accountId);
			break;
		case "del":
			form = new FormData();
			form.append('bind', deleteBindId);
			break;
        default:
			form = new FormData();
    }

	form.append("action", action);
	form.append("accountId", accountId);

	fetch('bankApi.php', {
		method: 'post',
		body: form
	}).then((response)=>{
		if(!response.ok) throw new Error('Erro ao executar requisição, status ' + response.status);
		return response.json();
	}).then((data)=>{
		close();
		if(!data.error){

			$(".header").innerHTML = data.account;
			$("#amount").innerHTML = data.amount;
			$(".container").innerHTML = data.list;
			$("#withdrawDebitId").innerHTML = data.withdrawDebit;
			$("#cardDebitId").innerHTML = data.cardDebit;
			$("#depositCreditId").innerHTML = data.depositCredit;
			
		}else{
			$("#boxError").innerHTML = data.errorMsg;
            box("boxError");
            setTimeout(()=>{close();}, 2500);
		}

		load.loadOff();
	}).catch((err)=>{
		console.log(err);
	});
}

request("load");


//VALIDATION
function validation(action){
	let form = $("#"+action+"Form input");

	for(let x = 0; x < form.length; x++){
		form[x].addEventListener("keyup", ()=>{
			keyEnter(event, "#"+action+"Confirm");
		});
	}

	$("#"+action+"Confirm").addEventListener("click", ()=>{
		let form = $("#"+action+"Form input[type=date], #"+action+"Form input[name=value], #"+action+"Form select");

		let e = 0;
		
		for(let x = 0; x < form.length; x++){
			if(form[x].value == ""){
				error(form[x]);
				e++;
			}else{
				errorClear(form[x]);
			}
		}
	
		if(e == 0){
			request(action, $("#"+action+"Form"));
		}
	});
}


validation('withdraw');
validation('card');
validation('deposit');


//DELETE
var deleteBindId = null;

function del(bind){
	deleteBindId = bind;
	box("delete");
}


$("#deleteConfirm").addEventListener("click", ()=>{
	request("del");
})