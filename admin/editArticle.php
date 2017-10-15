<?php  require(PARTS.'/header.php');
 //include(PARTS.'/left_aside.php'); ?>
<article>
      <div id="adminHeader">
        <h2>Кабинет администратора</h2>
        <p>Здравствуйте, <b><?php echo htmlspecialchars( $_SESSION['uname']) ?></b>. <a href="admin.php?action=logout"?>Выйти</a></p>
      </div>

      <h1><?php echo $results['pageTitle']?></h1>

      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

        <ul>

          <li>
            <label for="title">Заголовок</label>
            <input type="text" name="title" id="title" placeholder="Заголовок" required autofocus maxlength="55" value="<?php echo htmlspecialchars( $results['article']->title )?>" />
          </li>
          <li>
            <label for="summary">Краткое описание</label>
            <textarea name="summary" id="summary" placeholder="Краткое описание" required maxlength="255" style="height: 5em;"><?php echo htmlspecialchars( $results['article']->summary )?></textarea>
          </li>
          <li>
            <label for="content">Контент</label>
            <textarea name="content" id="content" placeholder="Контент" required maxlength="50000" style="height: 20em;"><?php echo htmlspecialchars( $results['article']->content )?></textarea>
          </li>
          <li>
            <label for="createdate">Дата создания</label>
            <input type="date" name="createdate" id="createdate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->createdate ? date( "Y-m-d", $results['article']->createdate ) : "" ?>" />
          </li>
		  <li>
            <label for="changedate">Дата последнего изменениия</label>
            <input type="date" name="changedate" id="changedate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->changedate ? date( "Y-m-d", $results['article']->changedate ) : "" ?>" />
          </li>
		  <li>
            <label for="pubdate">Дата публикации</label>
            <input type="date" name="pubdate" id="pubdate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->pubdate ? date( "Y-m-d", $results['article']->pubdate ) : "" ?>" />
          </li>
		  <li>
            <label for="autor">Автор</label>
            <input type="text" name="autor" id="autor" placeholder="Автор" required  maxlength="55" value="<?php echo htmlspecialchars( $results['article']->autor )?>" />
          </li>
		  <li>
            <label for="status">Статус</label>
            <input type="text" name="status" id="status" required  maxlength="5" value="<?php echo htmlspecialchars( $results['article']->status )?>" />
          </li>
		  <li>
            <label for="topic">Тема</label>
            <input type="text" name="topic" id="topic" required  maxlength="55" value="<?php echo htmlspecialchars( $results['article']->topic )?>" />
          </li>
		  <li>
            <label for="tegs">Теги</label>
            <input type="text" name="tegs" id="tegs" required  maxlength="55" value="<?php echo htmlspecialchars( $results['article']->tegs )?>" />
          </li>
		  <li>
            <label for="liked">Нравится</label>
            <input type="text" name="liked" id="liked" maxlength="5" value="<?php echo htmlspecialchars( $results['article']->liked )?>" />
          </li>
		  <li>
            <label for="disliked">Ненравится</label>
            <input type="text" name="disliked" id="disliked" maxlength="5" value="<?php echo htmlspecialchars( $results['article']->disliked )?>" />
          </li>
		  <li>
            <label for="looked">Просмотров</label>
            <input type="text" name="looked" id="looked" maxlength="55" value="<?php echo htmlspecialchars( $results['article']->looked )?>" />
          </li>


        </ul>

        <div class="buttons">
          <input type="submit" name="saveChanges" value="<?php if($results['formAction']=="editArticle"){ echo "Сохранить изменения"; }else{ echo "Сохранить";}?>" />
          <input type="submit" formnovalidate name="cancel" value="Отмена" />
		  <?php if($results['formAction']=="editArticle"){ ?><input type="submit" name="saveNew" value="Сохранить как новую" /><?php } ?>
        </div>

      </form>

<?php if ( $results['article']->id ) { ?>
      <p><a href="admin.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Delete This Article?')">Удалить эту статью</a></p>
<?php } ?>
</article>
<?php include(PARTS.'/footer.php');?>

