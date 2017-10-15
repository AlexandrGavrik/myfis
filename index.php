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
 include(PARTS.'/archive.php');
}
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
 include(PARTS.'/single.php');
}
function homepage() {
  $results = array();
  $data = Article::getList( HNA );
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "HomePage";
	require(PARTS.'/header.php');
 //include(PARTS.'/left_aside.php');
 include(PARTS.'/homepage.php');
} 
 include(PARTS.'/footer.php');?>