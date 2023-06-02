<?php

require_once 'interfaces/Crud.php';
require_once 'Salt.php';
require_once 'Model.php';

class User extends Model implements Crud  {
   	
    public function readAll() {
        $stmt = $this->db->query('SELECT id, name, email FROM User');
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row;
        }
        return $users;
    }

    public function read($id) {
        $stmt = $this->db->prepare('SELECT id, name, email FROM User WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
		
        $stmt = $this->db->prepare('INSERT INTO User (name, email, password, salt) VALUES (:name, :email, :password, :salt)');

		$stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
		
		list($salt, $hashed) = Salt::salt_hashed($data['password']);
        $stmt->bindParam(':password', $hashed);
		$stmt->bindParam(':salt', $salt);
		
		if (!$this->exec($stmt))
			return 0;

		return $this->db->lastInsertId();
	
    }

    public function update($id, $data) {
		
        $stmt = $this->db->prepare('UPDATE User SET name = :name, email = :email, password = :password, salt = :salt WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
		list($salt, $hashed) = Salt::salt_hashed($data['password']);
        $stmt->bindParam(':password', $hashed);
		$stmt->bindParam(':salt', $salt);
        $this->exec($stmt);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM User WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
