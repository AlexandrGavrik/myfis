<article>
	<h2><?php echo $admin;?>Александр Гаврик</h2>
<a id="logLink" data-js href="../admin/index.php?act=login">Кабинет Администратора</a>
<?php 
$pa="63bt098331";
$p = md5(md5(trim($pa)));
echo $p;
?>
<div id="cover"></div>
<form id="form" action="../admin/index.php?act=admin" method="POST">
	<label>Логин<input name="log" type="text"></label>
	<label>Пароль<input name="pas" type="text"></label>
	<input id="logOk" type="submit" value="Войти">
</form>

</article>
<script>
	function a$(eid){return document.getElementById(eid);}
	var links=document.getElementsByTagName('a'),
	a=document.getElementById('logLink'),
	c=document.getElementById('cover'),
	f=document.getElementById('form'),
	logOk=document.getElementById('logOk');
	
	window.onload = function() {
		for (var i = 0; i < links.length; i++) {
			if(links[i].hasAttribute('data-js')){
				links[i].removeAttribute('href');
				links[i].setAttribute('href', 'javascript:void(0);');
			}
		}	
	}
	
	a.addEventListener("click", showForm);
	c.addEventListener("click",closeForm);
	
	function closeForm(){
		c.style.display='none';
		f.style.display='none';
		i.style.display='none';
	}
	function showForm(){
		c.style.display='block';
		f.style.display='block';
		//logOk.addEventListener("click",sendForm);
	}
	function sendForm(){
		//logOk.removeEventListener("click",sendForm);
		closeForm();
		ajax();
	}
	function ajax(){
		var log=document.getElementsByName('log')[0].value;
		var pas=document.getElementsByName('pas')[0].value;
		var body = 'log=' + encodeURIComponent(name) + '&pas=' + encodeURIComponent(tel);
		
		var request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if(request.readyState === 4) {
				c.style.display='block';
				i.style.display='block';
				if(request.status === 200){//----------------------------------------------------------------
					var rrt = request.responseText;
					
				}//------------------------------------------------------------------------------------------
				else {i.innerHTML = 'Произошла ошибка при запросе: ' +  request.status + ' ' + request.statusText;}
			}
		}
		
		request.open('POST', 'ajax_log.php', true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.send(body);
	}
</script>