<?php
/** Station-specific social links shared by all page headers. */
defined( 'ABSPATH' ) || exit;

$platinum_social_station = $platinum_is_quito ? 'quito' : 'platinum';
$platinum_social_icons = array(
	'facebook' => array( 'Facebook', '<path d="M14 21v-8h3l.5-4H14V7c0-1.1.3-2 2-2h2V1.4C17.3 1.2 16.3 1 15 1c-3 0-5 1.8-5 5v3H7v4h3v8z" fill="currentColor" stroke="none"/>' ),
	'instagram' => array( 'Instagram', '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>' ),
	'x' => array( 'X', '<path d="m4 3 12.5 18H20L7.5 3zM20 3 4 21"/>' ),
	'youtube' => array( 'YouTube', '<rect x="2" y="5" width="20" height="14" rx="4"/><path d="m10 9 6 3-6 3z" fill="currentColor" stroke="none"/>' ),
);
$platinum_social_links = array();
foreach ( $platinum_social_icons as $platinum_network => $platinum_icon ) {
	$platinum_social_url = esc_url( get_theme_mod( 'platinum_social_' . $platinum_social_station . '_' . $platinum_network, '' ), array( 'http', 'https' ) );
	if ( $platinum_social_url ) {
		$platinum_social_links[ $platinum_network ] = $platinum_social_url;
	}
}

if ( ! $platinum_social_links ) {
	return;
}
?>
<nav class="platinum-header-social platinum-header-social--<?php echo esc_attr( $platinum_social_station ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Redes sociales de %s', 'platinum-home' ), $platinum_station ) ); ?>">
	<span class="platinum-social-caption"><?php esc_html_e( 'Síguenos', 'platinum-home' ); ?></span>
	<div class="platinum-social-links">
		<?php foreach ( $platinum_social_links as $platinum_network => $platinum_social_url ) : ?>
			<?php $platinum_social_label = $platinum_social_icons[ $platinum_network ][0] . ' · ' . $platinum_station; ?>
			<a href="<?php echo esc_url( $platinum_social_url ); ?>" <?php if ( '#' !== $platinum_social_url ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?> aria-label="<?php echo esc_attr( '#' === $platinum_social_url ? $platinum_social_label : sprintf( __( '%s (abre en otra pestaña)', 'platinum-home' ), $platinum_social_label ) ); ?>" title="<?php echo esc_attr( $platinum_social_label ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><?php echo $platinum_social_icons[ $platinum_network ][1]; // Static SVG markup defined above. ?></svg>
			</a>
		<?php endforeach; ?>
	</div>
</nav>
