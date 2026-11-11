<?php
/**
 * Plugin Name: Radio Platinum Home
 * Description: Portada de radio y cabecera y pie compartidos para todo el sitio.
 * Version: 1.12.0
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
	register_nav_menus( array(
		'platinum_primary' => __( 'Menú principal Radio Platinum', 'platinum-home' ),
		'platinum_footer'  => __( 'Menú del pie de página', 'platinum-home' ),
	) );
}, 20 );

/** Create the two programming categories once when WordPress has database access. */
add_action( 'init', function () {
	if ( get_option( 'platinum_home_programming_categories_created' ) ) {
		return;
	}

	$categories = array(
		'programacion-platinum' => 'Programación Platinum',
		'programacion-quito'    => 'Programación Quito',
	);

	foreach ( $categories as $slug => $name ) {
		if ( term_exists( $slug, 'category' ) ) {
			continue;
		}

		$result = wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
		if ( is_wp_error( $result ) ) {
			return;
		}
	}

	update_option( 'platinum_home_programming_categories_created', '1', false );
}, 20 );

/** Create editable starter menus when neither location has been assigned yet. */
add_action( 'init', function () {
	$locations = get_nav_menu_locations();
	$locations = is_array( $locations ) ? $locations : array();

	if ( empty( $locations['platinum_primary'] ) ) {
		$primary = wp_get_nav_menu_object( 'Menú principal Radio Platinum' );
		$primary = $primary ?: wp_create_nav_menu( 'Menú principal Radio Platinum' );
		if ( ! is_wp_error( $primary ) ) {
			$primary_id = is_object( $primary ) ? $primary->term_id : $primary;
			$platinum   = home_url( '/radio-platinum/' );
			$quito      = home_url( '/radio-quito/' );
			$platinum_parent = wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Radio Platinum', 'menu-item-url' => $platinum, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
			$quito_parent    = wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Radio Quito', 'menu-item-url' => $quito, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
			wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Inicio', 'menu-item-url' => home_url( '/' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-position' => 1 ) );
			if ( ! is_wp_error( $platinum_parent ) ) {
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Inicio', 'menu-item-url' => $platinum, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $platinum_parent ) );
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Audios', 'menu-item-url' => home_url( '/catalogo-audios-platinum/' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $platinum_parent ) );
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Programación', 'menu-item-url' => platinum_programming_category_url( 'programacion-platinum' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $platinum_parent ) );
			}
			if ( ! is_wp_error( $quito_parent ) ) {
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Inicio', 'menu-item-url' => $quito, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $quito_parent ) );
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Audios', 'menu-item-url' => home_url( '/catalogo-audios-quito/' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $quito_parent ) );
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Programación', 'menu-item-url' => platinum_programming_category_url( 'programacion-quito' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $quito_parent ) );
			}
			$locations['platinum_primary'] = $primary_id;
		}
	}

	if ( empty( $locations['platinum_footer'] ) ) {
		$footer = wp_get_nav_menu_object( 'Menú del pie de página' );
		$footer = $footer ?: wp_create_nav_menu( 'Menú del pie de página' );
		if ( ! is_wp_error( $footer ) ) {
			$footer_id = is_object( $footer ) ? $footer->term_id : $footer;
			foreach ( array( 'Créditos', 'Buzón Ciudadano', 'Rendición de cuentas', 'Código de ética', 'Código Deontológico' ) as $label ) {
				wp_update_nav_menu_item( $footer_id, 0, array( 'menu-item-title' => $label, 'menu-item-url' => '#', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
			}
			$locations['platinum_footer'] = $footer_id;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}, 30 );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'noticias_pagina';
	return $vars;
} );

add_filter( 'template_include', function ( $template ) {
	if ( is_front_page() && ! is_feed() && ! is_embed() ) {
		return __DIR__ . '/platinum-home/front-page.php';
	}
	if ( platinum_is_radio_platinum_page() && ! is_feed() && ! is_embed() ) {
		global $wp_query;
		$wp_query->is_404 = false;
		status_header( 200 );
		return __DIR__ . '/platinum-home/radio-platinum.php';
	}
	if ( ( platinum_is_audio_catalog_page() || platinum_is_audio_catalog_quito_page() ) && ! is_feed() && ! is_embed() ) {
		global $wp_query;
		$wp_query->is_404 = false;
		status_header( 200 );
		return __DIR__ . '/platinum-home/audio-catalog.php';
	}
	if ( platinum_is_radio_quito_page() && ! is_feed() && ! is_embed() ) {
		global $wp_query;
		$wp_query->is_404 = false;
		status_header( 200 );
		return __DIR__ . '/platinum-home/radio-quito.php';
	}
	if ( platinum_is_programming_category() && ! is_feed() && ! is_embed() ) {
		return __DIR__ . '/platinum-home/programming-category.php';
	}
	return $template;
}, 99 );

add_filter( 'pre_handle_404', function ( $preempt ) {
	if ( platinum_is_radio_platinum_page() || platinum_is_audio_catalog_page() || platinum_is_audio_catalog_quito_page() || platinum_is_radio_quito_page() ) {
		return true;
	}
	return $preempt;
}, 10 );

add_action( 'wp_enqueue_scripts', function () {
	if ( is_feed() || is_embed() ) {
		return;
	}
	$base = plugin_dir_url( __FILE__ ) . 'platinum-home/assets/';
	wp_enqueue_style( 'platinum-home', $base . 'home.css', array(), '1.12.0' );
	wp_enqueue_script( 'platinum-home', $base . 'home.js', array(), '1.2.0', true );
	wp_enqueue_style( 'platinum-accessibility', $base . 'accessibility.css', array( 'platinum-home' ), '1.1.0' );
	wp_enqueue_script( 'platinum-accessibility', $base . 'accessibility.js', array(), '1.1.0', true );
}, 100 );

/** Keep the accessibility panel outside page content and its color filters. */
add_action( 'wp_footer', function () {
	if ( is_admin() || is_feed() || is_embed() || wp_is_json_request() ) {
		return;
	}
	require __DIR__ . '/platinum-home/accessibility.php';
}, 5 );

add_filter( 'body_class', function ( $classes ) {
	if ( ! is_admin() && ! is_feed() && ! is_embed() ) {
		$classes[] = 'platinum-home';
	}
	return array_unique( $classes );
} );

/** Reuse site navigation and footer without replacing the theme's page content. */
add_filter( 'render_block_core/template-part', function ( $content, $block ) {
	if ( is_admin() || is_feed() || is_embed() || wp_is_json_request() ) {
		return $content;
	}
	$attributes = $block['attrs'] ?? array();
	$slug       = $attributes['slug'] ?? '';
	$tag        = $attributes['tagName'] ?? '';
	$part       = '';
	if ( 'header' === $slug || 'header' === $tag ) {
		$part = 'header';
	} elseif ( 'footer' === $slug || 'footer' === $tag ) {
		$part = 'footer';
	}
	if ( ! $part && $slug ) {
		$theme         = $attributes['theme'] ?? get_stylesheet();
		$template_part = get_block_template( $theme . '//' . $slug, 'wp_template_part' );
		if ( $template_part && in_array( $template_part->area, array( 'header', 'footer' ), true ) ) {
			$part = $template_part->area;
		}
	}
	if ( ! $part ) {
		return $content;
	}
	ob_start();
	require __DIR__ . '/platinum-home/' . $part . '.php';
	return ob_get_clean();
}, 10, 2 );

add_action( 'customize_register', function ( $customizer ) {
	foreach ( array( 'platinum' => 'Radio Platinum', 'quito' => 'Radio Quito' ) as $station => $name ) {
		$section = 'platinum_social_' . $station;
		$customizer->add_section( $section, array(
			'title'    => sprintf( __( 'Redes sociales — %s', 'platinum-home' ), $name ),
			'priority' => 36,
		) );
		foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'x' => 'X', 'youtube' => 'YouTube' ) as $network => $label ) {
			$key = $section . '_' . $network;
			$customizer->add_setting( $key, array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			) );
			$customizer->add_control( $key, array(
				'label'   => $label,
				'section' => $section,
				'type'    => 'url',
			) );
		}
	}
	$customizer->add_section( 'platinum_home', array(
		'title'    => __( 'Portada Radio Platinum', 'platinum-home' ),
		'priority' => 35,
	) );
	$fields = array(
		'platinum_stream'       => array( 'URL del streaming', 'https://example.com/radio-platinum.mp3' ),
		'platinum_video'        => array( 'URL del video de la portada', 'https://www.youtube.com/watch?v=jNQXAC9IVRw' ),
		'platinum_station_video' => array( 'URL del video de Radio Platinum', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ),
		'platinum_quito_stream' => array( 'URL del streaming de Radio Quito', 'https://example.com/radio-quito.mp3' ),
		'platinum_quito_video'  => array( 'URL del video de Radio Quito', 'https://www.youtube.com/watch?v=jNQXAC9IVRw' ),
	);
	foreach ( $fields as $key => $field ) {
		$customizer->add_setting( $key, array(
			'default'           => $field[1],
			'sanitize_callback' => 'esc_url_raw',
		) );
		$customizer->add_control( $key, array(
			'label'   => $field[0],
			'section' => 'platinum_home',
			'type'    => 'url',
		) );
	}
} );

/** Convert a YouTube watch, share, or embed URL into a safe embed URL. */
function platinum_home_video_url( $url ) {
	$parts = wp_parse_url( $url );
	$host  = strtolower( $parts['host'] ?? '' );
	$path  = $parts['path'] ?? '';
	$id    = '';
	if ( in_array( $host, array( 'youtu.be', 'www.youtu.be' ), true ) ) {
		$id = trim( $path, '/' );
	} elseif ( in_array( $host, array( 'youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com' ), true ) ) {
		parse_str( $parts['query'] ?? '', $query );
		$id = $query['v'] ?? '';
		if ( preg_match( '~^/(?:embed|shorts|live)/([A-Za-z0-9_-]{11})~', $path, $matches ) ) {
			$id = $matches[1];
		}
	}
	return is_string( $id ) && preg_match( '/^[A-Za-z0-9_-]{11}$/', $id )
		? 'https://www.youtube-nocookie.com/embed/' . $id
		: '';
}

/** Whether the current request is the Radio Platinum landing page. */
function platinum_is_radio_platinum_page() {
	return platinum_is_custom_path( 'radio-platinum' );
}

/** Whether the current request is the Radio Platinum audio catalog. */
function platinum_is_audio_catalog_page() {
	return platinum_is_custom_path( 'catalogo-audios-platinum' );
}

/** Whether the current request is the Radio Quito audio catalog. */
function platinum_is_audio_catalog_quito_page() {
	return platinum_is_custom_path( 'catalogo-audios-quito' );
}

/** Whether the current request is the Radio Quito landing page. */
function platinum_is_radio_quito_page() {
	return platinum_is_custom_path( 'radio-quito' );
}

/** Match one public virtual page path. */
function platinum_is_custom_path( $requested_path ) {
	if ( is_admin() || wp_doing_ajax() || wp_is_json_request() ) {
		return false;
	}
	$path      = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
	$home_path = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	$relative  = '/' . ltrim( substr( (string) $path, strlen( (string) $home_path ) ), '/' );
	return '/' . trim( $requested_path, '/' ) . '/' === trailingslashit( $relative );
}

/** Check whether the current archive is one of the station programming categories. */
function platinum_is_programming_category() {
	if ( ! is_category() ) {
		return false;
	}
	$category = get_queried_object();
	return $category instanceof WP_Term
		&& in_array( $category->slug, array( 'programacion-platinum', 'programacion-quito' ), true );
}

/** Return the category archive URL, with a safe fallback before the category exists. */
function platinum_programming_category_url( $slug ) {
	$category = get_category_by_slug( $slug );
	return $category ? get_category_link( $category ) : home_url( '/' );
}

/** Output a stable fallback while the first WordPress menu is being created. */
function platinum_primary_menu_fallback() {
	$home = home_url( '/' );
	?>
	<ul class="platinum-menu-list">
		<li><a href="<?php echo esc_url( $home ); ?>"><?php esc_html_e( 'Inicio', 'platinum-home' ); ?></a></li>
		<li class="menu-item-has-children"><a href="<?php echo esc_url( $home . 'radio-platinum/' ); ?>">Radio Platinum</a><ul class="sub-menu"><li><a href="<?php echo esc_url( $home . 'radio-platinum/' ); ?>"><?php esc_html_e( 'Inicio', 'platinum-home' ); ?></a></li><li><a href="<?php echo esc_url( $home . 'catalogo-audios-platinum/' ); ?>"><?php esc_html_e( 'Audios', 'platinum-home' ); ?></a></li><li><a href="<?php echo esc_url( platinum_programming_category_url( 'programacion-platinum' ) ); ?>"><?php esc_html_e( 'Programación', 'platinum-home' ); ?></a></li></ul></li>
		<li class="menu-item-has-children"><a href="<?php echo esc_url( $home . 'radio-quito/' ); ?>">Radio Quito</a><ul class="sub-menu"><li><a href="<?php echo esc_url( $home . 'radio-quito/' ); ?>"><?php esc_html_e( 'Inicio', 'platinum-home' ); ?></a></li><li><a href="<?php echo esc_url( $home . 'catalogo-audios-quito/' ); ?>"><?php esc_html_e( 'Audios', 'platinum-home' ); ?></a></li><li><a href="<?php echo esc_url( platinum_programming_category_url( 'programacion-quito' ) ); ?>"><?php esc_html_e( 'Programación', 'platinum-home' ); ?></a></li></ul></li>
	</ul>
	<?php
}

/** Fallback links displayed until the footer menu is assigned in WordPress. */
function platinum_footer_menu_fallback() {
	?>
	<ul class="platinum-footer-menu-list">
		<li><a href="#" data-placeholder><?php esc_html_e( 'Créditos', 'platinum-home' ); ?></a></li>
		<li><a href="#" data-placeholder><?php esc_html_e( 'Buzón Ciudadano', 'platinum-home' ); ?></a></li>
		<li><a href="#" data-placeholder><?php esc_html_e( 'Rendición de cuentas', 'platinum-home' ); ?></a></li>
		<li><a href="#" data-placeholder><?php esc_html_e( 'Código de ética', 'platinum-home' ); ?></a></li>
		<li><a href="#" data-placeholder><?php esc_html_e( 'Código Deontológico', 'platinum-home' ); ?></a></li>
	</ul>
	<?php
}
