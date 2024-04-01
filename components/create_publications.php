<?php
if (checkUser('access_level', 'author')) :
  $showForm = $_GET['show'] ?? '';
  function isActive($formName, $showForm) {
    return ($formName == $showForm) ? 'active' : '';
  }
?>

  <style>
    .publication_create {
      font-size: 14px;
    }

    .not_ref {
      text-decoration: none;
    }

    .not_ref:hover {
      text-decoration: underline;
    }

    textarea.publication_create {
      max-width: 540px;
      min-width: 540px;
      min-height: 100px;
    }
  </style>

  <div class="card post">
    <?php if ($showForm != 'show_form') : ?>
      <span class="<?php echo isActive('show_form', $showForm); ?>">
        <a href="?show=show_form" class="publication_create">
          <span class="not_ref" style="color: #2A5885;">Создать новую публикацию</span>
        </a>
      </span>
    <?php else : ?>
      <div class="form-fields">

        <span class="<?php echo isActive('table_management', $showForm); ?>">
          <a href="?" style="float: right; font-size: 14px; color: #2A5885;">Скрыть</a>
        </span>

        <form method="post" action="src/actions/create_publications.php" style="margin-bottom: 0px; margin-top: 10px;">
          <label for="publication-title" class="publication_create">Заголовок</label>
          <input type="text" id="publication-title" class="publication_create">

          <label for="publication-content" class="publication_create">Содержание</label>
          <textarea name="publication-content" class="publication_create"></textarea>

          <label for="publication-category" class="publication_create">Категория</label>
          <div style="display: flex; align-items: center;">
            <select name="category" id="category" class="publication_create" style="flex: 1;">
              <?php
              $connect = getConnect();
              $sql_categories = "SELECT id, title FROM categories";
              $result_categories = $connect->query($sql_categories);
              if ($result_categories->num_rows > 0) {
                while ($row = $result_categories->fetch_assoc())
                  echo optionTag('category', $row['id'], $row['title']);
              }
              $connect->close();
              ?>
            </select>
            <div style="margin-left: 20px;">
              <button
                formaction="category.php"
                id="no-category-button" 
                class="publication_create"
                style="background-color: #ff6161;"
              >Нет нужной</button>
            </div>
          </div>
          <button class="publication_create">Опубликовать</button></a>
        </form>
      </div>
    <?php endif; ?>
  </div>

<?php endif; ?>