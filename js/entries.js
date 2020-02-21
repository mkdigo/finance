page = "Entries";


//EXPENSES
$("#toolsExpenses").addEventListener("click", ()=>{
    box("boxExpenses", "expensesDebitId");
    $("#expensesDate").value = now();
});

const expensesForm = $("#expensesForm input[type=text]");

for(let x = 0; x < expensesForm.length; x++){
	expensesForm[x].addEventListener("keydown", ()=>{
		keyEnter(event, "#expensesConfirm");
	})
}

$("#expensesConfirm").addEventListener("click", ()=>{
	if(validation("expenses")){
		request("add", $("#expensesForm"));
	}
});



//ENTRIES
$("#toolsEntries").addEventListener("click", ()=>{
    box("boxEntry", "entryDebitId");
    $("#entryDate").value = now();
});

const entryForm = $("#entryForm input[type=text]");

for(let x = 0; x < entryForm.length; x++){
	entryForm[x].addEventListener("keydown", ()=>{
		keyEnter(event, "#entryConfirm");
	})
}

$("#entryConfirm").addEventListener("click", ()=>{
	if(validation("entry")){
		request("add", $("#entryForm"));
	}
});


//REVENUES
$("#toolsRevenues").addEventListener("click", ()=>{
    box("boxRevenues", "revenuesDebitId");
    $("#revenuesDate").value = now();
});

const revenuesForm = $("#revenuesForm input[type=text]");

for(let x = 0; x < revenuesForm.length; x++){
	revenuesForm[x].addEventListener("keydown", ()=>{
		keyEnter(event, "#revenuesConfirm");
	})
}

$("#revenuesConfirm").addEventListener("click", ()=>{
	if(validation("revenues")){
		request("add", $("#revenuesForm"));
	}
});


function validation(fields){
	let date = $("#"+fields+"Date");
	let debit = $("#"+fields+"DebitId");
	let credit = $("#"+fields+"CreditId");
	let value = $("#"+fields+"Value");
	if(date.value == ""){
		error(date);
		return false;
	}else if(debit.value == ""){
		error(debit);
		return false;
	}else if(credit.value == ""){
		error(credit);
		return false;
	}else if(value.value == ""){
		error(value);
		return false;
	}else{
		return true;
	}
}


var deleteBindId = null;

function del(id){
    deleteBindId = id;
    box("boxDelete");
}


$("#deleteConfirm").addEventListener("click", ()=>{
    request("del");
})

function request(action, formulary = null){
	let load = new Load();
	load.loadOn();

	let form;

	switch(action){
        case "add":
			form = new FormData(formulary);
			break;
		case "del":
			form = new FormData();
			form.append('bind', deleteBindId);
			break;
        default:
			form = new FormData();
    }
	
	form.append('action', action);

	fetch('entriesApi.php', {
		method: 'post',
		body: form
	}).then((response)=>{
		if(!response.ok) throw new Error('Erro ao executar requisição, status ' + response.status);
		return response.json();
	}).then((data)=>{
		close();
		if(!data.error){
			$(".container").innerHTML = data.load;
			$("#expensesDebitId").innerHTML = data.expenses;
			$("#expensesCreditId").innerHTML = data.currentAssets;
			$("#entryDebitId").innerHTML = data.accounts;
			$("#entryCreditId").innerHTML = data.accounts;
			$("#revenuesDebitId").innerHTML = data.banks;
			$("#revenuesCreditId").innerHTML = data.revenues;
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


request('load');
