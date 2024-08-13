<?php /* Template name: Front page */ ?>
<?php get_header(); ?>
<?php (new Front_Page_Promo_Section())->render(); ?>
<?php (new Front_Page_Banner_Section())->render(); ?>
<?php (new Front_Page_Main_Banner_Section())->render(); ?>
<?php (new Front_Page_Benefits_Section())->render(); ?>
<?php (new Front_Page_Products_Section())->render(); ?>
<?php (new Front_Page_Search_Section())->render(); ?>
<?php (new Front_Page_News_Section())->render(); ?>
<?php get_footer(); ?>