<?php
/** Radio Quito landing page with its own stream and weekly schedule. */
defined( 'ABSPATH' ) || exit;

$stream = get_theme_mod( 'platinum_quito_stream', 'https://example.com/radio-quito.mp3' );
$video  = platinum_home_video_url( get_theme_mod( 'platinum_quito_video', 'https://www.youtube.com/watch?v=jNQXAC9IVRw' ) );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'platinum-home platinum-station-page' ); ?>>
<?php wp_body_open(); ?>
<a class="platinum-skip" href="#radio-quito-content"><?php esc_html_e( 'Saltar al contenido de Radio Quito', 'platinum-home' ); ?></a>
<?php require __DIR__ . '/header.php'; ?>
<main class="platinum-container platinum-station-content" id="radio-quito-content">
	<div class="platinum-station-grid">
		<section class="platinum-station-media" aria-labelledby="quito-escuchar-heading">
			<p class="platinum-eyebrow"><?php esc_html_e( 'Radio Quito', 'platinum-home' ); ?></p>
			<h1 id="quito-escuchar-heading"><?php esc_html_e( 'Escuchar en vivo', 'platinum-home' ); ?></h1>
			<div class="platinum-player">
				<div class="platinum-player-label"><span>RADIO QUITO 760 AM</span><span class="platinum-live"><i aria-hidden="true"></i> EN VIVO</span></div>
				<audio controls preload="none" aria-label="<?php esc_attr_e( 'Escuchar Radio Quito', 'platinum-home' ); ?>" src="<?php echo esc_url( $stream ); ?>"></audio>
				<p class="platinum-stream-message" role="status" hidden><?php esc_html_e( 'La señal estará disponible próximamente. El enlace de streaming es provisional.', 'platinum-home' ); ?></p>
			</div>
			<div class="platinum-video-section">
				<h2 class="platinum-video-heading"><?php esc_html_e( 'Radio en video', 'platinum-home' ); ?><span>YouTube</span></h2>
				<div class="platinum-video-frame">
					<?php if ( $video ) : ?>
						<iframe src="<?php echo esc_url( $video ); ?>" title="<?php esc_attr_e( 'Radio Quito en vivo', 'platinum-home' ); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
					<?php else : ?>
						<p><?php esc_html_e( 'Configura el video de Radio Quito próximamente.', 'platinum-home' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php $platinum_schedule_station = 'quito'; require __DIR__ . '/schedule.php'; ?>
	</div>
</main>
<?php require __DIR__ . '/footer.php'; ?>
<?php wp_footer(); ?>
</body>
</html>
