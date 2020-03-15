const page = "Revenue"

const date = new Date;
const year = date.getFullYear();
const month = date.getMonth()+1;

$("#year").value = year;
$("#month").value = month;

$("#year").addEventListener("change", ()=>{
    request();
});

$("#month").addEventListener("change", ()=>{
    request();
});

function request(action = null){
    let load = new Load();
    load.loadOn();

    let form;

    switch(action){
        case "add":
            form = new FormData($("#addForm"));
            break;
        case "del":
            form = new FormData();
            form.append("id", deleteId);
            break;
        default:
            form = new FormData();
    }
    
    form.append("action", action);
    form.append("year", $("#year").value);
    form.append("month", $("#month").value);
    

    fetch('revenueApi.php', {
        method: 'post',
        body: form
    }).then((response)=>{
        if(!response.ok) throw new Error('Erro ao executar requisição, status ' + response.status);
        return response.json();
    }).then((data)=>{
        close();
        if(!data.error){

            $(".flexUl").innerHTML = data.list;
            
            $("#ttDays").innerHTML = data.ttDays;
            $("#ttOvertime").innerHTML = data.ttOvertime;
            $("#ttNights").innerHTML = data.ttNights;
            $("#grossAmount").innerHTML = data.grossAmount;
            
            $("#healthInsurance").innerHTML = data.healthInsurance;
            $("#retirement").innerHTML = data.retirement;
            $("#unemploymentInsurance").innerHTML = data.unemploymentInsurance;
            $("#incomeTax").innerHTML = data.incomeTax;
            
            $("#ttDiscounts").innerHTML = data.ttDiscounts;
            $("#netValue").innerHTML = data.netValue;

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


//ADD
$("#addConfirm").addEventListener("click", ()=>{
    request("add");
});

enter("add");


//DELETE
var deleteId = null;

function del(id){
    deleteId = id;
    box("boxDelete");
}


$("#deleteConfirm").addEventListener("click", ()=>{
    request("del");
});