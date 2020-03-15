<?php

require_once("class/connectPDO.php");

class Login{
	private $user, $pass, $status, $statusMsg;
	
	function __construct($us,$pa){
		$this->setUser($us);
		$this->setPass($pa);
		$this->execute();
	}
		
	//GET
	
	private function getUser(){
		return $this->user;
	}

	
	private function getPass(){
		return $this->pass;
	}

	
	public function getStatusMsg(){
		return $this->statusMsg;
	}


	public function getStatus(){
		return $this->status;
	}

	
	//SET
	
	private function setUser($user){
		$this->user = addslashes(trim($user));
	}

	
	private function setPass($pass){
		$this->pass = addslashes(trim($pass));
	}

	
	private function setStatusMsg($sm){
		$this->statusMsg = $sm;
	}


	private function setStatus($s){
		$this->status = $s;
	}


	private function execute(){
		global $pdo;

		try{
			$sql = "SELECT * FROM user WHERE user = :user";;
			$con = $pdo->prepare($sql);
			$con->bindValue(":user", $this->getUser(), PDO::PARAM_STR);
			
			if(!$con->execute()){
				throw new Exception("User query error");
			}

			if($con->rowCount() == 1){
				$r = $con->fetchObject();
				$salt = $r->salt;
				$bd_pass = $r->password;
				$pass_md5 = md5($this->getPass().$salt);
				if($bd_pass == $pass_md5){
					session_start();
					$_SESSION['user'] = $this->getUser();
					$_SESSION['userName'] = $r->name;
					$_SESSION['login'] = "bc3326c033a42c1ddb4e5a";
					$_SESSION['userId'] = $r->id;
					$_SESSION['alt'] = $r->alt;
					$this->setStatus(true);

					$this->setStatusMsg("Login ok");
				}else{
					throw new Exception("Usuário ou Senha inválida!");
				}
			}else{
				throw new Exception("Usuário ou Senha inválida!");
			}
			
		}catch(Exception $e){
			$this->setStatus(false);
			$this->setStatusMsg($e->getMessage());
		}

		
	}

}

?>