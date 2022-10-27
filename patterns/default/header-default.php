<?php
/**
 * Title: Header Default
 * Slug: header-default
 * Categories: header
 * Block Types: core/template-part/header
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"inherit":true,"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group alignfull" style="margin-top:0px;margin-bottom:0px"><!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm)"><!-- wp:image {"id":624,"width":120,"sizeSlug":"large","linkDestination":"custom"} -->
<figure class="wp-block-image size-large is-resized"><a href="/"><img src="<?php echo content_url( "/themes/blockify/assets/img/" ) ?>blockify.svg" alt="" class="wp-image-624" width="120"/></a></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"className":"is-reverse-on-mobile","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"},"reverseMobile":true} -->
<div class="wp-block-group is-reverse-on-mobile is-reverse-mobile"><!-- wp:navigation {"icon":"menu","layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"},"style":{"spacing":{"margin":{"left":"var:preset|spacing|md"}},"typography":{"fontSize":"16px"}}} /-->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"},"style":{"spacing":{"margin":{"right":"var:preset|spacing|sm","left":"var:preset|spacing|sm"}}},"fontSize":"small","hideMobile":false} -->
<div class="wp-block-buttons has-custom-font-size has-small-font-size" style="margin-right:var(--wp--preset--spacing--sm);margin-left:var(--wp--preset--spacing--sm)"><!-- wp:button {"backgroundColor":"transparent","textColor":"heading","style":{"spacing":{"padding":{"top":"0","right":"var:preset|spacing|xs","bottom":"0","left":"var:preset|spacing|xs"}},"typography":{"lineHeight":"2"},"border":{"width":"0px","style":"none"}},"boxShadow":{"useDefault":false,"blur":0,"color":"transparent","inset":false},"onclick":"document.body.classList.toggle('dark-mode')"} -->
<div class="wp-block-button has-box-shadow" style="border-style:none;border-width:0px;line-height:2;--wp--custom--box-shadow--blur:0px;--wp--custom--box-shadow--color:transparent"><a class="wp-block-button__link has-heading-color has-transparent-background-color has-text-color has-background wp-element-button" style="border-style:none;border-width:0px;padding-top:0;padding-right:var(--wp--preset--spacing--xs);padding-bottom:0;padding-left:var(--wp--preset--spacing--xs)"><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-heading-color">🔆</mark></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:buttons {"layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"margin":{"left":"var:preset|spacing|xs"}}},"hideMobile":true} -->
<div class="wp-block-buttons is-hide-mobile" style="margin-left:var(--wp--preset--spacing--xs)"><!-- wp:button {"className":"is-style-outline","fontSize":"small"} -->
<div class="wp-block-button has-custom-font-size is-style-outline has-small-font-size"><a class="wp-block-button__link wp-element-button">Sign Up</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
