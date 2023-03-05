<?php
/**
 * Title: Template Front Page
 * Slug: template-front-page
 * Categories: template
 * Inserter: false
 */
?>
<!-- wp:group {"tagName":"main","align":"full","layout":{"inherit":true}} -->
<main class="wp-block-group alignfull">

	<?php
	require __DIR__ . '/hero-default.php';
	require __DIR__ . '/feature-icons.php';
	require __DIR__ . '/feature-images.php';
	require __DIR__ . '/pricing-three-column.php';
	require __DIR__ . '/testimonial-three-column.php';
	require __DIR__ . '/blog-three-column.php';
	require __DIR__ . '/cta-center.php';
	?>

</main>
<!-- /wp:group -->
