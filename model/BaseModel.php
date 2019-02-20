<?php
class BaseModel
{
	private $pdo = null;
	private $transLevel = 0;	
	function __construct() {
		try {
			$dsn = DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME;
			$this->pdo =  new PDO($dsn, DB_USER, DB_PASSWORD);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage();
			die();
		}
	}
	
	function getRow($sql, $data = array()){
		$sth = $this->pdo->prepare($sql);
		$sth->execute($data);
		return $sth->fetch(PDO::FETCH_ASSOC);
	}
	
	function getAll($sql, $data = array()){
		$sth = $this->pdo->prepare($sql);
		$sth->execute($data);
		$val = $sth->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($val) && !is_array($val))
			$val = array($val);
		return $val;
	}
	
	function update($sql, $data = array()){
		$sth = $this->pdo->prepare($sql);
		return $sth->execute($data);
	}

	function delete($sql, $data = array()){
		$sth = $this->pdo->prepare($sql);
		return $sth->execute($data);
	}
	
	function create($sql, $data = array()){
		$sth = $this->pdo->prepare($sql);
		$sth->execute($data);
		$row_id = $this->pdo->lastInsertId();
		return $row_id;
	}
	
	protected function nestable() {
		return true;
	}
	
	protected function beginTransaction(){
		if($this->transLevel === 0 ) {
			if(!$this->pdo->beginTransaction())
				die("Could not start a transaction, please contact the admin.");
		}
		$this->transLevel++;
		return true;
	}
	
	protected function endTransaction(){
		$this->transLevel--;
		if($this->transLevel === 0) {
			if(!$this->pdo->commit())
				die("Could not commit a transaction, please contact the admin.");
		}
		return true;
	}
	
	protected function rollBack() {
		if($this->transLevel == 0 || !$this->nestable()) {
			$this->pdo->rollBack();
		}
	}
}