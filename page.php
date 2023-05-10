<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  View
 * @since   1.0
 */

use Timber\Timber;
use Timber\Post;

$context = Timber::context();

$timber_post     = new Post();
$context['post'] = $timber_post;

$templates = [
	'pages/' . $timber_post->post_name . '.twig',
	'templates/page.twig',
];

Timber::render( $templates, $context );
