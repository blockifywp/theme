<?php
/**
 * Title: Blog Three Column
 * Slug: blog-three-column
 * Categories: blog
 * 
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|sm"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-bottom:var(--wp--preset--spacing--sm)"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md","top":"var:preset|spacing|lg"}}},"className":"wp-block-heading"} -->
<h2 class="has-text-align-center wp-block-heading" style="margin-top:var(--wp--preset--spacing--lg);margin-bottom:var(--wp--preset--spacing--md)">Latest posts</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"flex","columns":3},"align":"wide","layout":{"inherit":false}} -->
<div class="wp-block-query alignwide"><!-- wp:post-template -->
<!-- wp:post-featured-image {"isLink":true,"style":{"spacing":{"margin":{"bottom":"1em"}}}} /-->

<!-- wp:post-date {"style":{"spacing":{"margin":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}}}} /-->

<!-- wp:post-title {"level":3,"isLink":true,"style":{"spacing":{"margin":{"top":"0.5em","right":"0em","bottom":"0.5em","left":"0em"}}},"fontSize":"24"} /-->

<!-- wp:post-excerpt {"moreText":"Read more","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} /-->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"align":"center","placeholder":"Add text or blocks that will display when the query returns no results."} -->
<p class="aligncenter has-text-align-center aligncenter"></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->