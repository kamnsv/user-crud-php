<?php

require_once 'Salt.php';
require_once 'Model.php';

class Token extends Model{

    public function provide($name, $length = 16) {
		
		$token = Salt::salt($length);
		list($salt, $hashed) = Salt::salt_hashed($token);
		
        $stmt = $this->db->prepare('INSERT INTO Token (name, token, salt) VALUES (:name, :token, :salt)');
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':token', $hashed);
		$stmt->bindParam(':salt', $salt);
		
		if (!$this->exec($stmt))
			return 0;
		
		return $token;
    }
}
