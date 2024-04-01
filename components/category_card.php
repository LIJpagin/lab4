<style>
  .category_card {
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 30px;
    padding-bottom: 0px;
    margin-bottom: 15px;
    position: relative;
    width: 600px;
  }

  .category_card_title {
    font-size: 20px;
    margin-bottom: 15px;
    line-height: 0.9;
    color: #2A5885;
  }

  .category_card_content {
    line-height: 1.4;
    font-size: 16px;
    color: #000;
    max-height: 90px; /* Высота скрытия текста */
    overflow: hidden;
    transition: max-height 0.5s ease;
    margin-bottom: 0px;
  }

  .show-more-checkbox {
    display: none;
  }

  .show-more-checkbox:checked ~ .category_card_content {
    max-height: none;
  }

  .show-more-checkbox:checked ~ .show-more-label {
    display: none;
  }

  .show-more-label {
    display: block;
    font-size: 14px;
    color: blue;
    cursor: pointer;
  }
</style>

<?php
  $connect = getConnect();
  $category_info = array();
  if (isset($category_id)) {
    $result = $connect->query("SELECT * FROM categories WHERE id = '" . $category_id . "'");
    if ($result->num_rows > 0) {
      $category_info = $result->fetch_assoc();
    }
  }
?>

<div class="category_card">
  <?php if (!checkUser('access_level', 'reader')) : ?>
    <a href="/category.php?id=<?= $category_info['id']?>" style="float: right; font-size: 14px; color: #2A5885;">Редактировать</a>
  <?php endif; ?>
  <h2 class="category_card_title"><?= $category_info["title"] ?></h2>
  <input class="show-more-checkbox" id="show-more-<?= $category_info['id'] ?>" type="checkbox">
  <p class="category_card_content"><?= $category_info["description"] ?></p>
  <?php if(strlen($category_info["description"]) > 90) : ?>
    <label for="show-more-<?= $category_info['id'] ?>" class="show-more-label">Показать полностью</label>
  <?php endif; ?>
  <?php if (checkUser('access_level', 'reader')) : ?>
    <?php
    // Проверяем, подписан ли пользователь на категорию
    $reader_id;
    $result = $connect->query("SELECT id FROM readers WHERE id_user = '".$_SESSION['user']['id']."'");
    if ($result->num_rows > 0) {
      $reader_id = $result->fetch_assoc()['id'];
    }
    $stmt_check = $connect->prepare("SELECT COUNT(*) FROM readers_categories WHERE id_category = ? AND id_reader = ?");
    $stmt_check->bind_param("ii", $category_id, $reader_id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();
    ?>
    <?php if ($count > 0) : ?>
      <form method="post">
        <input type="hidden" name="category_id" value="<?= $category_info['id'] ?>">
        <button style="font-size: 16px; background-color: #ff6161;" name="subscribe">Отписаться от категории</button>
      </form>
    <?php else : ?>
      <form method="post">
        <input type="hidden" name="category_id" value="<?= $category_info['id'] ?>">
        <button style="font-size: 16px;" name="subscribe">Подписаться на категорию</button>
      </form>
    <?php endif; ?>
  <?php endif; ?>
</div>
