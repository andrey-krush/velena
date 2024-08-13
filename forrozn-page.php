<?php /* Template name: Forrozn page */ ?>
<?php get_header(); ?>
<?php (new Forrozn_Page_Promo_Section())->render(); ?>
<?php (new Forrozn_Page_Advantages_Section())->render(); ?>
<?php (new Forrozn_Page_Find_Section())->render(); ?>
<?php get_footer(); ?>