<?php /* Template name: Cart (wholesale) */ ?>
<?php get_header(); ?>
    <main class="main">
        <?php ( new Wholesale_Cart_Products_Section())->render(); ?>
        <?php ( new Wholesale_Cart_Form_Section())->render(); ?>
    </main>

<?php get_footer(); ?>