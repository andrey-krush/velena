<?php

class Search_Page {

    public static function init() {

        add_action('init', [__CLASS__, 'pll_strings']);

        add_action('wp_ajax_search', [__CLASS__, 'search']);
        add_action('wp_ajax_nopriv_search', [__CLASS__, 'search']);

    }

    public static function get_url() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'search-page.php',
        ]);
       
        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? get_the_permalink( $page[ 0 ]->ID ) : false;
    }

    public static function search() {

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'numberposts' => 5,
        );

        if( isset( $_POST['search'] ) and !empty( $_POST['search'] ) ) :

            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'title_for_search',
                    'value' => $_POST['search'],
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'product_code',
                    'value' => $_POST['search'],
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'description_for_search',
                    'value' => $_POST['search'],
                    'compare' => 'LIKE'
                ),
            );

        endif;

        $posts = get_posts($args);

        if ( !empty( $posts ) ) :

            foreach( $posts as $item ) : ?>

                <li>
                    <a href="<?php echo get_the_permalink($item->ID); ?>" class="header__search-item">
                    <?php $thumbnail_id = get_post_thumbnail_id( $item->ID);
                    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                        <img src="<?php echo get_the_post_thumbnail_url($item->ID); ?>" alt="<?php echo $alt; ?>">
                            <span><?php echo get_the_title($item->ID); ?></span>
                    </a>
                </li>

        <?php endforeach;
        else:
            echo pll__('Немає результатів пошуку для'). ': <b>' . $_POST['search'] . '</b>';
        endif;

        die;
    }
    public static function pll_strings() {

        pll_register_string('search-1', 'Результати пошуку', 'search' );
        pll_register_string('search-2', 'Повернутися до каталогу продукції', 'search' );
        pll_register_string('search-3', 'Немає результатів пошуку для', 'search' );
        pll_register_string('search-3', 'Кількість товарів у списку:', 'search' );

    }

}