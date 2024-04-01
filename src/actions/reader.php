<?php

require_once __DIR__ . '/../helpers.php';

// Выносим данных из $_POST в отдельные переменные
$surname = $_POST['reader_surname'] ?? null;
$name = $_POST['reader_name'] ?? null;
$address = $_POST['reader_address'] ?? null;
$about_me = $_POST['reader_about_me'] ?? null;
$there_is_data = Old('there_is_data');

// Выполняем валидацию полученных данных с формы
if (empty($surname)) {
  setValidationError('reader_surname', 'Поле фамилии обязательное');
}
if (empty($name)) {
  setValidationError('reader_name', 'Поле имени обязательное');
}

// Если список с ошибками валидации не пустой, то производим редирект обратно на форму
if (!empty($_SESSION['validation'])) {
  setOldValue('reader_surname', $surname);
  setOldValue('reader_name', $name);
  setOldValue('reader_address', $address);
  setOldValue('reader_about_me', $about_me);
  redirect('/reader.php');
}

$db = getConnect();

try {
  if ($there_is_data == 'false')
    $db->query("INSERT INTO readers (
                  surname,
                  name,
                  address,
                  about_me,
                  id_user)
                VALUES (
                  '".$surname."',
                  '".$name."',
                  '".$address."',
                  '".$about_me."',
                  '".$_SESSION['user']['id']."'
                );");
  else
    $db->query("UPDATE readers 
                SET surname = '".$surname."', 
                    name = '".$name."', 
                    address = '".$address."', 
                    about_me = '".$about_me."'
                WHERE id_user = '".$_SESSION['user']['id']."';");
} catch (\Exception $e) {
  die($e->getMessage());
}

redirect('/home.php');
