<?php
/**
 * Title: Archive
 * Slug: archive
 * Categories: template
 * Template Types: archive
 * Inserter: false
 */
?>

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|xs"}}},"className":"is-style-sub-heading"} -->
<p class="aligncenter has-text-align-center is-style-sub-heading aligncenter"
   style="margin-top:var(--wp--preset--spacing--xs)">{archive_title}</p>
<!-- /wp:paragraph -->
<!-- wp:query-title {"type":"archive","textAlign":"center"} /-->
<!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"align":"wide","layout":{"inherit":false},"style":{"spacing":{"blockGap":"1.5em","padding":{"top":"var:preset|spacing|sm","bottom":"0"}}}} -->
<div class="wp-block-query alignwide"
	 style="padding-top:var(--wp--preset--spacing--sm);padding-bottom:0">
	<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
	<!-- wp:post-featured-image {"isLink":true,"style":{"aspectRatio":{"all":"16/9"},"objectFit":{"all":"cover"},"objectPosition":{"all":"center"},"height":{"all":"100%"},"width":{"all":"100%"}}} /-->
	<!-- wp:group {"style":{"typography":{"textDecoration":"none"},"spacing":{"blockGap":"var:preset|spacing|xxs","margin":{"top":"var:preset|spacing|xs"}}},"layout":{"type":"flex","flexWrap":"nowrap"},"fontSize":"14"} -->
	<div class="wp-block-group has-14-font-size"
		 style="margin-top:var(--wp--preset--spacing--xs);text-decoration:none">
		<!-- wp:post-date {"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"fontSize":"inherit"} /-->
		<!-- wp:paragraph -->
		<p>·</p>
		<!-- /wp:paragraph -->
		<!-- wp:post-terms {"term":"category","className":"is-style-default","fontSize":"inherit"} /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:post-title {"level":3,"isLink":true,"style":{"spacing":{"margin":{"top":"0.5em","right":"0em","bottom":"0.5em","left":"0em"}},"typography":{"lineHeight":"1.4"}},"fontSize":"24"} /-->
	<!-- wp:post-excerpt {"moreText":"Read more","style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"hideReadMore":true} /-->
	<!-- /wp:post-template -->
	<!-- wp:group {"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:query-pagination {"paginationArrow":"arrow","align":"full","style":{"typography":{"textDecoration":"none"},"spacing":{"padding":{"top":"var:preset|spacing|lg"}}},"className":"is-style-default","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination --></div>
	<!-- /wp:group --></div>
<!-- /wp:query -->
