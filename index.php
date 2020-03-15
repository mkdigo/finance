<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/global.css" rel="stylesheet" type="text/css"/>
    <link href="css/index.css" rel="stylesheet" type="text/css"/>
    <link rel="apple-touch-icon" sizes="114x114" href="templates/logo.png" />
    <link rel="icon" type="imagem/jpeg" href="templates/logo.png" />
    <script src="js/index.js"></script>
    <title>Sistema de Controle Financeiro</title>
</head>
<body>

<?php
require_once("class/connectPDO.php");
function getIP() {
	$ipaddress=isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"UNKNOWN";
	return $ipaddress;
}

$ip=getIP();
$now=time();

$sql="SELECT * FROM login_miss WHERE ip='$ip' AND t BETWEEN $now-600 AND $now";
$exec=$pdo->prepare($sql);
$exec->execute();
$numRows=$exec->rowCount();

if($numRows >= 3){ 
    echo"
        <div class='login'>
        <div>Controle Financeiro</div>
        <div style='text-align: center; margin-top: 70px; color: rgb(180, 30, 80); font-size: 28px;'>Sistema Bloquado!</div>
        </div>
    ";
}
else{
?>

<div class="login">
    <div>Controle Financeiro</div>
    <div>
        <form action="" method="post" id="loginForm">
        <ul>
            <li>
                <label for="user">Usuário: </label>
                <input type="text" name="user" id="user"/>
            </li>
            <li>
                <label for="pass">Senha: </label>
                <input type="password" name="pass" id="pass"/>
            </li>
            <li>
                <span id="msg"></span>
                <button type="submit" id="loginButton">Entrar</button>
            </li>
        </ul>
        </form>
    </div>
</div>

<?php
    if(isset($_POST['user'])){
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        
        require_once("class/login.php");
        
        $l = new Login($user, $pass);
        
        if($l->getStatus()){
            header("location:balance.php");
        }
        else{
            $sql = "INSERT INTO login_miss(ip,t)VALUES(?,?);";
            $exec = $pdo->prepare($sql);
            $exec->execute(array($ip, $now));
            
            echo"
                <script>
                    document.querySelector(\"#msg\").innerHTML = \"".$l->getStatusMsg()."\";
                </script>
            ";
        }
    
    }
}
?>

</body>
</html>