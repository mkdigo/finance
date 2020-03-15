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

    $json['error'] = false;
    $json['errorMsg'] = "Sem Erros";

    $action = $_POST['action'];

    function load(){
        global $pdo, $json;

        $sql = "SELECT * FROM shopping ORDER BY product";
        $con = $pdo->prepare($sql);
        
        if(!$con->execute()){
            $json['error'] = true;
            $json['errorMsg'] = "Shooping list query error";
        }

        $list = $con->fetchAll(PDO::FETCH_OBJ);

        $json['list'] = "
            <ul>
                <li>Produto</li>
                <li>Qtde</li>
                <li>Obs</li>
                <li></li>
            </ul>
        ";

        foreach($list as $rows){
            $json['list'] .= "
                <ul>
                    <li>$rows->product</li>
                    <li>$rows->quantity</li>
                    <li>$rows->comments</li>
                    <li>
                        <button type='button' onclick='del($rows->id)'>
                            <img src='templates/trash.png' />
                        </button>
                    </li>
                </ul>
            ";
        }
    }


    function add(){
        global $pdo, $json;

        $product = $_POST['product'];
        $quantity = $_POST['quantity'];
        $comments = $_POST['comments'];

        $sql = "INSERT INTO shopping (product, quantity, comments) VALUES (:product, :quantity, :comments)";
        $con = $pdo->prepare($sql);
        $con->bindValue(":product", $product, PDO::PARAM_STR);
        $con->bindValue(":quantity", $quantity, PDO::PARAM_INT);
        $con->bindValue(":comments", $comments, PDO::PARAM_STR);

        if(!$con->execute()){
            $json['error'] = true;
            $json['errorMsg'] = "Insert error";
        }
    }


    function del(){
        global $pdo, $json;

        $id = $_POST['id'];
        
        $sql = "DELETE FROM shopping WHERE id = :id";
        $con = $pdo->prepare($sql);
        $con->bindValue(":id", $id, PDO::PARAM_INT);

        if(!$con->execute()){
            $json['error'] = true;
            $json['errorMsg'] = "Delete error";
        }
    }


    switch($action){
        case "add":
            add();
        break;
        case "del":
            del();
        break;
    }


    load();


$json = json_encode($json);
echo $json;

}
