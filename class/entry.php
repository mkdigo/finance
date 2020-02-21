<?php

class Entry{
    
    private $date, $debitId, $creditId, $value, $comments, $userId, $pdo, $error, $errorMsg;

    function __construct($date, $debitId, $creditId, $value, $comments, $userId){
        $this->setDate($date);
        $this->setDebitId($debitId);
        $this->setCreditId($creditId);
        $this->setValue($value);
        $this->setComments($comments);
        $this->setUserId($userId);
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


    //DATE
    private function setDate($da){
        $this->date = $da;
    }

    private function getDate(){
        return $this->date;
    }
    
    //DEBIT ID
    private function setDebitId($de){
        $this->debitId = $de;
    }

    private function getDebitId(){
        return $this->debitId;
    }


    //CREDIT ID
    private function setCreditId($cr){
        $this->creditId = $cr;
    }

    private function getCreditId(){
        return $this->creditId;
    }


    //VALUE
    private function setValue($va){
        $this->value = str_replace(".", "", $va);
    }

    private function getValue(){
        return $this->value;
    }


    //COMMENTS
    private function setComments($co){
        $this->comments = $co;
    }

    private function getComments(){
        return $this->comments;
    }

    
    //USER ID
    private function setUserId($us){
        $this->userId = $us;
    }

    private function getUserId(){
        return $this->userId;
    }

    
    //ERROR
    private function setError($er){
        $this->error = $er;
    }

    public function getError(){
        return $this->error;
    }

    
    //ERROR MESSAGE
    private function setErrorMsg($msg){
        $this->errorMsg = $msg;
    }

    public function getErrorMsg(){
        return $this->errorMsg;
    }


    //EXECUTE
    private function execute(){
        try{
            //DEBIT
            $sql = "INSERT INTO entries(`date`, account_id, debit, comments, user) VALUES(:dat, :account_id, :debit, :comments, :userId);";
            $con = $this->getPdo()->prepare($sql);
            $con->bindValue(":dat", $this->getDate(), PDO::PARAM_STR);
            $con->bindValue(":account_id", $this->getDebitId(), PDO::PARAM_INT);
            $con->bindValue(":debit", $this->getValue(), PDO::PARAM_INT);
            $con->bindValue(":comments", $this->getComments(), PDO::PARAM_STR);
            $con->bindValue(":userId", $this->getUserId(), PDO::PARAM_INT);
            
            if(!$con->execute()){
                throw new Exception("DEBIT ENTRY ERROR");
            }
    
            $lastId = $this->getPdo()->lastInsertId();
    
            $sql = "UPDATE entries SET bind = $lastId WHERE id = $lastId;";
            $con = $this->getPdo()->prepare($sql);
            
            $con->execute();
    
    
            //CREDIT
            $sql = "INSERT INTO entries(`date`, account_id, credit, comments, user, bind) VALUES(:dat, :account_id, :credit, :comments, :userId, :bind);";
            $con = $this->getPdo()->prepare($sql);
            $con->bindValue(":dat", $this->getDate(), PDO::PARAM_STR);
            $con->bindValue(":account_id", $this->getCreditId(), PDO::PARAM_INT);
            $con->bindValue(":credit", $this->getValue(), PDO::PARAM_INT);
            $con->bindValue(":comments", $this->getComments(), PDO::PARAM_STR);
            $con->bindValue(":userId", $this->getUserId(), PDO::PARAM_INT);
            $con->bindValue(":bind", $lastId, PDO::PARAM_INT);
            
            if(!$con->execute()){
                $del = new DeleteEntry($lastId);
                throw new Exception("CREDIT ENTRY ERROR");
            }
    
            $this->setError(false);
    
        }catch(Exception $e){
            $this->setError(true);
            $this->setErrorMsg($e->getMessage());
        }
    }

}

?>