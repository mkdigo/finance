<?php

function banco($dat, $debitoId, $creditoId, $valor, $tarifaDebito, $tarifaCredito, $obs, $pdo, $userId){
    $tarifaId = 38;
    
    $debitoValor = ($tarifaDebito > 0) ? $valor - $tarifaDebito : $valor;
    $creditoValor = ($tarifaCredito > 0) ? $valor + $tarifaCredito : $valor;

    //LANÇAR CRÉDITO
    $sql = "INSERT INTO lancamentos(dat,conta,credito,obs,usuario) VALUES(:dat,:conta,:credito,:obs,:userId);";
    $con = $pdo->prepare($sql);
    $con->bindValue(":dat",$dat,PDO::PARAM_STR);
    $con->bindValue(":conta",$creditoId,PDO::PARAM_INT);
    $con->bindValue(":credito",$creditoValor,PDO::PARAM_INT);
    $con->bindValue(":obs",$obs,PDO::PARAM_STR);
    $con->bindValue(":userId",$userId,PDO::PARAM_INT);
    $con->execute();
    
    $lastId = $pdo->lastInsertId();

    $sql = "UPDATE lancamentos SET amarracao = $lastId WHERE id = $lastId;";
    $con = $pdo->prepare($sql);
    $con->execute();

    //LANÇAR DEBITO
    $sql = "INSERT INTO lancamentos(dat,conta,debito,obs,usuario,amarracao) VALUES(:dat,:conta,:debito,:obs,:userId,:amarracao);";
    $con = $pdo->prepare($sql);
    $con->bindValue(":dat",$dat,PDO::PARAM_STR);
    $con->bindValue(":conta",$debitoId,PDO::PARAM_INT);
    $con->bindValue(":debito",$debitoValor,PDO::PARAM_INT);
    $con->bindValue(":obs",$obs,PDO::PARAM_STR);
    $con->bindValue(":userId",$userId,PDO::PARAM_INT);
    $con->bindValue(":amarracao",$lastId,PDO::PARAM_INT);
    $con->execute();

    //LANÇAR TARIFA
    if($tarifaCredito > 0 || $tarifaDebito > 0){
        $tarifa = $tarifaCredito + $tarifaDebito;
        $sql = "INSERT INTO lancamentos(dat,conta,debito,usuario,amarracao) VALUES(:dat,:conta,:debito,:userId,:amarracao);";
        $con = $pdo->prepare($sql);
        $con->bindValue(":dat",$dat,PDO::PARAM_STR);
        $con->bindValue(":conta",$tarifaId,PDO::PARAM_INT);
        $con->bindValue(":debito",$tarifa,PDO::PARAM_INT);
        $con->bindValue(":userId",$userId,PDO::PARAM_INT);
        $con->bindValue(":amarracao",$lastId,PDO::PARAM_INT);
        $con->execute();
    }
}


?>