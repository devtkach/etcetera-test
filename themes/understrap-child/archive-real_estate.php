<?php get_header(); ?>

<div class="container my-5">
    <h1 class="text-center">Our Real Estate Listings</h1>
    <div class="mb-4 text-center">
        <?php
        $active_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        $terms = get_terms(array(
            'taxonomy' => 'region',
            'hide_empty' => true,
        ));

        if (!empty($terms) && !is_wp_error($terms)) :
            echo '<div class="btn-group" role="group" aria-label="Category Filter">';
            echo '<button type="button" class="btn filter ' . ($active_filter === 'all' ? 'btn-primary active' : 'btn-secondary') . '" data-filter="all">All</button>';
            foreach ($terms as $term) :
                echo '<button type="button" class="btn filter ' . ($active_filter === $term->slug ? 'btn-primary active' : 'btn-secondary') . '" data-filter="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</button>';
            endforeach;
            echo '</div>';
        endif;
        ?>

    </div>

    <div class="row" id="real-estate-listings">
        <?php if (have_posts()) : while (have_posts()) : the_post();
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
                    <img src="<?php the_post_thumbnail_url('medium'); ?>"
                         class="rounded-top border border-secondary h-50 border-0"
                         alt="<?php the_title(); ?>">
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
        <?php endwhile; else : ?>
            <p class="text-center">No real estate listings found.</p>
        <?php endif; ?>
    </div>

<!--    <div class="row">-->
<!--        <div class="col-12">-->
<!--            <nav aria-label="Page navigation">-->
<!--                <ul class="pagination justify-content-center mt-4">-->
<!--                    <li class="page-item">-->
<!--                        --><?php //if (get_previous_posts_link()) : ?>
<!--                            <a class="page-link" href="--><?php //echo get_previous_posts_page_link(); ?><!--">« Previous</a>-->
<!--                        --><?php //else : ?>
<!--                            <span class="page-link disabled">« Previous</span>-->
<!--                        --><?php //endif; ?>
<!--                    </li>-->
<!--                    <li class="page-item">-->
<!--                        --><?php //if (get_next_posts_link()) : ?>
<!--                            <a class="page-link" href="--><?php //echo get_next_posts_page_link(); ?><!--">Next »</a>-->
<!--                        --><?php //else : ?>
<!--                            <span class="page-link disabled">Next »</span>-->
<!--                        --><?php //endif; ?>
<!--                    </li>-->
<!--                </ul>-->
<!--            </nav>-->
<!---->
<!--        </div>-->
<!--    </div>-->
</div>

<?php get_footer(); ?>
