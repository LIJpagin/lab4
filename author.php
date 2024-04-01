<?php
require_once __DIR__ . '/src/helpers.php';
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<?php include_once __DIR__ . '/components/head.php' ?>
<style>
  form.author_form {
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 10px 30px 10px 30px;
    margin-top: 30px;
    margin-bottom: 30px;
    position: relative;
    height: 100;
  }

  body.author_body {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }

  .author_change_info {
    font-size: 16px;
  }

  textarea.author_change_info {
    max-width: 400px;
    min-width: 400px;
    max-height: 300px;
    min-height: 100px;
}
</style>

<?php
  $connect = getConnect();
  // получаем данные о разрешенных нулевых полях
  $sql_author_columns_is_nullable
      = "SELECT COLUMN_NAME, IS_NULLABLE
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE TABLE_SCHEMA = 'content_management_system'
          AND TABLE_NAME = 'authors';";
  $author_columns_is_nullable = $connect->query($sql_author_columns_is_nullable);
  // переформировываем столбцы в строки
  $author_columns = array();
  while ($row = $author_columns_is_nullable->fetch_assoc()) {
      $author_columns[$row['COLUMN_NAME']] = $row['IS_NULLABLE'];
  }
  // пытаемся получить данные 
  $there_is_data = false;
  setOldValue('there_is_data', 'false');
  $sql_author_info 
      = "SELECT surname, name, patronymic, birthdate, work_experience
          FROM authors
          WHERE id_user = '".$_SESSION['user']['id']."'";
  $result_author_info = $connect->query($sql_author_info);
  $author_info = $connect->query($sql_author_info)->fetch_assoc();
  // если получили выставляем их в поля
  if ($result_author_info->num_rows > 0) {
    $there_is_data = true;
    setOldValue('there_is_data', 'true');
    setOldValue('author_surname', $author_info['surname']);
    setOldValue('author_name', $author_info['name']);
    setOldValue('author_patronymic', $author_info['patronymic']);
    setOldValue('author_birthdate', $author_info['birthdate']);
    setOldValue('author_work_experience', $author_info['work_experience']);
  }
  ?>

<body class="author_body">  
  <form class="author_form" action="src/actions/author.php" method="post" enctype="multipart/form-data">
  <h4>Данные автора</h4>

    <label for="author_surname" class="author_change_info">
      Фамилия
      <?php echo $author_columns['surname'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      <input
        class="author_change_info"
        type="text"
        id="author_surname"
        name="author_surname"
        placeholder="Иванов"
        value="<?php echo old('author_surname'); ?>"
        <?php echo validationErrorAttr('author_surname'); ?>
      >
      <?php if (hasValidationError('author_surname')) : ?>
        <small><?php echo validationErrorMessage('author_surname'); ?></small>
      <?php endif; ?>
    </label>

    <label for="author_name" class="author_change_info">
      Имя
    <?php echo $author_columns['name'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      <input 
        class="author_change_info"
        type="author_name"
        id="author_name"
        name="author_name"
        placeholder="Иван"
        value="<?php echo old('author_name') ?>"
        <?php echo validationErrorAttr('author_name'); ?>
      >
      <?php if (hasValidationError('author_name')) : ?>
        <small><?php echo validationErrorMessage('author_name'); ?></small>
      <?php endif; ?>
    </label>

    <label for="author_patronymic" class="author_change_info">
      Отчество
    <?php echo $author_columns['patronymic'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      <input 
        class="author_change_info"
        type="author_patronymic"
        id="author_patronymic"
        name="author_patronymic"
        placeholder="Иванович"
        value="<?php echo old('author_patronymic') ?>"
        <?php echo validationErrorAttr('author_patronymic'); ?>
      >
      <?php if (hasValidationError('author_patronymic')) : ?>
        <small><?php echo validationErrorMessage('author_patronymic'); ?></small>
      <?php endif; ?>
    </label>

    <label for="author_birthdate" class="author_change_info">
      Дата рождения
      <?php echo $author_columns['birthdate'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      <input 
        class="author_change_info"
        type="text"
        id="author_birthdate"
        name="author_birthdate"
        placeholder="ГГГГ-ММ-ДД"
        value="<?php echo old('author_birthdate') ?>"
        <?php echo validationErrorAttr('author_birthdate'); ?>
      >
      <?php if (hasValidationError('author_birthdate')) : ?>
        <small><?php echo validationErrorMessage('author_birthdate'); ?></small>
      <?php endif; ?>
    </label>

    <label for="author_work_experience" class="author_change_info">
      Стаж работы
      <?php echo $author_columns['work_experience'] == "NO" ? '<span style="color: red;">*</span>' : ''; ?>
      <input 
        class="author_change_info"
        type="number"
        id="author_work_experience"
        name="author_work_experience"
        placeholder="Стаж работы"
        value="<?php echo old('author_work_experience') ?>"
        <?php echo validationErrorAttr('author_work_experience'); ?>
      >
      <?php if (hasValidationError('author_work_experience')) : ?>
        <small><?php echo validationErrorMessage('author_work_experience'); ?></small>
      <?php endif; ?>
    </label>

    <button class="author_change_info" type="submit" id="submit">Продолжить</button>

    <?php if ($there_is_data == true) : ?>
      <button formaction="/home.php" class="author_change_info" style="background-color: #ff6161;">Отменить</button>
    <?php endif; ?>
  </form>

</body>

</html>