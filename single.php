<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  View
 * @since    1.0
 */

use Timber\Timber;
use Timber\Post;

$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['post'] = $timber_post;

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'templates/single-password.twig', $context );
} else {
	Timber::render(
		[
			'templates/single-' . $timber_post->ID . '.twig',
			'templates/single-' . $timber_post->post_type . '.twig',
			'templates/single-' . $timber_post->slug . '.twig',
			'templates/single.twig',
		],
		$context
	);
}
