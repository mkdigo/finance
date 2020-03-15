const page = "Accounts";

$("#toolsAdd").addEventListener("click", ()=>{
    box("boxAdd", "addAccount");
})

$("#addConfirm").addEventListener("click", ()=>{
    if($("#addAccount").value == ""){
        error($("#addAccount"));
    }else{
        errorClear($("#addAccount"));
        request("add");
    }
})

$("#editConfirm").addEventListener("click", ()=>{
    if($("#editAccount").value == ""){
        error($("#editAccount"));
    }else{
        errorClear($("#editAccount"));
        request("edit");
    }
})

function edit(id, account, group, subGroup){
    box("boxEdit");
    $("#editAccount").value = account;
    $("#editGroup").value = group;
    changeGroup('edit');
    $("#editSubGroup").value = subGroup;
    $("#editAccountId").value = id;
}


//SET SUBGROUP
const assets = "<option value='Circulante'>Circulante</option><option value='Permanente'>Permanente</option><option value='Realizável a longo prazo'>Realizável a longo prazo</option>";

const liabilities = "<option value='Circulante'>Circulante</option><option value='Exigível a longo prazo'>Exigível a longo prazo</option>";

const equity = "<option value='Capital Social'>Capital Social</option><option value='Lucros/Prejuizos acumulado'>Lucros/Prejuizos acumulado</option>";

const income = "<option value='Despesas'>Despesas</option><option value='Receitas'>Receitas</option>";

function changeGroup(box){
    let group = $("#"+box+"Group").value;
    switch(group){
        case "Ativo":
            $("#"+box+"SubGroup").innerHTML = assets;
            break;
        case "Passivo":
            $("#"+box+"SubGroup").innerHTML = liabilities;
            break;
        case "Patrimônio Líquido":
            $("#"+box+"SubGroup").innerHTML = equity;
            break;
        case "Contas de Resultado":
            $("#"+box+"SubGroup").innerHTML = income;
            break;
        default:
            $("#"+box+"SubGroup").innerHTML = "";
    }
}


$("#addGroup").addEventListener("change", ()=>{
    changeGroup("add");
});

$("#editGroup").addEventListener("change", ()=>{
    changeGroup("edit");
});


/*
ASSETS
<option value='Circulante'>Circulante</option>
<option value='Permanente'>Permanente</option>
<option value='Realizável a longo prazo'>Realizável a longo prazo</option>

LIABILITIES
<option value='Circulante'>Circulante</option>
<option value='Exigível a longo prazo'>Exigível a longo prazo</option>

EQUITY
<option value='Lucros/Prejuizos acumulado'>Lucros/Prejuizos acumulado</option>
<option value='Capital Social'>Capital Social</option>

INCOME STATEMENT
<option value='Despesas'>Despesas</option>
<option value='Receitas'>Receitas</option>
*/

function request(action = null){
    let load = new Load();
    load.loadOn();

    let form;

    switch(action){
        case "add":
            form = new FormData($("#addForm"));
            break;
        case "edit":
            form = new FormData($("#editForm"));
            break;
        default:
            form = new FormData();
    }

    form.append("action", action);
    

    fetch('accountsApi.php', {
        method: 'post',
        body: form
    }).then((response)=>{
        if(!response.ok) throw new Error('Erro ao executar requisição, status ' + response.status);
        return response.json();
    }).then((data)=>{
        close();
        if(!data.error){

            $(".container").innerHTML = data.list;
            
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