<?php

class Foropt_Page {

    public static function init() {
        add_action( 'acf/init', [__CLASS__, 'acf_add_local_field_group'] );
        add_action('init', [__CLASS__, 'pll_strings']);
    }
   
    public static function get_url() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'foropt-page.php',
        ]);
       
        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? get_the_permalink( $page[ 0 ]->ID ) : false;
    }

    public static function get_ID() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'foropt-page.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? $page[ 0 ]->ID : false;
    }

    public static function pll_strings() {

        pll_register_string('form-1', 'Ваше ім’я', 'form' );
        pll_register_string('form-2', 'Ваша електронна адреса', 'form' );        
        pll_register_string('form-3', 'Ваш меседж', 'form' );
        pll_register_string('form-4', 'Кількість товару', 'form' );
        pll_register_string('form-5', 'Креслення товару', 'form' );
        pll_register_string('form-6', 'Обрати файл', 'form' );
        pll_register_string('form-7', 'Надіслати', 'form' );
        pll_register_string('form-8', 'Напишіть нам', 'form' );
        pll_register_string('form-9', 'Дякуємо за ваше звернення! Ми якнайшвидше його розглянемо та дамо вам нашу відповідь', 'form', true );
        pll_register_string('form-10', 'Повідомлення надіслано', 'form' );
        pll_register_string('form-11', 'The E-mail must be a valid email address.', 'form' );
        pll_register_string('form-12', 'E-mail is required.', 'form' );
        pll_register_string('form-13', 'Паролі не співпадають', 'form' );
        pll_register_string('form-14', 'This field is required', 'form' );
        pll_register_string('form-15', 'This field is invalid', 'form' );
        pll_register_string('form-16', 'Allowed extensions: ', 'form' );
        pll_register_string('form-17', 'Maximum file size is', 'form' );
        pll_register_string('form-18', 'First Name is required', 'form' );
        pll_register_string('form-19', 'Last Name is required.', 'form' );
        pll_register_string('form-20', 'Postcode is required.', 'form' );
        pll_register_string('form-21', 'Language is required.', 'form' );
        pll_register_string('form-22', 'City is required.', 'form' );
        pll_register_string('form-23', 'Telephone is required.', 'form' );
        pll_register_string('form-24', 'Мінімальна кількість символів 8', 'form' );
        pll_register_string('form-25', 'Заяку на відновлення паролю надіслано!', 'form' );
        pll_register_string('form-26', 'Для продовження дій перейдіть за посилання у листі, який надійшов на вашу пошту', 'form' );
        pll_register_string('form-27', 'Пароль змінено', 'form' );
        pll_register_string('form-28', 'Вітаємо пароль успішно змінено', 'form' );


    }

    public static function acf_add_local_field_group() {

        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_647c411e91b50',
                'title' => 'Foropt page',
                'fields' => array(
                    array(
                        'key' => 'field_647c411f0ebcb',
                        'label' => 'Promo section',
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
                        'key' => 'field_647c412f0ebcc',
                        'label' => '',
                        'name' => 'promo_section',
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
                                'key' => 'field_647c413d0ebcd',
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
                                'key' => 'field_647c414e0ebce',
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
                                'rows' => 3,
                                'placeholder' => '',
                                'new_lines' => 'br',
                            ),
                            array(
                                'key' => 'field_647c415c0ebcf',
                                'label' => 'Background image',
                                'name' => 'background_image',
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
                        ),
                    ),
                    array(
                        'key' => 'field_647c41b20ebd0',
                        'label' => 'Advantages section',
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
                        'key' => 'field_647c41c00ebd1',
                        'label' => '',
                        'name' => 'advantages_section',
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
                                'key' => 'field_647c41c80ebd2',
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
                                'key' => 'field_647c41cf0ebd3',
                                'label' => 'Subtitle',
                                'name' => 'subtitle',
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
                                'key' => 'field_647c41d50ebd4',
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
                            ),
                            array(
                                'key' => 'field_647c41de0ebd5',
                                'label' => 'Advantages',
                                'name' => 'advantages',
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
                                        'key' => 'field_647c41e80ebd6',
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
                                        'parent_repeater' => 'field_647c41de0ebd5',
                                    ),
                                    array(
                                        'key' => 'field_647c41f70ebd7',
                                        'label' => 'Text',
                                        'name' => 'text',
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
                                        'parent_repeater' => 'field_647c41de0ebd5',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_647c42b10ebd8',
                        'label' => 'Find block',
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
                        'key' => 'field_647c42be0ebd9',
                        'label' => '',
                        'name' => 'find_block',
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
                                'key' => 'field_647c42c80ebda',
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
                                'key' => 'field_647c42ce0ebdb',
                                'label' => 'Subtitle',
                                'name' => 'subtitle',
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
                                'key' => 'field_647c42d80ebdc',
                                'label' => 'Button',
                                'name' => 'button',
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
                            array(
                                'key' => 'field_647c42e30ebdd',
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
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_647c43230ebde',
                        'label' => 'Contact us block',
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
                        'key' => 'field_647c43300ebdf',
                        'label' => '',
                        'name' => 'contact_us_block',
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
                                'key' => 'field_647c43410ebe0',
                                'label' => 'TItle',
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
                                'key' => 'field_647c43da0ebe1',
                                'label' => 'Subtitle',
                                'name' => 'subtitle',
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
                                'key' => 'field_647c43f50ebe2',
                                'label' => 'Text inside of form',
                                'name' => 'text_inside_of_form',
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
                            array(
                                'key' => 'field_648590cff1811',
                                'label' => 'Emails to send form',
                                'name' => 'emails',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => 'Fill it like this: email1, email2, email3',
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
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'page_template',
                            'operator' => '==',
                            'value' => 'foropt-page.php',
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