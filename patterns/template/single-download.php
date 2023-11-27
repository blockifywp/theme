<?php
/**
 * Title: Single Download
 * Slug: single-download
 * Categories: template
 * Template Types: single-download
 * Inserter: false
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->
<!-- wp:group {"tagName":"main","className":"site-main","metadata":{"name":"Main"},"layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
	<!-- wp:post-title {"textAlign":"center","level":1} /-->

	<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"1em","top":"1em"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
	<div class="wp-block-group" style="padding-top:1em;padding-bottom:1em">
		<!-- wp:post-date /-->

		<!-- wp:post-terms {"term":"category"} /--></div>
	<!-- /wp:group -->

	<!-- wp:post-content {"layout":{"inherit":true}} /-->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->