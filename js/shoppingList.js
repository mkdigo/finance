const page = "ShoppingList";

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
            form.append("id", delId);
            break;
        default:
            form = new FormData();
    }

    form.append("action", action);
    

    fetch('shoppingListApi.php', {
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


//ADD
$("#toolsAdd").addEventListener("click", ()=>{
    box('boxAdd', 'addProduct');
    $("#addQuantity").value = 1;
});

$("#addConfirm").addEventListener("click", ()=>{
    let fields = $("#addQuantity, #addProduct");
    let e = 0;

    for(let x = 0; x < fields.length; x++){
        if(fields[x].value == ""){
            error(fields[x]);
            e++
        }else{
            errorClear(fields[x]);
        }
    }

    if(e == 0){
        request("add");
    }
});

enter("add");


//DELETE
var delId = null;

function del(id){
    delId = id;
    box("boxDel");
}


$("#delConfirm").addEventListener("click", ()=>{
    request("del");
});
