<?php /* Template Name: Catalog page */ ?>
<?php get_header(); ?>
<?php (new Catalog_Page_Promo() )->render(); ?>
<?php (new Catalog_Page_Subcategories() )->render(); ?>
<?php (new Catalog_Page_Categories() )->render(); ?>
<?php (new Catalog_Page_Destination() )->render(); ?>
<?php get_footer(); ?>