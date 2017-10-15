<article>
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