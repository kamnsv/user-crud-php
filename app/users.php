<?php
require_once 'classes/User.php';
require_once 'db.php';

$user = new User($pdo, $debug);
$id = isset($_GET['id']) ? $_GET['id'] : '';
$method = $_SERVER['REQUEST_METHOD'];

// Получение списка пользователей
if ('GET' == $method && !isset($_GET['id'])) {
    echo json_encode($user->readAll());
    exit;
}

// Получение пользователя по ID
if ('GET' == $method && isset($_GET['id'])) {
	$data = $user->read($id);
	if (False === $data) {
		http_response_code(400);
		echo 'User not found';
		exit;
	}
	echo json_encode($data); 
    exit;
}

// Создание пользователя
if ('POST' == $method) {

	try {
		$data = json_decode(file_get_contents('php://input'), true);
	} catch (Exception | ErrorException $e){
		http_response_code(400);
        echo 'Invalid parsing data';
        exit;
	}
	
    if (!empty($data['name']) && !empty($data['email']) && !empty($data['password'])) {
        $data['id'] = $user->create($data);
		var_dump($data);
		if (0 == $data['id']){
			http_response_code(400);
			echo 'Invalid insert data';
			exit;
		}
        echo json_encode($data);
        exit;
    } else {
        http_response_code(400);
        echo 'Invalid data';
        exit;
    }
}

// Обновление пользователя по ID
if ('PUT' == $method && isset($_GET['id'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data['name']) && !empty($data['email']) && !empty($data['password'])) {
        $result = $user->update($id, $data);
        if (0 == $result) {
            http_response_code(404);
            echo 'User not found';
            exit;
        }
		$data['id'] = $id;
        echo json_encode($data);
        exit;
    } else {
        http_response_code(400);
        echo json_encode('Invalid data');
        exit;
    }
}

// Удаление пользователя по ID
if ('DELETE' == $method && isset($_GET['id'])) {
    $result = $user->delete($id);
    if (0 == $result) {
        http_response_code(404);
        echo 'User not found';
        exit;
    }
    http_response_code(200);
    exit;
}

// Если запрос не соответствует ни одному из вышеописанных условий, то возвращаем ошибку
http_response_code(400);
echo 'Invalid request';
exit;
