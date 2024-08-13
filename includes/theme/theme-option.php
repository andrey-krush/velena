<?php

class Theme_Option {
    public static function init() {
        add_action( 'acf/init', [ __CLASS__, 'acf_add_options_page' ] );
        add_action( 'acf/init', [ __CLASS__, 'acf_add_local_field_group' ] );
        add_action( 'acf/init', [ __CLASS__, 'acf_add_local_field_group_attributes' ] );

        add_action('acf/save_post', [__CLASS__, 'addNeededOptionsMeta']);

        add_action('edit_type', [__CLASS__, 'addNeededRangeMetaToTerm'], 10, 1);
        add_action('edit_appointment', [__CLASS__, 'addNeededRangeMetaToTerm'], 10, 1);

    }


    public static function acf_add_options_page() {
        if ( ! function_exists('acf_add_options_page') ) return;

        acf_add_options_page( [
            'page_title' => 'Сторінка опцій',
            'menu_title' => 'Сторінка опцій',
            'menu_slug' => 'theme-options',
            'redirect' => false,
        ]);

    }

    public static function addNeededRangeMetaToTerm($term_id) {

        $characteristics = get_field('category_characteristics', 'term_' . $term_id);
        $term_language = pll_get_term_language($term_id);
        $range_attributes = get_option('range_attributes_' . $term_language);
        $term_range_attributes = [];

        foreach ( $characteristics as $item ) :

            if( !empty( $range_attributes ) and in_array($item['characteristic_type'], $range_attributes) ) :

                $term_range_attributes[] = $item['characteristic_type'];

            endif;

        endforeach;

        update_term_meta($term_id, 'term_range_attributes', $term_range_attributes);

    }

    public static function addNeededOptionsMeta( ) {

        $screen = get_current_screen();

        if( $screen->id == 'toplevel_page_theme-options' ) :

            $languages = pll_languages_list();
            $attributes = get_field('attributes', 'option');

            foreach ( $languages as $language ) :

                $range_meta_attributes[$language] = get_option('range_attributes_' . $language);
                $range_meta_attributes[$language] = !empty( $range_meta_attributes[$language] ) ? $range_meta_attributes[$language] : [];

            endforeach;

            foreach ( $attributes as $item ) :

                if( $item['is_range_slider'] ) :

                    foreach ( $languages as $language ) :

                        if( !in_array($item['attribute_name_' . $language], $range_meta_attributes[$language]) ) :

                            $range_meta_attributes[$language][] = $item['attribute_name_' . $language];

                        endif;

                        $key_name = str_replace(' ', '', $item['attribute_name_' . $language]);
                        update_option('measurement_' . $key_name   , $item['unit_of_measurement_' . $language]);

                    endforeach;

                else :

                    foreach ( $languages as $language ) :

                        if( !empty( $item['attribute_values_' . $language] ) ) :

                            $key_name = str_replace(' ', '', $item['attribute_name_' . $language]);
                            update_option('correct_values_order_' . $key_name , $item['attribute_values_' . $language]);

                        endif;

                    endforeach;

                endif;

            endforeach;

            foreach ( $languages as $language ) :

                update_option('range_attributes_' . $language, $range_meta_attributes[$language]);

            endforeach;



        endif;

    }

    public static function acf_add_local_field_group() {

        /** Autogenerated by ACF */
        if ( ! function_exists('acf_add_local_field_group') ) return;

        $args = array(
            'key' => 'group_6490d4699d40d',
            'title' => 'Theme option',
            'fields' => array(
                array(
                    'key' => 'field_6490d46a53580',
                    'label' => 'Cart option',
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
                    'key' => 'field_6490d4a853581',
                    'label' => '',
                    'name' => 'cart_option',
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
                            'key' => 'field_6490d4b553582',
                            'label' => 'The minimum amount in cart',
                            'name' => 'the_minimum_amount_in_cart',
                            'aria-label' => '',
                            'type' => 'number',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'translations' => 'copy_once',
                            'min' => '',
                            'max' => '',
                            'placeholder' => '',
                            'step' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_659094545a651',
                    'label' => 'Order account status',
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
                    'key' => 'field_6590946a5a652',
                    'label' => '',
                    'name' => 'order_account_status',
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
                            'key' => 'field_6590asf294cd5a658',
                            'label' => 'Pending',
                            'name' => 'pending',
                            'aria-label' => '',
                            'type' => 'color_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'enable_opacity' => 0,
                            'return_format' => 'string',
                            'translations' => 'copy_once',
                        ),
                        array(
                            'key' => 'field_6590947e5a653',
                            'label' => 'Cancelled',
                            'name' => 'cancelled',
                            'aria-label' => '',
                            'type' => 'color_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'enable_opacity' => 0,
                            'return_format' => 'string',
                            'translations' => 'copy_once',
                        ),
                        array(
                            'key' => 'field_659094935a654',
                            'label' => 'On hold',
                            'name' => 'on_hold',
                            'aria-label' => '',
                            'type' => 'color_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'enable_opacity' => 0,
                            'return_format' => 'string',
                            'translations' => 'copy_once',
                        ),
                        array(
                            'key' => 'field_659094a85a655',
                            'label' => 'Processing',
                            'name' => 'processing',
                            'aria-label' => '',
                            'type' => 'color_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'enable_opacity' => 0,
                            'return_format' => 'string',
                            'translations' => 'copy_once',
                        ),
                        array(
                            'key' => 'field_659094b55a656',
                            'label' => 'Сompleted',
                            'name' => 'completed',
                            'aria-label' => '',
                            'type' => 'color_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'enable_opacity' => 0,
                            'return_format' => 'string',
                            'translations' => 'copy_once',
                        ),
                        array(
                            'key' => 'field_659094c15a657',
                            'label' => 'Refunded',
                            'name' => 'refunded',
                            'aria-label' => '',
                            'type' => 'color_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'enable_opacity' => 0,
                            'return_format' => 'string',
                            'translations' => 'copy_once',
                        ),
                        array(
                            'key' => 'field_659094cd5a658',
                            'label' => 'Failed',
                            'name' => 'failed',
                            'aria-label' => '',
                            'type' => 'color_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'enable_opacity' => 0,
                            'return_format' => 'string',
                            'translations' => 'copy_once',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-options',
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
        );

        foreach ( pll_languages_list() as $key => $item ) :

            $args['fields'][] = array(
                'key' => 'field_6490d12346a53580' . $key,
                'label' => 'Months ' . $item,
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
            );

            $args['fields'][] = array(
                'key' => 'field_65363858c64de' . $key,
                'label' => '',
                'name' => 'month_' . $item,
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
                        'key' => 'field_6536386fc64df',
                        'label' => 'January',
                        'name' => 'month_1',
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
                        'key' => 'field_65363876c64e0',
                        'label' => 'February',
                        'name' => 'month_2',
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
                        'key' => 'field_6536387dc64e1',
                        'label' => 'March',
                        'name' => 'month_3',
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
                        'key' => 'field_65363885c64e2',
                        'label' => 'April',
                        'name' => 'month_4',
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
                        'key' => 'field_6536388dc64e3',
                        'label' => 'May',
                        'name' => 'month_5',
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
                        'key' => 'field_65363894c64e4',
                        'label' => 'June',
                        'name' => 'month_6',
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
                        'key' => 'field_6536389bc64e5',
                        'label' => 'July',
                        'name' => 'month_7',
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
                        'key' => 'field_653638a1c64e6',
                        'label' => 'August',
                        'name' => 'month_8',
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
                        'key' => 'field_653638b4c64e7',
                        'label' => 'September',
                        'name' => 'month_9',
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
                        'key' => 'field_653638bbc64e8',
                        'label' => 'October',
                        'name' => 'month_10',
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
                        'key' => 'field_653638c3c64e9',
                        'label' => 'November',
                        'name' => 'month_11',
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
                        'key' => 'field_653638cac64ea',
                        'label' => 'December',
                        'name' => 'month_12',
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
                ),
            );

        endforeach;

        $args['fields'][] = array(
            'key' => 'field_6490123123d46a53580',
            'label' => 'Local pickup options',
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
        );

        foreach ( pll_languages_list() as $key => $item ) :

            $args['fields'][] = [
                'key' => 'field_6564612315a6d5ef' . $key,
                'label' => 'Local Pickup Options ' . $item,
                'name' => 'local_pickup_options_' . $item,
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
                'button_label' => 'Додати рядок',
                'rows_per_page' => 20,
                'sub_fields' => array(
                    array(
                        'key' => 'field_6512364618e6d5f1',
                        'label' => '',
                        'name' => 'option',
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
                        'parent_repeater' => 'field_6564612315a6d5ef' . $key,
                    ),
                ),
            ];

        endforeach;

        $args['fields'][] = array(
            'key' => 'field_656461476d5ee',
            'label' => 'Wholesale statutes',
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
        );

		$wholesale_options = array(
            'key' => 'field_6564615a6d5ef',
            'label' => '',
            'name' => 'wholesale_options',
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
            'button_label' => 'Додати рядок',
            'rows_per_page' => 20,
            'sub_fields' => array(
                array(
                    'key' => 'field_6564618e6d5f1',
                    'label' => 'Text color',
                    'name' => 'text_color',
                    'aria-label' => '',
                    'type' => 'color_picker',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'enable_opacity' => 0,
                    'return_format' => 'string',
                    'translations' => 'copy_once',
                    'parent_repeater' => 'field_6564615a6d5ef',
                ),
            ),
        );

        foreach ( pll_languages_list() as $key => $item ) :

            $wholesale_options['sub_fields'][] =  array(
                'key' => 'field_656461826d5f0' . $key,
                'label' => 'Status text ' . $item,
                'name' => 'status_text_' . $item,
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
                'parent_repeater' => 'field_6564615a6d5ef',
            );

        endforeach;

        $args['fields'][] = $wholesale_options;

        acf_add_local_field_group($args);
        
    }

    public static function acf_add_local_field_group_attributes() {

        $languages = pll_languages_list();
        $args = array(
            'key' => 'group_652452ecdac45',
            'title' => 'Attributes',
            'fields' => array(
                array(
                    'key' => 'field_652452ef84382',
                    'label' => 'Attributes',
                    'name' => 'attributes',
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
                    'layout' => 'block',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_660c60716fed6',
                            'label' => 'Range slider?',
                            'name' => 'is_range_slider',
                            'aria-label' => '',
                            'type' => 'true_false',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '',
                            'default_value' => 0,
                            'translations' => 'copy_once',
                            'ui' => 0,
                            'ui_on_text' => '',
                            'ui_off_text' => '',
                            'parent_repeater' => 'field_652452ef84382',
                        )
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-options',
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
        );


        foreach ( pll_languages_list() as $key => $item ) :


            $args['fields'][0]['sub_fields'][] = array(
                'key' => 'field_6524535a84383' . $key,
                'label' => 'Attribute name ' . $item,
                'name' => 'attribute_name_' . $item,
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
                'parent_repeater' => 'field_652452ef84382',
            );

            $args['fields'][0]['sub_fields'][] = array(
                'key' => 'field_6524537a84384' . $key+1,
                'label' => 'Attribute values ' . $item,
                'name' => 'attribute_values_' . $item,
                'aria-label' => '',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_660c60716fed6',
                            'operator' => '!=',
                            'value' => '1',
                        ),
                    ),
                ),
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
                        'key' => 'field_6524538a84385',
                        'label' => 'Value',
                        'name' => 'value',
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
                        'parent_repeater' => 'field_6524537a84384' . $key+1,
                    ),
                ),
                'parent_repeater' => 'field_652452ef84382',
            );

            $args['fields'][0]['sub_fields'][] = [
                'key' => 'field_660c60c16fed7' . $key + 1,
                'label' => 'Unit of measurement ' . $item,
                'name' => 'unit_of_measurement_' . $item,
                'aria-label' => '',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_660c60716fed6',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
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
                'parent_repeater' => 'field_652452ef84382',
            ];

        endforeach;

        acf_add_local_field_group($args);

    }
}
