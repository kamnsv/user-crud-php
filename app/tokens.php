<?php 

$name = $_POST['name'];

// Если нет name то возвращаем ошибку
if (empty($name)) {
	http_response_code(400);
	echo "key 'name' is empty";
	exit;
}

// Генерируем случайную строку для salt
$salt = bin2hex(random_bytes(16));

// Генерируем случайный токен
$token = bin2hex(random_bytes(16));

// Хэшируем токен с использованием salt
$hashed = hash('sha256', $token . $salt);

require_once 'db.php'; 

// Добавляем в базу
$stmt = $pdo->prepare('INSERT INTO Token (name, token, salt) VALUES (:name, :token, :salt)');
try {
	$stmt->execute(['name' =>  $name, 'token' => $hashed, 'salt' => $salt]);
}
catch (PDOException $e){
	echo $e->getMessage();
	http_response_code(400);
	exit;
}

// Выводим результаты
if ($stmt->rowCount() != 0){
	#echo json_encode(['name' =>  $name, 'token' => $token]);
	echo 'Вы: ', $name, '</br>';
	echo 'Ваш токен: ', $token;
}
else {
	
	http_response_code(500);
	echo 'Internal Server Error';
	exit;
}