<?php

session_start();

require_once __DIR__ . '/config.php';

function redirect(string $path)
{
    header("Location: $path");
    die();
}

function setValidationError(string $fieldName, string $message): void
{
    $_SESSION['validation'][$fieldName] = $message;
}

function hasValidationError(string $fieldName): bool
{
    return isset($_SESSION['validation'][$fieldName]);
}

function validationErrorAttr(string $fieldName): string
{
    return isset($_SESSION['validation'][$fieldName]) ? 'aria-invalid="true"' : '';
}

function validationErrorMessage(string $fieldName): string
{
    $message = $_SESSION['validation'][$fieldName] ?? '';
    unset($_SESSION['validation'][$fieldName]);
    return $message;
}

function setOldValue(string $key, mixed $value): void
{
    $_SESSION['old'][$key] = $value;
}

function old(string $key)
{
    $value = $_SESSION['old'][$key] ?? '';
    unset($_SESSION['old'][$key]);
    return $value;
}

function setMessage(string $key, string $message): void
{
    $_SESSION['message'][$key] = $message;
}

function hasMessage(string $key): bool
{
    return isset($_SESSION['message'][$key]);
}

function getMessage(string $key): string
{
    $message = $_SESSION['message'][$key] ?? '';
    unset($_SESSION['message'][$key]);
    return $message;
}

function getConnect(): mysqli
{
    try {
        return new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    } catch (\Exception $e) {
        die("Connection error: {$e->getMessage()}");
    }
}

function findUser(string $login): mysqli_result|bool
{
    $db = getConnect();
    try {
        return $db->query("SELECT * FROM `users` WHERE login = '$login';");
    } catch (\Exception $e) {
        die($e->getMessage());
    }
}

function currentUser(): mysqli_result|false
{
    $db = getConnect();

    if (!isset($_SESSION['user'])) {
        return false;
    }

    $userId = $_SESSION['user']['id'] ?? null;

    try {
        return $db->query("SELECT * FROM users WHERE id = '".$userId."'");
    } catch (\Exception $e) {
        die($e->getMessage());
    }
}

function logout(): void
{
    unset($_SESSION['user']['id']);
    redirect('/');
}

function checkAuth(): void
{
    if (!isset($_SESSION['user']['id'])) {
        redirect('/');
    }
}

function checkGuest(): void
{
    if (isset($_SESSION['user']['id'])) {
        redirect('/home.php');
    }
}

function formatDataTime($date_time) {
    $now = time();
    $post_time = strtotime($date_time);
    $diff = $now - $post_time;
    
    if ($diff < 60) {
        return "Только что";
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . " мин. назад";
    } elseif ($diff < 86400 && date('Ymd', $now) == date('Ymd', $post_time)) {
        return "Сегодня в " . date('H:i', $post_time);
    } elseif ($diff < 172800 && date('Ymd', $now - 86400) == date('Ymd', $post_time)) {
        return "Вчера в " . date('H:i', $post_time);
    } else {
        return date('j M в H:i', $post_time);
    }
}

function selected(string $key, string $value): string
{
    return isset($_GET[$key]) && $_GET[$key] == $value ? 'selected' : '';
}

function optionTag(string $key, string $value, string $content): string
{
    return '<option value="'.$value.'" '.selected($key, $value).'>'.$content.'</option>';
}

function checkUser(string $field, string $value) {
    return isset($_SESSION['user'][$field]) && $_SESSION['user']['access_level'] === $value;
}

function formatDate($dateString)
{
  $date = new DateTime($dateString);
  return $date->format('j F Y года');
}


?>