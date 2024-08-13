<?php

class Type_Post {

    public static function init() {
        add_action('init', [__CLASS__, 'pll_strings']);
        add_action('acf/init', [__CLASS__, 'acf_add_local_field_group']);
    }

    public static function pll_strings() {

        pll_register_string('post-1', 'Повернутися до списку публікацій', 'post' );
        pll_register_string('post-2', 'Публікації', 'post' );        
        pll_register_string('post-3', 'Усі публікації', 'post' );

    }

    public static function acf_add_local_field_group() {
        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_645be404ecd2c',
                'title' => 'Post',
                'fields' => array(
                    array(
                        'key' => 'field_645be4059f5a0',
                        'label' => 'Post',
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
                        'key' => 'field_645be4119f5a1',
                        'label' => '',
                        'name' => 'post',
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
                                'key' => 'field_645be4189f5a2',
                                'label' => 'Gallery',
                                'name' => 'gallery',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'layout' => 'table',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_645be4239f5a3',
                                        'label' => '',
                                        'name' => 'image',
                                        'aria-label' => '',
                                        'type' => 'image',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'url',
                                        'library' => 'all',
                                        'translations' => 'copy_once',
                                        'min_width' => '',
                                        'min_height' => '',
                                        'min_size' => '',
                                        'max_width' => '',
                                        'max_height' => '',
                                        'max_size' => '',
                                        'mime_types' => '',
                                        'preview_size' => 'medium',
                                        'parent_repeater' => 'field_645be4189f5a2',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'post',
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