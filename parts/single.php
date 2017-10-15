<article>
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