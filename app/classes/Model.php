<?php

class Model{
    protected $db;
	public $debug;
	
    public function __construct(PDO $pdo, $debug = 0){
        $this->db = $pdo;
		$this->debug = $debug;
    }
	
	public function exec($stmt){
		try {
			$stmt->execute();
		}catch (PDOException $e){
			if ($this->debug)
				echo 'PDOException: ' . $e->getMessage();
			return 0;
		}catch (Exception | ErrorException $e){
			if ($this->debug)
				var_dump($e);
			return 0;
		}
		return 1;
	}
	
}
