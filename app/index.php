<?php if ('GET' == $_SERVER['REQUEST_METHOD']): ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
  </head>
  <body>
	<main class="token">
    <h1 class="token__title">Регистрация токена</h1>
    <form action="/" method="post" class="token__form">
      <label for="token" class="token__label">Введите имя:</label>
      <input type="text" name="name" class="token__put"/>
      <hr>
	  <span class="token__note">Вы получите бессрочный токен</span>
      <hr>
	  <input class="token__get" type="submit" value="Отправить">
    </form>
  </body>
</html>
<?php elseif ('POST' == $_SERVER['REQUEST_METHOD']): ?>

<?php 

$name = $_POST['name'];

// Если нет name то возвращаем ошибку
if (empty($name)) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid request']);
	exit;
}

echo 'Вы: ', $name;

// Генерируем случайную строку для salt
$salt = bin2hex(random_bytes(16));

// Генерируем случайный токен
$token = bin2hex(random_bytes(32));

// Хэшируем токен с использованием salt
$hashed = hash('sha256', $token . $salt);

require_once 'db.php'; 

// Добавляем в базу
$stmt = $pdo->prepare('INSERT INTO Token (name, token, salt) VALUES (:name, :token, :salt)');
$stmt->execute(['name' =>  $name, 'token' => $hashed, 'salt' => $salt]);

// Выводим результаты
if ($stmt->lastInsertId() > 0)
	echo 'Ваш токен: ', $token;
else {
	http_response_code(500);
	echo json_encode(['error' => 'Internal Server Error']);
	exit;
}

?>

<?php endif ?>
