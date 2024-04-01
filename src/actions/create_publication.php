<?php
require_once __DIR__ . '/../helpers.php';

// Выносим данных из $_POST в отдельные переменные
$title = $_POST['title'] ?? null;
$content = $_POST['content'] ?? null;
$id_category = $_POST['category'] ?? null;

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
  redirect('/home.php?show=show_form'.$id);
}

$db = getConnect();
$author = $db->query("SELECT id FROM authors WHERE id_user = '" . $_SESSION['user']['id'] . "'")->fetch_assoc();
$db->query("INSERT INTO publications (title, content, id_author, id_category) 
            VALUES ('$title', '$content', " . $author['id'] . ", '$id_category')");
$db->close();

redirect('/');