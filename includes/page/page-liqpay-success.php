<?php 

class Page_Liqpay_Success {

    public static function init() {
        add_action( 'acf/init', [__CLASS__, 'acf_add_local_field_group'] );
    }
   
    public static function get_url() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'liqpay-success-page.php',
        ]);
       
        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? get_the_permalink( $page[ 0 ]->ID ) : false;
    }

    public static function get_ID() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'liqpay-success-page.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? $page[ 0 ]->ID : false;
    }

    public static function acf_add_local_field_group() {

        if ( function_exists('acf_add_local_field_group') ):

        
            
        endif;    
            
    }
}