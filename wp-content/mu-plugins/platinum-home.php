<?php
/**
 * Plugin Name: Radio Platinum Home
 * Description: Portada de radio con noticias recientes y paginación.
 * Version: 1.1.0
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
	if ( ! is_front_page() || is_feed() || is_embed() ) {
		return;
	}
	$base = plugin_dir_url( __FILE__ ) . 'platinum-home/assets/';
	wp_enqueue_style( 'platinum-home', $base . 'home.css', array(), '1.1.0' );
	wp_enqueue_script( 'platinum-home', $base . 'home.js', array(), '1.0.0', true );
}, 100 );

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
