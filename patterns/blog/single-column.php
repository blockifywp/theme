<?php
/**
 * Title: Single Column
 * Slug: single-column
 * Categories: blog
 * ID: 11923
 */
?>
<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center","level":1,"style":{"spacing":{"margin":{"top":"var:preset|spacing|lg"}}}} -->
<h1 class="has-text-align-center" style="margin-top:var(--wp--preset--spacing--lg)">Latest Posts</h1>
<!-- /wp:heading -->
<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"style":{"spacing":{"padding":{"bottom":"var:preset|spacing|lg","top":"var:preset|spacing|sm"}}},"layout":{"inherit":true}} -->
<div class="wp-block-query" style="padding-top:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--lg)"><!-- wp:post-template -->
<!-- wp:post-featured-image {"height":"200px"} /-->
<!-- wp:post-date {"style":{"spacing":{"margin":{"bottom":"1em","top":"1em"}}}} /-->
<!-- wp:post-title {"level":3,"isLink":true,"style":{"spacing":{"margin":{"top":"0px","bottom":"var:preset|spacing|xs"}}}} /-->
<!-- wp:post-excerpt {"moreText":"Read more","style":{"spacing":{"margin":{"bottom":"2em"}}}} /-->
<!-- /wp:post-template -->
<!-- wp:query-pagination -->
<!-- wp:query-pagination-previous /-->
<!-- wp:query-pagination-numbers /-->
<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->
<!-- wp:query-no-results -->
<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when the query returns no results."} -->
<p></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
