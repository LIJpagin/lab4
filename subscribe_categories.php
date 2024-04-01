<?php
require_once __DIR__ . '/src/helpers.php';
if (checkUser('access_level', 'reader')) :
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<?php include_once __DIR__ . '/components/head.php';
  $connect = getConnect();
  $reader_id;
  $result1 = $connect->query("SELECT id FROM readers WHERE id_user = '".$_SESSION['user']['id']."'");
  if ($result1->num_rows > 0) {
    $reader_id = $result1->fetch_assoc()['id'];
  }

  $categories_id = array();
  $result2 = $connect->query("SELECT id_category FROM readers_categories WHERE id_reader = '".$reader_id."'");
  while ($row = $result2->fetch_assoc()) {
    $categories_id[] = $row['id_category'];
  }
  if (isset($_POST['subscribe'])) {
    // Получаем id категории и id пользователя
    $user_id = $_SESSION['user']['id'];
    $reader_id;
    $result = $connect->query("SELECT id FROM readers WHERE id_user = '".$user_id."'");
    if ($result->num_rows > 0) {
      $reader_id = $result->fetch_assoc()['id'];
    }

    // Проверяем, подписан ли пользователь на категорию
    $stmt_check = $connect->prepare("SELECT COUNT(*) FROM readers_categories WHERE id_category = ? AND id_reader = ?");
    $stmt_check->bind_param("ii", $_POST['category_id'], $reader_id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
      // Если пользователь уже подписан, выполните действия отписки
      $stmt_unsubscribe = $connect->prepare("DELETE FROM readers_categories WHERE id_category = ? AND id_reader = ?");
      $stmt_unsubscribe->bind_param("ii", $_POST['category_id'], $reader_id);
      $stmt_unsubscribe->execute();
      $stmt_unsubscribe->close();
    } else {
      // Если пользователь еще не подписан, выполните действия подписки
      $stmt_subscribe = $connect->prepare("INSERT INTO readers_categories (id_category, id_reader) VALUES (?, ?)");
      $stmt_subscribe->bind_param("ii", $_POST['category_id'], $reader_id);
      $stmt_subscribe->execute();
      $stmt_subscribe->close();
    }
  }
?>

<style>
  body.subscribe_category_body {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }
</style>

<body class="subscribe_category_body">
  <?php include_once __DIR__ . '/components/header.php' ?>
  <div style="margin-top: 70px;">
    <h4 style="text-align: center; margin-bottom: 15px;">Подписки на категории</h4>
    <?php include_once __DIR__ . '/components/scroll_to_top_button.php' ?>
    <?php foreach ($categories_id as $category_id) : ?>
      <?php include __DIR__ . '/components/category_card.php' ?>
    <?php endforeach; ?>
    <?php if (count($categories_id) == 0) : ?>
      <h6 style="text-align: center; margin-bottom: 15px;">У Вас нет подписок</h6>
      <form style="margin-bottom: 0px;">
          <button class="search_button" formaction="search_categories.php">Перейти к поиску</button>
      </form>
    <?php endif; ?>
  </div>
</body>

</html>
<?php endif; ?>