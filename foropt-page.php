<?php /* Template name: Foropt page */ ?>
<?php get_header(); ?>
<?php (new Foropt_Page_Promo_Section())->render(); ?>
<?php (new Foropt_Page_Advantages_Section())->render(); ?>
<?php (new Foropt_Page_Find_Section())->render(); ?>   
<?php get_footer(); ?>