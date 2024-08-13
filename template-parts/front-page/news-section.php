<?php

class Front_Page_News_Section {

    public function __construct() {
        
        $news_section = get_field('news_section');
        $this->title = $news_section['title'];
        $this->archive_link_text = $news_section['archive_link_text'];
        $this->archive_link = (new Archive_Post_Page() )::get_url();

        $this->posts = get_posts(array(
            'post_type' => 'post',
            'numberposts' => '3',
            'post_status' => 'publish'
        ));

    }

    public function render() {
        ?>
        
        <?php if( !empty( $this->posts ) ) : ?>
            <section class="news">
                <div class="container">
                    <div class="news__head">
                        <?php if( !empty( $this->title ) ) : ?>
                            <div class="news__title title-h2">
                                <h2><?php echo $this->title; ?></h2>
                            </div>
                        <?php endif; ?>
                        <a href="<?php echo $this->archive_link; ?>" class="section-arrow mobile-hide">
                            <span><?php echo $this->archive_link_text; ?></span>
                            <div class="section-arrow__img">
                                <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 10.5L5.17412 6.74329C5.6155 6.34605 5.6155 5.65395 5.17412 5.25671L1 1.5" stroke="#FEB11C" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </a>
                    </div>
                    <div class="news__container">
                    <?php foreach( $this->posts as $item ) : ?>
                        <article class="card-post">
                            <?php $month_array = get_field( 'month_' . pll_current_language(), 'option'); ?>
                            <a href="<?php echo get_the_permalink($item->ID); ?>" class="card-post__link">
                                <div class="section-data">
                                    <time datetime="2023-03-05"><?php echo get_the_date( 'd', $item->ID ) . ' ' . $month_array['month_'.get_the_date( 'n', $item->ID )]; ?></time>
                                </div>
                                <div class="card-post__inner">
                                    <div class="card-post__title">
                                        <h3><?php echo $item->post_title; ?></h3>
                                    </div>
                                    <?php $excerpt = get_the_excerpt($item->ID); ?>
                                    <?php $excerpt = str_replace('[...]', '', $excerpt); ?>
                                    <?php $excerpt = mb_substr($excerpt, 0, 100); ?>
                                    <div class="card-post__text">
                                        <p><?php echo $excerpt; ?>...</p>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                        <a href="<?php echo $this->archive_link; ?>" class="section-arrow mobile-show">
                            <span><?php echo $this->archive_link_text; ?></span>
                            <div class="section-arrow__img">
                                <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 10.5L5.17412 6.74329C5.6155 6.34605 5.6155 5.65395 5.17412 5.25671L1 1.5" stroke="#FEB11C" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </a>
                    </div>

                </div>
            </section>
        <?php endif;?>
    </main>

        <?php
    }
}