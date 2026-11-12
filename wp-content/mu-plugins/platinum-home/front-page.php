<?php
/** Radio homepage using the shared site header and footer. */
defined( 'ABSPATH' ) || exit;

$news_page = max( 1, absint( get_query_var( 'noticias_pagina', 1 ) ) );
$news      = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'category_name'       => 'noticias',
	'posts_per_page'      => 4,
	'paged'               => $news_page,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'ignore_sticky_posts' => true,
) );
$stream    = get_theme_mod( 'platinum_stream', 'https://example.com/radio-platinum.mp3' );
$video_source = esc_url_raw( get_theme_mod( 'platinum_video', '' ) );
$video        = '';

if ( $video_source ) {
	$response = wp_safe_remote_get( $video_source, array(
		'timeout'     => 5,
		'redirection' => 2,
	) );

	if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
		$candidate = esc_url_raw( trim( wp_remote_retrieve_body( $response ) ) );
		$parts     = wp_parse_url( $candidate );
		$host      = strtolower( $parts['host'] ?? '' );

		if ( 'https' === ( $parts['scheme'] ?? '' ) && in_array( $host, array( 'facebook.com', 'www.facebook.com' ), true ) && '/plugins/video.php' === ( $parts['path'] ?? '' ) ) {
			$video = $candidate;
		}
	}
}
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
<main class="platinum-container platinum-home-content">
<div class="platinum-columns">
	<section class="platinum-media" id="escuchar" aria-labelledby="escuchar-heading">
		<p class="platinum-eyebrow"><?php esc_html_e( 'Radio Platinum', 'platinum-home' ); ?></p>
		<h1 id="escuchar-heading">Escuchar en vivo</h1>
		<div class="platinum-player">
			<div class="platinum-player-label"><span>RADIO PLATINUM 90.9 FM</span><span class="platinum-live"><i aria-hidden="true"></i> EN VIVO</span></div>
			<audio controls preload="none" aria-label="Escuchar Radio Platinum 90.9 FM" src="<?php echo esc_url( $stream ); ?>"></audio>
			<p class="platinum-stream-message" role="status" hidden>La señal estará disponible próximamente. El enlace de streaming es provisional.</p>
		</div>
		<div class="platinum-video-section" id="video">
			<h2 class="platinum-video-heading"><?php esc_html_e( 'Radio en video', 'platinum-home' ); ?><span>Facebook</span></h2>
			<div class="platinum-video-frame">
				<?php if ( $video ) : ?>
					<iframe src="<?php echo esc_url( $video ); ?>" title="Radio Platinum — Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
				<?php else : ?>
					<p>El video de Facebook no está disponible en este momento.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<section class="platinum-news" id="noticias" aria-labelledby="noticias-heading">
		<p class="platinum-eyebrow"><?php esc_html_e( 'Últimas noticias', 'platinum-home' ); ?></p>
		<h2 id="noticias-heading">Servicio Informativo Radio Platinum</h2>
		<?php if ( $news->have_posts() ) : ?>
			<?php for ( $news_index = 0; $news_index < min( 2, $news->post_count ); $news_index++ ) : $news->the_post(); ?>
				<?php require __DIR__ . '/news-item.php'; ?>
			<?php endfor; ?>
		<?php else : ?>
			<p class="platinum-empty">Todavía no hay noticias en esta página.</p>
			<?php if ( $news_page > 1 ) : ?><a href="<?php echo esc_url( $home . '#noticias' ); ?>">Volver a las últimas noticias</a><?php endif; ?>
		<?php endif; ?>
	</section>
</div>
<?php if ( $news->post_count > 2 ) : ?>
	<section class="platinum-more-news" aria-labelledby="mas-noticias-heading">
		<h2 id="mas-noticias-heading"><?php esc_html_e( 'Más noticias', 'platinum-home' ); ?></h2>
		<div class="platinum-more-news-grid">
			<?php for ( $news_index = 2; $news_index < $news->post_count; $news_index++ ) : $news->the_post(); ?>
				<?php require __DIR__ . '/news-item.php'; ?>
			<?php endfor; ?>
		</div>
	</section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
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
</main>
<?php require __DIR__ . '/footer.php'; ?>
<?php wp_footer(); ?>
</body>
</html>
