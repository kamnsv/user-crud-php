<?php 

$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];
if (empty($url) || '/index.php' == $url)
	$url = '/';

if ('/users' == $url) {
	require_once 'users.php';
	exit;
}
	
if ('POST' == $method && '/' == $url){
	require_once 'tokens.php';
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

