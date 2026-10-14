<?php
/** Radio homepage. The ordinary theme still handles articles and other pages. */
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
$asset_url = plugin_dir_url( __FILE__ ) . 'assets/';
$logo      = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' );
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
<header class="platinum-header">
	<div class="platinum-container platinum-header-inner">
		<a class="platinum-brand" href="<?php echo esc_url( $home ); ?>" aria-label="Radio Platinum — Inicio">
			<img src="<?php echo esc_url( $logo ?: $asset_url . 'radio-platinum.svg' ); ?>" alt="Radio Platinum" width="108" height="30">
		</a>
		<nav class="platinum-menu" aria-label="Navegación principal">
			<a href="<?php echo esc_url( $home ); ?>" aria-current="page">Inicio</a>
			<details class="platinum-dropdown">
				<summary>Radio Platinum <span aria-hidden="true"></span></summary>
				<div class="platinum-dropdown-links">
					<a href="#escuchar">Escuchar en vivo</a>
					<a href="#" data-placeholder>Programación</a>
					<a href="#" data-placeholder>Contacto</a>
				</div>
			</details>
			<details class="platinum-dropdown">
				<summary>Radio Quito <span aria-hidden="true"></span></summary>
				<div class="platinum-dropdown-links">
					<a href="#video">Ver transmisión</a>
					<a href="#" data-placeholder>Programación</a>
					<a href="#" data-placeholder>Contacto</a>
				</div>
			</details>
		</nav>
	</div>
</header>
<main class="platinum-container platinum-columns">
	<section class="platinum-media" id="escuchar" aria-labelledby="escuchar-heading">
		<h1 id="escuchar-heading">Escuchar en vivo</h1>
		<div class="platinum-player">
			<div class="platinum-player-label"><span>RADIO PLATINUM 90.9 FM</span><span class="platinum-live"><i aria-hidden="true"></i> EN VIVO</span></div>
			<audio controls preload="none" aria-label="Escuchar Radio Platinum 90.9 FM" src="<?php echo esc_url( $stream ); ?>"></audio>
			<p class="platinum-stream-message" role="status" hidden>La señal estará disponible próximamente. El enlace de streaming es provisional.</p>
		</div>
		<div class="platinum-video-frame" id="video">
			<div class="platinum-video-inner">
				<?php if ( $video ) : ?>
					<iframe src="<?php echo esc_url( $video ); ?>" title="Video de ejemplo — Radio Platinum" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
				<?php else : ?>
					<p>Configura un enlace de YouTube en Personalizar → Portada Radio Platinum.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<section class="platinum-news" id="noticias" aria-labelledby="noticias-heading">
		<h2 id="noticias-heading">Servicio Informativo Radio Platinum</h2>
		<?php if ( $news->have_posts() ) : ?>
			<?php while ( $news->have_posts() ) : $news->the_post(); ?>
				<article class="platinum-story">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="platinum-date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( wp_date( 'l d/m/Y', get_post_timestamp(), null ) ); ?></time></div>
					<div class="platinum-story-content"><strong class="platinum-time"><?php echo esc_html( get_the_time( 'H:i' ) ); ?></strong> <?php the_content(); ?></div>
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
<div class="platinum-notice" role="status" hidden>Enlace provisional. Próximamente disponible.</div>
<?php wp_footer(); ?>
</body>
</html>
