<?php
require_once __DIR__ . '/src/helpers.php';
if (checkUser('access_level', 'author')) :
?>

  <!DOCTYPE html>
  <html lang="ru" data-theme="light">
  <?php include_once __DIR__ . '/components/head.php' ?>

  <style>
    form.form {
      background-color: #fff;
      border: 1px solid #DCE1E6;
      border-radius: 10px;
      padding: 10px 30px 10px 30px;
      margin-top: 30px;
      margin-bottom: 30px;
      position: relative;
    }

    .change_info {
      font-size: 16px;
    }

    textarea.change_info {
      max-width: 400px;
      min-width: 400px;
      max-height: 300px;
      min-height: 100px;
    }

    .publication {
      font-size: 14px;
    }

    textarea.publication {
      max-width: 540px;
      min-width: 540px;
      min-height: 100px;
    }
  </style>

  <?php
    $connect = getConnect();

    // получаем данные о разрешенных нулевых полях
    $columns_is_nullable = $connect->query(
      "SELECT COLUMN_NAME, IS_NULLABLE
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = 'content_management_system'
        AND TABLE_NAME = 'publications';");
    // переформировываем столбцы в строки
    $columns = array();
    while ($row = $columns_is_nullable->fetch_assoc()) {
      $columns[$row['COLUMN_NAME']] = $row['IS_NULLABLE'];
    }

    // пытаемся получить данные читателя
    setOldValue('query', 'INSERT');
    if (isset($_GET['id'])) {
      $result_info = $connect->query(
        "SELECT * FROM publications WHERE id = '" . $_GET['id'] . "'");
      if ($result_info->num_rows > 0) {
        $info = $result_info->fetch_assoc();
        setOldValue('query', 'UPDATE');
        setOldValue('id', $info['id']);
        setOldValue('title', $info['title']);
        setOldValue('content', $info['content']);
        setOldValue('id_category', $info['id_category']);
      }
    }
    
    $showForm = $_GET['show'] ?? '';
    function isActive($formName, $showForm)
    {
      return ($formName == $showForm) ? 'active' : '';
    }
  ?>

  <body style="height: 100vh;">
    <form method="post" action="src/actions/publication.php" class="form">
      <h4>Данные публикации</h4>

      <label for="title" class="publication">
        Заголовок
        <?php echo $columns['title'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
        <input
          class="publication"
          type="text"
          id="title"
          name="title"
          value="<?php echo old('title'); ?>"
          <?php echo validationErrorAttr('title'); ?>
        >
        <?php if (hasValidationError('title')) : ?>
          <small><?php echo validationErrorMessage('title'); ?></small>
        <?php endif; ?>
      </label>

      <label for="content" class="publication">
        Описание
        <?php echo $columns['content'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      </label>
      <textarea 
        class="publication"
        id="content"
        name="content"
      ><?php echo old('content') ?></textarea>
      <?php if (hasValidationError('content')) : ?>
          <small><?php echo validationErrorMessage('content'); ?></small>
      <?php endif; ?>

      <label for="category" class="publication">Категория</label>
      <div style="display: flex; align-items: center;">
        <select name="id_category" id="category" class="publication" style="flex: 1;">
          <?php
          $connect = getConnect();
          $sql_categories = "SELECT id, title FROM categories";
          $result_categories = $connect->query($sql_categories);
          $selected = old('id_category');
          if ($result_categories->num_rows > 0) {
            while ($row = $result_categories->fetch_assoc())
              echo '<option value="'.$row['id'].'" '.($row['id'] == $selected ? 'selected' : '').'>'.$row['title'].'</option>';
          }
          $connect->close();
          ?>
        </select>
        <div style="margin-left: 20px;">
          <button formaction="category.php" id="no-category-button" class="publication" style="background-color: #ff6161;">Нет нужной</button>
        </div>
      </div>
      <button class="publication">Продолжить</button>
      
      <button formaction="/home.php" class="publication" style="background-color: #ff6161;">Отменить</button>

    </form>
  </body>

  </html>

<?php endif; ?>