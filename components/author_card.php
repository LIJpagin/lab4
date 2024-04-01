<style>
  .author_card {
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 30px;
    padding-bottom: 0px;
    margin-bottom: 15px;
    position: relative;
    width: 600px;
  }

  .author_card_FIO {
    font-size: 20px;
    margin-bottom: 15px;
    line-height: 0.9;
    color: #2A5885;
  }

  .author_card_info {
    line-height: 1.4;
    font-size: 16px;
    color: #000;
  }

  .author_card_category {
    font-style: italic;
    font-size: 16px;
  }

  li {
    margin-bottom: 0px;
  }
  ul {
    margin-bottom: 10px;
  }
</style>

<?php
  $connect = getConnect();
  $author_info = array();
  $author_categories = array();
  if (isset($author_id)) {
    $result = $connect->query("SELECT * FROM authors WHERE id = '" . $author_id . "'");
    if ($result->num_rows > 0) {
      $author_info = $result->fetch_assoc();
    }
    $result = $connect->query(
      "SELECT DISTINCT categories.id, categories.title
        FROM publications
        JOIN categories ON publications.id_category = categories.id
        WHERE publications.id_author = '" . $author_id . "';"
    );
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $author_categories[] = $row;
      }
    }
  }
?>

<div class="author_card">
  <h2 class="author_card_FIO"><?= $author_info["surname"] ?> <?= $author_info["name"] ?> <?= $author_info["patronymic"] ?></h2>
  <span class="author_card_info">Дата рождения <?= formatDate($author_info["birthdate"]) ?></span></br>
  <span class="author_card_info">Стаж написания публикаций составляет <?= $author_info["work_experience"] ?> лет</span></br>
  <?php if (count($author_categories) > 0) : ?>
    <span class="author_card_info">Категории, в которых публиковался:</span></br>
    <ul>
      <?php foreach ($author_categories as $author_category) : ?>
        <li class="author_card_category">
          <a href="/category_info.php?id=<?= $author_category['id'] ?>">
            <?= $author_category['title'] ?>  
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <?php if (checkUser('access_level', 'reader')) : ?>
    <?php
    // Проверяем, подписан ли пользователь на автора
    $reader_id;
    $result = $connect->query("SELECT id FROM readers WHERE id_user = '".$_SESSION['user']['id']."'");
    if ($result->num_rows > 0) {
      $reader_id = $result->fetch_assoc()['id'];
    }
    $stmt_check = $connect->prepare("SELECT COUNT(*) FROM readers_authors WHERE id_author = ? AND id_reader = ?");
    $stmt_check->bind_param("ii", $author_id, $reader_id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();
    ?>
    <?php if ($count > 0) : ?>
      <form method="post">
        <input type="hidden" name="author_id" value="<?= $author_info['id'] ?>">
        <button style="font-size: 16px; background-color: #ff6161;" name="subscribe">Отписаться от автора</button>
      </form>
    <?php else : ?>
      <form method="post">
        <input type="hidden" name="author_id" value="<?= $author_info['id'] ?>">
        <button style="font-size: 16px;" name="subscribe">Подписаться на автора</button>
      </form>
    <?php endif; ?>
  <?php endif; ?>
</div>