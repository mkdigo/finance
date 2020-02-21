<?php
//COLOCAR O INPUT HIDDEN POR ULTIMO NO FORMULARIO - HIDDEN=QTDE DE CAMPOS HIDDEN
class Insert{
	
	private $table,$hidden,$connect,$sql,$ex;
	
	function __construct($table,$hidden,$connect){
		$this->setTable($table);
		$this->setHidden($hidden);
		$this->setConnect($connect);
		$this->setSql();
		$this->setEx();
	}
			
	//SET
	
	private function setSql(){
		$n=count($_POST);
		$x=0;
		foreach($_POST as $key => $value){
			if($x==0){
				$c="`".$key."`";
				if($value==""){$val=="NULL";}
				else{
					$val="'".$value."'";
				}
				$x++;
			}
			elseif($x<$n-$this->getHidden()){
				$c=$c.",`".$key."`";
				if($value==""){$val=$val.",NULL";}
				else{
					$val=$val.",'".$value."'";
				}
				$x++;
			}
		}
		
		$this->sql="insert into ".$this->getTable()." (".$c.")values(".$val.");";
	}
	
	private function setEx(){
		$this->ex=mysqli_query($this->connect->ConnectGo(),$this->getSql())or die(mysqli_error());
	}
	
	private function setTable($ta){
		$this->table=$ta;
	}
	
	private function setHidden($hi){
		$this->hidden=$hi;
	}
	
	private function setConnect($con){
		$this->connect=$con;
	}
	
	//GET
	
	private function getTable(){
		return $this->table;
	}
	
	private function getHidden(){
		return $this->hidden;
	}
	
	private function getConnect(){
		return $this->connect;
	}
	
	public function getSql(){
		return $this->sql;
	}
	
	private function getEx(){
		return $this->ex;
	}
}

?>