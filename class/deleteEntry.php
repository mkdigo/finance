<?php

class DeleteEntry{
    private $bind, $error, $pdo;

    function __construct($bind){
        $this->setBind($bind);
        $this->setPdo();
        $this->execute();
    }


    //PDO
    private function setPdo(){
        global $pdo;
        $this->pdo = $pdo;
    }

    private function getPdo(){
        return $this->pdo;
    }


    //BIND
    private function setBind($bi){
        $this->bind = $bi;
    }

    private function getBind(){
        return $this->bind;
    }


    //ERROR
    private function setError($er){
        $this->error = $er;
    }

    public function getError(){
        return $this->error;
    }


    //EXECUTE
    private function execute(){
        $sql = "DELETE FROM entries WHERE bind = :bind";
        $con = $this->getPdo()->prepare($sql);
        $con->bindValue(":bind", $this->getBind(), PDO::PARAM_INT);
        if($con->execute()){
            $this->setError(false);
        }else{
            $this->setError(true);
        }
    }
}

?>