<?php
/**
 * Title: Blockify Blog Single Column
 * Slug: blockify/blog-single-column
 * Categories: blog
 * Block Types: 
 */
?><!-- wp:heading {"textAlign":"center","level":1,"style":{"spacing":{"margin":{"top":"2em"}}}} -->
<h1 class="has-text-align-center" style="margin-top:2em">Latest Posts</h1>
<!-- /wp:heading -->

<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"style":{"spacing":{"padding":{"bottom":"2em","top":"2em"}}},"layout":{"inherit":true}} -->
<div class="wp-block-query" style="padding-top:2em;padding-bottom:2em"><!-- wp:post-template -->
<!-- wp:post-featured-image {"height":"200px"} /-->

<!-- wp:post-date {"style":{"spacing":{"margin":{"bottom":"1em","top":"1em"}}}} /-->

<!-- wp:post-title {"level":3,"isLink":true,"style":{"spacing":{"margin":{"top":"0px","bottom":"0.5em"}}}} /-->

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
<!-- /wp:query -->