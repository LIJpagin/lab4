<?php

require_once __DIR__ . '/../helpers.php';

// Выносим данных из $_POST в отдельные переменные
$query = Old('query');
$id = Old('id') ?? null;
$title = $_POST['title'] ?? null;
$content = $_POST['content'] ?? null;
$id_category = $_POST['id_category'] ?? null;

// Выполняем валидацию полученных данных с формы
if (empty($title)) {
  setValidationError('title', 'Поле заголовок обязательное');
}
if (empty($content)) {
  setValidationError('content', 'Поле содержания обязательное');
}
if (strlen($content) <= 20) {
  setValidationError('content', 'Содержание должно быть больше 20 символов');
}

// Если список с ошибками валидации не пустой, то производим редирект обратно на форму
if (!empty($_SESSION['validation'])) {
  setOldValue('title', $title);
  setOldValue('content', $content);
  setOldValue('id_category', $id_category);
  redirect('/publication.php?id='.$id);
}

$db = getConnect();
try {
  if ($query == 'INSERT') {
    $db->query("INSERT INTO publications (title, content, id_category)
                VALUES ('".$title."', '".$content.", '".$id_category."')");
  }
  elseif ($query == 'UPDATE') {
    $db->query("UPDATE publications SET title = '".$title."',
                content = '".$content."', id_category = '".$id_category."'
                WHERE id = '".$id."'");
  }
} catch (\Exception $e) {
  die($e->getMessage());
}

redirect('/home.php');
