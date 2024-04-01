<?php
require_once __DIR__ . '/src/helpers.php';

checkGuest();
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<?php include_once __DIR__ . '/components/head.php' ?>
<style>
  form.login_form {
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 10px 30px 10px 30px;
    margin-bottom: 15px;
    position: relative;
    height: 100;
  }

  body.login_body {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    height: 100vh;
  }
</style>

<body class="login_body">

  <form class="login_form" action="src/actions/login.php" method="post">
    <h4>Вход</h4>

    <?php if (hasMessage('error')) : ?>
      <div class="notice error"><?php echo getMessage('error') ?></div>
    <?php endif; ?>

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

    <label for="password">
      Пароль
      <input type="password" id="password" name="password" placeholder="******">
    </label>

    <button type="submit" id="submit">Продолжить</button>
  </form>

  <p>У меня еще нет <a href="/register.php">аккаунта</a></p>

</body>

</html>