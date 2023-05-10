<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  GoodVentures
 * @since   1.0
 */
use Timber\Timber;

$context = Timber::context();

Timber::render( 'pages/404.twig', $context );
