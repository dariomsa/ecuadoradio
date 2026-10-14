<?php
/** Shared navigation for the homepage and theme templates. */
defined( 'ABSPATH' ) || exit;

$platinum_home_url = home_url( '/' );
$platinum_logo     = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' );
$platinum_logo     = $platinum_logo ?: plugin_dir_url( __FILE__ ) . 'assets/radio-platinum.svg';
?>
<header class="platinum-header">
	<div class="platinum-container platinum-header-inner">
		<a class="platinum-brand" href="<?php echo esc_url( $platinum_home_url ); ?>" aria-label="<?php esc_attr_e( 'Radio Platinum — Inicio', 'platinum-home' ); ?>">
			<img src="<?php echo esc_url( $platinum_logo ); ?>" alt="Radio Platinum" width="140" height="40">
		</a>
		<nav class="platinum-menu" aria-label="<?php esc_attr_e( 'Navegación principal', 'platinum-home' ); ?>">
			<a href="<?php echo esc_url( $platinum_home_url ); ?>"<?php if ( is_front_page() ) : ?> aria-current="page"<?php endif; ?>><?php esc_html_e( 'Inicio', 'platinum-home' ); ?></a>
			<details class="platinum-dropdown">
				<summary>Radio Platinum <span aria-hidden="true"></span></summary>
				<div class="platinum-dropdown-links">
					<a href="<?php echo esc_url( $platinum_home_url . '#escuchar' ); ?>"><?php esc_html_e( 'Escuchar en vivo', 'platinum-home' ); ?></a>
					<a href="#" data-placeholder><?php esc_html_e( 'Programación', 'platinum-home' ); ?></a>
					<a href="#" data-placeholder><?php esc_html_e( 'Contacto', 'platinum-home' ); ?></a>
				</div>
			</details>
			<details class="platinum-dropdown">
				<summary>Radio Quito <span aria-hidden="true"></span></summary>
				<div class="platinum-dropdown-links">
					<a href="<?php echo esc_url( $platinum_home_url . '#video' ); ?>"><?php esc_html_e( 'Ver transmisión', 'platinum-home' ); ?></a>
					<a href="#" data-placeholder><?php esc_html_e( 'Programación', 'platinum-home' ); ?></a>
					<a href="#" data-placeholder><?php esc_html_e( 'Contacto', 'platinum-home' ); ?></a>
				</div>
			</details>
		</nav>
	</div>
</header>
