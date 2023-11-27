<?php
/**
 * Title: Page
 * Slug: page
 * Categories: template
 * Template Types: page
 * Inserter: false
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->
<!-- wp:group {"tagName":"main","className":"site-main","metadata":{"name":"Main"},"layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
	<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|lg","bottom":"var:preset|spacing|lg"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull"
		 style="padding-top:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--lg)">
		<!-- wp:post-title {"textAlign":"center","level":1} /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|xl"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull"
		 style="padding-bottom:var(--wp--preset--spacing--xl)">
		<!-- wp:post-content {"layout":{"type":"constrained"}} /-->
	</div>
	<!-- /wp:group -->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->