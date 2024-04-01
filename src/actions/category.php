<?php

require_once __DIR__ . '/../helpers.php';

// Выносим данных из $_POST в отдельные переменные
$there_is_data = Old('there_is_data');
$title = $_POST['category_title'] ?? null;
$description = $_POST['category_description'] ?? null;

// Выполняем валидацию полученных данных с формы
if (empty($title)) {
  setValidationError('category_title', 'Поле заголовок обязательное');
}
if (empty($description)) {
  setValidationError('category_description', 'Поле описания обязательное');
}
if (strlen($description) <= 20) {
  setValidationError('category_description', 'Описание должен быть больше 20 символов');
}

// Если список с ошибками валидации не пустой, то производим редирект обратно на форму
if (!empty($_SESSION['validation'])) {
  setOldValue('category_title', $title);
  setOldValue('category_description', $description);
  redirect('/category.php');
}

$db = getConnect();
try {
  if ($there_is_data == 'false')
    $db->query("INSERT INTO categories (title, description)
                VALUES ('".$title."', '".$description."')");
  else
    $db->query("UPDATE categories 
                SET title = '".$title."', 
                    description = '".$description."'
                WHERE id = '".Old('category_id')."'");
} catch (\Exception $e) {
  die($e->getMessage());
}

redirect('/home.php');
