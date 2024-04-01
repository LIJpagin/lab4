<?php
require_once __DIR__ . '/src/helpers.php';
checkGuest();
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<?php include_once __DIR__ . '/components/head.php' ?>
<style>
  form.register_form {
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 10px 30px 10px 30px;
    margin-bottom: 15px;
    position: relative;
    height: 100;
  }

  body.register_body {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    height: 100vh;
  }
</style>

<body class="register_body">

  <form class="register_form" action="src/actions/register.php" method="post" enctype="multipart/form-data">
    <h4>Регистрация</h4>

    <label for="login_name">
      Логин
      <input
        type="text"
        id="login_name"
        name="login_name"
        value="<?php echo old('login_name') ?>"
        <?php echo validationErrorAttr('login_name'); ?>
      >
      <?php if (hasValidationError('login_name')) : ?>
        <small><?php echo validationErrorMessage('login_name'); ?></small>
      <?php endif; ?>
    </label>

    <div class="grid">
      <label for="password">
        Пароль
        <input 
          type="password"
          id="password"
          name="password"
          placeholder="******"
          <?php echo validationErrorAttr('password'); ?>
        >
        <?php if (hasValidationError('password')) : ?>
          <small><?php echo validationErrorMessage('password'); ?></small>
        <?php endif; ?>
      </label>

      <label for="password_confirmation">
        Подтверждение
        <input
          type="password"
          id="password_confirmation"
          name="password_confirmation"
          placeholder="******"
        >
      </label>
    </div>
    <label for="access_level">Роль</label>
    <select name="access_level" id="access_level">
      <?php $selected = old('access_level');?> 
      <option value="reader"
        <?php echo ($selected == "reader") ? 'selected' : '';?>
      >Читатель</option>
      <option value="author"
        <?php echo ($selected == "author") ? 'selected' : '';?>
      >Автор</option>
      <option value="editorial"
        <?php echo ($selected == "editorial") ? 'selected' : '';?>
      >Редакционный офис</option>
      <option value="administrator"
        <?php echo ($selected == "administrator") ? 'selected' : '';?>
      >Администратор</option>
    </select>

    <button type="submit" id="submit">Продолжить</button>
  </form>

  <p>У меня уже есть <a href="/">аккаунт</a></p>

</body>

</html>