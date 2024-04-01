<style>
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #fff;
    color: #fff;
    padding: 10px 20px;
    box-sizing: border-box;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
    border: 1px solid #DCE1E6;
  }

  .header .header-logo {
    display: flex;
    align-items: center;
    text-decoration: dashed;
    height: 100%;
  }

  .header img {
    width: auto;
    height: 30px;
    margin-right: 10px;
  }

  .header-logo h4 {
    margin: 0;
  }
  
</style>

<div class="card header">
  <div class="header-user">
    <a href="<?php echo "/".$_SESSION['user']['access_level'].".php"; ?>"><img src="./assets/user.svg"><span style="font-size: 18px;"><?=$_SESSION['user']['login'] ?><span> </a>
  </div>
  <div class="header-logo">
    <a class="header-logo" href="/">
      <img src="./assets/logo.png">
      <h4>NEWasty</h4>
    </a>
  </div>
  <div class="header-logout">
    <a href="src/actions/logout.php"><img src="./assets/logout.svg"></a>
  </div>
</div>