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
			$platinum   = '/radio-platinum/';
			$quito      = '/radio-quito/';
			$platinum_parent = wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Radio Platinum', 'menu-item-url' => $platinum, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
			$quito_parent    = wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Radio Quito', 'menu-item-url' => $quito, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
			wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Inicio', 'menu-item-url' => '/', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-position' => 1 ) );
			if ( ! is_wp_error( $platinum_parent ) ) {
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Inicio', 'menu-item-url' => $platinum, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $platinum_parent ) );
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Audios', 'menu-item-url' => '/catalogo-audios-platinum/', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $platinum_parent ) );
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Programación', 'menu-item-url' => platinum_relative_internal_menu_url( platinum_programming_category_url( 'programacion-platinum' ) ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $platinum_parent ) );
			}
			if ( ! is_wp_error( $quito_parent ) ) {
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Inicio', 'menu-item-url' => $quito, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $quito_parent ) );
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Audios', 'menu-item-url' => '/catalogo-audios-quito/', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $quito_parent ) );
				wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Programación', 'menu-item-url' => platinum_relative_internal_menu_url( platinum_programming_category_url( 'programacion-quito' ) ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $quito_parent ) );
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

/** Convert the already saved internal menu links to root-relative paths once. */
add_action( 'init', function () {
	if ( get_option( 'platinum_home_relative_menu_links_updated' ) ) {
		return;
	}

	$locations = get_nav_menu_locations();
	$menu_ids  = array_filter( array(
		$locations['platinum_primary'] ?? 0,
		$locations['platinum_footer'] ?? 0,
	) );

	foreach ( array_unique( $menu_ids ) as $menu_id ) {
		$items = wp_get_nav_menu_items( $menu_id );
		foreach ( $items ?: array() as $item ) {
			if ( 'custom' !== $item->type ) {
				continue;
			}

			$relative_url = platinum_relative_internal_menu_url( $item->url );
			if ( null === $relative_url || $relative_url === $item->url ) {
				continue;
			}

			update_post_meta( $item->ID, '_menu_item_url', $relative_url );
			clean_post_cache( $item->ID );
		}
	}

	update_option( 'platinum_home_relative_menu_links_updated', '1', false );
}, 40 );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'noticias_pagina';
	return $vars;
} );

/** Shared query arguments for the latest news on the homepage and its load-more request. */
function platinum_home_news_query_args( $page = 1 ) {
	return array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'category_name'       => 'noticias',
		'posts_per_page'      => 4,
		'paged'               => max( 1, absint( $page ) ),
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
	);
}

/** Return one additional homepage news page without navigating away from the current scroll position. */
function platinum_home_load_more_news() {
	check_ajax_referer( 'platinum_load_more_news', 'nonce' );

	$page = max( 2, absint( $_POST['page'] ?? 2 ) );
	$news = new WP_Query( platinum_home_news_query_args( $page ) );

	ob_start();
	while ( $news->have_posts() ) {
		$news->the_post();
		require __DIR__ . '/platinum-home/news-item.php';
	}
	$html = ob_get_clean();
	wp_reset_postdata();

	wp_send_json_success( array(
		'html'     => $html,
		'next_page' => $page + 1,
		'has_more' => $page < $news->max_num_pages,
	) );
}
add_action( 'wp_ajax_platinum_load_more_news', 'platinum_home_load_more_news' );
add_action( 'wp_ajax_nopriv_platinum_load_more_news', 'platinum_home_load_more_news' );

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
	wp_enqueue_style( 'platinum-home', $base . 'home.css', array(), '1.14.0' );
	wp_enqueue_script( 'platinum-home', $base . 'home.js', array(), '1.4.0', true );
	wp_enqueue_style( 'platinum-accessibility', $base . 'accessibility.css', array( 'platinum-home' ), '1.1.0' );
	wp_enqueue_script( 'platinum-accessibility', $base . 'accessibility.js', array(), '1.1.0', true );
	if ( is_page( 42 ) ) {
		wp_enqueue_style( 'platinum-leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
		wp_enqueue_script( 'platinum-leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
		wp_enqueue_script( 'platinum-mailbox-map', $base . 'mailbox-map.js', array( 'platinum-leaflet' ), '1.0.0', true );
	}
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

/** Read a Facebook iframe URL from a configured station video URL. */
function platinum_home_facebook_video_url( $source_url ) {
	$source_url = esc_url_raw( $source_url );
	$source     = wp_parse_url( $source_url );
	$video      = '';

	if ( ! in_array( $source['scheme'] ?? '', array( 'http', 'https' ), true ) || empty( $source['host'] ) ) {
		return $video;
	}

	$cache_key = 'platinum_home_video_' . md5( $source_url );
	$cached    = get_transient( $cache_key );
	if ( false !== $cached ) {
		return is_string( $cached ) ? $cached : $video;
	}

	$response = wp_safe_remote_get( $source_url, array(
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

	set_transient( $cache_key, $video, $video ? 5 * MINUTE_IN_SECONDS : MINUTE_IN_SECONDS );
	return $video;
}

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

/** Return a root-relative URL when the URL belongs to this site. */
function platinum_relative_internal_menu_url( $url ) {
	$parts = wp_parse_url( $url );
	if ( ! is_array( $parts ) || empty( $parts['host'] ) ) {
		return $url;
	}

	$home_host = strtolower( wp_parse_url( home_url( '/' ), PHP_URL_HOST ) ?? '' );
	$host      = strtolower( $parts['host'] );
	$hosts     = array_filter( array( $home_host, '89.127.235.30', 'ecuadoradio.ec', 'www.ecuadoradio.ec' ) );
	if ( ! in_array( $host, $hosts, true ) ) {
		return null;
	}

	$relative = $parts['path'] ?? '/';
	$relative = '' !== $relative ? $relative : '/';
	if ( isset( $parts['query'] ) ) {
		$relative .= '?' . $parts['query'];
	}
	if ( isset( $parts['fragment'] ) ) {
		$relative .= '#' . $parts['fragment'];
	}

	return $relative;
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
