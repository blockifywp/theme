<?php
/**
 * Title: Form
 * Slug: form
 * Categories: contact
 * ID: 12330
 */
?>
<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:heading -->
<h2 class="wp-block-heading">Contact Us</h2>
<!-- /wp:heading -->
<!-- wp:html -->
<form action="#" method="POST">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" required>
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
    <label for="message">Message</label>
    <textarea id="message" name="message" rows="4" required></textarea>
<div class="wp-block-buttons is-layout-flex">
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="#">Send</a></div>
</div>
</form>
<!-- /wp:html --></div>
<!-- /wp:group -->