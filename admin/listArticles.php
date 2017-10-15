<?php  require(PARTS.'/header.php');
 //include(PARTS.'/left_aside.php');?>
<article>
      <div id="adminHeader">
        <h2>Кабинет администратора</h2>
        <p>Здравствуйте, <b><?php echo htmlspecialchars( $_SESSION['uname']) ?></b>. <a href="admin.php?action=logout"?>Выход</a></p>
      </div>

      <h1>Все статьи</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>


<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

      <table>
        <tr>
		  <th>Номер</th>
          <th>Дата</th>
          <th>Статья</th>
        </tr>
 
 <?php 	$page = isset( $_GET['page'] ) ? $_GET['page'] : "1";//page number
		$pages=ceil($results['totalRows']/AIP);//pages count
		$bk=$page*AIP-AIP;//bottom article number
		$tk=$page*AIP;if($tk>$results['totalRows']){$tk=$results['totalRows'];}//top...
		foreach ( $results['articles'] as $key=>$article ) { 
		  if($key<$bk){continue;}
		  else{
			if($key>=$tk)break;
 ?>

		  <tr onclick="location='admin.php?action=editArticle&amp;articleId=<?php echo $article->id?>'">
			<td><?php echo $key+1;?></td>
			<td><?php echo date('j M Y', $article->pubdate)?></td>
			<td><?php echo $article->title?></td>
		  </tr>

<?php } } ?> 

      </table>
		
<?php include(ADMIN.'/pagelinks.php'); ?>

      <p><a href="admin.php?action=newArticle">Создать статью</a></p>
</article>
<?php include(PARTS.'/footer.php'); ?>

