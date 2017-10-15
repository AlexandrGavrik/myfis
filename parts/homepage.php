 
		<hr>
<?php foreach ( $results['articles'] as $article ) { ?>
	<article>
		<a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>">
			<span class="pubdate"><time datetime=<?php echo date('Y-m-j', $article->pubdate)?> pubdate><?php echo date('j F Y', $article->pubdate)?></time></span>
			<h1><?php echo htmlspecialchars( $article->title )?></h1>
			<p class="summary"><?php echo htmlspecialchars( $article->summary )?></p>
		</a>
	</article>
	<hr>
<?php } ?>