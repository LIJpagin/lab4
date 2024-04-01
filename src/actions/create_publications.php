<?php
require_once __DIR__ . '/../helpers.php';

// Выносим данных из $_POST в отдельные переменные
$publication_create_title = $_POST['publication_create_title'] ?? null;

// Выполняем валидацию полученных данных с формы
if (empty($publication_create_title) || strlen($publication_create_title) < 20) {
  setValidationError('publication_create_title', 'Заголовок должен содержать больше 20 символов');
}

$conn = getConnect();

$title = $_POST['publication-title'];
$content = $_POST['publication-content'];
$category = $_POST['category'];
$author = $conn->query("SELECT id FROM authors WHERE id_user = '" . $_SESSION['user']['id'] . "'")->fetch_assoc();

$sql = "INSERT INTO publications (title, content, id_author, id_category) 
        VALUES ('$title', '$content', " . $author['id'] . ", '$category')";
$conn->query($sql);
$conn->close();

redirect('/');