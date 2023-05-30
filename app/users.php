<?php
//CRUD модель пользователей
class UserModel {
    private $db;

    public function __construct($pdo){
        $this->db = $pdo;
    }

    public function getAllUsers() {
        $stmt = $this->db->query('SELECT id, name, email FROM User');
        $users = [];
        while ($row = $stmt->fetch()) {
            $users[] = $row;
        }
        return $users;
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare('SELECT * FROM User WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function addUser($name, $email, $password) {
        $stmt = $this->db->prepare('INSERT INTO User (name, email, password) VALUES (:name, :email, :password)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->lastInsertId();
    }

    public function updateUser($id, $name, $email, $password) {
        $stmt = $this->db->prepare('UPDATE User SET name = :name, email = :email, password = :password WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare('DELETE FROM User WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}

require_once 'db.php';

$uset = UserModel($PDO);
$id = $_GET['id'];
$method = $_SERVER['REQUEST_METHOD'];

// Получение списка пользователей
if ('GET' == $method && empty($id)) {
    echo json_encode($user->getAllUsers());
    exit;
}

// Получение пользователя по ID
if ('GET' == $method && !empty($id)) {
    echo json_encode($user->getUserById($id)));
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
        echo json_encode(['error' => 'Invalid data']);
        exit;
    }
}

// Обновление пользователя по ID
if ('PUT' == $method && !empty($id)) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data['name']) && !empty($data['email']) && !empty($data['password'])) {
        $result = $user->updateUser($id, $data['name'], $data['email'], $data['password'])
        if (0 == $result) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            exit;
        }
		$data['id'] = $id;
        echo json_encode($data);
        exit;
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data']);
        exit;
    }
}

// Удаление пользователя по ID
if ('DELETE' == $method && !empty($id)) {
    $result = $user->deleteUser($id);
    if (0 == $result) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }
    http_response_code(204);
    exit;
}

// Если запрос не соответствует ни одному из вышеописанных условий, то возвращаем ошибку
http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
exit;