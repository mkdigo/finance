<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
}else{
	require_once("connectPDO.php");
	require_once("connect.php");
	$userId = $_SESSION['userId'];


    require_once("connectPDO.php");
    require_once("class/functions.php");

    $acao = $_POST['acao'];

    if($acao == "add"){

        $contaId = $_POST['contaId'];
        $valor = $_POST['valor'];
        $venc = $_POST['venc'];
        $obs = $_POST['obs'];

        if(!empty($contaId) && !empty($valor) && !empty($venc)){
            $sql = "INSERT INTO pagar (conta, valor, vencimento, obs) VALUES (:conta, :valor, :venc, :obs)";
            $con = $pdo->prepare($sql);
            $con->bindValue(":conta", $contaId, PDO::PARAM_INT);
            $con->bindValue(":valor", $valor, PDO::PARAM_INT);
            $con->bindValue(":venc",$venc,PDO::PARAM_STR);
            $con->bindValue(":obs",$obs,PDO::PARAM_STR);
            $con->execute();
        }

    }else if($acao == "del"){
        $id = $_POST['id'];

        if(!empty($id)){
            $sql = "DELETE FROM pagar WHERE id = :id";
            $con = $pdo->prepare($sql);
            $con->bindValue(":id", $id, PDO::PARAM_INT);
            $con->execute();
        }
    }else if($acao == 'baixa'){
        $pagarId = $_POST['id'];
        $debitoId = $_POST['debitoId'];
        $creditoId = $_POST['creditoId'];
        $valor = $_POST['valor'];
        $pagamento = $_POST['pagamento'];
        $obs = $_POST['obs'];

        if(!empty($pagarId) || !empty($debitoId) || !empty($creditoId) || !empty($valor) || !empty($pagamento)){
            $sql = "UPDATE pagar SET pagamento = '$pagamento' WHERE id = $pagarId";
            $con = $pdo->prepare($sql);
            $con->execute();
        
            lancar($pagamento, $debitoId, $creditoId, $valor, $obs, $pdo, $userId);
        }
    }

    echo"
        <ul>
            <li>Conta</li>
            <li>Valor</li>
            <li>Vencimento</li>
            <li>Obs</li>
        </ul>
    ";

    $sql = "SELECT * FROM pagar WHERE pagamento IS NULL ORDER BY vencimento";
    $con = $pdo->prepare($sql);
    $con->execute();
    $list = $con->fetchAll(PDO::FETCH_OBJ);
    $tt = 0;
    foreach($list as $rows){
        $sql2 = "SELECT conta FROM contas WHERE id = $rows->conta";
        $con2 = $pdo->prepare($sql2);
        $con2->execute();
        $list2 = $con2->fetch(PDO::FETCH_ASSOC);
        $conta = $list2['conta'];
        $valor = number($rows->valor);
        $venc = $rows->vencimento;
        $obs = $rows->obs;

        $baixa = "onclick=\"baixa($rows->id, $rows->conta, '$conta', '$valor', '$venc', '$obs')\"";
        echo"
            <ul>
                <li $baixa>$conta</li>
                <li $baixa>$valor</li>
                <li $baixa>$venc</li>
                <li>$obs<img src='templates/excluir.png' onclick='cxDel($rows->id)'/></li>
            </ul>
        ";
        $tt += $rows->valor;
    }
    echo"
        <script>
            document.getElementById('tt').innerHTML = '".number($tt)."'
        </script>
    ";
}
?>