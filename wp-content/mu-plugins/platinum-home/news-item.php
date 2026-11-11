<?php
/** One news item, rendered inside the homepage query loop. */
defined( 'ABSPATH' ) || exit;
?>
<article class="platinum-story">
	<div class="platinum-date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( wp_date( 'l d/m/Y', get_post_timestamp(), null ) ); ?></time><span aria-hidden="true">·</span><time class="platinum-time" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_time( 'H:i' ) ); ?></time></div>
	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<div class="platinum-story-content"><?php the_content(); ?></div>
</article>
