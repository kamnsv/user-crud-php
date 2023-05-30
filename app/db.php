<?php 
try {
	$pdo =  new PDO('mysql:host='.getenv('PMA_HOST').';dbname='.getenv('MYSQL_DATABASE'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'));
}catch (PDOException $e){

		echo 'PDOException: ' . $e->getMessage();

}catch (Exception | ErrorException $e){

    var_dump($e);

}