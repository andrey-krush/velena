<?php /* Template name: Cart success(wholesale) */ ?>
<?php get_header(); ?>
    <?php $title = get_field('content', get_the_ID())['title'];?>
    <?php $subtitle = get_field('content', get_the_ID())['subtitle'];?>
    <?php $link = get_field('content', get_the_ID())['link'];?>
    <div class="woocommerce-order register">
        <div class="register__container success-message">
            <?php if( !empty( $title ) ) : ?>
                <div class="success-message__title title-h1">
                    <h1><?php echo $title; ?></h1>
                </div>
            <?php endif; ?>
            <?php if( !empty( $title ) ) : ?>
                <div class="success-message__text section-text section-text--big">
                    <p><?php echo $subtitle; ?></p>
                </div>
            <?php endif; ?>
            <?php if( !empty( $link ) ) : ?>
                <div class="success-message__btn">
                    <a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
<?php get_footer(); ?>