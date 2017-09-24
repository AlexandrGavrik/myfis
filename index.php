<?php 

session_start();
require('./config.php');



$action = isset( $_GET['action'] ) ? $_GET['action'] : "";


switch ( $action ) {
  case 'archive':
    archive();
    break;
  case 'viewArticle':
    viewArticle();
    break;
  default:
    homepage();
}
function archive() {
  $results = array();
  $data = Article::getList();
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Article Archive";
  require(PARTS.'/header.php');
 //include(PARTS.'/left_aside.php');

	?>  <article>
		<h1>Архив</h1>
		  <ul id="headlines" class="archive">
	<?php foreach ( $results['articles'] as $article ) { ?>
			<li>
			  <h2>
				<span class="pubDate"><?php echo date('j F Y', $article->pubdate)?></span><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><?php echo htmlspecialchars( $article->title )?></a>
			  </h2>
			  <p class="summary"><?php echo htmlspecialchars( $article->summary )?></p>
			</li>
	<?php } ?>
		  </ul>
		  <p>Всего статей: <?php echo $results['totalRows']?></p>
		  <p><a href="./">Домой</a></p>
		</article>
	<?php }
function viewArticle() {
  if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
    homepage();
    return;
  }
  function looked(){
	$l="looked";
	if(empty($_COOKIE['uip'])){
		$ip=getRealIP();
		$cook_time=strtotime("+1 day");
		setcookie('uip', $ip, $cook_time);
		echo date('r',$cook_time).'<br>'.$ip;
		Article::counter((int)$_GET["articleId"], $l);
	}else{$message="Рад снова видеть Вас!";}
}
function liked($l){
	$ip=getRealIP();
	$like=Ip::checkIp($ip, $_GET["articleId"]);
	if($like==null){
		$liked=new Ip;
		$liked->storeFormValues(array("ip"=>$ip, "action"=>$l, "articleid"=>$_GET["articleId"]));
		$liked->insert();
		Article::counter($_GET["articleId"], $l);
	}
}
function comment(){
	if ( isset( $_POST['autor'] )&& isset( $_POST['content'] ) ) {
		$comment = new Comment;
		$comment->storeFormValues( $_POST );
		$comment->insert();
		header( "Location: index.php?action=viewArticle&status=changesSaved&articleId=".$_GET["articleId"] );
	}else{$message="!!!ВВЕДЁННЫЕ ВАМИ ДАННЫЕ НЕКОРРЕКТНЫ!!!";}
}
 $vote = isset( $_GET['vote'] ) ? $_GET['vote'] : "";
switch ( $vote ) {
  case 'pro':
    liked("liked");
    break;
  case 'against':
    liked("disliked");
    break;
  case 'comment':
    comment();
    break;
  default:
    looked();
}


  $results = array();
  $results['article'] = Article::getById( (int)$_GET["articleId"] );
  $results['pageTitle'] = $results['article']->title;
  

$data = Comment::getList((int)$_GET["articleId"]);
$comments = $data['results'];
$commentsRows = $data['totalRows'];

  require(PARTS.'/header.php');//echo $_SERVER['HTTP_USER_AGENT'];print_r($_SERVER['SystemRoot']);
 //include(PARTS.'/left_aside.php');//echo $_COOKIE['uip'];
	?>
<?php

?>		<article>
		<p class="message"><?php echo $message;?></p>
		<p><a href="./">Домой</a></p>
		
		<h1><?php echo htmlspecialchars( $results['article']->title )?></h1>
		<p class="artic_info"><?php echo $results['article']->autor." - ".date('j F Y', $results['article']->pubdate)?></p>
		  <div class="summary"><?php echo htmlspecialchars( $results['article']->summary )?></div>
		  <div class="content"><?php echo $results['article']->content?></div>
		  

			<p id="like" class="like">
				<a href=".?action=viewArticle&amp;articleId=<?php echo $results['article']->id?>&amp;vote=pro">Нравится: <?php echo $results['article']->liked;?>  </a>
				<a href=".?action=viewArticle&amp;articleId=<?php echo $results['article']->id?>&amp;vote=against"> Ненравится: <?php echo $results['article']->disliked;?></a>
				<span> Просмотров: <?php echo $results['article']->looked;?>  </span>
				<span> Комментов: <?php echo $commentsRows;?></span>
			</p>
		

		<section>
		<p>Оставить комментарий</p>
		<form action=".?action=viewArticle&amp;articleId=<?php echo $results['article']->id?>&amp;vote=comment#<?php echo $comment->id;?>" method="POST">
			<input hidden name="articleid" type="text" value="<?php echo $results['article']->id?>"></input>
		<ul>
		<li>
			<label for="autor">Имя</label>
            <input type="text" name="autor" id="autor" placeholder="Автор"  required  maxlength="55"/>
		</li><li>
			<label for="content">Коммент</label>
            <textarea name="content" id="content" placeholder="Контент" required maxlength="2000" style="height: 10rem; width:600px; "></textarea>
			<input type="submit" name="sub" value="Оставить" />
		</li>
		</ul>
		</form>
		</section>
		</section>
<?php

foreach ( $comments as $comment ) { ?>
			<li id="<?php echo $comment->id;?>">
			  
				<p><span class="pubdate"><?php echo date('j F H:i:s', $comment->createdate);?></span><?php echo htmlspecialchars( $comment->autor )?></p>
				<p><?php echo $comment->content; ?></p>
			</li>
	<?php } ?>
		
		
		
		
		
		</section>
		</article>
<?php }
function homepage() {
  $results = array();
  $data = Article::getList( HNA );
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "HomePage";
	require(PARTS.'/header.php');
 //include(PARTS.'/left_aside.php');
	?>
	<article>
		<h5>Hello my friends</h5> 
	<?php foreach ( $results['articles'] as $article ) { ?>

			<li>
			  <h2>
				<span class="pubdate"><?php echo date('j F', $article->pubdate)?></span><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><?php echo htmlspecialchars( $article->title )?></a>
			  </h2>
			  <p class="summary"><?php echo htmlspecialchars( $article->summary )?></p>
			</li>

	<p>Всего статей: <?php } echo $results['totalRows']; ?></p>
	


	</article><?php 
} 
 include(PARTS.'/footer.php');?>