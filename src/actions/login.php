<?php

require_once __DIR__ . '/../helpers.php';

$login_name = $_POST['login_name'] ?? null;
$password = $_POST['password'] ?? null;

$result = findUser($login_name);
$user = $result->fetch_assoc();

if ($result->num_rows > 0 && $password == $user['password']) {
    $_SESSION['user']['id'] = $user['id'];
    $_SESSION['user']['login'] = $user['login'];
    $_SESSION['user']['access_level'] = $user['access_level'];
    redirect('/home.php');
}
else {
    setMessage('error', "Неверный логин или пароль");
    redirect('/');
}
