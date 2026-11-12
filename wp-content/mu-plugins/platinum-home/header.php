<?php
/** Shared navigation for the homepage and theme templates. */
defined( 'ABSPATH' ) || exit;

$platinum_home_url = home_url( '/' );
$platinum_is_quito = platinum_is_radio_quito_page()
	|| platinum_is_audio_catalog_quito_page()
	|| is_category( array( 'programacion-quito', 'radio-quito' ) )
	|| ( is_single() && has_category( array( 'programacion-quito', 'radio-quito' ) ) );

// Child categories inherit the station branding of their parent archive.
if ( is_category() && ! $platinum_is_quito ) {
	foreach ( get_ancestors( get_queried_object_id(), 'category', 'taxonomy' ) as $platinum_ancestor_id ) {
		$platinum_ancestor = get_term( $platinum_ancestor_id, 'category' );
		if ( $platinum_ancestor instanceof WP_Term && in_array( $platinum_ancestor->slug, array( 'programacion-quito', 'radio-quito' ), true ) ) {
			$platinum_is_quito = true;
			break;
		}
	}
}

$platinum_station = $platinum_is_quito ? 'Radio Quito' : 'Radio Platinum';
$platinum_logo    = plugin_dir_url( __FILE__ ) . 'assets/' . ( $platinum_is_quito ? 'radio-quito.png' : 'radio-platinum.png' );
$platinum_brand_url = $platinum_is_quito ? home_url( '/radio-quito/' ) : $platinum_home_url;
?>
<header class="platinum-header">
	<div class="platinum-container platinum-header-inner">
		<a class="platinum-brand" href="<?php echo esc_url( $platinum_brand_url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%s — Inicio', 'platinum-home' ), $platinum_station ) ); ?>">
			<img src="<?php echo esc_url( $platinum_logo ); ?>" alt="<?php echo esc_attr( $platinum_station ); ?>" width="<?php echo $platinum_is_quito ? '74' : '107'; ?>" height="<?php echo $platinum_is_quito ? '36' : '24'; ?>">
		</a>
		<nav class="platinum-menu" aria-label="<?php esc_attr_e( 'Navegación principal', 'platinum-home' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'platinum_primary',
				'container'      => false,
				'menu_class'     => 'platinum-menu-list',
				'fallback_cb'    => 'platinum_primary_menu_fallback',
			) );
			?>
		</nav>
		<div class="platinum-header-actions">
			<?php require __DIR__ . '/social-links.php'; ?>
			<button class="platinum-search-toggle" type="button" aria-expanded="false" aria-controls="platinum-search-panel" aria-label="<?php esc_attr_e( 'Abrir búsqueda', 'platinum-home' ); ?>" data-open-label="<?php esc_attr_e( 'Abrir búsqueda', 'platinum-home' ); ?>" data-close-label="<?php esc_attr_e( 'Cerrar búsqueda', 'platinum-home' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path></svg>
			</button>
		</div>
	</div>
	<div class="platinum-search-panel" id="platinum-search-panel" hidden>
		<div class="platinum-container">
			<form class="platinum-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label for="platinum-search-field"><?php esc_html_e( 'Buscar en Ecuadoradio', 'platinum-home' ); ?></label>
				<div class="platinum-search-form-controls">
					<input id="platinum-search-field" type="search" name="s" placeholder="<?php esc_attr_e( 'Escriba su búsqueda', 'platinum-home' ); ?>" autocomplete="off">
					<button type="submit"><?php esc_html_e( 'Buscar', 'platinum-home' ); ?></button>
				</div>
			</form>
		</div>
	</div>
</header>
