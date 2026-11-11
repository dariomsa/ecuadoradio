<?php
/**
 * Plugin Name: Radio Platinum — Comentarios desactivados
 * Description: Cierra comentarios y avisos de enlaces, y oculta los comentarios públicos sin borrarlos.
 * Version: 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Core checks these filters for both the public form and REST submissions.
add_filter( 'comments_open', '__return_false', 100 );
add_filter( 'pings_open', '__return_false', 100 );

// New entries also use closed discussion settings by default.
add_filter( 'pre_option_default_comment_status', function () {
	return 'closed';
} );
add_filter( 'pre_option_default_ping_status', function () {
	return 'closed';
} );

// Hide the complete comments section, including its heading, in block themes.
add_filter( 'pre_render_block', function ( $output, $block ) {
	if ( is_admin() || wp_is_json_request() ) {
		return $output;
	}
	$name = $block['blockName'] ?? '';
	if ( 'core/latest-comments' === $name
		|| str_starts_with( $name, 'core/comments' )
		|| str_starts_with( $name, 'core/comment-' )
		|| str_starts_with( $name, 'core/post-comments' ) ) {
		return '';
	}
	return $output;
}, 100, 2 );

// Classic comments templates should not output a list or a form either.
add_filter( 'comments_template', function ( $template ) {
	return is_admin() ? $template : __DIR__ . '/platinum-home/comments-disabled.php';
}, 100 );

add_filter( 'comments_array', function ( $comments ) {
	return is_admin() ? $comments : array();
}, 100 );

add_filter( 'get_comments_number', function ( $count ) {
	return is_admin() ? $count : 0;
}, 100 );
