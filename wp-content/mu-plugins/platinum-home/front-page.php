<?php
/** Radio homepage using the shared site header and footer. */
defined( 'ABSPATH' ) || exit;

$news_page = max( 1, absint( get_query_var( 'noticias_pagina', 1 ) ) );
$news      = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 6,
	'paged'               => $news_page,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'ignore_sticky_posts' => true,
) );
$stream    = get_theme_mod( 'platinum_stream', 'https://example.com/radio-platinum.mp3' );
$video     = platinum_home_video_url( get_theme_mod( 'platinum_video', 'https://www.youtube.com/watch?v=jNQXAC9IVRw' ) );
$home      = home_url( '/' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'platinum-home' ); ?>>
<?php wp_body_open(); ?>
<a class="platinum-skip" href="#noticias">Saltar a las noticias</a>
<?php require __DIR__ . '/header.php'; ?>
<main class="platinum-container platinum-columns">
	<section class="platinum-media" id="escuchar" aria-labelledby="escuchar-heading">
		<p class="platinum-eyebrow"><?php esc_html_e( 'Radio Platinum', 'platinum-home' ); ?></p>
		<h1 id="escuchar-heading">Escuchar en vivo</h1>
		<div class="platinum-player">
			<div class="platinum-player-label"><span>RADIO PLATINUM 90.9 FM</span><span class="platinum-live"><i aria-hidden="true"></i> EN VIVO</span></div>
			<audio controls preload="none" aria-label="Escuchar Radio Platinum 90.9 FM" src="<?php echo esc_url( $stream ); ?>"></audio>
			<p class="platinum-stream-message" role="status" hidden>La señal estará disponible próximamente. El enlace de streaming es provisional.</p>
		</div>
		<div class="platinum-video-section" id="video">
			<h2 class="platinum-video-heading"><?php esc_html_e( 'Radio en video', 'platinum-home' ); ?><span>YouTube</span></h2>
			<div class="platinum-video-frame">
				<?php if ( $video ) : ?>
					<iframe src="<?php echo esc_url( $video ); ?>" title="Radio Platinum — Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
				<?php else : ?>
					<p>Configura un enlace de YouTube en Personalizar → Portada Radio Platinum.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<section class="platinum-news" id="noticias" aria-labelledby="noticias-heading">
		<p class="platinum-eyebrow"><?php esc_html_e( 'Últimas noticias', 'platinum-home' ); ?></p>
		<h2 id="noticias-heading">Servicio Informativo Radio Platinum</h2>
		<?php if ( $news->have_posts() ) : ?>
			<?php while ( $news->have_posts() ) : $news->the_post(); ?>
				<article class="platinum-story">
					<div class="platinum-date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( wp_date( 'l d/m/Y', get_post_timestamp(), null ) ); ?></time><span aria-hidden="true">·</span><time class="platinum-time" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_time( 'H:i' ) ); ?></time></div>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="platinum-story-content"><?php the_content(); ?></div>
				</article>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p class="platinum-empty">Todavía no hay noticias en esta página.</p>
			<?php if ( $news_page > 1 ) : ?><a href="<?php echo esc_url( $home . '#noticias' ); ?>">Volver a las últimas noticias</a><?php endif; ?>
		<?php endif; ?>
		<?php
		$pagination = paginate_links( array(
			'base'      => add_query_arg( 'noticias_pagina', '%#%', $home ) . '#noticias',
			'format'    => '',
			'current'   => $news_page,
			'total'     => $news->max_num_pages,
			'mid_size'  => 1,
			'prev_text' => '‹ Anterior',
			'next_text' => 'Siguiente ›',
			'type'      => 'list',
		) );
		if ( $pagination ) :
		?>
			<nav class="platinum-pagination" aria-label="Paginación de noticias"><?php echo wp_kses_post( $pagination ); ?></nav>
		<?php endif; ?>
	</section>
</main>
<aside class="platinum-social" aria-label="Redes sociales">
	<a href="#" data-placeholder aria-label="Facebook"><svg viewBox="0 0 40 40" aria-hidden="true"><rect x="3" y="3" width="34" height="34" rx="6"/><path fill="#fff" d="M25 9h-4c-4 0-6 3-6 6v4h-3v5h3v13h6V24h4l1-5h-5v-3c0-2 1-2 2-2h3z"/></svg></a>
	<a href="#" data-placeholder aria-label="Twitter"><svg viewBox="0 0 40 40" aria-hidden="true"><rect x="3" y="3" width="34" height="34" rx="6"/><path fill="#fff" d="M31 12c-1 1-2 1-3 1-4-4-9-1-8 3-5 0-9-2-12-5-2 3 0 6 2 7l-2-1c0 3 2 5 4 5l-2 1c1 2 3 3 5 3-3 2-5 3-8 2 9 6 22 0 22-12l2-4z"/></svg></a>
	<a href="#" data-placeholder aria-label="Instagram"><svg viewBox="0 0 40 40" aria-hidden="true"><rect x="5" y="5" width="30" height="30" rx="8" fill="none" stroke="currentColor" stroke-width="3"/><circle cx="20" cy="20" r="7" fill="none" stroke="currentColor" stroke-width="3"/><circle cx="29" cy="11" r="2"/></svg></a>
	<a href="#" data-placeholder aria-label="YouTube"><svg viewBox="0 0 40 40" aria-hidden="true"><rect x="1" y="7" width="38" height="27" rx="7"/><path d="m16 14 12 6-12 6z" fill="#fff"/></svg></a>
</aside>
<?php require __DIR__ . '/footer.php'; ?>
<?php wp_footer(); ?>
</body>
</html>
