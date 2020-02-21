<?php
    try{
        $dbnameLocal = "finance";
        $userLocal = "root";
        $passLocal = "";

        $dbname = "u824314751_finan";
        $user = "u824314751_digo";
        $pass = "Apollo1104";

        $pdo=new PDO("mysql:host=localhost;dbname=$dbnameLocal;charset=utf8","$userLocal","$passLocal");
        // $pdo=new PDO("mysql:host=localhost;dbname=$dbname;charset=utf8","$user","$pass");
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
    date_default_timezone_set('Asia/Tokyo');
?>