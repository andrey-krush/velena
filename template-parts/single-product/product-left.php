<?php

class Single_Product_Product_Left {

    public function __construct(){

        $product = wc_get_product( get_the_ID() );

        $this->title = get_the_title();
        $this->post_thumbnail = get_the_post_thumbnail_url();
        $thumbnail_id = get_post_thumbnail_id(get_the_ID());
        $this->alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
        $this->attachment_ids = $product->get_gallery_image_ids();

        $this->main_options = get_field('main_option');

        $this->characteristics = get_field('characteristics');

        $type_main_category = get_terms([
            'taxonomy' => 'type',
            'parent' => 0,
            'object_ids' => [get_the_ID()]
        ]);

        if( !empty( $type_main_category ) ) :

            $children_type_category = get_terms([
                'taxonomy' => 'type',
                'parent' => $type_main_category[0]->term_id,
                'object_ids' => [get_the_ID()]
            ]);

        endif;

        if( !empty($children_type_category) ) :

            $term_characteristics = get_field('characteristics', $children_type_category[0]);

        elseif( !empty( $type_main_category ) ) :

            $term_characteristics = get_field('characteristics', $type_main_category[0]);

        endif;

        $this->possible_range_keys = get_post_meta(get_the_ID(), 'possible_range_keys');

        foreach ($this->characteristics as $key => $item) :


            foreach ($term_characteristics as $subitem) :

                $subitem_key_without_spaces = str_replace(' ', '', $subitem['characteristic_type']);
                if (($key == $subitem['characteristic_type'] or $key == $subitem_key_without_spaces) and !empty($subitem['is_on_product_page'])) :
                    $this->page_charatecristics[$subitem['characteristic_type']] = $item;
                endif;

                if ( in_array($key, $this->possible_range_keys) ) :

                    $key_to_search = str_replace(['_value', '_'], '', $key);
                    $key_without_value_string = str_replace('_value', '', $key);

                    if( $key_to_search == $subitem['characteristic_type'] or $key_to_search == $subitem_key_without_spaces)  :

                        if( !empty( $this->characteristics[$key_without_value_string . '_additional_value'] ) ) :

                            $value = $this->characteristics[$key_without_value_string . '_additional_value'];
                            $measurement = '';
                            unset($this->characteristics[$key_without_value_string . '_value']);
                        else :

                            $value = $this->characteristics[$key_without_value_string . '_value'];
                            $measurement = get_option('measurement_' . $subitem_key_without_spaces);
                            unset($this->characteristics[$key_without_value_string . '_additional_value']);

                        endif;

                        if( !empty($subitem['is_on_product_page'] ) ) :
                            $this->page_charatecristics[$subitem['characteristic_type']] = [
                                'value' => $value,
                                'measurement' => $measurement
                            ];
                        endif;

                    endif;
                endif;

            endforeach;


        endforeach;
    }

    public function render() {
        ?>

        <div class="product__left">
            <div class="mobile-show">
                <?php if( !empty( $this->main_options['product_code'] ) ) : ?>
                    <div class="product__code">
                        <span><?php pll_e('SKU'); ?></span>
                        <?php echo $this->main_options['product_code']; ?>
                    </div>
                <?php endif; ?>
                <?php if( !empty( $this->title ) ) : ?>
                    <div class="product__title title-h1">
                        <h1><?php echo $this->title; ?></h1>
                    </div>
                <?php endif; ?>
                <div class="product__info">

                    <?php if( !empty( $this->page_charatecristics ) ) : ?>
                        <?php foreach( $this->page_charatecristics as $key => $item ) : ?>
                            <?php if( !empty( $item ) ) : ?>
                                <?php $key = str_replace('_', ' ', $key); ?>
                                <div class="product__code product__code--small">
                                    <span><?php echo $key; ?></span>
                                    <?php echo $item[0]; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <a href="" class="js-add-favorite product-card__favorite">
                        <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.5 1L14.1971 8.78783L22.4371 8.9463L15.8639 13.9179L18.2595 21.8037L11.5 17.0885L4.74047 21.8037L7.13608 13.9179L0.56285 8.9463L8.80295 8.78783L11.5 1Z"
                                  stroke="#BDBDBD" stroke-linejoin="round"/>
                        </svg>
                        <span><?php echo pll__('До обраного'); ?></span>
                    </a>
                </div>
            </div>
            <div class="product__gallery">
                <div class="product__gallery-main">
                    <div class="product-card__tags">
                        <?php if (in_array('new',$this->main_options['product_tag'])) : ?>
                            <span class="tag tag--new"><?php echo pll__('Новий'); ?></span>
                        <?php endif; ?>
                        <?php if (in_array('sale',$this->main_options['product_tag'])) : ?>
                            <span class="tag"><?php echo pll__('Акція'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="product__gallery-arrows">
                        <div class="product__gallery-arrows--prev arr-prev-main">
                            <svg width="13" height="24" viewBox="0 0 13 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 2L3.12132 9.87868C1.94975 11.0503 1.94975 12.9497 3.12132 14.1213L11 22"
                                      stroke="#333333" stroke-width="4" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="product__gallery-arrows--next arr-next-main">
                            <svg width="13" height="24" viewBox="0 0 13 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 22L9.87868 14.1213C11.0503 12.9497 11.0503 11.0503 9.87868 9.87868L2 2"
                                      stroke="#333333" stroke-width="4" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <?php if( !empty( $this->post_thumbnail ) or !empty( $this->attachment_ids ) ) : ?>
                        <div class="swiper swiper-images">
                            <div class="swiper-wrapper">
                                <?php if( !empty( $this->post_thumbnail ) ) : ?>
                                    <a href="<?php echo $this->post_thumbnail; ?>" data-fancybox="main-gallery" class="swiper-slide product__gallery-main__item">
                                        <img src="<?php echo $this->post_thumbnail; ?>" alt="<?php echo $this->alt; ?>">
                                    </a>
                                <?php endif; ?>
                                <?php if( !empty( $this->attachment_ids ) ) : ?>
                                    <?php foreach( $this->attachment_ids as $item ) : ?>
                                        <a href="<?php echo wp_get_attachment_url($item); ?>" data-fancybox="main-gallery" class="swiper-slide product__gallery-main__item">
                                            <img src="<?php echo wp_get_attachment_url($item); ?>" alt="image-2">
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="product__gallery-next">
                    <div class="product__gallery-arrows">
                        <div class="product__gallery-arrows--prev arr-prev-next">
                            <svg width="13" height="24" viewBox="0 0 13 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 2L3.12132 9.87868C1.94975 11.0503 1.94975 12.9497 3.12132 14.1213L11 22"
                                      stroke="#333333" stroke-width="4" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="product__gallery-arrows--next arr-next-next">
                            <svg width="13" height="24" viewBox="0 0 13 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 22L9.87868 14.1213C11.0503 12.9497 11.0503 11.0503 9.87868 9.87868L2 2"
                                      stroke="#333333" stroke-width="4" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <?php if( !empty( $this->post_thumbnail ) or !empty( $this->attachment_ids ) ) : ?>
                        <div class="swiper swiper-thumbs">
                            <div class="swiper-wrapper">
                                <?php if( !empty( $this->post_thumbnail ) ) : ?>
                                    <div class="swiper-slide product__gallery-next__item">
                                        <img src="<?php echo $this->post_thumbnail; ?>" alt="image-1">
                                    </div>
                                <?php endif; ?>
                                <?php if( !empty( $this->attachment_ids ) ) : ?>
                                    <?php foreach( $this->attachment_ids as $item ) : ?>
                                        <div class="swiper-slide product__gallery-next__item">
                                            <img src="<?php echo wp_get_attachment_url($item); ?>" alt="image-2">
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
    }
}