<?php
/**
 * Title: Form Boxed
 * Slug: form-boxed
 * Categories: contact
 * ID: 12342
 */
?>
<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|lg","right":"var:preset|spacing|lg","bottom":"var:preset|spacing|lg","left":"var:preset|spacing|lg"}},"width":{"all":"100%"}},"backgroundColor":"neutral-50","className":"is-style-surface","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-surface has-neutral-50-background-color has-background" style="padding-top:var(--wp--preset--spacing--lg);padding-right:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--lg);padding-left:var(--wp--preset--spacing--lg)"><!-- wp:paragraph {"align":"center","className":"is-style-sub-heading"} -->
<p class="aligncenter has-text-align-center is-style-sub-heading aligncenter">Get in touch</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|xxs","bottom":"var:preset|spacing|sm"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-top:var(--wp--preset--spacing--xxs);margin-bottom:var(--wp--preset--spacing--sm)">Contact Us</h2>
<!-- /wp:heading -->
<!-- wp:html -->
<form action="#" method="POST">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" required>
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
    <label for="message">Message</label>
    <textarea id="message" name="message" rows="6" required></textarea>
<div class="wp-block-buttons is-layout-flex">
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="#">Send</a></div>
</div>
</form>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->