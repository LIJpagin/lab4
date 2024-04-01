<?php
require_once __DIR__ . '/src/helpers.php';
if (!checkUser('access_level', 'reader')) :
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<?php include_once __DIR__ . '/components/head.php' ?>
<style>
  body.category_body {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    height: 100vh;
  }

  form.category_form {
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 10px 30px 10px 30px;
    margin-top: 30px;
    margin-bottom: 30px;
    position: relative;
  }

  .category_change_info {
    font-size: 16px;
  }

  textarea.category_change_info {
    max-width: 400px;
    min-width: 400px;
    max-height: 300px;
    min-height: 100px;
}
</style>

<?php
  $connect = getConnect();
  // получаем данные о разрешенных нулевых полях
  $category_columns_is_nullable = $connect->query(
    "SELECT COLUMN_NAME, IS_NULLABLE
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE TABLE_SCHEMA = 'content_management_system'
      AND TABLE_NAME = 'categories';");
  // переформировываем столбцы в строки
  $category_columns = array();
  while ($row = $category_columns_is_nullable->fetch_assoc()) {
      $category_columns[$row['COLUMN_NAME']] = $row['IS_NULLABLE'];
  }
  $there_is_data = false;
  setOldValue('there_is_data', 'false');
  // пытаемся получить данные читателя
  if (isset($_GET['id'])) {
    $result_category_info = $connect->query(
      "SELECT *
        FROM categories
        WHERE id = '".$_GET['id']."'");
    if ($result_category_info->num_rows > 0) {
      $there_is_data = true;
      $category_info = $result_category_info->fetch_assoc();
      setOldValue('there_is_data', 'true');
      setOldValue('category_id', $category_info['id']);
      setOldValue('category_title', $category_info['title']);
      setOldValue('category_description', $category_info['description']);
    }
  }
?>

<body class="category_body">  
  <form class="category_form" action="src/actions/category.php" method="post" enctype="multipart/form-data">
    
    <h4>Данные категории</h4>

    <label for="category_title" class="category_change_info">
      Название
      <?php echo $category_columns['title'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      <input
        class="category_change_info"
        type="text"
        id="category_title"
        name="category_title"
        value="<?php echo old('category_title'); ?>"
        <?php echo validationErrorAttr('category_title'); ?>
      >
      <?php if (hasValidationError('category_title')) : ?>
        <small><?php echo validationErrorMessage('category_title'); ?></small>
      <?php endif; ?>
    </label>

    <label for="category_description" class="category_change_info">
      Описание
      <?php echo $category_columns['description'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
    </label>
    <textarea 
      class="category_change_info"
      id="category_description"
      name="category_description"
    ><?php echo old('category_description') ?></textarea>
    <?php if (hasValidationError('category_description')) : ?>
        <small><?php echo validationErrorMessage('category_description'); ?></small>
    <?php endif; ?>

    <button class="category_change_info" type="submit" id="submit">Продолжить</button>
    <button formaction="/home.php" class="category_change_info" style="background-color: #ff6161;">Отменить</button>
  </form>

</body>

</html>

<?php endif; ?>