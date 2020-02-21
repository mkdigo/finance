<?php
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a" || !isset($_POST['acao'])){
        session_destroy();
        header("location:index.php");
    }else{
    
        $userId = $_SESSION['userId'];
        require_once("connectPDO.php");
        require_once("class/functions.php");
        require_once("class/banco.php");
        
        $contaId = $_POST['contaId'];
        $acao = $_POST['acao'];
        
        if($acao == "excluir"){
            $amarracao = $_POST['amarracao'];
            deleteLancamento($amarracao, $pdo);
        }else{
            $debitoId = $_POST['debitoId'];
            $creditoId = $_POST['creditId'];
            
            $dat = $_POST['dat'];
            $valor = $_POST['valor'];
            $tarifa = $_POST['tarifa'];
            $obs = $_POST['obs'];
            $obs = ($tarifa > 0) ? "Tarifa: " . $tarifa . ", " . $obs : $obs;
            $obs = ($acao == "cartao") ? "Cartão, " . $obs : $obs;
        
        

            if($acao == "saque" || $acao == "cartao"){
                banco($dat, $debitoId, $creditoId, $valor, 0, $tarifa, $obs, $pdo, $userId);
            }elseif($acao == "deposito"){
                banco($dat, $debitoId, $creditoId, $valor, $tarifa, 0, $obs, $pdo, $userId);
            }
        }

        echo"
            <ul>
            <li>Data</li>
            <li>Débito</li>
            <li>Crédito</li>
            <li>Saldo</li>
            <li>Obs</li>
            </ul>
        ";

        //PEGAR SALDO
        $sql = "SELECT (SUM(`debito`) - SUM(`credito`)) as saldo FROM `lancamentos` WHERE conta = $contaId";
        $con = $pdo->prepare($sql);
        $con->execute();
        $result = $con->fetch(PDO::FETCH_ASSOC);
        $saldo = $result['saldo'];

        //LISTAR TABELA
        $sql = "SELECT * FROM lancamentos WHERE conta = $contaId ORDER BY dat DESC, id DESC";
        $con = $pdo->prepare($sql);
        $con->execute();
        $list = $con->fetchAll(PDO::FETCH_OBJ);

        $debitoTT = 0;
        $creditoTT = 0;
        foreach($list as $rows){
            echo"
                <ul>
                    <li>$rows->dat</li>
                    <li>".number($rows->debito)."</li>
                    <li>".number($rows->credito)."</li>
                    <li>".number($saldo)."</li>
                    <li>$rows->obs<img src='templates/excluir.png' onclick='cxExcluir($rows->amarracao)'/></li>
                </ul>
            ";

            $saldo = $saldo - $rows->debito + $rows->credito;
            $debitoTT += $rows->debito;
            $creditoTT += $rows->credito;
            
        }
        $saldoTT = number($debitoTT - $creditoTT);

        echo"
            <script>
                $('.saldo span').html('$saldoTT')
            </script>
        ";
    }
?>