const page = "BillsToPay";


function request(action = null){
    let load = new Load();
    load.loadOn();

    let form;

    switch(action){
        case "add":
            form = new FormData($("#addForm"));
            break;
        case "pay":
            form = new FormData($("#payForm"));
            break;
        case "del":
            form = new FormData();
            form.append("id", delBillId);
            break;
        default:
            form = new FormData();
    }

    form.append("action", action);
    

    fetch('billsToPayApi.php', {
        method: 'post',
        body: form
    }).then((response)=>{
        if(!response.ok) throw new Error('Erro ao executar requisição, status ' + response.status);
        return response.json();
    }).then((data)=>{
        close();
        if(!data.error){

            $(".container").innerHTML = data.list;
            $(".amount span").innerHTML = data.amount;
            $("#addAccountId").innerHTML = data.accounts;
            
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

request();


$("#addConfirm").addEventListener("click", ()=>{
    let values = $("#addValue, #addDueDate");
    let e = 0;

    for(let x = 0; x < values.length; x++){
        if(values[x].value == ""){
            error(values[x]);
            e++;
        }else{
            errorClear(values[x]);
        }
    }

    if(e == 0){
        request("add");
    }
});

enter("add");


function pay(id, account, value, dueDate){
    $("#payId").value = id;
    $("#payAccount").innerHTML = account;
    $("#payValue").innerHTML = value;
    $("#payDueDate").innerHTML = dueDate;
    box("boxPay", "payDate");
}


$("#payConfirm").addEventListener("click", ()=>{
    let date = $("#payDate");
    if(date.value == ""){
        error(date);
    }else{
        errorClear(date);
        request("pay");
    }
});

enter("pay");


var delBillId = null;
function del(id, account, value, dueDate){
    delBillId = id;
    $("#delAccount").innerHTML = account;
    $("#delValue").innerHTML = value;
    $("#delDueDate").innerHTML = dueDate;
    box("boxDel");
}


$("#delConfirm").addEventListener("click", ()=>{
    request("del");
});