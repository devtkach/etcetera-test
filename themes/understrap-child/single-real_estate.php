<?php get_header(); ?>

<div class="container my-5">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="card shadow-lg border-0 mb-5">
            <div class="row g-0">
                <div class="col-md-6">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="real-estate-image">
                            <?php the_post_thumbnail('large', array('class' => 'img-fluid w-100 rounded-start')); ?>
                        </div>
                    <?php else : ?>
                        <img src="https://via.placeholder.com/600x400" class="img-fluid w-100 rounded-start"
                             alt="No Image Available">
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <div class="card-body">
                        <h1 class="card-title display-5 mb-4"><?php the_title(); ?></h1>

                        <?php
                        $number_of_floors = get_field('number_of_floors');
                        $eco_rating = get_field('eco_rating');
                        $building_type = get_field('building_type');
                        $premises = get_field('premises');
                        $area = isset($premises['area']) ? $premises['area'] : 'N/A';
                        $image_id = isset($premises['image']) ? $premises['image'] : null;
                        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : 'https://via.placeholder.com/600x400';
                        $number_of_rooms = isset($premises['number_of_rooms']) ? $premises['number_of_rooms'] : 'N/A';
                        $balcony = isset($premises['balcony']) && $premises['balcony'] === 'yes' ? 'Yes' : 'No';
                        $bathroom = isset($premises['bathroom']) && $premises['bathroom'] === 'yes' ? 'Yes' : 'No';
                        ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Number of Floors:</strong> <?php echo $number_of_floors ? $number_of_floors : 'N/A'; ?></p>
                                <p><strong>Building Type:</strong> <?php echo $building_type ? $building_type : 'N/A'; ?></p>
                                <p><strong>Eco Rating:</strong>
                                    <span>
                                        <?php echo $eco_rating ? $eco_rating : 'N/A'; ?>
                                    </span>
                                </p>
                                <p><strong>Area (sq.m):</strong> <?php echo $area; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Number of Rooms:</strong> <?php echo $number_of_rooms; ?></p>
                                <p><strong>Balcony:</strong> <?php echo $balcony; ?></p>
                                <p><strong>Bathroom:</strong> <?php echo $bathroom; ?></p>
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <img src="<?php echo esc_url($image_url); ?>" class="img-fluid rounded shadow"
                                 style="max-width: 80%; height: auto;" alt="Premises Image">
                        </div>

                        <div class="mb-4">
                            <?php $location = get_field('location_coordinates'); ?>
                            <p><strong>Location Coordinates:</strong> <?php echo $location ? $location : 'N/A'; ?></p>
                            <?php if ($location) : ?>
                                <p><a href="https://www.google.com/maps/search/?api=1&query=<?php echo $location; ?>"
                                      class="btn btn-primary" target="_blank">View on Google Maps</a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-5">
            <div>
                <?php
                $prev_post = get_previous_post(); // Get the previous post object
                if ($prev_post) {
                    echo '<a href="' . get_permalink($prev_post->ID) . '" class="btn btn-outline-primary px-4 py-2">« Previous Building</a>';
                }
                ?>
            </div>
            <div>
                <?php
                $next_post = get_next_post(); // Get the next post object
                if ($next_post) {
                    echo '<a href="' . get_permalink($next_post->ID) . '" class="btn btn-outline-primary px-4 py-2">Next Building »</a>';
                }
                ?>
            </div>
        </div>


    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
