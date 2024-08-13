<?php

class Single_Post_Content {

    public function __construct() {

        $this->title = get_the_title();
        $month_array = get_field( 'month_' . pll_current_language(), 'option');
        $this->date = get_the_date( 'd' ) . ' ' . $month_array['month_'.get_the_date( 'n' )];
        $this->image = get_the_post_thumbnail_url();
        $thumbnail_id = get_post_thumbnail_id(get_the_ID());
        $this->alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
        $this->content = apply_filters('the_content', get_the_content());
        $this->gallery = get_field( 'post' )['gallery'];
        $this->post_category = get_the_terms(get_the_ID(), 'category')[0]->name;
        $this->archive_url = (new Archive_Post_Page() )::get_url();
        
    }

    public function render() {
        ?>

            <main class="main">
                <section class="blog-single">
                    <div class="container container--small">
                        <div class="blog-single__info">
                            <div class="blog-single__title page-head__title title-h1">
                                <h1><?php echo $this->title; ?></h1>
                            </div>
                            <div class="section-data">
                                <time datetime="2023-03-05"><?php echo $this->date; ?></time>
                                <?php if( !empty( $this->post_category ) ) : ?>
                                    <span><?php echo $this->post_category; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if( !empty( $this->image ) ) : ?>
                            <div class="blog-single__img">
                                <img src="<?php echo $this->image; ?>" width="860" height="530" alt="<?php echo $this->alt; ?>">
                            </div>
                        <?php endif; ?>
                        <?php if( !empty( $this->content ) ) : ?>
                            <div class="section-text blog-single__text">
                                <?php echo $this->content; ?>
                            </div>
                        <?php endif; ?>
                        <?php if( !empty( $this->gallery ) ) : ?>
                            <div class="gallery">
                                <div class="gallery__container">
                                    <?php foreach( $this->gallery as $key => $value ) : ?>
                                        <a href="<?php echo $value['image']; ?>" class="gallery__link" data-fancybox="gallery-1">
                                            <img src="<?php echo $value['image']; ?>" width="370" height="260" alt="gallery__img">
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <a class="blog-single__back arrow-back" href="<?php echo $this->archive_url; ?>">
                            <div class="arrow-back__img">
                                <img src="<?=TEMPLATE_PATH?>/img/ico-back.svg" alt="arrow-back">
                            </div>
                            <span><?php echo pll__('Повернутися до списку публікацій'); ?></span>
                        </a>
                    </div>
                </section>
            </main>

        <?php
    }
}