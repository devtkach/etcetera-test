<?php
/**
 * Plugin Name: Real Estate Plugin
 * Description: Плагін для управління об'єктами нерухомості з використанням власного post type та таксономії.
 * Version: 1.0
 * Author: Mykyta Tkach
 * Text Domain: real-estate-plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/post-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-real-estate-query.php';

function real_estate_enqueue_scripts()
{
    wp_enqueue_script('real-estate-filter-js', plugin_dir_url(__FILE__) . 'assets/js/real-estate-filter.js', array('jquery'), '1.0', true);

    wp_localize_script('real-estate-filter-js', 'real_estate_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}

add_action('wp_enqueue_scripts', 'real_estate_enqueue_scripts');

function real_estate_filter_shortcode()
{
    ob_start();
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'real_estate',
        'posts_per_page' => 5,
        'paged' => $paged,
    );

    $query = new WP_Query($args);
    ?>
    <div class="container mt-4">
        <form id="real-estate-filter" class="bg-light p-4 rounded">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="number_of_floors" class="form-label">Number of Floors:</label>
                    <select name="number_of_floors" id="number_of_floors" class="form-select">
                        <option value="">Any</option>
                        <?php for ($i = 1; $i <= 20; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="building_type" class="form-label">Building Type:</label>
                    <select name="building_type" id="building_type" class="form-select">
                        <option value="">Any</option>
                        <option value="panel">Panel</option>
                        <option value="brick">Brick</option>
                        <option value="foam_block">Foam Block</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="eco_rating" class="form-label">Eco-friendliness (1-5):</label>
                    <select name="eco_rating" id="eco_rating" class="form-select">
                        <option value="">Any</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <h4 class="mt-4">Premises</h4>
            <div class="row align-items-center">

                <div class="col-md-4 mb-3">
                    <label for="number_of_rooms" class="form-label">Number of Rooms:</label>
                    <select name="number_of_rooms" id="number_of_rooms" class="form-select">
                        <option value="">Any</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Balcony:</label>
                    <div>
                        <label><input type="radio" name="balcony" value="yes"> Yes</label>
                        <label><input type="radio" name="balcony" value="no" checked> No</label>
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Bathroom:</label>
                    <div>
                        <label><input type="radio" name="bathroom" value="yes"> Yes</label>
                        <label><input type="radio" name="bathroom" value="no" checked> No</label>
                    </div>
                </div>
            </div>
            <div class="my-3 d-flex gap-3">
                <button type="button" id="real-estate-filter-submit" class="btn btn-primary">Filter</button>
                <button type="button" id="real-estate-filter-reset" class="btn btn-secondary">Reset</button>
            </div>
        </form>

        <div id="real-estate-results" class="mt-4 row">

            <?php if ($query->have_posts()) : ?>
                <div class="row">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="card border-0 col-md-4 mb-4">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('medium'); ?>"
                                     class="rounded-top border border-secondary h-50 border-0"
                                     alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <div class="card-body border border-top-0 border-secondary">
                                <h5 class="card-title"><?php the_title(); ?></h5>
                                <div class="row">
                                    <p class="card-text col-md-6">Number of
                                        Floors: <?php the_field('number_of_floors'); ?></p>
                                    <p class="card-text col-md-6">Eco Rating: <?php the_field('eco_rating'); ?></p>
                                    <p class="card-text col-md-6">
                                        Rooms: <?php the_field('premises_number_of_rooms'); ?></p>
                                    <p class="card-text col-md-6">Balcony: <?php the_field('premises_balcony'); ?></p>
                                    <p class="card-text col-md-6">Bathroom: <?php the_field('premises_bathroom'); ?></p>
                                    <p class="card-text col-md-6">Building
                                        type: <?php the_field('building_type'); ?></p>
                                </div>
                                <div class="mt-3">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="pagination">
                    <?php
                    $total_pages = $query->max_num_pages;
                    for ($i = 1; $i <= $total_pages; $i++):
                        if ($i == $paged) :
                            echo '<button class="ajax-initial-page-link page-link active" disabled data-page="' . $i . '">' . $i . '</button>';
                        else:
                            echo '<button class="ajax-initial-page-link page-link" data-page="' . $i . '">' . $i . '</button>';
                        endif;
                    endfor;
                    ?>
                </div>
            <?php else: ?>
                <p>No properties found.</p>
            <?php endif;
            wp_reset_postdata(); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('real_estate_filter', 'real_estate_filter_shortcode');


class Real_Estate_Filter_Widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'real_estate_filter_widget',
            __('Real Estate Filter', 'text_domain'),
            array('description' => __('A widget to filter real estate objects', 'text_domain'))
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo '<h3 class="widget-title">' . __('Filter Real Estate Objects', 'text_domain') . '</h3>';
        echo do_shortcode('[real_estate_filter]');
        echo $args['after_widget'];
    }
}

function register_real_estate_filter_widget()
{
    register_widget('Real_Estate_Filter_Widget');
}

add_action('widgets_init', 'register_real_estate_filter_widget');

new Real_Estate_Query();
