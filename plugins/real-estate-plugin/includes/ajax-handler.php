<?php
function real_estate_filter_ajax()
{
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    if (isset($_POST['reset']) && $_POST['reset'] == 'true'):
        $args = array(
            'post_type' => 'real_estate',
            'posts_per_page' => 5,
            'paged' => $paged,
        );
    else:
        $number_of_floors = sanitize_text_field($_POST['number_of_floors']);
        $building_type = sanitize_text_field($_POST['building_type']);
        $eco_rating = sanitize_text_field($_POST['eco_rating']);
        $number_of_rooms = sanitize_text_field($_POST['number_of_rooms']);
        $balcony = sanitize_text_field($_POST['balcony']);
        $bathroom = sanitize_text_field($_POST['bathroom']);

        $meta_query = array('relation' => 'AND');

        if (!empty($number_of_floors)) {
            $meta_query[] = array(
                'key' => 'number_of_floors',
                'value' => $number_of_floors,
                'compare' => '='
            );
        }

        if (!empty($building_type)) {
            $meta_query[] = array(
                'key' => 'building_type',
                'value' => $building_type,
                'compare' => '='
            );
        }

        if (!empty($eco_rating)) {
            $meta_query[] = array(
                'key' => 'eco_rating',
                'value' => $eco_rating,
                'compare' => '='
            );
        }

        if (!empty($number_of_rooms)) {
            $meta_query[] = array(
                'key' => 'premises_number_of_rooms',
                'value' => $number_of_rooms,
                'compare' => '='
            );
        }

        if (!empty($balcony)) {
            $meta_query[] = array(
                'key' => 'premises_balcony',
                'value' => $balcony,
                'compare' => '='
            );
        }

        if (!empty($bathroom)) {
            $meta_query[] = array(
                'key' => 'premises_bathroom',
                'value' => $bathroom,
                'compare' => '='
            );
        }

        $args = array(
            'post_type' => 'real_estate',
            'posts_per_page' => 5,
            'paged' => $paged,
            'meta_query' => $meta_query,
        );
    endif;

    $query = new WP_Query($args);

    if ($query->have_posts()):
        while ($query->have_posts()):
            $query->the_post();
            $number_of_floors = get_field('number_of_floors');
            $eco_rating = get_field('eco_rating');
            $premises = get_field('premises');
            $number_of_rooms = $premises['number_of_rooms'];
            $balcony = $premises['balcony'];
            $bathroom = $premises['bathroom'];
            ?>
            <div class="card border-0 col-md-4 mb-4">
                <?php if (has_post_thumbnail()) : ?>
                    <img src="<?php the_post_thumbnail_url('medium'); ?>"
                         class=" rounded-top border border-secondary h-50 border-0"
                         alt="<?php the_title(); ?>">
                <?php endif; ?>
                <div class="card-body border border-top-0 border-secondary">
                    <h5 class="card-title"><?php the_title(); ?></h5>
                    <div class="row">
                        <p class="card-text col-md-6 col-mb-4">Number of Floors: <?php echo $number_of_floors; ?></p>
                        <p class="card-text col-md-6 col-mb-4">Eco Rating: <?php echo $eco_rating ?></p>
                        <p class="card-text col-md-6 col-mb-4">Rooms: <?php echo $number_of_rooms; ?></p>
                        <p class="card-text col-md-6 col-mb-4">Balcony: <?php echo $balcony ?></p>
                        <p class="card-text col-md-6 col-mb-4">Bathroom: <?php echo $bathroom; ?></p>
                    </div>
                    <div class="mt-3">
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

        <?php $total_pages = $query->max_num_pages;
        if ($total_pages > 1): ?>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $total_pages; $i++):
                    if ($i == $paged) :
                        echo '<button class="ajax-initial-page-link page-link active" disabled data-page="' . $i . '">' . $i . '</button>';
                    else:
                        echo '<button class="ajax-initial-page-link page-link" data-page="' . $i . '">' . $i . '</button>';
                    endif;
                endfor;
                ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <p>No results found.</p>
    <?php endif;
    wp_die();
}

add_action('wp_ajax_real_estate_filter', 'real_estate_filter_ajax');
add_action('wp_ajax_nopriv_real_estate_filter', 'real_estate_filter_ajax');


function real_estate_pagination_ajax()
{
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $args = array(
        'post_type' => 'real_estate',
        'posts_per_page' => 5,
        'paged' => $paged,
    );
    $query = new WP_Query($args);

    if ($query->have_posts()):
        while ($query->have_posts()):
            $query->the_post();
            $number_of_floors = get_field('number_of_floors');
            $eco_rating = get_field('eco_rating');
            $premises = get_field('premises');
            $number_of_rooms = $premises['number_of_rooms'];
            $balcony = $premises['balcony'];
            $bathroom = $premises['bathroom'];
            ?>
            <div class="card border-0 col-md-4 mb-4">
                <?php if (has_post_thumbnail()) : ?>
                    <img src="<?php the_post_thumbnail_url('medium'); ?>"
                         class=" rounded-top border border-secondary h-50 border-0"
                         alt="<?php the_title(); ?>">
                <?php endif; ?>
                <div class="card-body border border-top-0 border-secondary">
                    <h5 class="card-title"><?php the_title(); ?></h5>
                    <div class="row">
                        <p class="card-text col-md-6 col-mb-4">Number of Floors: <?php echo $number_of_floors; ?></p>
                        <p class="card-text col-md-6 col-mb-4">Eco Rating: <?php echo $eco_rating ?></p>
                        <p class="card-text col-md-6 col-mb-4">Rooms: <?php echo $number_of_rooms; ?></p>
                        <p class="card-text col-md-6 col-mb-4">Balcony: <?php echo $balcony ?></p>
                        <p class="card-text col-md-6 col-mb-4">Bathroom: <?php echo $bathroom; ?></p>
                    </div>
                    <div class="mt-3">
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        <?php $total_pages = $query->max_num_pages;
        if ($total_pages > 1): ?>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $total_pages; $i++):
                    if ($i == $paged) :
                        echo '<button class="ajax-initial-page-link page-link active" disabled data-page="' . $i . '">' . $i . '</button>';
                    else:
                        echo '<button class="ajax-initial-page-link page-link" data-page="' . $i . '">' . $i . '</button>';
                    endif;
                endfor; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        </div><p>No results found.</p>
    <?php endif;
    wp_die();
}

add_action('wp_ajax_real_estate_pagination', 'real_estate_pagination_ajax');
add_action('wp_ajax_nopriv_real_estate_pagination', 'real_estate_pagination_ajax');
