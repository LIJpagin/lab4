<?php
require_once __DIR__ . '/src/helpers.php';
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<?php include_once __DIR__ . '/components/head.php' ?>
<style>
  form.reader_form {
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 10px 30px 10px 30px;
    margin-top: 30px;
    margin-bottom: 30px;
    position: relative;
  }

  body.reader_body {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }

  .reader_change_info {
    font-size: 16px;
  }

  textarea.reader_change_info {
    max-width: 400px;
    min-width: 400px;
    max-height: 300px;
    min-height: 100px;
}
</style>

<?php
  $connect = getConnect();
  // получаем данные о разрешенных нулевых полях
  $sql_reader_columns_is_nullable
      = "SELECT COLUMN_NAME, IS_NULLABLE
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE TABLE_SCHEMA = 'content_management_system'
          AND TABLE_NAME = 'readers';";
  $reader_columns_is_nullable = $connect->query($sql_reader_columns_is_nullable);
  // переформировываем столбцы в строки
  $reader_columns = array();
  while ($row = $reader_columns_is_nullable->fetch_assoc()) {
      $reader_columns[$row['COLUMN_NAME']] = $row['IS_NULLABLE'];
  }
  // пытаемся получить данные читателя
  $there_is_data = false;
  setOldValue('there_is_data', 'false');
  $sql_reader_info 
      = "SELECT surname, name, address, about_me, id_user
          FROM readers
          WHERE id_user = '".$_SESSION['user']['id']."'";
  $result_reader_info = $connect->query($sql_reader_info);
  $reader_info = $connect->query($sql_reader_info)->fetch_assoc();
  // если получили выставляем их в поля
  if ($result_reader_info->num_rows > 0) {
    $there_is_data = true;
    setOldValue('there_is_data', 'true');
    setOldValue('reader_surname', $reader_info['surname']);
    setOldValue('reader_name', $reader_info['name']);
    setOldValue('reader_address', $reader_info['address']);
    setOldValue('reader_about_me', $reader_info['about_me']);
  }
  ?>

<body class="reader_body">  
  <form class="reader_form" action="src/actions/reader.php" method="post" enctype="multipart/form-data">
    <h4>Данные читателя</h4>

    <label for="reader_surname" class="reader_change_info">
      Фамилия
      <?php echo $reader_columns['surname'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      <input
        class="reader_change_info"
        type="text"
        id="reader_surname"
        name="reader_surname"
        placeholder="Иванов"
        value="<?php echo old('reader_surname'); ?>"
        <?php echo validationErrorAttr('reader_surname'); ?>
      >
      <?php if (hasValidationError('reader_surname')) : ?>
        <small><?php echo validationErrorMessage('reader_surname'); ?></small>
      <?php endif; ?>
    </label>

    <label for="reader_name" class="reader_change_info">
      Имя
    <?php echo $reader_columns['name'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      <input 
        class="reader_change_info"
        type="reader_name"
        id="reader_name"
        name="reader_name"
        placeholder="Иван"
        value="<?php echo old('reader_name') ?>"
        <?php echo validationErrorAttr('reader_name'); ?>
      >
      <?php if (hasValidationError('reader_name')) : ?>
        <small><?php echo validationErrorMessage('reader_name'); ?></small>
      <?php endif; ?>
    </label>

    <label for="reader_address" class="reader_change_info">
      Адрес
      <?php echo $reader_columns['address'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      <input 
        class="reader_change_info"
        type="reader_address"
        id="reader_address"
        name="reader_address"
        placeholder="Россия, Москва"
        value="<?php echo old('reader_address') ?>"
      >
    </label>

    <label for="reader_about_me" class="reader_change_info">
      О себе
      <?php echo $reader_columns['about_me'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
    </label>
    <textarea 
      class="reader_change_info"
      id="reader_about_me"
      name="reader_about_me"
    ><?php echo old('reader_about_me') ?></textarea>

    <button class="reader_change_info" type="submit" id="submit">Продолжить</button>
    <?php if ($there_is_data == true) : ?>
      <button formaction="/home.php" class="reader_change_info" style="background-color: #ff6161;">Отменить</button>
    <?php endif; ?>
  </form>

</body>

</html>