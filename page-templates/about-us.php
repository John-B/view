<?php
/**
 * Template Name: About Us
 *
 * @package  WordPress
 * @subpackage  View
 * @since   1.0
*/

use Timber\Timber;
use Timber\Post;

$context = Timber::context();

$timber_post     = Timber::get_post();
$context['post'] = $timber_post;
$templates = [
    'pages/about.twig'
];

Timber::render( $templates, $context );
