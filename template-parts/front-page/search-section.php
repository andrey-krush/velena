<?php

class Front_Page_Search_Section {

    public function __construct() {
        $this->title = get_field('product_section')['title'];
        $this->text = get_field('product_section')['text'];
        $this->products = get_field('product_section')['products'];
        $this->link_under_selects = get_field('product_section')['link'];

    }

    public function render() {
        ?>

            <section class="search">
                <div class="container container--full">
                    <div class="search__slider mobile-show">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <?php if ( !empty( $this->products ) ) : ?>
                                    <?php foreach ( $this->products as $product ) : ?>
                                        <div class="swiper-slide">
                                            <?php ( new Type_Products() )->render_product_article($product); ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="search__slider-btn search__slider-btn--prev">
                                <svg width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 2L3.12132 9.87868C1.94975 11.0503 1.94975 12.9497 3.12132 14.1213L11 22" stroke="#828282" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="search__slider-btn search__slider-btn--next">
                                <svg width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 22L9.87868 14.1213C11.0503 12.9497 11.0503 11.0503 9.87868 9.87868L2 2" stroke="#828282" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="search__container">
                        <?php if ( !empty( $this->products ) ) : ?>
                            <?php ( new Type_Products() )->render_product_article($this->products[0]); ?>
                            <?php unset($this->products[0]); ?>
                        <?php endif; ?>
                        <?php $products_type = get_terms( array(
                            'taxonomy'   => 'type',
                            'hide_empty' => false,
                        ) ); ?>
                        <?php $products_appointment = get_terms( array(
                            'taxonomy'   => 'appointment',
                            'hide_empty' => false,
                        ) ); ?>
                            <div class="search__field">
                                <div class="search__info">
                                    <?php if ( !empty( $this->title ) ) : ?>
                                        <div class="search__title title-h2">
                                            <h2><?php echo $this->title; ?></h2>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ( !empty( $this->text ) ) : ?>
                                    <div class="search__text section-text">
                                        <p><?php echo $this->text; ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <form class="search__form">
                                    <div class="input input--select">
                                        <input type="hidden" name="action" value="front_page_search">

                                        <input type="text" readonly class="output_text" value="Тип продукції">
                                        <input type="hidden" name="type" class="output_value" required>
                                        <ul class="input__dropdown">
                                            <?php foreach ( $products_type as $item ) : ?>
                                                <li data-value="<?php echo $item->term_id; ?>"><?php echo $item->name; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="input__arrow">
                                            <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 2L7 7L12 2" stroke="#828282" stroke-width="4"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="input input--select">
                                        <input type="text" readonly class="output_text" value="Призначення">
                                        <input type="hidden" name="appointment" class="output_value" required>
                                        <ul class="input__dropdown">
                                            <?php foreach ( $products_appointment as $item ) : ?>
                                                <li data-value="<?php echo $item->slug; ?>"><?php echo $item->name; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="input__arrow">
                                            <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 2L7 7L12 2" stroke="#828282" stroke-width="4"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <!-- <div class="input input--select">
                                        <input type="text" readonly class="output_text" value="Обєм">
                                        <input type="hidden" name="type" class="output_value" required>
                                        <ul class="input__dropdown">
                                            <li data-value="bear">Пиво</li>
                                            <li data-value="vine">Вино</li>
                                        </ul>
                                        <div class="input__arrow">
                                            <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 2L7 7L12 2" stroke="#828282" stroke-width="4"/>
                                            </svg>
                                        </div>
                                    </div> -->
                                    <button type="submit" class="search__btn btn btn--medium">Шукати</button>
                                </form>
                                <?php if( !empty( $this->link_under_selects ) ) : ?>
                                    <a href="<?php echo $this->link_under_selects['url']; ?>" class=" section-arrow">
                                        <span><?php echo $this->link_under_selects['title']; ?></span>
                                        <div class="section-arrow__img">
                                            <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 1L5.17412 4.75671C5.6155 5.15395 5.6155 5.84605 5.17412 6.24329L1 10" stroke="#FEB11C" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php if ( !empty( $this->products ) ) : ?>
                            <?php foreach ( $this->products as $product ) : ?>
                                <?php ( new Type_Products() )->render_product_article($product); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        <?php
    }
}