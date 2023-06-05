<?php
/**
 * Good Ventures Theme
 *
 * @package  WordPress
 * @subpackage  View
 * @since   1.0
 */

 use Timber\Timber;

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */

if ( ! class_exists( Timber::class ) ) {
	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure your developer runs installs required composer packages</p></div>';
		}
	);
	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

require_once __DIR__ . '/inc/disable-emojis.php';
require_once __DIR__ . '/inc/wpseo_title.php';

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = [ 'views' ];

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = 'wp_kses_post';

/**
 * Enqueues
 *
 * @return void
 */
add_action( 'wp_enqueue_scripts', 'theme_enqueues' );
function theme_enqueues() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'legacy-webfonts', get_stylesheet_directory_uri() . '/assets/legacy.webfonts.css', [], '1.0.0' );
	wp_enqueue_style( 'legacy-styles', get_stylesheet_directory_uri() . '/assets/legacy.styles.css', [], '1.0.0' );
	wp_enqueue_style( 'legacy-additional-styles', get_stylesheet_directory_uri() . '/assets/legacy.additional-styles.css', [], '1.0.0' );
	wp_enqueue_style( '2021-styles', get_stylesheet_directory_uri() . '/assets/2021.css', [], '1.0.1' );
	wp_enqueue_script( 'legacy-scripts', get_stylesheet_directory_uri() . '/assets/legacy-scripts.min.js', [], '1.0.0', true );
	wp_enqueue_script( 'modernizr', get_stylesheet_directory_uri() . '/assets/modernizr.custom.js', [], '1.0', true );
	wp_enqueue_script( '2021-scripts', get_stylesheet_directory_uri() . '/assets/2021.js', [], '1.0', true );
}

add_theme_support( 'post-thumbnails' );

/**
 * Various theme supports
 *
 * @return void
 */
add_action( 'after_setup_theme', 'theme_supports' );
function theme_supports() {

			// Disable Gutenberg Editor for all post types
			add_filter( 'use_block_editor_for_post', '__return_false', 10 );
			add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			/*
			 * Let WordPress manage the document title.
			 * By adding theme support, we declare that this theme does not use a
			 * hard-coded <title> tag in the document head, and expect WordPress to
			 * provide it for us.
			 */
			add_theme_support( 'title-tag' );

			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
			 */
			add_theme_support( 'post-thumbnails' );

			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support(
				'html5',
				[
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				]
			);

			add_theme_support( 'menus' );

			register_nav_menus(
				[
					'header_primary'   => __( 'Header Primary', 'view' ),
					'footer_primary'   => __( 'Footer Primary', 'view' ),
					'footer_secondary' => __( 'Footer Secondary', 'view' ),
					'footer_tertiary'  => __( 'Footer Tertiary', 'view' ),
					'copyright'        => __( 'Footer Copyright', 'view' ),
				]
			);
}

/**
 *
 */
add_filter( 'template_include', 'add_custom_url_template_loader' );
function add_custom_url_template_loader( $template ) {
	$url_path = trim( wp_parse_url( add_query_arg( [] ), PHP_URL_PATH ), '/' );
	if ( 'our-portfolio/grants-database' !== $url_path ) {
		return $template;
	}
	return get_template_directory() . '/custom-grants-table.php';
}

/**
 * Add Pagination
 */
add_action( 'init', 'add_grants_database_pagination' );
function add_grants_database_pagination() {
	add_rewrite_rule(
		'our-portfolio/grants-database/page/?([0-9]{1,})/?$',
		'our-portfolio/grants-database/page/?paged=$matches[1]',
		'top'
	);
}

add_action( 'template_redirect', 'prefix_url_rewrite_templates' );
function prefix_url_rewrite_templates() {
	$url_path = trim( wp_parse_url( add_query_arg( [] ), PHP_URL_PATH ), '/' );
	if ( 'our-portfolio/grants-database' !== substr( $url_path, 0, 29 ) ) {
		return;
	}
	global $wp_query;

	if ( $wp_query->is_404 ) {
		$wp_query->is_404 = false;
	}

	add_filter(
		'pre_get_document_title',
		function( $title ) {
			return __( 'Grants Database', 'view' ) . ' | ' . get_bloginfo( 'name' );
		},
		5
	);

	add_filter(
		'template_include',
		function() {
			return get_template_directory() . '/custom-grants-table.php';
		}
	);
}

// Move Yoast to bottom
add_filter(
	'wpseo_metabox_prio',
	function () {
		return 'low';
	}
);

// Create a dynamic grant csv download endpoint: /our-portfolio/grants-database/?download
add_action( 'template_redirect', 'grants_database_download_csv' );
function grants_database_download_csv() {
	$url_path = trim( wp_parse_url( add_query_arg( [] ), PHP_URL_PATH ), '/' );

	// Conditions to bail early
	if ( 'our-portfolio/grants-database' !== $url_path ) return;
	if ( ! isset( $_GET['download'] ) ) return;
	header( 'Content-Description: File Transfer');
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=grants.csv' );
	header( 'Pragma: no-cache' );
	header( 'Expires: 0' );

	// Add titles row
	$titles = array(
		'Grant',
		'Organization',
		'Main Topic',
		'Topics',
		'Date of Award',
		'Amount'
	);

	$csv = fopen( 'php://output', 'w' );
	fwrite($csv, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
	fputcsv( $csv, $titles );

	// Add data rows
	$args = array(
		'post_type'      => 'grant',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'	 => 'publish'
	);

	$grants = new WP_Query( $args );

	if ( $grants->have_posts() ) {
		while ( $grants->have_posts() ) {
			$grants->the_post();

			// Get the taxonomy organization terms
			$organization_terms = get_the_terms( get_the_ID(), 'organization' );
			$organizations = join(", ", wp_list_pluck( $organization_terms, 'name' ));

			// Get the taxonomy post_tag terms
			$topic_terms = get_the_terms( get_the_ID(), 'post_tag' );
			$topic_names_array = wp_list_pluck( $topic_terms, 'name' );
			$topic_id_array = wp_list_pluck( $topic_terms, 'term_id' );
			$topic_names_string = join('; ', $topic_names_array);
		        $main_topic_ignore_array = ["Potential GiveWell Top Charities", "Economic Empowerment", "Transformative Basic Science"];

 			// identify main topic, which is the least used term
			    $term_count = 0;
		            foreach ( $topic_id_array as $topic_id) {
				$term = get_term($topic_id);
				if(!in_array($term->name, $main_topic_ignore_array) && ($term->count <= $term_count || $term_count == 0)) {
				  $term_count = $term->count;
				  $main_topic = $term->name;
		        	}  
			    }
			$grant = array(
				get_the_title(),
				$organizations,
				$main_topic,
				$topic_names_string,
				get_field( 'date_of_award' ),
				"$" . number_format(str_replace(",", "", get_field( 'amount' )), 0)
			);
			fputcsv( $csv, $grant );
		}
	}
	fclose( $csv );

	// Set the headers and send the file
	exit;
}

// Add a new function to Twig for Co-author Plus and allow graceful fallback if plugin is not installed
add_filter( 'timber/twig', 'add_to_twig' );

function display_authors() {
	if ( !function_exists('get_coauthors') ) {
		global $post;

		// Return post author
		return get_the_author_meta( 'display_name', $post->post_author );
	}

	// Use co-author plus to display authors
	// * Note the coauthors() function returns duplicates in this environment, so we're using a different function instead
	$authors = get_coauthors();
	$author_names = array();
	foreach ( $authors as $author ) {
		$author_names[] = $author->display_name;
	}
	return join( ', ', $author_names );
}

function add_to_twig( $twig ) {
	$twig->addFunction( new \Timber\Twig_Function( 'display_authors', 'display_authors' ) );
	return $twig;
}

// Filter blog post contents when post_date is on or before 2021-06-03
add_filter( 'the_content', 'filter_blog_posts_fix_footnotes' );
function filter_blog_posts_fix_footnotes($content) {
	if ( !is_singular( 'post' ) ) return $content;

	global $post;

	// Get post date from th post object
	$post_date = $post->post_date;

	// If post date is greater than 2021-06-03, return the content
	if ( $post_date > '2021-06-03' ) return $content;

	// footnote pattern to match {footnote_%d} or &#123;footnote_%d&#125;
	$pattern = '/\{footnote_(\d+)\}|\&#123;footnote_(\d+)\&#125;/';
	
	// Replace the pattern with: <sup><a id="footnote-ref-%d" href="#footnote-%d">[%d]</a><sup>
	$content = preg_replace_callback( $pattern, function( $matches ) {
		$footnote_id = $matches[1] ?: $matches[2]; // If the first pattern is not matched, use the second pattern
		return '<sup><a id="footnote-ref-' . $footnote_id . '" href="#footnote-' . $footnote_id . '">[' . $footnote_id . ']</a></sup>';
	}, $content );

	return $content;
}
add_action( 'template_redirect', 'add_test_header' );
function add_test_header()
{
  header('Expires:  Fri, 02 Dec 1990 16:00:00 GMT');
}
