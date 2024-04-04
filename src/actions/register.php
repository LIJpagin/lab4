<?php

require_once __DIR__ . '/../helpers.php';

// Выносим данных из $_POST в отдельные переменные

$login_name = $_POST['login_name'] ?? null;
$password = $_POST['password'] ?? null;
$passwordConfirmation = $_POST['password_confirmation'] ?? null;
$access_level = $_POST['access_level'] ?? null;

// Выполняем валидацию полученных данных с формы

if (empty($login_name) ) {
    setValidationError('login_name', 'Неверное имя');
}

$result = findUser($login_name);
$user = $result->fetch_assoc();
if ($result->num_rows > 0) {
    setValidationError('login_name', 'Логин занят');
}

if (empty($password)) {
    setValidationError('password', 'Пароль пустой');
}

if ($password !== $passwordConfirmation) {
    setValidationError('password', 'Пароли не совпадают');
}

// Если список с ошибками валидации не пустой, то производим редирект обратно на форму

if (!empty($_SESSION['validation'])) {
    setOldValue('login_name', $login_name);
    setOldValue('password', $password);
    setOldValue('access_level', $access_level);
    redirect('/register.php');
}

$db = getConnect();

try {
    $db->query("INSERT INTO users (login, password, access_level)
    VALUES ('".$login_name."', '".$login_name."', '".$access_level."');");
} catch (\Exception $e) {
    die($e->getMessage());
}

$result = findUser($login_name);
$user = $result->fetch_assoc();
if ($result->num_rows > 0 && $password == $user['password']) {
    $_SESSION['user']['id'] = $user['id'];
    $_SESSION['user']['login'] = $user['login'];
    $_SESSION['user']['access_level'] = $user['access_level'];
}

redirect('/'.$access_level.'.php');
