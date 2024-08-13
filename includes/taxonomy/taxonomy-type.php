<?php

class Taxonomy_Type {
	

	public static function init() {

        add_action( 'init', [ __CLASS__, 'register_taxonomy' ] );
        add_action( 'acf/init', [__CLASS__, 'acf_add_local_field_group'] );
        
	}

    public static function register_taxonomy() {

        register_taxonomy( 'type','product', array(
            "label" => "Тип продукту",
            "singular_label" => "Тип продукту",
            'show_ui' => true,
            'show_admin_column' => true,
            "hierarchical" => true,
            'publicly_queryable'    => true,
            'show_in_rest'          => true,
        ));

    }

    public static function acf_add_local_field_group() {

        if (function_exists('acf_add_local_field_group')):

            acf_add_local_field_group(array(
                'key' => 'group_64fad9f06c52a',
                'title' => 'Taxonomy type',
                'fields' => array(
                    array(
                        'key' => 'field_64fad9f261b93',
                        'label' => 'Main settings',
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
                        'key' => 'field_64fada527b676',
                        'label' => '',
                        'name' => 'main_settings',
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
                                'key' => 'field_64fada617b677',
                                'label' => 'Header image',
                                'name' => 'header_image',
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
                            ),
                            array(
                                'key' => 'field_64asr21dqwq217b677',
                                'label' => 'Catalog image',
                                'name' => 'catalog_image',
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
                            ),
                            array(
                                'key' => 'field_64fasf2sada617b677',
                                'label' => 'Catalog description',
                                'name' => 'catalog_description',
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
                                'library' => 'all',
                                'new_lines' => 'br',
                                'translations' => 'copy_once',
                                'min_width' => '',
                                'min_height' => '',
                                'min_size' => '',
                                'max_width' => '',
                                'max_height' => '',
                                'max_size' => '',
                                'mime_types' => '',
                                'preview_size' => 'medium',
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'taxonomy',
                            'operator' => '==',
                            'value' => 'type',
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

            acf_add_local_field_group(array(
                'key' => 'group_65245452d809d',
                'title' => 'Characteristics',
                'fields' => array(
                    array(
                        'key' => 'field_65245455fc472',
                        'label' => 'Category characteristics',
                        'name' => 'category_characteristics',
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
                                'key' => 'field_65245639fc473',
                                'label' => 'Characteristic type',
                                'name' => 'characteristic_type',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    '' => NULL,
                                ),
                                'default_value' => '',
                                'return_format' => 'label',
                                'translations' => 'copy_once',
                                'allow_null' => 0,
                                'other_choice' => 0,
                                'layout' => 'vertical',
                                'save_other_choice' => 0,
                                'parent_repeater' => 'field_65245455fc472',
                            ),
                            array(
                                'key' => 'field_652d5517496a61',
                                'label' => 'Show in filtration?',
                                'name' => 'show_in_filtration',
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
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                                'parent_repeater' => 'field_65245455fc472',
                            ),
                            array(
                                'key' => 'field_652d551743296a61',
                                'label' => 'Show on product card?',
                                'name' => 'is_on_card',
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
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                                'parent_repeater' => 'field_65245455fc472',
                            ),
                            array(
                                'key' => 'field_652d321551743296a61',
                                'label' => 'Show on top of product page?',
                                'name' => 'is_on_product_page',
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
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                                'parent_repeater' => 'field_65245455fc472',
                            ),
                            array(
                                'key' => 'field_64b3250ceb6b1be',
                                'label' => 'Image',
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
                                'parent_repeater' => 'field_65245455fc472',
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'taxonomy',
                            'operator' => '==',
                            'value' => 'type',
                        ),
                    ),
                    array(
                        array(
                            'param' => 'taxonomy',
                            'operator' => '==',
                            'value' => 'appointment',
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
