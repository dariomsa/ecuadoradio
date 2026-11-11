<?php
/** Accessibility tools available on both station websites. */
defined( 'ABSPATH' ) || exit;

$platinum_accessibility_actions = array(
	'text-increase' => array( __( 'Aumentar texto', 'platinum-home' ), 'A+', false ),
	'text-decrease' => array( __( 'Disminuir texto', 'platinum-home' ), 'A−', false ),
	'grayscale'     => array( __( 'Escala de grises', 'platinum-home' ), '◐', false ),
	'high-contrast' => array( __( 'Alto contraste', 'platinum-home' ), '◑', false ),
	'negative'      => array( __( 'Contraste negativo', 'platinum-home' ), '◒', false ),
	'light'         => array( __( 'Fondo claro', 'platinum-home' ), '☼', false ),
	'links'         => array( __( 'Subrayar enlaces', 'platinum-home' ), '↗', false ),
	'font'          => array( __( 'Fuente legible', 'platinum-home' ), 'Aa', false ),
	'reset'         => array( __( 'Restablecer', 'platinum-home' ), '↺', false ),
);
?>
<div id="accessibility-widget" hidden>
	<button id="accessibility-toggle" type="button" aria-label="<?php esc_attr_e( 'Abrir herramientas de accesibilidad', 'platinum-home' ); ?>" data-open-label="<?php esc_attr_e( 'Abrir herramientas de accesibilidad', 'platinum-home' ); ?>" data-close-label="<?php esc_attr_e( 'Cerrar herramientas de accesibilidad', 'platinum-home' ); ?>" aria-expanded="false" aria-controls="accessibility-panel">
		<svg width="36" height="36" viewBox="0 0 36 36" aria-hidden="true" focusable="false">
			<circle cx="18" cy="18" r="15" fill="none" stroke="currentColor" stroke-width="2"/>
			<circle cx="18" cy="9" r="2.5" fill="currentColor"/>
			<path d="M10 13 L18 15 L26 13 M18 15 L18 21 M18 21 L14 28 M18 21 L22 28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</button>
	<div id="accessibility-panel" role="region" aria-labelledby="accessibility-title" hidden>
		<h2 id="accessibility-title"><?php esc_html_e( 'Herramientas de accesibilidad', 'platinum-home' ); ?></h2>
		<ul>
			<?php foreach ( $platinum_accessibility_actions as $platinum_action => $platinum_action_options ) : ?>
				<li><button id="accessibility-<?php echo esc_attr( $platinum_action ); ?>" type="button"<?php if ( $platinum_action_options[2] ) : ?> disabled<?php endif; ?><?php if ( in_array( $platinum_action, array( 'grayscale', 'high-contrast', 'negative', 'light', 'links', 'font' ), true ) ) : ?> aria-pressed="false"<?php endif; ?>><span class="accessibility-action-icon" aria-hidden="true"><?php echo esc_html( $platinum_action_options[1] ); ?></span><?php echo esc_html( $platinum_action_options[0] ); ?></button></li>
			<?php endforeach; ?>
		</ul>
		<p id="accessibility-text-status" role="status" aria-live="polite" aria-atomic="true" data-label="<?php esc_attr_e( 'Tamaño del texto:', 'platinum-home' ); ?>"><?php esc_html_e( 'Tamaño del texto: 100%', 'platinum-home' ); ?></p>
	</div>
</div>
