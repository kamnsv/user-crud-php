<?php

$debug = getenv('DEBUG_MODE');
if ($debug) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$method = $_SERVER['REQUEST_METHOD'];
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


if ('/index.php' == $url)
	$url = '/';

if ('/users' == $url) {
	require_once 'auth.php';
	require_once 'users.php';
	exit;
}
	
if ('POST' == $method && '/' == $url){
	// Предоставление токена
	
	$name = $_POST['name'];

	// Если нет name то возвращаем ошибку
	if (empty($name)) {
		http_response_code(400);
		echo "key 'name' is empty";
		exit;
	}

	require_once 'db.php'; 
	require_once 'classes/Token.php';

	$token = (new Token($pdo, $debug))->provide($name, getenv('SIZE_TOKEN'));

	if (0 === $token){
		http_response_code(400);
		exit;
	}
	
	echo 'Для Вас: ', $name, '</br>';
	echo 'Создан токен: ', $token ;
	
	exit;	
}

if ('GET' == $method && '/' == $url): ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
  </head>
  <body>
	<main class="token">
    <h1 class="token__title">Регистрация токена</h1>
    <form action="/" method="post" class="token__form" accept-charset="utf-8">
      <label for="token" class="token__label">Введите имя:</label>
      <input type="text" name="name" class="token__put"/>
      <hr>
	  <span class="token__note">Вы получите бессрочный токен</span>
      <hr>
	  <input class="token__get" type="submit" value="Отправить">
    </form>
  </body>
</html>
<?php else: ?>
<?php 
	http_response_code(404);
	echo 'Not found';
	exit;
?>
<?php endif ?>
