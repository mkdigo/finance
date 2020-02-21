<?php


class Login{
	private $user, $pass, $sql, $ex, $rows, $status, $pdo;
	
	function __construct($us,$pa,$pdoConnect){
		$this->setUser($us);
		$this->setPass($pa);
		$this->setSql();
		$this->setPdo($pdoConnect);
		$this->setEx();
		$this->setRows();
		$this->setStatus();
	}
		
	//GET
	
	private function getUser(){
		return $this->user;
	}
	
	private function getPass(){
		return $this->pass;
	}
	
	private function getSql(){
		return $this->sql;
	}
	
	private function getEx(){
		return $this->ex;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	private function getRows(){
		return $this->rows;
	}
	
	private function getPdo(){
		return $this->pdo;
	}
	
	
	//SET
	
	private function setUser($user){
		$this->user = addslashes(trim($user));
	}
	
	private function setPass($pass){
		$this->pass = addslashes(trim($pass));
	}
	
	private function setSql(){
		$this->sql="select * from usuarios where user = :user";
	}
	
	private function setEx(){
		$this->ex=$this->getPdo()->prepare($this->getSql());
		$this->ex->bindValue(":user", $this->getUser());
		$this->ex->execute();
	}
	
	private function setRows(){
		$this->rows = $this->getEx()->rowCount();
	}
			
	private function setStatus(){
		if($this->getRows() == 1){
			$r=$this->getEx()->fetch(PDO::FETCH_ASSOC);
			$salt=$r['salt'];
			$bd_pass=$r['password'];
			$pass_md5=md5($this->getPass().$salt);
			if($bd_pass==$pass_md5){
				session_start();
				$_SESSION['user'] = $this->getUser();
				$_SESSION['userName'] = $r['name'];
				$_SESSION['login'] = "bc3326c033a42c1ddb4e5a";
				$_SESSION['userId'] = $r['id'];
				$_SESSION['alt'] = $r['alt'];
				$this->status=true;
			}
		}
		else{
			$this->status=false;
		}
	}
	
	private function setPdo($p){
		$this->pdo=$p;
	}
}

?>