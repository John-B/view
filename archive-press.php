<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  View
 * @since   1.0
 */
use Timber\Timber;
use Timber\PostQuery;
use Timber\Post;

$context          = Timber::context();
$context['posts'] = new PostQuery();
$context['posts']['pagination'] = Timber::get_pagination(
	[
		'show_all' => false,
		'mid_size' => 1,
		'end_size' => 1,
	]
);

$templates        = [ 'templates/press.twig' ];
Timber::render( $templates, $context );
