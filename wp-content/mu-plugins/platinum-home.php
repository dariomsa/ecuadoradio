<?php
/**
 * Plugin Name: Radio Platinum Home
 * Description: Portada de radio y cabecera y pie compartidos para todo el sitio.
 * Version: 1.3.0
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'noticias_pagina';
	return $vars;
} );

add_filter( 'template_include', function ( $template ) {
	if ( is_front_page() && ! is_feed() && ! is_embed() ) {
		return __DIR__ . '/platinum-home/front-page.php';
	}
	return $template;
}, 99 );

add_action( 'wp_enqueue_scripts', function () {
	if ( is_feed() || is_embed() ) {
		return;
	}
	$base = plugin_dir_url( __FILE__ ) . 'platinum-home/assets/';
	wp_enqueue_style( 'platinum-home', $base . 'home.css', array(), '1.3.0' );
	wp_enqueue_script( 'platinum-home', $base . 'home.js', array(), '1.0.0', true );
}, 100 );

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
	$customizer->add_section( 'platinum_home', array(
		'title'    => __( 'Portada Radio Platinum', 'platinum-home' ),
		'priority' => 35,
	) );
	$fields = array(
		'platinum_stream' => array( 'URL del streaming', 'https://example.com/radio-platinum.mp3' ),
		'platinum_video'  => array( 'URL del video de YouTube', 'https://www.youtube.com/watch?v=jNQXAC9IVRw' ),
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
