<?php

// Se o status retornar 2 é porque o user já existe no banco

class UserAdd{
    private $name, $user, $pass, $newPass, $salt, $pdo, $sql, $con, $status;

    function __construct($name, $user, $pass, $pdoConnect){
        $this->setName($name);
        $this->setUser($user);
        $this->setPass($pass);
        $this->setPdo($pdoConnect);
        
        $this->exec();
    }

    //GET

    private function getName(){
        return $this->name;
    }

    private function getUser(){
        return $this->user;
    }
    
    private function getPass(){
        return $this->pass;
    }

    private function getNewPass(){
        return $this->newPass;
    }

    private function getSalt(){
        return $this->salt;
    }

    private function getPdo(){
        return $this->pdo;
    }

    private function getSql(){
        return $this->sql;
    }

    public function getStatus(){
        return $this->status;
    }

    //SET
    private function setName($na){
        $this->name = addslashes(trim($na));
    }

    private function setUser($us){
        $this->user = addslashes(trim($us));
    }

    private function setPass($pa){
        $this->pass = addslashes(trim($pa));
    }

    private function setNewPass(){
        $this->newPass = md5($this->getPass().$this->getSalt());
    }

    private function setSalt(){
        $this->salt = substr(sha1(mt_rand()), 0, 22);
    }

    private function setPdo($pd){
        $this->pdo = $pd;
    }

    private function setSql($sq){
        $this->sql = $sq;
    }

    private function setStatus($st){
        $this->status = $st;
    }

    private function exec(){
        $this->setSql("SELECT * FROM usuarios WHERE user = :user");
        $this->con =$this->getPdo()->prepare($this->getSql());
        $this->con->bindValue(":user",$this->getUser());
        $this->con->execute();
        if($this->con->rowCount() != 0){
            $this->setStatus(2);
        }
        else{
            $this->setSalt();
            $this->setNewPass();

            $this->setSql("INSERT INTO usuarios(name, user, password, salt) VALUES(:name, :user, :password, :salt)");
            $this->con =$this->getPdo()->prepare($this->getSql());
            $this->con->bindValue(":name",$this->getName());
            $this->con->bindValue(":user",$this->getUser());
            $this->con->bindValue(":password",$this->getNewPass());
            $this->con->bindValue(":salt",$this->getSalt());
            $this->con->execute();

            $this->setStatus($this->getNewPass()."<br/>".$this->getPass()."<br/>".$this->getSalt());

        }
    }
}
?>