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
use Timber\PostQuery;

$templates = [ 'templates/search.twig' ];

$context                        = Timber::context();
$context['search_query']        = get_search_query();
$context['posts']['pagination'] = Timber::get_pagination(
	[
		'show_all' => false,
		'mid_size' => 3,
		'end_size' => 1,
	]
);

Timber::render( $templates, $context );
