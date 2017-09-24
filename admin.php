<?php 
session_start();
require( "config.php" );

$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$uname = isset( $_SESSION['uname'] ) ? $_SESSION['uname'] : "";

 if ( $action != "login" && $action != "logout" && !$uname ) {
  login();
  exit;
} 

switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'newArticle':
    newArticle();
    break;
  case 'editArticle':
    editArticle();
    break;
  case 'deleteArticle':
    deleteArticle();
    break;
  default:
    listArticles();
}

function login() {
  $results = array();
  $results['pageTitle'] = "Admin Login";
  if ( isset( $_POST['login'] ) ) {
    // Пользователь получает форму входа: попытка авторизировать пользователя
    if ( $_POST['username'] == ADNAME && md5(md5($_POST['password'])) == ADPAS ) {
      // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
      $_SESSION['uname'] = ADNAME;
      header( "Location: admin.php" );
    } else {
      // Ошибка входа: выводим сообщение об ошибке для пользователя
      $results['errorMessage'] = "Incorrect username or password. Please try again.";
      require( ADMIN . "/loginForm.php" );
    }
  } else {
    // Пользователь еще не получил форму: выводим форму
    require( ADMIN . "/loginForm.php" );
  }
}
function logout() {
  unset( $_SESSION['uname'] );
  header( "Location: admin.php" );
}
function newArticle() {
  $results = array();
  $results['pageTitle'] = "New Article";
  $results['formAction'] = "newArticle";
  if ( isset( $_POST['saveChanges'] ) ) {
    // Пользователь получает форму редактирования статьи: сохраняем новую статью
    $article = new Article;
    $article->storeFormValues( $_POST );
    $article->insert();
    header( "Location: admin.php?status=changesSaved" );
  } elseif ( isset( $_POST['cancel'] ) ) {
    // Пользователь сбросид результаты редактирования: возвращаемся к списку статей
    header( "Location: admin.php" );
  } else {
    // Пользователь еще не получил форму редактирования: выводим форму
    $results['article'] = new Article;
    require( ADMIN . "/editArticle.php" );
  }
}
function editArticle() {
  $results = array();//Пользователь получил форму редактирова
  $results['pageTitle'] = "Edit Article";
  $results['formAction'] = "editArticle";
  if ( isset( $_POST['saveChanges'] ) ) {
    // ния статьи: сохраняем изменения
    if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
      header( "Location: admin.php?error=articleNotFound" );
      return;
    }
    $article->storeFormValues( $_POST );
    $article->update();
    header( "Location: admin.php?status=changesSaved" );
  } elseif ( isset( $_POST['saveNew'] ) ) {
	$article = new Article;
    $article->storeFormValues( $_POST );
	$article->id=null;
    $article->insert();
    header( "Location: admin.php?status=changesSaved" );
  } elseif ( isset( $_POST['cancel'] ) ) {
    // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
    header( "Location: admin.php" );
  } else {
    // Пользвоатель еще не получил форму редактирования: выводим форму
    $results['article'] = Article::getById( (int)$_GET['articleId'] );
    require( ADMIN . "/editArticle.php" );
  }
}
function deleteArticle() {
  if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
    header( "Location: admin.php?error=articleNotFound" );
    return;
  }
  $article->delete();
  header( "Location: admin.php?status=articleDeleted" );
}
function listArticles() {
	
  $results = array();
  $data = Article::getList();
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Все статьи";
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "articleNotFound" ) $results['errorMessage'] = "Error: Article not found.";
  }
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "articleDeleted" ) $results['statusMessage'] = "Article deleted.";
	
  }
  require( ADMIN . "/listArticles.php" );
}

?>
