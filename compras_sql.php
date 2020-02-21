<?php
require_once("connectPDO.php");

if(isset($_POST['acao'])){
    $acao = $_POST['acao'];

    if($acao == 'del'){
        $id = $_POST['id'];

        $sql = "DELETE FROM compras WHERE id = $id";
        $con = $pdo->prepare($sql);
        $con->execute();
    }elseif($acao == 'add'){
        $produto = $_POST['produto'];
        $qtde = $_POST['qtde'];
        $obs = $_POST['obs'];

        if(!empty($produto) && !empty($qtde) && $qtde != 0){
            $sql = "INSERT INTO compras (produto, qtde, obs) VALUES(:produto, :qtde, :obs)";
            $con = $pdo->prepare($sql);
            $con->bindValue(":produto", $produto, PDO::PARAM_STR);
            $con->bindValue(":qtde", $qtde, PDO::PARAM_INT);
            $con->bindValue(":obs", $obs, PDO::PARAM_STR);
            $con->execute();
        }
    }
}


echo"
    <ul>
        <li>Produto</li>
        <li>Qtde</li>
        <li>Obs</li>
    </ul>
";

$sql = "SELECT * FROM compras ORDER BY produto";
$con = $pdo->prepare($sql);
$con->execute();
$list = $con->fetchAll(PDO::FETCH_OBJ);
foreach($list as $rows){
    echo"
    <ul>
        <li>$rows->produto</li>
        <li>$rows->qtde</li>
        <li>$rows->obs<img src='templates/excluir.png' onclick='cxDel($rows->id)'/></li>
    </ul>
    ";
}

?>