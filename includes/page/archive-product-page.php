<?php

class Archive_Product_Page {

    public static function init()
    {
//        add_action('acf/init', [__CLASS__, 'acf_add_local_field_group']);
        add_action('init', [__CLASS__, 'pll_strings']);
        add_action('wp_ajax_nopriv_update_filters', [__CLASS__, 'filtration']);
        add_action('wp_ajax_update_filters', [__CLASS__, 'filtration']);
    }

    public static function pll_strings()
    {

        pll_register_string('category-1', 'Призначення', 'category');
        pll_register_string('category-2', 'Застосувати', 'category');
        pll_register_string('category-3', 'Фільтр', 'category');
        pll_register_string('category-4', 'Скинути всі', 'category');
        pll_register_string('category-5', 'Ціна', 'category');
        pll_register_string('category-6', 'Головна', 'category');
        pll_register_string('category-7', 'Показано', 'category');
        pll_register_string('category-8', 'із', 'category');
        pll_register_string('category-9', 'Надаємо своїм клієнтам високоякісну продукцію, виготовлену з дотриманням міжнародних стандартів. Ми тісно співпрацюємо у створенні оптимального дизайну банок та пляшок на ранніх стадіях процесу розробки нового продукту.', 'category', true);
        pll_register_string('category-10', 'Відвантаження продукції провадиться з нашого складу, розташованого в м.Київ, або безпосередньо зі скляних заводів. Здійснюємо доставку до всіх регіонів України за допомогою транспортних та поштових компаній.', 'category', true);
        pll_register_string('category-10', 'Призначення продукту', 'category', true);
        pll_register_string('category-10', 'Тип продукту', 'category', true);
        pll_register_string('category-11', 'Нічого не знайдено', 'category');
        pll_register_string('category-11', 'Ціна договірна', 'category');

    }

    public static function filtration() {
//        wp_reset_query();
        unset($_GET['action']);

        $taxonomy = $_GET['taxonomy'];
        $slug = $_GET['slug'];

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 16,
        ];

        $args['tax_query'] = [
            [
                'taxonomy' => $_GET['taxonomy'],
                'field' => 'slug',
                'terms' => $_GET['slug']
            ]
        ];

        unset($_GET['taxonomy']);
        unset($_GET['slug']);

        $add_args = $_GET;
        if( isset( $_GET['appointment_array'] ) and !empty( $_GET['appointment_array'] ) ) :

            $args['tax_query']['relation'] = 'AND';
            $appointment_array = $_GET['appointment_array'];

            $args['tax_query'][] = [
                'taxonomy' => 'appointment',
                'field' => 'slug',
                'terms' => $appointment_array
            ];

            unset($_GET['appointment_array']);

        endif;

        if( isset( $_GET['type_array'] ) and !empty( $_GET['type_array'] ) ) :

            $args['tax_query']['relation'] = 'AND';

            $args['tax_query'][] = [
                'taxonomy' => 'type',
                'field' => 'slug',
                'terms' => $_GET['type_array']
            ];

            unset($_GET['type_array']);

        endif;


        if( isset( $_GET['type'] ) and !empty( $_GET['type'] ) ) :

            $args['tax_query']['relation'] = 'AND';

            $args['tax_query'][] = [
                'taxonomy' => 'type',
                'field' => 'slug',
                'terms' => $_GET['type']
            ];

            unset($_GET['type']);

        endif;

        $meta_query = ['relation' => 'AND'];


        if( !empty($_GET['price_from']) ) :
            $meta_query[] = [
                'key' => '_price',
                'value' => array( $_GET['price_from'], $_GET['price_to'] ),
                'compare' => 'BETWEEN',
                'type' => 'numeric'
            ];

            unset($_GET['price_from']);
            unset($_GET['price_to']);
        endif;

        if (!empty($_GET['orderby'])) :

            if ($_GET['orderby'] == 'price') :
                remove_all_actions('pre_get_posts');
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'ASC';
            elseif ($_GET['orderby'] == 'price-desc') :
                remove_all_actions('pre_get_posts');
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'DESC';
            endif;

            $_GET['orderby1'] = $_GET['orderby'];
            unset($_GET['orderby']);

        endif;

        unset($_GET['orderby']);

        if( !empty( $_GET ) ) :
            foreach ( $_GET as $key => $item) :
                $array = ['relation' => 'OR'];

                if ( !empty( $item ) and ( $key !== 'orderby1' ) ) {

                    if( str_contains($key, '_from') ) :

                        $key = str_replace('_from', '', $key);

                        $array = [
                            'key' => 'characteristics_' . $key . '_value',
                            'value' => [$_GET[$key . '_from'], $_GET[$key . '_to']],
                            'type'    => 'numeric',
                            'compare' => 'BETWEEN',
                        ];

                    else :
                        foreach ( $item as $subitem ) :
                            $array[] = [
                                'key' => 'characteristics_' . $key,
                                'value' => $subitem,
//                                'compare' => 'LIKE'
                            ];

                        endforeach;
                    endif;

                    $meta_query[] = $array;
                }
                

            endforeach;
        endif;

        $args['meta_query'] = $meta_query;

        $new_query = new WP_Query($args);
        ob_start();
        ?>

        <?php if( $new_query->have_posts() ) : ?>
            
            <div class="product-list__cards">
                <?php while( $new_query->have_posts() ) : $new_query->the_post();

                    (new Type_Products())->render_product_article(get_the_ID());

                endwhile; ?>
            </div>
            
            <?php  
            if ( $new_query->max_num_pages > 1 ) {
                $paginationHtml = str_replace( 'wp-admin/admin-ajax.php',$taxonomy . '/' . $slug ,paginate_links(array(
                    'total' => $new_query->max_num_pages,
                    'current' => 1,
                    'prev_next' => false,
                    'end_size' => 1,
                    'mid_size' => 1,
                    'add_args' => $add_args
                )));
            } else {
                $paginationHtml = '';
            }
            if(!empty($paginationHtml)){ ?>
                <div class="product-list__pagination pagination">
                    <?php echo $paginationHtml; ?>
                </div>
            <?php } ?>
        <?php else: ?>
            <div class="product-list__nothing">
                <p><?php pll_e('Нічого не знайдено'); ?></p>
            </div>
        <?php endif;
        
        $wrapper = ob_get_clean();
        
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $posts_per_page = 16;
        
        if( $posts_per_page >= $new_query->found_posts ) :
            $count_posts = pll__('Показано') . ' 1 – '. $new_query->found_posts . ' ' . pll__('із') . ' ' . $new_query->found_posts;
        else :
            if( $paged == 1 ) :
                $count_posts = pll__('Показано') . ' 1 – '. $posts_per_page . ' ' . pll__('із') . ' ' . $new_query->found_posts;
            elseif( $paged == $new_query->max_num_pages ) :
                $count_posts = pll__('Показано') . ' ' . $posts_per_page * ( $paged - 1 ) + 1 . ' – ' . $new_query->found_posts . ' ' .  pll__('із') . ' ' . $new_query->found_posts;
            else :
                $count_posts = pll__('Показано') . ' ' . $posts_per_page * ( $paged - 1 ) + 1 . ' – ' . $posts_per_page * $paged . ' ' . pll__('із') . ' ' . $new_query->found_posts;
            endif;
        endif;
        
        wp_send_json_success( array( 'wrapper' => $wrapper, 'count_posts' => $count_posts ) );
        die;
    }

}