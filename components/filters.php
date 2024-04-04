<style>
  .filter-sort {
    background-color: #f0f0f0;
    border-radius: 5px;
    width: 250px;
    margin-top: 70px;
    margin-right: 15px;
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 10px 20px 0px 20px;
  }

  .filter-sort select {
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
    margin-bottom: 10px;
  }

  .filter-sort label {
    margin-right: 20px;
    font-size: 14px;
  }

  .filter-apply {
    font-size: 14px;
  }
</style>

<div method="get" class="filter-sort">
  <form class="card" style="margin-bottom: 0px;">

    <h6 style="text-align: center; margin-bottom: 10px;">Фильтры</h6>

    <label for="filter-date">По дате и времени</label>
    <select id="filter-date" name="filter-date">
      <option value="newest" <?php echo selected('filter-date', 'newest'); ?>>Сначала новые</option>
      <option value="oldest" <?php echo selected('filter-date', 'oldest'); ?>>Сначала старые</option>
      <option value="random" <?php echo selected('filter-date', 'random'); ?>>Случайные</option>
    </select>

    <label for="filter-category">По категории</label>
    <select id="filter-category" name="filter-category">
      <option value="">Все категории</option>
      <?php if (checkUser('access_level', 'reader')) : ?>
        <option value="my_categories" <?php echo selected('filter-category', 'my_categories');?>>Мои подписки</option>
      <?php endif; ?>
      <?php
        $connect = getConnect();
        $result_categories = $connect->query("SELECT id, title FROM categories");
        while ($result_categories->num_rows > 0 && $row = $result_categories->fetch_assoc()) {
          echo optionTag('filter-category', $row['id'], $row['title']);
        }
      ?>
    </select>

    <label for="filter-author">По автору</label>
    <select id="filter-author" name="filter-author">
      <option value="">Все авторы</option>
      <?php if (checkUser('access_level', 'author')) : ?>
        <option value="my_publications" <?php echo selected('filter-author', 'my_publications');?>>Мои публикации</option>
      <?php endif; ?>
      <?php if (checkUser('access_level', 'reader')) : ?>
        <option value="my_authors" <?php echo selected('filter-author', 'my_authors');?>>Мои подписки</option>
      <?php endif; ?>
      <?php
        $result_authors = $connect->query("SELECT id, CONCAT(surname, ' ', name) AS full_name FROM authors");
        if ($result_authors->num_rows > 0) {
          while ($row = $result_authors->fetch_assoc()) {
            echo optionTag('filter-author', $row['id'], $row['full_name']);
          }
        }
      ?>
    </select>
    
    <button class="filter-apply">Применить</button>

  </form>
</div>

<?php
$filter_author = isset($_GET['filter-author']) ? $_GET['filter-author'] : '';
$filter_category = isset($_GET['filter-category']) ? $_GET['filter-category'] : '';
$sort_order = isset($_GET['filter-date'])
  && ($_GET['filter-date'] == 'oldest'
      || $_GET['filter-date'] == 'newest'
      || $_GET['filter-date'] == 'random')
  ? $_GET['filter-date'] : 'newest';

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
      WHERE 1";

if (!empty($filter_author)) {
  if ($filter_author === "my_publications") {
    $result_author_id = $connect->query("SELECT id FROM authors WHERE id_user = '".$_SESSION['user']['id']."'");
    $author_id = $result_author_id->fetch_assoc()['id'];
    if ($result_author_id->num_rows > 0)
      $sql_publications_with_filters .= " AND authors.id = '".$author_id."'";
  }
  else if ($filter_author === "my_authors") {
    $reader_id = $connect->query("SELECT id FROM readers WHERE id_user = '".$_SESSION['user']['id']."'")->fetch_assoc()['id'];
    $result_subscribed_authors = $connect->query("SELECT id_author FROM readers_authors WHERE id_reader = '".$reader_id."'");
    if ($result_subscribed_authors->num_rows > 0) {
        $subscribed_authors = array();
        while ($row = $result_subscribed_authors->fetch_assoc()) {
            $subscribed_authors[] = $row['id_author'];
        }
        $sql_publications_with_filters .= " AND authors.id IN (".implode(",", $subscribed_authors).")";
    } else {
        $sql_publications_with_filters .= " AND 0";
    }
  }
  else {
    $sql_publications_with_filters .= " AND authors.id = '$filter_author'";
  }
}

if (!empty($filter_category)) {
  if ($filter_category === "my_categories") {
    $reader_id = $connect->query("SELECT id FROM readers WHERE id_user = '".$_SESSION['user']['id']."'")->fetch_assoc()['id'];
    $result_subscribed_categories = $connect->query("SELECT id_category FROM readers_categories WHERE id_reader = '".$reader_id."'");
    if ($result_subscribed_categories->num_rows > 0) {
        $subscribed_categories = array();
        while ($row = $result_subscribed_categories->fetch_assoc()) {
            $subscribed_categories[] = $row['id_category'];
        }
        $sql_publications_with_filters .= " AND categories.id IN (".implode(",", $subscribed_categories).")";
    } else {
        $sql_publications_with_filters .= " AND 0"; 
    }
  }
  else {
    $sql_publications_with_filters .= " AND categories.id = '$filter_category'";
  }
}


if ($sort_order == 'newest')
  $sql_publications_with_filters .= " ORDER BY publications.date_time DESC";
if ($sort_order == 'oldest')
    $sql_publications_with_filters .= " ORDER BY publications.date_time ASC";
if ($sort_order == 'random')
  $sql_publications_with_filters .= " ORDER BY RAND()";

$_SESSION['sql_publications_with_filters'] = $sql_publications_with_filters;
?>