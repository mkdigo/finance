const page = "Bank";


function request(action, formulary = null){
	let load = new Load();
	load.loadOn();

	//action = action;

	let urlVar = getUrlVar();
	let accountId = urlVar['id'];

	let form;

	switch(action){
        case "withdraw":
			form = new FormData(formulary);
			form.append("creditId", accountId);
			action = "add";
			break;
		case "card":
			form = new FormData(formulary);
			form.append("creditId", accountId);
			action = "add";
			break;
		case "deposit":
			form = new FormData(formulary);
			form.append("debitId", accountId);
			action = "add";
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


$("#withdrawConfirm").addEventListener("click", ()=>{
	let form = $("#formWithdraw input[type=date], #formWithdraw input[name=value], #formWithdraw select");
	let e = 0;
	for(let x = 0; x < form.length; x++){
		if(form[x].value == ""){
			error(form[x]);
			e++;
		}
	}

	if(e == 0){
		request("withdraw", $("#formWithdraw"));
	}
});