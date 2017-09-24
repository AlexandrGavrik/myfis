<?php session_start();?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
</head>
<body>
<?php
if($_POST['log']!=null&&$_POST['pas']!=null){
	$l=$_POST['log'];$pa=md5(md5($_POST['pas']));
	require('../config.php');
	if($pa=="a6684ffbe228e8b647ebca219c95080a"){
		$_SESSION['ul'] = $l;$_SESSION['un'] = "Александр"; echo "ok";
	}else{
		echo "Неверная пара: логин-пароль";
	}
?>
<p><?php echo $n;?>, Ваша заявка принята! Мы ответим Вам в ближайшее время.</p>
<?php }else{ ?>
	<p>Данные отсутствуют.</p>
<?php } ?>


</body>
</html>