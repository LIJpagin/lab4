<?php

require_once __DIR__ . '/../helpers.php';

// Выносим данных из $_POST в отдельные переменные
$surname = $_POST['author_surname'] ?? null;
$name = $_POST['author_name'] ?? null;
$patronymic = $_POST['author_patronymic'] ?? null;
$birthdate = $_POST['author_birthdate'] ?? null;
$work_experience = $_POST['author_work_experience'] ?? null;
$there_is_data = Old('there_is_data') ?? null;

// Выполняем валидацию полученных данных с формы
if (empty($surname)) {
  setValidationError('author_surname', 'Поле фамилии обязательное');
}
if (empty($name)) {
  setValidationError('author_name', 'Поле имени обязательное');
}
if (empty($birthdate)) {
  setValidationError('author_birthdate', 'Поле дня рождения обязательное');
}
if (empty($work_experience)) {
  setValidationError('author_work_experience', 'Поле стажа работы обязательное');
}

// Если список с ошибками валидации не пустой, то производим редирект обратно на форму
if (!empty($_SESSION['validation'])) {
  setOldValue('author_surname', $surname);
  setOldValue('author_name', $name);
  setOldValue('author_patronymic', $patronymic);
  setOldValue('author_birthdate', $birthdate);
  setOldValue('author_work_experience', $work_experience);
  redirect('/author.php');
}

$db = getConnect();

try {
  if ($there_is_data == 'false')
    $db->query("INSERT INTO authors (
                  surname,
                  name,
                  patronymic,
                  birthdate,
                  work_experience,
                  id_user)
                VALUES (
                  '".$surname."',
                  '".$name."',
                  '".$patronymic."',
                  '".$birthdate."',
                  '".$work_experience."',
                  '".$_SESSION['user']['id']."'
                );");
  else
    $db->query("UPDATE authors 
                SET surname = '".$surname."', 
                    name = '".$name."', 
                    patronymic = '".$patronymic."', 
                    birthdate = '".$birthdate."', 
                    work_experience = '".$work_experience."'
                WHERE id_user = '".$_SESSION['user']['id']."';");
} catch (\Exception $e) {
  die($e->getMessage());
}

redirect('/home.php');
