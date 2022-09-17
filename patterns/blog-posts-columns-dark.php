<?php
/**
 * Title: Blockify Blog Posts Columns Dark
 * Slug: blockify/blog-posts-columns-dark
 * Categories: blog
 * Block Types:
 */
?><!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"3em","right":"1em","bottom":"2em","left":"1em"},"margin":{"top":"3em","bottom":"3em"}},"border":{"radius":"5px"}},"backgroundColor":"neutral-900","textColor":"neutral-100","layout":{"contentSize":"1100px"}} -->
<div class="wp-block-group alignwide has-neutral-100-color has-neutral-900-background-color has-text-color has-background alignwide" style="border-radius:5px;margin-top:3em;margin-bottom:3em;padding-top:3em;padding-right:1em;padding-bottom:2em;padding-left:1em"><!-- wp:heading {"textAlign":"center","textColor":"white"} -->
<h2 class="has-text-align-center has-white-color has-text-color">Latest Posts</h2>
<!-- /wp:heading -->

<!-- wp:columns {"style":{"spacing":{"blockGap":"2em","padding":{"top":"3em","bottom":"3em"}}}} -->
<div class="wp-block-columns" style="padding-top:3em;padding-bottom:3em"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:query {"queryId":0,"query":{"perPage":"1","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:post-featured-image {"height":"200px","style":{"spacing":{"margin":{"bottom":"1em"}}},"backgroundColor":"neutral-800"} /-->

<!-- wp:post-date /-->

<!-- wp:post-title {"level":3,"isLink":true,"style":{"spacing":{"margin":{"top":"0em","right":"0em","bottom":"0.7em","left":"0em"}}},"textColor":"white"} /-->

<!-- wp:post-author {"showBio":false,"byline":"By","style":{"border":{"radius":"99px"}},"fontSize":"16"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:query {"queryId":2,"query":{"perPage":"4","pages":0,"offset":"1","postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"list","columns":3},"style":{"spacing":{"blockGap":"1em"}}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"80px"} -->
<div class="wp-block-column" style="flex-basis:80px"><!-- wp:post-featured-image {"width":"80px","height":"80px","scale":"fill","style":{"spacing":{"margin":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}}},"backgroundColor":"neutral-800"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"75%"} -->
<div class="wp-block-column" style="flex-basis:75%"><!-- wp:post-date /-->

<!-- wp:post-title {"level":5,"isLink":true,"textColor":"white"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when the query returns no results."} -->
<p></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
