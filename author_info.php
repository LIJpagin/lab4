<?php
require_once __DIR__ . '/src/helpers.php';
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<?php include_once __DIR__ . '/components/head.php' ?>
<style>
  body.author_card_body {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }
</style>

<?php
  $connect = getConnect();
  $author_id;
  if (isset($_GET['id'])) {
    $author_id = $_GET['id'];
    $sql_publications_with_filters
      = "SELECT publications.id AS publication_id,
                  publications.title AS publication_title,
                  publications.content,
                  publications.date_time,
                  authors.id AS authors_id,
                  authors.surname,
                  authors.name,
                  authors.patronymic,
                  authors.work_experience,
                  publications.id_category,
                  categories.id AS categories_id,
                  categories.title AS category_title
            FROM publications 
            INNER JOIN authors ON publications.id_author = authors.id
            INNER JOIN categories ON publications.id_category = categories.id
            WHERE 1 AND authors.id = '" . $_GET['id'] . "'
            ORDER BY publications.date_time DESC";
    $_SESSION['sql_publications_with_filters'] = $sql_publications_with_filters;
  }
  if (isset($_POST['subscribe'])) {
    // Получаем id автора и id пользователя
    $user_id = $_SESSION['user']['id'];
    $reader_id;
    $result = $connect->query("SELECT id FROM readers WHERE id_user = '".$user_id."'");
    if ($result->num_rows > 0) {
      $reader_id = $result->fetch_assoc()['id'];
    }

    // Проверяем, подписан ли пользователь на автора
    $stmt_check = $connect->prepare("SELECT COUNT(*) FROM readers_authors WHERE id_author = ? AND id_reader = ?");
    $stmt_check->bind_param("ii", $_POST['author_id'], $reader_id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
      // Если пользователь уже подписан, выполните действия отписки
      $stmt_unsubscribe = $connect->prepare("DELETE FROM readers_authors WHERE id_author = ? AND id_reader = ?");
      $stmt_unsubscribe->bind_param("ii", $_POST['author_id'], $reader_id);
      $stmt_unsubscribe->execute();
      $stmt_unsubscribe->close();
    } else {
      // Если пользователь еще не подписан, выполните действия подписки
      $stmt_subscribe = $connect->prepare("INSERT INTO readers_authors (id_author, id_reader) VALUES (?, ?)");
      $stmt_subscribe->bind_param("ii", $_POST['author_id'], $reader_id);
      $stmt_subscribe->execute();
      $stmt_subscribe->close();
    }
  }
?>

<body class="author_card_body">
  <?php include_once __DIR__ . '/components/header.php' ?>
  <div style="margin-top: 70px;">
    <?php include_once __DIR__ . '/components/scroll_to_top_button.php' ?>
    <?php include_once __DIR__ . '/components/author_card.php' ?>
    <h6 style="text-align: center;">Публикации автора</h6>
    <?php include_once __DIR__ . '/components/publications.php' ?>
  </div>
</body>

</html>
