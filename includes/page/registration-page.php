<?php

class Registration_Page {

    public static function init() {

        add_action( 'wp_ajax_nopriv_register_form' ,[ __CLASS__, 'ajaxRegistrationHandler' ] );
        add_action( 'wp_ajax_register_form', [ __CLASS__, 'ajaxRegistrationHandler' ] );

        add_action( 'wp_ajax_nopriv_login' ,[ __CLASS__, 'ajaxLoginHandler' ] );
        add_action( 'wp_ajax_login', [ __CLASS__, 'ajaxLoginHandler' ] );

        add_action( 'wp_ajax_nopriv_lost_password' ,[ __CLASS__, 'ajaxLostPasswordHandler' ] );
        add_action( 'wp_ajax_lost_password', [ __CLASS__, 'ajaxLostPasswordHandler' ] );

        add_action( 'wp_ajax_nopriv_reset_password' ,[ __CLASS__, 'ajaxResetPasswordHandler' ] );
        add_action( 'wp_ajax_reset_password', [ __CLASS__, 'ajaxResetPasswordHandler' ] );

        add_action('init', [__CLASS__, 'pll_strings']);
        add_action( 'acf/init', [__CLASS__, 'acf_add_local_field_group'] );

    }


    public static function get_url() {
        
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'registration-page.php',
        ]);
       
        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? get_the_permalink( $page[ 0 ]->ID ) : false;
    }

    public static function get_ID() {

        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'registration-page.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? $page[ 0 ]->ID : false;
    }

    public static function pll_strings() {

        pll_register_string('register-1', 'E-mail адреса', 'register' );
        pll_register_string('register-2', 'Пароль', 'register' );
        pll_register_string('register-3', 'Ім’я', 'register' );
        pll_register_string('register-4', 'Прізвище', 'register' );
        pll_register_string('register-5', 'Телефон', 'register' );
        pll_register_string('register-6', 'Якщо ви є представником компанії / ФОП — заповніть, будь ласка, поля:', 'register' );
        pll_register_string('register-7', 'Назва компанії / ФОП', 'register' );
        pll_register_string('register-8', 'ЄДРПОУ', 'register' );
        pll_register_string('register-9', 'Адресу вашого розташування буде використано при доставці кур’єром', 'register' );
        pll_register_string('register-10', 'Область', 'register' );
        pll_register_string('register-11', 'Індекс', 'register' );
        pll_register_string('register-12', 'Місто', 'register' );
        pll_register_string('register-13', 'Вулиця, дім, квартира, офіс, тощо', 'register' );
        pll_register_string('register-14', 'Створити акаунт', 'register' );
        pll_register_string('register-15', 'Успішна реєстрація', 'register' );
        pll_register_string('register-16', 'Ви успішно зареєструвались на ', 'register' );

        pll_register_string('register-17', 'Заявку на реєстрацію оформлено', 'register' );
        pll_register_string('register-18', 'Дякуємо за вашу заявку! Ми якнайшвидше розглянемо вашу заявку. Чекайте на імейл про резульнати на вашій електронній поштовій скринці', 'register' );


        pll_register_string('reset-1', 'Відновлення паролю', 'reset' );
        pll_register_string('reset-2', 'Для відновлення паролю перейдіть за посиланням - ', 'reset' );
        pll_register_string('reset-3', 'Відновлення паролю', 'reset' );
        pll_register_string('reset-4', 'Введіть новий пароль', 'reset' );
        pll_register_string('reset-5', 'Зберегти', 'reset' );


        pll_register_string('login-1', 'Вхід у кабінет', 'login-popup' );
        pll_register_string('login-2', 'Новий клієнт? Створити', 'login-popup' );
        pll_register_string('login-3', 'новий акаунт', 'login-popup' );
        pll_register_string('login-4', 'Логін', 'login-popup' );
        pll_register_string('login-4', 'Пароль', 'login-popup' );
        pll_register_string('login-4', 'Забули пароль?', 'login-popup' );
        pll_register_string('login-4', 'Увійти', 'login-popup' );

        pll_register_string('lost-1', 'Відновлення паролю', 'lost-password' );
        pll_register_string('lost-2', 'Введіть свою електрону пошту, яку ви вказували при реєстрації', 'lost-password' );
        pll_register_string('lost-3', 'Відправити лист', 'lost-password' );
       
    }

    public static function ajaxResetPasswordHandler() {
        $pass_new = $_POST['new_password'];
        $pass_new_repeat = $_POST['new_password_again'];
        $email = base64_decode($_POST['email']);
        $email = explode('-',$email)[1];
        $user = get_user_by( 'email', $email );

        if ( empty( $user->ID ) ) {
            $error = pll__('Користувача з даним емейлом не існує');
            wp_send_json_error( $error, 400 );
        }

        if ( $pass_new != $pass_new_repeat ) {
            $error = pll__('Паролі не співпадають');
            wp_send_json_error( $error, 400 );
        }

        wp_set_password( $pass_new, $user->ID );
    }

    public static function ajaxLostPasswordHandler() {
        $email = $_POST['email'];

        $user = get_user_by( 'email', $email );

        if ( !empty( $user->ID ) ) {
            
            $resetLink = home_url().'?confirm='.base64_encode('encrypted-'.$email).'#reset_password';
            $subject = pll__('Відновлення паролю');
            $message = pll__('Для відновлення паролю перейдіть за посиланням - '). $resetLink;

            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($user->user_email, $subject, $message, $headers);
        } else {
            $error = pll__('Користувача з даним емейлом не існує');
            wp_send_json_error( $error, 400 );
        }
    }

    public static function ajaxLoginHandler() {
        $email = $_POST['login'];  
        $password = $_POST['password'];    

        if ( !get_user_by_email( $email ) ) {
            $error = pll__('Користувача з даним емейлом не існує');
            wp_send_json_error( $error, 400 );
        }
        
        $login_data = array();  
        $login_data['user_login'] = $email;  
        $login_data['user_password'] = $password;  
        $login_data['remember'] = true;  
              
        $login_user = wp_signon( $login_data, true ); 
        if ( !empty( $login_user->errors ) ) {
            $error = pll__('Введено невірний пароль');
            wp_send_json_error( $error, 400 );
        }   

        $user = get_user_by_email( $email );


        foreach( pll_languages_list() as $item ) :

            if( !empty( $_COOKIE['wishlist_products_' . $item] ) ) :

                foreach( json_decode($_COOKIE['wishlist_products_' . $item], true) as $subitem ) :

                    add_user_meta( $user->data->ID, 'liked_posts_' . $item, $subitem );

                endforeach;

            endif;

        endforeach;
 
        foreach( pll_languages_list() as $item ) :
            delete_user_meta($user->data->ID, 'wholesale_products_' . $item);
            
            if( !empty( $_COOKIE['wholesale_products_' . $item] ) ) :
                foreach( json_decode(stripslashes($_COOKIE['wholesale_products_' . $item]),true) as $subitem ) :
                    add_user_meta( $user->data->ID, 'wholesale_products_' . $item, $subitem );

                endforeach;

            endif;

        endforeach;
        if( $_POST['is_popup'] ) :
            wp_send_json_success( [ 'redirect_url' =>  $_POST['link'] ], 200 );
        else :
            wp_send_json_success( [ 'redirect_url' =>  wc_get_page_permalink( 'myaccount' ) ], 200 );
        endif;

        die;
    }

    public static function ajaxRegistrationHandler() {

        $email = $_POST['Email'];
        $password = $_POST['Password'];
        $password_again = $_POST['Password_again'];
        $name = $_POST['Name'];
        $surname = $_POST['Surname'];
        $phone = $_POST['Phone'];
        $company_name = $_POST['Name_company'];
        $edrpou = $_POST['EDRPOU'];
        $area = $_POST['Area'];
        $index = $_POST['Index'];
        $city = $_POST['City'];
        $home = $_POST['Home'];

        if ( $password != $password_again ) {
            $error = pll__('Паролі не співпадають');
            wp_send_json_error( ['name' => 'Password_again', 'error' => $error], 400 );
        }
        if ( get_user_by_email( $email ) ) {
            $error = pll__('Користувач з таким емейлом вже є');
            wp_send_json_error( ['name' => 'Email', 'error' => $error], 400 );
        }

        $userdata = array(

            'user_pass'  => $password,  
            'user_login' => $email,     
            'user_email' => $email,   
            'first_name' => $name,
            'last_name'  => $surname,
            
        );


        $user_id = wp_insert_user( $userdata );


        
        update_user_meta( $user_id, 'billing_address_1', $home );
        update_user_meta( $user_id, 'billing_postcode', $index );
        update_user_meta( $user_id, 'billing_city', $city );
        update_user_meta( $user_id, 'billing_state', $area );
        update_user_meta( $user_id, 'billing_phone', $phone );
        update_user_meta( $user_id, 'billing_email', $email );
        update_user_meta( $user_id, 'billing_first_name', $name );
        update_user_meta( $user_id, 'billing_last_name', $surname );
        update_user_meta( $user_id, 'billing_company', $company_name );
        

        update_field('edrpou', $edrpou, 'user_' . $user_id);    
        
        $headers = 'content-type: text/html';
        $message = pll__('Ви успішно зареєструвались на ') . home_url();
        wp_mail( $email, pll__('Успішна реєстрація'), $message, $headers );
                
        wp_send_json_success();
        die;

    }

    public static function acf_add_local_field_group() {

        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_648a10ab7ffae',
                'title' => 'Register page',
                'fields' => array(
                    array(
                        'key' => 'field_648a10ac30446',
                        'label' => 'Areas',
                        'name' => 'areas',
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
                                'key' => 'field_648a1113rxsa0bb30447',
                                'label' => 'Code',
                                'name' => 'code',
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
                                'parent_repeater' => 'field_648a10ac30446',
                            ),
                            array(
                                'key' => 'field_648a10bb30447',
                                'label' => 'Name',
                                'name' => 'item',
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
                                'parent_repeater' => 'field_648a10ac30446',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_648a10cd30448',
                        'label' => 'Privacy policy text',
                        'name' => 'privacy_policy_text',
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
                        'media_upload' => 1,
                        'delay' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'page_template',
                            'operator' => '==',
                            'value' => 'registration-page.php',
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