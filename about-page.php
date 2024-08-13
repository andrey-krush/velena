<?php /* Template name: About page */ ?>
<?php get_header(); ?>
<?php (new About_Page_Promo_Section() )->render(); ?>
<?php (new About_Page_Video_Section() )->render(); ?>
<?php (new About_Page_Benefits_Section() )->render(); ?>
<?php (new About_Page_Gallery_Section() )->render(); ?>
<?php get_footer(); ?>