<?php
/**
 * Title: Template Single
 * Slug: single
 * Categories: template
 * Template Types: template-single
 * Inserter: false
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->
<!-- wp:group {"tagName":"main","className":"site-main","metadata":{"name":"Main"},"layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
	<!-- wp:post-terms {"term":"category","style":{"spacing":{"padding":{"top":"var:preset|spacing|xs"}},"typography":{"textDecoration":"none"}},"className":"is-style-sub-heading","align":"center"} /-->
	<!-- wp:post-title {"textAlign":"center","level":1} /-->
	<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"var:preset|spacing|xxs","top":"var:preset|spacing|xxs"},"blockGap":"var:preset|spacing|xxs"},"typography":{"textDecoration":"none"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
	<div class="wp-block-group"
		 style="padding-top:var(--wp--preset--spacing--xxs);padding-bottom:var(--wp--preset--spacing--xxs);text-decoration:none">
		<!-- wp:avatar {"size":32,"isLink":true,"style":{"border":{"radius":"99px"}}} /-->
		<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"","isLink":true,"linkTarget":"_blank"} /-->
		<!-- wp:paragraph -->
		<p>·</p>
		<!-- /wp:paragraph -->
		<!-- wp:post-date /--></div>
	<!-- /wp:group -->
	<!-- wp:post-featured-image {"style":{"aspectRatio":{"all":"16/9"},"objectFit":{"all":"cover"}},"usePlaceholder":"none"} /-->
	<!-- wp:post-content {"style":{"spacing":{"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md","left":"0","right":"0"}}},"layout":{"type":"constrained"}} /-->
	<!-- wp:group {"layout":{"type":"default"}} -->
	<div class="wp-block-group"><!-- wp:post-comments-form /-->
		<!-- wp:comments {"style":{"typography":{"textDecoration":"none"}},"className":"wp-block-comments-query-loop"} -->
		<div class="wp-block-comments wp-block-comments-query-loop"
			 style="text-decoration:none">
			<!-- wp:comments-title {"level":4,"style":{"spacing":{"padding":{"top":"var:preset|spacing|xxs","bottom":"var:preset|spacing|xxs"}}}} /-->
			<!-- wp:comment-template -->
			<!-- wp:columns {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
			<div class="wp-block-columns"
				 style="margin-bottom:var(--wp--preset--spacing--md)">
				<!-- wp:column {"width":"40px"} -->
				<div class="wp-block-column" style="flex-basis:40px">
					<!-- wp:avatar {"size":40,"style":{"border":{"radius":"40px"}}} /--></div>
				<!-- /wp:column -->
				<!-- wp:column -->
				<div class="wp-block-column">
					<!-- wp:comment-author-name {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"className":"is-style-heading"} /-->
					<!-- wp:comment-date {"style":{"spacing":{"margin":{"top":"var:preset|spacing|xxs","bottom":"0"}}},"fontSize":"14"} /-->
					<!-- wp:comment-content /-->
					<!-- wp:comment-reply-link {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"14"} /-->
					<!-- wp:comment-edit-link {"fontSize":"14"} /--></div>
				<!-- /wp:column --></div>
			<!-- /wp:columns -->
			<!-- /wp:comment-template -->
			<!-- wp:spacer {"height":"var:preset|spacing|md"} -->
			<div style="height:var(--wp--preset--spacing--md)"
				 aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->
			<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"space-between"}} -->
			<!-- wp:comments-pagination-previous /-->
			<!-- wp:comments-pagination-numbers /-->
			<!-- wp:comments-pagination-next /-->
			<!-- /wp:comments-pagination --></div>
		<!-- /wp:comments --></div>
	<!-- /wp:group -->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->