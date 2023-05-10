<?php
/**
 * The template to handle the blog archive
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  View
 * @since   1.0
 */
use Timber\Timber;

$context = Timber::context();

Timber::render(
	[
		'templates/posts.twig',
	],
	$context
);
