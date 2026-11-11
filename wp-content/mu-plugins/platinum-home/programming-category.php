<?php
/** Programming category archive for Radio Platinum and Radio Quito. */
defined( 'ABSPATH' ) || exit;

$category      = get_queried_object();
$category_name = $category instanceof WP_Term ? $category->name : __( 'Programación', 'platinum-home' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'platinum-home platinum-programming-page' ); ?>>
<?php wp_body_open(); ?>
<a class="platinum-skip" href="#programas"><?php esc_html_e( 'Saltar a programas', 'platinum-home' ); ?></a>
<?php require __DIR__ . '/header.php'; ?>
<main class="platinum-container platinum-programming-content" id="programas">
	<p class="platinum-eyebrow"><?php echo esc_html( $category_name ); ?></p>
	<h1><?php esc_html_e( 'Programación', 'platinum-home' ); ?></h1>
	<?php if ( have_posts() ) : ?>
		<div class="platinum-program-grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'platinum-program-card' ); ?>>
					<a class="platinum-program-image" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<span><?php esc_html_e( 'Programa', 'platinum-home' ); ?></span>
						<?php endif; ?>
					</a>
					<div class="platinum-program-copy">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="platinum-program-excerpt"><?php the_excerpt(); ?></div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '‹ ' . __( 'Anterior', 'platinum-home' ), 'next_text' => __( 'Siguiente', 'platinum-home' ) . ' ›' ) ); ?>
	<?php else : ?>
		<p class="platinum-empty"><?php esc_html_e( 'Todavía no hay programas publicados en esta categoría.', 'platinum-home' ); ?></p>
	<?php endif; ?>
</main>
<?php require __DIR__ . '/footer.php'; ?>
<?php wp_footer(); ?>
</body>
</html>
