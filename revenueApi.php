<?php

session_start();
require_once("class/connectPDO.php");
require_once("class/functions.php");

if(!isset($_SESSION['user']) || !isset($_SESSION['login']) || $_SESSION['login'] != "bc3326c033a42c1ddb4e5a"){
	session_destroy();
	header("location:index.php");
} else{
    $userId = $_SESSION['userId'];

    $json['error'] = false;
    $json['errorMsg'] = "Sem Erros";

    $action = $_POST['action'];
    

    function load(){
        global $pdo, $json, $userId;

        try{
            $year = $_POST['year'];
            $month = $_POST['month'];
            
            // $year = 2018;
            // $month = 8;


            $json['list'] = "
                <ul>
                    <li>Dia</li>
                    <li>Turno</li>
                    <li>Hrs Extras</li>
                    <li>Valor Bruto</li>
                    <li></li>
                </ul>
            ";


            $sql = "SELECT * FROM revenue WHERE MONTH(`date`) = :m AND YEAR(`date`) = :y AND user = $userId ORDER BY `date`";
            $con = $pdo->prepare($sql);
            $con->bindValue(":y", $year, PDO::PARAM_INT);
            $con->bindValue(":m", $month, PDO::PARAM_STR);
            
            if(!$con->execute()){
                throw new Exception("Revenue query error");
            }

            $list = $con->fetchAll(PDO::FETCH_OBJ);

            $ttDays = 0;
            $ttNights = 0;
            $ttOvertime = 0;

            /*
                SHITS:
                1 = day
                2 = night
                3 = day off (day)
                4 = day off (night)
            */

            $hourlyWage = 1420;
            $overtimePercentage = 0.3;
            $workedHours = (7+50/60);
            $night = 6;

            foreach($list as $rows){


                // if($rows->shift == 1){
                //     $grossAmountOfDay = ($rows->overtime * $hourlyWage * ($overtimePercentage + 1)) + ($workedHours * $hourlyWage);
                // }elseif($rows->shift == 2){
                //     $grossAmountOfDay = ($rows->overtime * $hourlyWage * ($overtimePercentage + 1)) + ($workedHours * $hourlyWage) + ($night * $hourlyWage * $overtimePercentage);
                //     $ttNights++;
                // }

                switch($rows->shift){
                    case 1:
                        $grossAmountOfDay = ($rows->overtime * $hourlyWage * ($overtimePercentage + 1)) + ($workedHours * $hourlyWage);
                    break;
                    case 2:
                        $grossAmountOfDay = ($rows->overtime * $hourlyWage * ($overtimePercentage + 1)) + ($workedHours * $hourlyWage) + ($night * $hourlyWage * $overtimePercentage);
                        $ttNights++;
                    break;
                }

                $ttDays++;
                $ttOvertime += $rows->overtime;

                $shift = $rows->shift;

                switch($shift){
                    case 1:
                        $shift = "Dia";
                    break;
                    case 2:
                        $shift = "Noite";
                    break;
                    case 1:
                        $shift = "Shukin Dia";
                    break;
                    case 1:
                        $shift = "Shukin Noite";
                    break;
                }
                
                $date = $rows->date;
                $dateExplode = explode("-", $date);
                $day = $dateExplode[2];


                $json['list'] .= "
                    <ul>
                        <li>$day</li>
                        <li>$shift</li>
                        <li>$rows->overtime</li>
                        <li>".number($grossAmountOfDay)."</li>
                        <li>
                            <button type='button' onclick='del($rows->id)'><img src=\"templates/trash.png\"></button>
                        </li>
                    </ul>
                ";
            }

            $json["ttDays"] = $ttDays;
            $json["ttOvertime"] = $ttOvertime;

            $ttNightsValue = $ttNights * $night * $overtimePercentage * $hourlyWage;
            $json["ttNights"] = number($ttNightsValue);
            
            $grossAmount = ($ttDays * $workedHours * $hourlyWage) + ($ttOvertime * $hourlyWage * ($overtimePercentage + 1)) + $ttNightsValue;
            $json["grossAmount"] = number($grossAmount);

            
            $healthInsurance = 11220;
            $json['healthInsurance'] = number($healthInsurance);

            $retirement = 31110;
            $json['retirement'] = number($retirement);

            $unemploymentInsurance = $grossAmount * 0.003;
            $json['unemploymentInsurance'] = number($unemploymentInsurance);

            $incomeTax = $grossAmount * 0.03;
            $json['incomeTax'] = number($incomeTax);
            

            $ttDiscounts = $healthInsurance + $retirement + $unemploymentInsurance + $incomeTax;
            $json['ttDiscounts'] = number($ttDiscounts);

            $json['netValue'] = number($grossAmount - $ttDiscounts);
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
    }


    function add(){
        global $pdo, $userId, $json;
        
        try{
            $date = $_POST['date'];
            $shift = $_POST['shift'];
            $overtime = ($_POST['overtime'] == "") ? 0 : $_POST['overtime'];

            $sql = "SELECT `date` FROM revenue WHERE `date` LIKE :date";
            $con = $pdo->prepare($sql);
            $con->bindValue(":date", $date, PDO::PARAM_STR);

            if(!$con->execute()){
                throw new Exception("Date query error");
            }

            if($con->rowCount() > 0){
                throw new Exception("Existing entry");
            }

            $sql = "INSERT INTO revenue (`date`, shift, overtime, user) VALUES(:date, :shift, :overtime, :user)";
            $con = $pdo->prepare($sql);
            $con->bindValue(":date", $date, PDO::PARAM_STR);
            $con->bindValue(":shift", $shift, PDO::PARAM_INT);
            $con->bindValue(":overtime", $overtime, PDO::PARAM_STR);
            $con->bindValue(":user", $userId, PDO::PARAM_INT);

            if(!$con->execute()){
                throw new Exception("Insert error");
            }
        }catch(Exception $e){
            $json['error'] = true;
            $json['errorMsg'] = $e->getMessage();
        }
   
    }


    function del(){
        global $pdo, $json;

        $id = $_POST['id'];

        $sql = "DELETE FROM revenue WHERE id = :id";
        $con = $pdo->prepare($sql);
        $con->bindValue(":id", $id, PDO::PARAM_INT);

        if(!$con->execute()){
            $json['error'] = true;
            $json['errorMsg'] = "Delete Error";
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