<style>
  .post_container {
    width: 600px;
  }

  .post {
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 10px 30px 10px 30px;
    margin-bottom: 15px;
    position: relative;
  }

  .post_title {
    font-size: 20px;
    margin-bottom: 15px;
    line-height: 0.9;
    color: #2A5885;
  }

  .post_category {
    font-style: italic;
    font-size: 16px;
    color: #818C99;
    float: right;
  }

  .post_time {
    font-size: 12px;
    color: #818CA6;
    float: right;
  }

  .post_content {
    line-height: 1.4;
    font-size: 16px;
    color: #000;
    margin-bottom: 0px;
    max-height: 90px; /* Высота скрытия текста */
    overflow: hidden;
  }

  .post_author {
    font-size: 14px;
    margin-bottom: 2px;
    line-height: 1;
    color: #2A5885;
    display: inline-block;
  }

  .show-more-checkbox {
    display: none;
  }

  .show-more-checkbox:checked ~ .post_content {
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

  // запрос id автора для отображения ссылки на редактирование публикаций в ленте
  $result_choice_publication_author_id = $connect->query("SELECT id FROM authors WHERE id_user = '".$_SESSION['user']['id']."'");
  $choice_publication_author_id;
  if ($result_choice_publication_author_id->num_rows > 0) {
    $choice_publication_author_id = $result_choice_publication_author_id->fetch_assoc()['id'];
  }

  // запрос публикаций по фильтрам
  $posts = [];
  if (isset($_SESSION['sql_publications_with_filters'])) {
    $result = $connect->query($_SESSION['sql_publications_with_filters']);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
      }
    }
  }
  $connect->close();
?>

<div class="post_container">
  <?php if (count($posts) > 0) : ?>
    <?php foreach ($posts as $post) : ?>
      <div class="card post">
        <div class="post_details">
          <div>
            <a href="/author_info.php?id=<?= $post["authors_id"] ?>">
              <span class="post_author not_ref"><?= $post["surname"] ?> <?= $post["name"] ?></span>
            </a>
            <a href="/category_info.php?id=<?= $post["categories_id"] ?>">
              <span class="post_category not_ref"><?= $post["category_title"] ?></span>
            </a>
          </div>
          <span class="post_time"><?= formatDataTime($post["date_time"]) ?></span>
        </div>
        <h2 class="post_title"><?= $post["publication_title"] ?></h2>
        <input class="show-more-checkbox" id="show-more-<?= $post['publication_id'] ?>" type="checkbox">
        <p class="post_content"><?= $post["content"] ?></p>
        <?php if(strlen($post["content"]) > 90) : ?>
          <label for="show-more-<?= $post['publication_id'] ?>" class="show-more-label">Показать полностью</label>
        <?php endif; ?>
        <?php if (checkUser('access_level', 'author') && ($choice_publication_author_id === $post["authors_id"])) : ?>
          <a href="publication.php?id=<?= $post['publication_id']?>" style="font-size: 14px; color: #2A5885;">Редактировать</a>
          <a href="../src/actions/delete_publication.php?id=<?= $post['publication_id'] ?>" style="font-size: 14px; color: #2A5885;">Удалить</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <div class="card post">
      <h2 class="post_title" style="padding-top: 15px;">Нет публикаций</h2>
    </div>
  <?php endif; ?>
</div>