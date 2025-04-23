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
global $paged;
if (!isset($paged) || !$paged) {
    $paged = 1;
}

$context                        = Timber::context();
$context['search_query']        = get_search_query();

Timber::render( $templates, $context );
