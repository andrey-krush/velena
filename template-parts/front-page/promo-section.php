<?php

class Front_Page_Promo_Section {

    public function __construct() {
        $this->slider = get_field('promo_section')['slider'];
    }

    public function render() {
        ?>

            <main class="main">
                <div class="page-promo">
                    <div class="page-promo__bg">

                        <div class="page-promo__bg-slider">
                            <div class="swiper">
                                <div class="swiper-wrapper">

                                <?php foreach ( $this->slider as $item ) : ?>
                                    <div class="swiper-slide">
                                        <?php if ( !empty( $item['video'] ) ) : ?>
                                            <div class="page-promo__bg-video image-parallax" data-sens="0.1">
                                                <video autoplay muted playsinline loop class="image-parallax__img">
                                                    <source src="<?php echo $item['video']['url']; ?>" type="video/mp4">
                                                </video>
                                            </div>
                                        <?php else : ?>
                                            <?php if ( !empty( $item['image'] ) ) : ?>
                                                <div class="page-promo__img image-parallax" data-sens="-0.08">
                                                    <picture>
                                                        <source media="(max-width: 768px)" srcset="<?php echo $item['image']; ?>">
                                                        <img src="<?php echo $item['image']; ?>" alt="image" class="image-parallax__img">
                                                    </picture>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                <?php endforeach; ?>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="page-promo__container">
                        <div class="container container--small">

                            <div class="swiper">
                                <div class="swiper-wrapper">

                                <?php foreach ( $this->slider as $item ) : ?>

                                    <div class="swiper-slide">

                                        <div class="page-promo__content">

                                            <?php if ( !empty( $item['title'] ) ) : ?>
                                                <div class="page-promo__title title-big">
                                                    <span><?php echo $item['title']; ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ( !empty( $item['text'] ) ) : ?>
                                                <div class="page-promo__text">
                                                    <p><?php echo $item['text']; ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ( !empty( $item['button'] ) ) : ?>
                                                <div class="page-promo__btn">
                                                    <a href="<?php echo $item['button']['url']; ?>" class="btn btn--primary"><?php echo $item['button']['title']; ?></a>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ( !empty( $item['image'] ) ) : ?>
                                            <div class="page-promo__img image-parallax" data-sens="-0.08">
                                                <picture>
                                                    <source media="(max-width: 768px)" srcset="<?php echo $item['image']; ?>">
                                                    <img src="<?php echo $item['image']; ?>" alt="image" class="image-parallax__img">
                                                </picture>
                                            </div>
                                        <?php endif; ?>

                                    </div>

                                <?php endforeach; ?>
                                   
                                </div>

                                <div class="swiper-nav-pagination">
                                    <button type="button" class="swiper-nav swiper-button-prev">
                                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17 2.5L9.12132 10.3787C7.94975 11.5503 7.94975 13.4497 9.12132 14.6213L17 22.5" stroke="#333333" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <div class="swiper-pagination"></div>
                                    <button type="button" class="swiper-nav swiper-button-next">
                                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 22.5L16.8787 14.6213C18.0503 13.4497 18.0503 11.5503 16.8787 10.3787L9 2.5" stroke="#333333" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

        <?php
    }
}