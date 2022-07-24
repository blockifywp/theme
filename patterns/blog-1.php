<?php
/**
 * Title: Blockify Blog 1
 * Slug: blockify/blog-1
 * Categories: blog
 * 
 */
?><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"top":"1em","bottom":"1em"}}}} -->
<h2 class="has-text-align-center" style="margin-top:1em;margin-bottom:1em">Latest Posts</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"flex","columns":3},"style":{"spacing":{"padding":{"bottom":"5em"}}},"layout":{"inherit":false}} -->
<div class="wp-block-query" style="padding-bottom:5em"><!-- wp:post-template -->
<!-- wp:post-featured-image /-->

<!-- wp:post-date /-->

<!-- wp:post-title {"isLink":true} /-->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"align":"center","placeholder":"Add text or blocks that will display when the query returns no results."} -->
<p class="aligncenter has-text-align-center aligncenter"></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->