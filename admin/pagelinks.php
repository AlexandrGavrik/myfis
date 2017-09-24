<?php	if($page>1){?>
	<a href="admin.php?page=<?php echo $page-1; ?>">Назад</a>
<?php }else{?>
	<span style="color:grey">Назад</span>
<?php }
		if($page==1){$l=0;$r=2;}elseif($page==$pages){$l=2;$r=0;}else{$l=1;$r=1;}
		for($i=0;$i<=$pages;$i++){
			if($i==0)continue;
			if($i<($page-$l)||$i>($page+$r))continue;
			if($i==$page){
?>
		<span style="color:grey"><?php echo $i;?></span>
<?php }else{ ?>
		<a href="admin.php?page=<?php echo $i;?>"><?php echo $i;?></a>
<?php } } 
		if($page<$pages){?>
		<a href="admin.php?page=<?php echo $page+1; ?>">Вперёд</a>
<?php }else{?>
	<span style="color:grey">Вперёд</span>
<?php }?>
	
		
      <p>Показано статей: <?php echo $bk."-".$tk." из ".$results['totalRows']?></p>