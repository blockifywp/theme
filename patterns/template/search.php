<?php
/**
 * Title: Search
 * Slug: search
 * Categories: template
 * Template Types: search
 * Inserter: false
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->
<!-- wp:group {"tagName":"main","className":"site-main","metadata":{"name":"Main"},"layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
	<!-- wp:paragraph {"align":"center","style":{"spacing":{"padding":{"top":"var:preset|spacing|xs"}}},"className":"is-style-sub-heading"} -->
	<p class="aligncenter has-text-align-center is-style-sub-heading aligncenter"
	   style="padding-top:var(--wp--preset--spacing--xs)">Search Results</p>
	<!-- /wp:paragraph -->
	<!-- wp:query-title {"type":"search","textAlign":"center","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|xs"}}}} /-->
	<!-- wp:pattern {"slug":"blog-grid-boxed"} /-->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->
