<?php
/**
 * Template Name: Other Investments
 *
 * @package  WordPress
 * @subpackage  View
 * @since   1.0
*/

use Timber\Timber;

// Custom pagination setting up using ACF repeater: https://timber.github.io/docs/guides/pagination/#what-if-im-not-using-the-default-query?
global $paged;
if (!isset($paged) || !$paged){
	$paged = 1;
}

$posts_per_page = 3; // Custom override for this page

// Get the repeater field called investments
$investments = get_field('investments');
$max_numb_pages = ceil(count($investments) / $posts_per_page);

// Sort the posts by date
usort($investments, function($a, $b) {
	return strtotime($b['content']['date_of_investment']) - strtotime($a['content']['date_of_investment']);
});

// Get the investment posts for the current page
$offset = $posts_per_page * ($paged - 1);

$investments_to_show = array_slice($investments, $offset, $posts_per_page);

$context = Timber::context();
$timber_post['investments'] = $investments_to_show;

$context['post'] = $timber_post;
$context['posts']['pagination'] = Timber::get_pagination(
	[
		'current'  => max( 1, $paged ),
		'total'    => $max_numb_pages,
		'show_all' => false,
		'mid_size' => 1,
		'end_size' => 1,
	]
);

$templates = [
    'pages/other-investments.twig'
];

Timber::render( $templates, $context );