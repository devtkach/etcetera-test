<?php
class Real_Estate_Query {

    public function __construct() {
        add_action('pre_get_posts', [$this, 'modify_query']);
    }

    public function modify_query($query) {
        if (!is_admin() && $query->is_main_query() && $query->is_post_type_archive('real_estate')) {
            $query->set('meta_key', 'eco_rating');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
        }
    }
}
