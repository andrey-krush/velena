<?php 

class Account_Page {

    public static function init() {
        add_action( 'acf/init', [__CLASS__, 'acf_add_local_field_group'] );
        add_action( 'template_redirect', [__CLASS__, 'redirect_to_edit_account' ] );
        add_action( 'template_redirect', [__CLASS__, 'redirect_to_homepage' ]);
        add_filter( 'woocommerce_account_menu_items', [__CLASS__, 'custom_my_account_menu_items' ] );

        add_action('init', [__CLASS__, 'pll_strings']);

        add_action( 'wp_ajax_nopriv_edit_account' ,[ __CLASS__, 'ajaxEditAccountHandler' ] );
        add_action( 'wp_ajax_edit_account', [ __CLASS__, 'ajaxEditAccountHandler' ] );
        
    } 
   
    public static function pll_strings() {

        pll_register_string('account-1', 'Персональні дані', 'account' );
        pll_register_string('account-2', 'Контактні дані', 'account' );
        pll_register_string('account-3', 'Заміна паролю', 'account' );
        pll_register_string('account-4', 'Старий пароль', 'account' );
        pll_register_string('account-5', 'Новий пароль', 'account' );
        pll_register_string('account-6', 'Новий пароль повторно', 'account' );
        pll_register_string('account-7', 'Зберегти зміни', 'account' );
        pll_register_string('account-8', 'Налаштування акаунту', 'account' );
        pll_register_string('account-9', 'Кабінет клієнта', 'account' );
        pll_register_string('account-10', 'Історія замовлень', 'account' );
        pll_register_string('account-11', 'Вихід', 'account' );
        pll_register_string('account-12', 'Замовлення', 'account' );
        pll_register_string('account-13', 'Ваші дані успішно збережені', 'account' );

        pll_register_string('error-1', 'Користувач з таким емейлом вже є', 'errors' );
        pll_register_string('error-2', 'Паролі не співпадають', 'errors' );
        pll_register_string('error-3', 'Користувача з даним емейлом не існує', 'errors' );
        pll_register_string('error-4', 'Введено невірний пароль', 'errors' );
        
      
    }

    public static function ajaxEditAccountHandler() {
        $billing_first_name = $_POST['billing_first_name'];
        $billing_last_name = $_POST['billing_last_name'];
        $billing_company = $_POST['billing_company'];
        $edrpou = $_POST['edrpou'];
        $billing_phone = $_POST['billing_phone'];
        $billing_email = $_POST['billing_email'];
        $area = $_POST['area'];
        $billing_postcode = $_POST['billing_postcode'];
        $billing_city = $_POST['billing_city'];
        $billing_address_1 = $_POST['billing_address_1'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $new_password_again = $_POST['new_password_again'];

        $user = wp_get_current_user();

        $user_id = $user->ID;
        $hash = $user->user_pass;

        $profile_first_name = get_user_meta($user_id, 'billing_first_name', true);
        $profile_last_name = get_user_meta($user_id, 'billing_last_name', true);
        $profile_address_1 = get_user_meta($user_id, 'billing_address_1', true);
        $profile_postcode = get_user_meta($user_id, 'billing_postcode', true);
        $profile_city = get_user_meta($user_id, 'billing_city', true);
        $profile_state = get_user_meta($user_id, 'billing_state', true);
        $profile_phone = get_user_meta($user_id, 'billing_phone', true);
        $profile_email = get_user_meta($user_id, 'billing_email', true);
        $profile_company = get_user_meta($user_id, 'billing_company', true);
        $profile_edrpou = get_user_meta( $user_id, 'edrpou', true );
   

        if ( $profile_address_1 != $billing_address_1 ) {
            update_user_meta( $user_id, 'billing_address_1', $billing_address_1 );
        }
        if ( $profile_postcode != $billing_postcode ) {
            update_user_meta( $user_id, 'billing_postcode', $billing_postcode );
        }
        if ( $profile_city != $billing_city ) {
            update_user_meta( $user_id, 'billing_city', $billing_city );
        }
        if ( $profile_state != $area ) {
            update_user_meta( $user_id, 'billing_state', $area );
        }
        if ( $profile_phone != $billing_phone ) {
            update_user_meta( $user_id, 'billing_phone', $billing_phone );
        }
        if ( $profile_email != $billing_email ) {
            $user_by_email = get_user_by('email',$billing_email);
            if (empty($user_by_email)) {
                update_user_meta( $user_id, 'billing_email', $billing_email );
                global $wpdb;
                $wpdb->update(
                    $wpdb->users,
                    array( 'user_email' => $billing_email ),
                    array( 'ID' => $user_id )
                );
            } else {
                $error = pll__('Користувач з таким емейлом вже є');
                wp_send_json_error( ['name'=>'billing_email', 'error'=>$error], 400 );
            }
        }
        if ( $profile_company != $billing_company ) {
            update_user_meta( $user_id, 'billing_company', $billing_company );
        }
        if ( $profile_first_name != $billing_first_name ) {
            update_user_meta( $user_id, 'billing_first_name', $billing_first_name );
        }
        if ( $profile_last_name != $billing_last_name ) {
            update_user_meta( $user_id, 'billing_last_name', $billing_last_name );
        }
        if ( $profile_edrpou != $edrpou ) {
            update_field('edrpou', $edrpou, 'user_' . $user_id);  
        }

        if ( !empty( $old_password ) ) {
            if ( wp_check_password( $old_password, $hash ) ) {
                if(isset($new_password) && $new_password != '') {
                    if ($new_password == $new_password_again) {
                        wp_set_password($new_password, $user->ID);
                        $creds['user_login'] = $user->user_email;
                        $creds['user_password'] = $new_password;
                        $creds['remember'] = true;
                        $user = wp_signon($creds, false);
                    } else {
                        $error = pll__('Паролі не співпадають');
                        wp_send_json_error(['name' => 'new_password_again', 'error' => $error], 400);
                    }
                }
            } else {
                $error = pll__('Введено невірний пароль');
                    wp_send_json_error( ['name'=>'old_password', 'error'=>$error], 400 );
            }
        }
         

    }

    public static function redirect_to_homepage() {
        if ( ! is_user_logged_in() && is_account_page() ) {
            wp_redirect(home_url());
            exit();
        } 
    }

    public static function redirect_to_edit_account(){
        
        if( is_account_page() && empty( WC()->query->get_current_endpoint() ) ){
            wp_safe_redirect( wc_get_account_endpoint_url( 'edit-account' ) );
            exit;
        }
        
    }

    public static function custom_my_account_menu_items( $items ) {
    unset($items['edit-address']);
    unset($items['dashboard']);
    unset($items['downloads']);
    unset($items['customer-logout']);
    return $items;
    }

    public static function acf_add_local_field_group() {

        if ( function_exists('acf_add_local_field_group') ):

            

        endif;    
            
    }
}