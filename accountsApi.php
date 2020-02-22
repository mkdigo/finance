<?php

session_start();
require_once("class/connectPDO.php");
require_once("class/functions.php");
require_once("class/entry.php");
require_once("class/deleteEntry.php");

if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
} else{
    $userId = $_SESSION['userId'];

    $json['error'] = false;
    $json['errorMsg'] = "Sem Erros";

    $action = $_POST['action'];

    
    function add(){
        global $pdo, $json;

        try{
            $account = $_POST['account'];
            $group = $_POST['group'];
            $subGroup = $_POST['subGroup'];
    
            $sql = "INSERT INTO accounts (account, `group`, `subgroup`) VALUES(:account, :group, :subgroup)";
            $con = $pdo->prepare($sql);
            $con->bindValue(":account", $account, PDO::PARAM_STR);
            $con->bindValue(":group", $group, PDO::PARAM_STR);
            $con->bindValue(":subgroup", $subGroup, PDO::PARAM_STR);

            if(!$con->execute()){
                throw new Exception("Insert Error");
            }
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
    }


    function load(){
        global $pdo, $json;

        try{
            $json['list'] = "
                <ul>
                    <li>Conta</li>
                    <li>Grupo</li>
                    <li>Sub-Grupo</li>
                </ul>
            ";
    
            $sql = "SELECT * FROM accounts ORDER BY account";
            $con = $pdo->prepare($sql);
            
            if(!$con->execute()){
                throw new Exception("Accounts query erro");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);
            foreach($list as $rows){
                $json['list'] .= "
                    <ul>
                        <li>$rows->account</li>
                        <li>$rows->group</li>
                        <li>$rows->subgroup</li>
                    </ul>
                ";
            }
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
    }

    switch($action){
        case "add":
            add();
        break;
    }
    
    load();
    
    $json = json_encode($json);
    echo $json;

}

?>