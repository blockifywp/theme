<?php
/**
 * Title: Three Column
 * Slug: three-column
 * Categories: blog
 * ID: 10248
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|sm","top":"var:preset|spacing|xs"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--xs);padding-bottom:var(--wp--preset--spacing--sm)"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"0","top":"0"},"padding":{"bottom":"var:preset|spacing|sm"}}},"className":"wp-block-heading"} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-top:0;margin-bottom:0;padding-bottom:var(--wp--preset--spacing--sm)">Latest posts</h2>
<!-- /wp:heading -->
<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":"","offset":"","postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide","layout":{"inherit":false},"style":{"spacing":{"blockGap":"1.5em"}}} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:post-featured-image {"isLink":true,"style":{"aspectRatio":{"all":"16/9"},"objectFit":{"all":"cover"},"objectPosition":{"all":"center"},"height":{"all":"100%"},"width":{"all":"100%"}}} /-->
<!-- wp:group {"style":{"typography":{"textDecoration":"none"},"spacing":{"blockGap":"var:preset|spacing|xxs","margin":{"top":"var:preset|spacing|xs"}}},"layout":{"type":"flex","flexWrap":"nowrap"},"fontSize":"14"} -->
<div class="wp-block-group has-14-font-size" style="margin-top:var(--wp--preset--spacing--xs);text-decoration:none"><!-- wp:post-date {"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"fontSize":"inherit"} /-->
<!-- wp:paragraph -->
<p>·</p>
<!-- /wp:paragraph -->
<!-- wp:post-terms {"term":"category","className":"is-style-default","fontSize":"inherit"} /--></div>
<!-- /wp:group -->
<!-- wp:post-title {"level":3,"isLink":true,"style":{"spacing":{"margin":{"top":"0.5em","right":"0em","bottom":"0.5em","left":"0em"}},"typography":{"lineHeight":"1.4"}},"fontSize":"24"} /-->
<!-- wp:post-excerpt {"moreText":"Read more","excerptLength":20,"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"hideReadMore":true} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->