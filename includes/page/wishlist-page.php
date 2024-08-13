<?php

class Wishlist_Page {

    public static function init() {

        add_action( 'wp_ajax_nopriv_like_product' ,[ __CLASS__, 'productLikeHandler' ] );
        add_action( 'wp_ajax_like_product', [ __CLASS__, 'productLikeHandler' ] );

        add_action( 'init', [ __CLASS__, 'acf_add_local_field_group' ] );
        add_action( 'init', [ __CLASS__, 'register_strings' ] );
        add_action( 'save_post', [ __CLASS__, 'send_mail_on_stock_change' ] );
    }

    public static function get_url() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'wishlist-page.php',
        ]);
       
        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? get_the_permalink( $page[ 0 ]->ID ) : false;
    }

    public static function register_strings() {

        pll_register_string('wishlist-1', 'Обране', 'wishlist');
        pll_register_string('wishlist-2', 'В обраному нічого немає', 'wishlist');
        pll_register_string('wishlist-3', 'До вподобань', 'wishlist');
        pll_register_string('wishlist-4', 'Кількість товарів в обраному:', 'wishlist');
        pll_register_string('wishlist-5', 'Товар знову у продажу', 'wishlist');
        pll_register_string('wishlist-6', 'Вітаємо!', 'wishlist');
        pll_register_string('wishlist-7', "Повідомляємо, що відмічений вами товар знову з'явився у продажу:", 'wishlist');

    }

    public static function productLikeHandler() {
        $favorite_text = 'В обраному :count';
        $favorite_text_products = 'товар,товари,товарів';
        $favorite_text_products = explode(',', $favorite_text_products);
        $post_id = $_POST['product_id'];
        $is_liked = $_POST['is_liked'];
        $liked_count = 0;
        $post_language = pll_get_post_language($post_id);
        

        foreach( pll_languages_list() as $item ) :
                    
            if( $item != $post_language ) : 

                $translated_post = pll_get_post( $post_id, $item );

                if( !empty( $post ) ) : 
                    $posts[$item] = $translated_post;
                endif;
                    
            endif;

        endforeach; 

        $posts[$post_language] = $post_id;

        if( is_user_logged_in() ) :

            $user = wp_get_current_user();
            $user_liked_posts = get_user_meta( $user->data->ID, 'liked_posts_' . $post_language );
            
            if( $is_liked == 'true' ) :
                $liked_count = count($user_liked_posts) + 1;
                
                if( in_array( $post_id, $user_liked_posts ) ) : 

                    wp_send_json_error();

                endif;

                add_user_meta( $user->data->ID, 'primary_posts', $post_id );

                foreach( $posts as $key => $item ) : 

                    add_user_meta( $user->data->ID, 'liked_posts_' . $key, $item );

                endforeach;

            else :

                $liked_count = count($user_liked_posts) - 1;
                delete_user_meta( $user->data->ID, 'primary_posts', $item );

                foreach( $posts as $key => $item ) : 

                    delete_user_meta( $user->data->ID, 'liked_posts_' . $key, $item );

                endforeach;

            endif;
        else :
            if(!isset($_COOKIE['wishlist_products_' . pll_current_language()]) || empty($_COOKIE['wishlist_products_' . pll_current_language()])){
                $liked_count = $is_liked == 'true' ? 1 : 0;
                setcookie('wishlist_products_' . pll_current_language(), json_encode([]), time() + (30 * 86400), '/');
            } else {
                $liked_count = count(json_decode(stripslashes($_COOKIE['wishlist_products_' . pll_current_language()]), true)) + ($is_liked == 'true' ? 1 : -1);
            }
            if( $is_liked == 'true' ) :
                
                foreach ( pll_languages_list() as $key => $item ) :

                    $wishlist = isset($_COOKIE['wishlist_products_' . $item]) ? json_decode(stripslashes($_COOKIE['wishlist_products_' . $item]), true) : [];

                    if( !empty( $posts[$item] ) and !in_array( $posts[$item], $wishlist ) ) :
                        $wishlist[] = $posts[$item];
                    endif;
                    setcookie('wishlist_products_' . $item, json_encode($wishlist), time() + (30 * 86400), '/');
                    
                    
                endforeach;
                
            else :
                foreach ( pll_languages_list() as $key => $item ) :

                    $wishlist = isset($_COOKIE['wishlist_products_' . $item]) ? json_decode(stripslashes($_COOKIE['wishlist_products_' . $item]), true) : [];

                    if( in_array( $posts[$item], $wishlist ) ) :
                        $post_key = array_search($posts[$item], $wishlist);
                        unset($wishlist[$post_key]);
                    endif;

                    setcookie('wishlist_products_' . $item, json_encode($wishlist), time() + (30 * 86400), '/');

                endforeach;
    
                
            endif;

        endif;
    
        if($liked_count>0) {
            $favorite_text_products = pll__('Кількість товарів в обраному:') . ' ' . $liked_count;
        } else {
            $favorite_text_products = '';
        }
        
        wp_send_json_success(['liked_text'=>$favorite_text_products, 'is_liked'=>$is_liked]);
    }

    public static function send_mail_on_stock_change( $post_ID ) {

        $post = get_post( $post_ID );

        if( $post->post_type == 'product' ) :

            $product = wc_get_product($post_ID);

            $previous_stock_status = get_post_meta($post_ID, 'previous_stock_status', true);
            $stock_status = $product->get_stock_status();

            if( $stock_status == 'instock' and ( $previous_stock_status == 'outofstock' ) ) :

                $users_liked_post = get_users(array(
                    'fields' => 'user_email',
                    'number' => 999999,
                    'meta_query' => array(
                        array(
                            'key' => 'primary_posts',
                            'value' => $post_ID,
                            'compare' => 'LIKE'
                        )
                    )
                ));

                $post_language = pll_get_post_language( $post_ID );

                $headers = array('Content-Type: text/html; charset=UTF-8');

                $subject = pll_translate_string( 'Товар знову у продажу', $post_language );

                $message = pll_translate_string( 'Вітаємо!', $post_language) . '<br>';
                $message .= pll_translate_string( "Повідомляємо, що відмічений вами товар знову з'явився у продажу:", $post_language) . '<br>';
                $message .= get_the_permalink($post_ID);

                foreach ( $users_liked_post as $item ) :

                    wp_mail( $item, $subject, $message, $headers );

                endforeach;

            endif;

            update_post_meta($post_ID, 'previous_stock_status', $stock_status);

        endif;


    }

    public static function acf_add_local_field_group() {

        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_64bed99b1ced3',
                'title' => 'Wishlist page',
                'fields' => array(
                    array(
                        'key' => 'field_64bed99c7f68d',
                        'label' => '',
                        'name' => 'content',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_64bed9b97f68e',
                                'label' => 'Title',
                                'name' => 'title',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'translations' => 'translate',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_64bed9be7f68f',
                                'label' => 'Subtitle',
                                'name' => 'subtitle',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'translations' => 'translate',
                                'maxlength' => '',
                                'rows' => 2,
                                'placeholder' => '',
                                'new_lines' => 'br',
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'page_template',
                            'operator' => '==',
                            'value' => 'wishlist-page.php',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ));
            
        endif;
            
    }


}