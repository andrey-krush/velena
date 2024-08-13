<?php

class Taxonomy_Appointment {
	

	public static function init() {

        add_action( 'init', [ __CLASS__, 'register_taxonomy' ] );
        add_action( 'acf/init', [__CLASS__, 'acf_add_local_field_group'] );
        
	}

    public static function register_taxonomy() {

        register_taxonomy( 'appointment','product', array(
            "hierarchical" => true,
            "label" => "Призначення продукту",
            "singular_label" => "Призначення продукту",
            'show_ui' => true,
            'show_admin_column' => true,
            'rewrite' => array(
                'slug' => 'purpose',
                'with_front' => false
            ),
            ));

    }

    public static function acf_add_local_field_group() {

        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_64b50c395f81b',
                'title' => 'Taxonomy appointment',
                'fields' => array(
                    array(
                        'key' => 'field_64b50c396b1bb',
                        'label' => 'Main page settings',
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
                        'key' => 'field_64b50c746b1bc',
                        'label' => '',
                        'name' => 'main_page_settings',
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
                                'key' => 'field_64b50c926b1bd',
                                'label' => 'Main image',
                                'name' => 'main_image',
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
                                'key' => 'field_64b50asf2ceb6b1be',
                                'label' => 'Left image',
                                'name' => 'left_image',
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
                                'key' => 'field_64b50ceb6b1be',
                                'label' => 'Right image',
                                'name' => 'right_image',
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
                                'key' => 'field_647asf2sc5d10d8255',
                                'label' => 'Offset left image',
                                'name' => 'offset_left_image',
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
                                'rows' => 2,
                                'placeholder' => '',
                                'new_lines' => 'br',
                                'parent_repeater' => 'field_647c5cf1d8254',
                            ),
                            array(
                                'key' => 'field_647asf2asf2ssc5d10d8255',
                                'label' => 'Offset right image',
                                'name' => 'offset_right_image',
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
                                'rows' => 2,
                                'placeholder' => '',
                                'new_lines' => 'br',
                                'parent_repeater' => 'field_647c5cf1d8254',
                            ),
                        ),
                    ),
                ),
                'location' => array(
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

            acf_add_local_field_group(array(
                'key' => 'group_6560518a764a7',
                'title' => 'Texts under products on category page',
                'fields' => array(
                    array(
                        'key' => 'field_6560518a4557d',
                        'label' => 'Text on left side',
                        'name' => 'text_on_left_side',
                        'aria-label' => '',
                        'type' => 'wysiwyg',
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
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 0,
                        'delay' => 0,
                    ),
                    array(
                        'key' => 'field_656051b44557e',
                        'label' => 'Text on right side',
                        'name' => 'text_on_right_side',
                        'aria-label' => '',
                        'type' => 'wysiwyg',
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
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 0,
                        'delay' => 0,
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
