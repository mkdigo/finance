<?php

class Balance{
    private $group, $subGroup, $month, $year, $pdo, $amount, $print, $error, $errorMsg;

    function __construct($group, $subGroup, $month, $year){
        $this->setGroup($group);
        $this->setSubGroup($subGroup);
        $this->setMonth($month);
        $this->setYear($year);
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


    //GROUP
    private function setGroup($gr){
        $this->group = $gr;
    }

    private function getGroup(){
        return $this->group;
    }


    //SUB GROUP
    private function setSubGroup($su){
        $this->subGroup = $su;
    }

    private function getSubGroup(){
        return $this->subGroup;
    }


    //MONTH
    private function setMonth($mo){
        $this->month = $mo;
    }

    private function getMonth(){
        return $this->month;
    }


    //YEAR
    private function setYear($ye){
        $this->year = $ye;
    }

    private function getYear(){
        return $this->year;
    }


    //AMOUNT
    private function setAmount($am){
        $this->amount = $am;
    }

    public function getAmount(){
        return $this->amount;
    }


    //PRINT
    private function setPrint($pr){
        $this->print = $pr;
    }

    public function getPrint(){
        return $this->print;
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


    private function number($n){
        $v = number_format($n, 0, ',', '.');
        return $v;
    }


    //LAST DAY FOR SELECT
    private function getLastDay(){
        return $this->getYear()."-".$this->getMonth()."-01";
    }


    //EXECUTE
    private function execute(){
        try{
            $sql = "SELECT * FROM accounts WHERE `group` = :group AND `subgroup` = :subgroup ORDER BY account";
            $con = $this->getPdo()->prepare($sql);
            $con->bindValue(":group", $this->getGroup(), PDO::PARAM_STR);
            $con->bindValue(":subgroup", $this->getSubGroup(), PDO::PARAM_STR);
            
            if(!$con->execute()){
                throw new Exception("ACCOUNT SELECT ERROR");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);
            
            $str = "";
            $amount = 0;

            foreach($list as $rows){
                $sql2 = "SELECT SUM(debit) AS amount FROM entries WHERE account_id = $rows->id AND `date` <= LAST_DAY('".$this->getLastDay()."')";
                $con2 = $this->getPdo()->prepare($sql2);
                
                if(!$con2->execute()){
                    throw new Exception("DEBIT SUM ERROR");
                }

                $result = $con2->fetchObject();
                $debit = $result->amount;

                $sql2 = "SELECT SUM(credit) AS amount FROM entries WHERE account_id = $rows->id AND `date` <= LAST_DAY('".$this->getLastDay()."')";
                $con2 = $this->getPdo()->prepare($sql2);
                
                if(!$con2->execute()){
                    throw new Exception("CREDIT SUM ERROR");
                }

                $result = $con2->fetchObject();
                $credit = $result->amount;

                $value = $debit - $credit;
                $value = ($this->getGroup() == "Contas de Resultado" && $this->getSubGroup() == "Receitas" || $this->getGroup() == "Passivo" || $this->getGroup() == "Patrimônio Líquido") ? $value * -1 : $value;

                $str .= "<li>$rows->account</li><li>".$this->number($value)."</li>";
                $amount += $value;
            }

            $this->setPrint($str);
            $this->setAmount($amount);

        }catch(Exception $e){
            $this->setError(true);
            $this->setErrorMsg($e->getMessage());
        }
    }
}


class Dre{
    private $subGroup, $month, $year, $pdo, $amount, $print, $error, $errorMsg;

    function __construct($subGroup, $month, $year){
        $this->setSubGroup($subGroup);
        $this->setMonth($month);
        $this->setYear($year);
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


    //SUB GROUP
    private function setSubGroup($su){
        $this->subGroup = $su;
    }

    private function getSubGroup(){
        return $this->subGroup;
    }


    //MONTH
    private function setMonth($mo){
        $this->month = $mo;
    }

    private function getMonth(){
        return $this->month;
    }


    //YEAR
    private function setYear($ye){
        $this->year = $ye;
    }

    private function getYear(){
        return $this->year;
    }


    //AMOUNT
    private function setAmount($am){
        $this->amount = $am;
    }

    public function getAmount(){
        return $this->amount;
    }


    //PRINT
    private function setPrint($pr){
        $this->print = $pr;
    }

    public function getPrint(){
        return $this->print;
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


    private function number($n){
        $v = number_format($n, 0, ',', '.');
        return $v;
    }


    //EXECUTE
    private function execute(){
        try{
            $sql = "SELECT * FROM accounts WHERE `group` = 'Contas de Resultado' AND `subgroup` = :subgroup ORDER BY account";
            $con = $this->getPdo()->prepare($sql);
            $con->bindValue(":subgroup", $this->getSubGroup(), PDO::PARAM_STR);
            
            if(!$con->execute()){
                throw new Exception("ACCOUNT SELECT ERROR");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);
            
            $str = "";
            $amount = 0;

            foreach($list as $rows){
                $sql2 = "SELECT SUM(debit) AS amount FROM entries WHERE account_id = $rows->id AND MONTH(`date`) = '".$this->getMonth()."' AND YEAR(`date`) = '".$this->getYear()."'";
                $con2 = $this->getPdo()->prepare($sql2);
                
                if(!$con2->execute()){
                    throw new Exception("DEBIT SUM ERROR");
                }

                $result = $con2->fetchObject();
                $debit = $result->amount;

                $sql2 = "SELECT SUM(credit) AS amount FROM entries WHERE account_id = $rows->id AND MONTH(`date`) = '".$this->getMonth()."' AND YEAR(`date`) = '".$this->getYear()."'";
                $con2 = $this->getPdo()->prepare($sql2);
                
                if(!$con2->execute()){
                    throw new Exception("CREDIT SUM ERROR");
                }

                $result = $con2->fetchObject();
                $credit = $result->amount;

                $value = $debit - $credit;
                $value = ($this->getSubGroup() == "Receitas") ? $value * -1 : $value;

                if($value != 0){
                    $str .= "<li>$rows->account</li><li>".$this->number($value)."</li>";
                }
                $amount += $value;
            }

            $this->setPrint($str);
            $this->setAmount($amount);

        }catch(Exception $e){
            $this->setError(true);
            $this->setErrorMsg($e->getMessage());
        }
    }
}
?>