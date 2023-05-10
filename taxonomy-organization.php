<?php
/**
 * The template for displaying the Organization Taxonomy Term pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

use Timber\Timber;
use Timber\PostQuery;

$queried = get_queried_object();

$context = Timber::context();

$grants = new PostQuery(
	[
		'post_type' => 'grant',
		'tax_query' => [
			[
				'taxonomy' => $queried->taxonomy,
				'field'    => 'slug',
				'terms'    => $queried->slug,
			],
		],
	]
);

$context['grants'] = $grants->get_posts();

$context['term'] = [
	'data' => $queried,
	'meta' => [
		'image'           => get_field( 'image', $queried ),
		'content'         => get_field( 'content', $queried ),
		'read_more_links' => get_field( 'read_more_links', $queried ),
	],
];

Timber::render( 'templates/organization.twig', $context );
