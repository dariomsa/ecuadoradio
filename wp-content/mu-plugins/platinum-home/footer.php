<?php
/** Footer shared by the Radio Platinum homepage. */
defined( 'ABSPATH' ) || exit;

$footer_links = array(
	__( 'Créditos', 'platinum-home' ),
	__( 'Buzón Ciudadano', 'platinum-home' ),
	__( 'Rendición de cuentas', 'platinum-home' ),
	__( 'Código de ética', 'platinum-home' ),
	__( 'Código Deontológico', 'platinum-home' ),
);
?>
<footer class="platinum-footer">
	<div class="platinum-footer-top">
		<nav class="platinum-container platinum-footer-links" aria-label="<?php esc_attr_e( 'Información institucional', 'platinum-home' ); ?>">
			<?php foreach ( $footer_links as $label ) : ?>
				<a href="#" data-placeholder><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>
	</div>
	<div class="platinum-footer-bottom">
		<div class="platinum-container">
			<a class="platinum-footer-brand" href="#" data-placeholder aria-label="Ecuadoradio">
				<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'assets/ecuadoradio.svg' ); ?>" alt="Ecuadoradio — cadena radial" width="154" height="30">
			</a>
			<p class="platinum-footer-legal"><?php esc_html_e( 'Copyright ©2024 - Ecuadoradio. Todos los derechos reservados. Queda prohibida la reproducción total o parcial, por cualquier medio, de textos, sonidos, ilustraciones, videos, fotografías sin autorización expresa de ECUADORADIO S.A.', 'platinum-home' ); ?> <a href="#" data-placeholder><?php esc_html_e( 'Términos y Condiciones', 'platinum-home' ); ?></a></p>
		</div>
	</div>
</footer>
