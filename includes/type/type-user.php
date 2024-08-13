<?php 

class Type_User {

    public static function init() {

        add_action('acf/init', [__CLASS__, 'acf_add_local_field_group']);

    }

    public static function acf_add_local_field_group() {
        
        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_648a187122e46',
                'title' => 'User',
                'fields' => array(
                    // array(
                    //     'key' => 'field_648a18725c3cd',
                    //     'label' => 'Company name / ФОП',
                    //     'name' => 'company_name',
                    //     'aria-label' => '',
                    //     'type' => 'text',
                    //     'instructions' => '',
                    //     'required' => 0,
                    //     'conditional_logic' => 0,
                    //     'wrapper' => array(
                    //         'width' => '',
                    //         'class' => '',
                    //         'id' => '',
                    //     ),
                    //     'default_value' => '',
                    //     'translations' => 'translate',
                    //     'maxlength' => '',
                    //     'placeholder' => '',
                    //     'prepend' => '',
                    //     'append' => '',
                    // ),
                    array(
                        'key' => 'field_648a18915c3ce',
                        'label' => 'EDRPOU',
                        'name' => 'edrpou',
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
                'location' => array(
                    array(
                        array(
                            'param' => 'user_form',
                            'operator' => '==',
                            'value' => 'edit',
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