<?php
//Генератор соли

class Salt {
	
	public static function salt_hashed($pswd, $length = 32) {
		$salt =Salt::salt($length);
		$hashed = hash('sha256', $pswd . $salt);
		return [$salt, $hashed];
	}
	
    public static function salt($length = 32) {
        $salt = '';
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars_count = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $salt .= $chars[random_int(0, $chars_count - 1)];
        }

        return $salt;
    }
}
