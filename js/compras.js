const page = "compras"

$(document).ready(function(){
    $("#addConfirmar").click(function(){
        let produto = $("#addProduto").val()
        let qtde = $("#addQtde").val()
        let obs = $("#addObs").val()

        $.ajax({
            url: 'compras_sql.php',
            type: 'POST',
            cache: false,
            dataType: "html",
            data: {
                acao: 'add',
                produto: produto,
                qtde: qtde,
                obs: obs
            },
            success: function(data){
                $(".conteudo div").empty().html(data)
                cancelar()
            }
        })
    })
})

var delId = null

function cxDel(id){
    delId = id;
    cx('cxDel')
}

function del(){
    $.ajax({
        url:'compras_sql.php',
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