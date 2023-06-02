<?php 
	 
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
	http_response_code(401);
    echo 'Unauthorized', $debug ? ' not HTTP_AUTHORIZATION' : '';
    exit;
}

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

if (strpos($authHeader, 'Basic') === 0) {
	try {
	$base64Credentials = substr($authHeader, 6);
	$credentials = base64_decode($base64Credentials);
	list($name, $token) = explode(':', $credentials);
	}
	catch (Exception | ErrorException $e){
		http_response_code(401);
		echo $e;
		exit;
	}
	
} else {
	http_response_code(401);
    echo 'Unauthorized', $debug ? ' not Basic '.$authHeader : '';
    exit;
}

require_once 'db.php'; 

$stmt = $pdo->prepare('SELECT * FROM Token WHERE name = :name');

try {
	$stmt->execute(['name' => $name]);
}
catch (PDOException $e){
	http_response_code(401);
	echo $e;
	exit;
}

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) == 0){
	http_response_code(401);
	echo 'Unauthorized', $debug ? ' not name': '';
	exit;
}

if (hash('sha256',  $token. $rows[0]['salt']) != $rows[0]['token']){
	http_response_code(401);
	echo 'Unauthorized', $debug ? ' error token': '';
	exit;
}
