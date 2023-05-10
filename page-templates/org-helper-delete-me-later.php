<?php
/**
 * Template Name: Org Helper
 *
 * @package  WordPress
 * @subpackage  View
 * @since   1.0
*/

use Timber\Timber;
use Timber\Post;

$context = Timber::context();

$timber_post = new Post();
$term_data   = [];
$terms       = get_terms(
	'organization',
	[
		'hide_empty' => false,
	]
);
// var_dump( $terms ) || die;
foreach ( $terms as $term ) {
	$content = get_field( 'content', $term );
	if ( ! $content ) {
		continue;
	}
	$term_data[] = [
		'name'         => $term->name,
		'edit_link'    => get_home_url() . '/wp-admin/term.php?taxonomy=organization&tag_ID=832',
		'profile_link' => get_home_url() . '/organization/' . $term->slug,
		'legacy_link'  => 'https://www.goodventures.org/our-portfolio/organizations/' . $term->slug,
	];
}

// var_dump( $term_data ) || die;
$context['orgs']                = $term_data;
$context['count_all_terms']     = count( $terms );
$context['count_profile_terms'] = count( $term_data );
$context['post']                = $timber_post;
$templates                      = [
	'pages/org-helper-delete-me-later.twig',
];

Timber::render( $templates, $context );
