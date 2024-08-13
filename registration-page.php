<?php /* Template name: Registration page */ ?>
<?php 
if ( is_user_logged_in() ) : 
    wp_redirect(wc_customer_edit_account_url());
endif;     
?>
<?php get_header(); ?>
<?php ( new Registration_Page_Content() )->render(); ?>
<?php get_footer(); ?>

