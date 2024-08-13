<?php

class Wholesale_Cart_Success_Page {

    public static function init() {
        add_action('init', [__CLASS__, 'pll_strings']);
        add_action('init', [__CLASS__, 'acf_add_local_field_group']);
    }

    public static function pll_strings() {

        pll_register_string('wholesalecart-1', 'Оптове замовлення', 'wholesalecart' );
        pll_register_string('wholesalecart-2', 'Натисніть сюди, щоб увійти', 'wholesalecart' );
        pll_register_string('wholesalecart-3', 'Вже замовляли у нас?', 'wholesalecart' );


    }
    public static function get_url() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'wholesale-cart-succes-page.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? get_the_permalink( $page[ 0 ]->ID ) : false;
    }

    public static function acf_add_local_field_group() {

        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_656dcdc63bedd',
                'title' => 'Wholesale success',
                'fields' => array(
                    array(
                        'key' => 'field_656dcdc61ef30',
                        'label' => 'Content',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_656dcdd91ef31',
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
                                'key' => 'field_656dcde41ef32',
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
                                'key' => 'field_656dcde91ef33',
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
                                'new_lines' => 'wpautop',
                            ),
                            array(
                                'key' => 'field_656dce061ef34',
                                'label' => 'Link',
                                'name' => 'link',
                                'aria-label' => '',
                                'type' => 'link',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => 'array',
                                'translations' => 'copy_once',
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'page_template',
                            'operator' => '==',
                            'value' => 'wholesale-cart-succes-page.php',
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