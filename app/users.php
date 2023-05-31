<?php
require_once 'models/User.php';
require_once 'db.php';

$user = new User($pdo);
$id = isset($_GET['id']) ? $_GET['id'] : '';
$method = $_SERVER['REQUEST_METHOD'];

// Получение списка пользователей
if ('GET' == $method && empty($id)) {
    echo json_encode($user->getAllUsers());
    exit;
}

// Получение пользователя по ID
if ('GET' == $method && !empty($id)) {
    echo json_encode($user->getUserById($id));
    exit;
}

// Создание пользователя
if ('POST' == $method) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data['name']) && !empty($data['email']) && !empty($data['password'])) {
        $data['id'] = $user->addUser($data['name'], $data['email'], $data['password']);
        echo json_encode($data);
        exit;
    } else {
        http_response_code(400);
        echo 'Invalid data';
        exit;
    }
}

// Обновление пользователя по ID
if ('PUT' == $method && !empty($id)) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data['name']) && !empty($data['email']) && !empty($data['password'])) {
        $result = $user->updateUser($id, $data['name'], $data['email'], $data['password']);
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
if ('DELETE' == $method && !empty($id)) {
    $result = $user->deleteUser($id);
    if (0 == $result) {
        http_response_code(404);
        echo 'User not found';
        exit;
    }
    http_response_code(204);
    exit;
}

// Если запрос не соответствует ни одному из вышеописанных условий, то возвращаем ошибку
http_response_code(400);
echo 'Invalid request';
exit;