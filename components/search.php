<?php
if (checkUser('access_level', 'reader')) :
?>

<style>
  .subscribe {
    background-color: #f0f0f0;
    border-radius: 5px;
    width: 250px;
    margin-left: 15px;
    margin-bottom: 15px;
    background-color: #fff;
    border: 1px solid #DCE1E6;
    border-radius: 10px;
    padding: 10px 20px 0px 20px;
  }

  .search_button {
    font-size: 14px;
  }
</style>

<div class="subscribe">
  <form style="margin-bottom: 0px;">

    <h6 style="text-align: center; margin-bottom: 10px;">Что-то новенькое</h6>
        
    <button class="search_button" formaction="search_authors.php">Автор</button>
    <button class="search_button" formaction="search_categories.php">Категория</button>
    <button class="search_button" formaction="search_editorial.php">Редакция</button>

  </form>
</div>

<?php endif; ?>