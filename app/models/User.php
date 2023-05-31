<?php
//CRUD модель пользователей
class User {
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
        $stmt = $this->db->prepare('SELECT id, name, email FROM User WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function addUser($name, $email, $password) {
		$salt = bin2hex(random_bytes(16));
		$hashed = hash('sha256', $password . $salt);
        $stmt = $this->db->prepare('INSERT INTO User (name, email, password, salt) VALUES (:name, :email, :password, :salt)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed);
		$stmt->bindParam(':salt', $salt);
        $stmt->execute();
        return $stmt->lastInsertId();
    }

    public function updateUser($id, $name, $email, $password) {
		$salt = bin2hex(random_bytes(16));
		$hashed = hash('sha256', $password . $salt);
        $stmt = $this->db->prepare('UPDATE User SET name = :name, email = :email, password = :password, salt = :salt WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed);
		$stmt->bindParam(':salt', $salt);
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