<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";
	
	$css_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_styles );

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $css_version );
	wp_enqueue_script( 'jquery' );
	
	$js_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_scripts );
	
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $js_version, true );
    wp_localize_script('child-understrap-scripts', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );


function filter_real_estate() {
    $paged = (isset($_POST['page'])) ? $_POST['page'] : 1;
    $term_slug = (isset($_POST['term'])) ? sanitize_text_field($_POST['term']) : '';

    $args = array(
        'post_type' => 'real_estate',
        'posts_per_page' => 6,
        'paged' => $paged,
    );

    if ($term_slug && $term_slug !== 'all') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'region',
                'field'    => 'slug',
                'terms'    => $term_slug,
            ),
        );
    }

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post();
            $building_type = get_field('building_type');
            $number_of_floors = get_field('number_of_floors');
            $eco_rating = get_field('eco_rating');
            $premises = get_field('premises');
            $number_of_rooms = isset($premises['number_of_rooms']) ? $premises['number_of_rooms'] : 'N/A';
            $balcony = isset($premises['balcony']) && $premises['balcony'] === 'yes' ? 'Yes' : 'No';
            $bathroom = isset($premises['bathroom']) && $premises['bathroom'] === 'yes' ? 'Yes' : 'No';
            ?>
            <div class="card border-0 col-md-4 mb-4">
                <?php if (has_post_thumbnail()) : ?>
                    <img src="<?php the_post_thumbnail_url('medium'); ?>" class="rounded-top border border-secondary h-50 border-0" alt="<?php the_title(); ?>">
                <?php endif; ?>
                <div class="card-body border border-top-0 border-secondary">
                    <h5 class="card-title"><?php the_title(); ?></h5>
                    <div class="row">
                        <p class="card-text col-md-6">Number of Floors: <?php echo $number_of_floors ?></p>
                        <p class="card-text col-md-6">Eco Rating: <?php echo $eco_rating; ?></p>
                        <p class="card-text col-md-6">Rooms: <?php echo $number_of_rooms; ?></p>
                        <p class="card-text col-md-6">Balcony: <?php echo $balcony; ?></p>
                        <p class="card-text col-md-6">Bathroom: <?php echo $bathroom; ?></p>
                        <p class="card-text col-md-6">Building type: <?php echo $building_type; ?></p>
                    </div>
                    <div class="mt-3">
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p class="text-center">No real estate listings found.</p>
    <?php endif;

    wp_reset_postdata();
    die();
}
add_action('wp_ajax_filter_real_estate', 'filter_real_estate');
add_action('wp_ajax_nopriv_filter_real_estate', 'filter_real_estate');

