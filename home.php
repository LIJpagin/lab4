<?php
require_once __DIR__ . '/src/helpers.php';

checkAuth();
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<?php include_once __DIR__ . '/components/head.php' ?>
<style>
  .news_feed {
    display: flex;
    justify-content: space-between;
  }

  .subscribes {
    flex: 1;
    margin-top: 70px;
  }

  .publications {
    flex: 1;
    margin-top: 70px;
  }

  .filters {
    flex: 1;
  }

  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
  }

  .content {
    padding: 20px;
  }
</style>

<body>
  <?php include_once __DIR__ . '/components/scroll_to_top_button.php' ?>
  <div class="news_feed">
    <?php include_once __DIR__ . '/components/header.php' ?>
    <div class="filters">
      <?php include_once __DIR__ . '/components/filters.php' ?>
    </div>
    <div class="publications">
      <?php include_once __DIR__ . '/components/create_publications.php' ?>
      <?php include_once __DIR__ . '/components/publications.php' ?>
    </div>
    <div class="subscribes">
      <?php include_once __DIR__ . '/components/subscribe.php' ?>
      <?php include_once __DIR__ . '/components/search.php' ?>
    </div>
  </div>
</body>

</html>