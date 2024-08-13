<?php

class Cart_Page_Content_Section {

    public function __construct() {
        
    }

    public function render() {
        ?>

            <main class="main">

                <section class="cart">
                    <div class="container">
                        <div class="cart__container">
                            <div class="cart__title title-h1">
                                <h1><?php echo pll__('Ваш кошик'); ?></h1>
                            </div>
                            <div class="cart__body">
                                <?php if ( WC()->cart->get_cart_contents_count() > 0 ) : ?>
                                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : 
                                        ?>

                                        <?php $product_id = $cart_item['product_id']; ?>
                                        <?php $product = wc_get_product($product_id); ?>
                                        <article class="cart-item" data-cart-id="<?php echo $cart_item_key; ?>">
                                            <a href="<?php echo get_the_permalink($product_id); ?>" class="cart-item__img">
                                            <?php $thumbnail_id = get_post_thumbnail_id($product_id);
                                            $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                                                <img src="<?php echo get_the_post_thumbnail_url($product_id); ?>" alt="<?php echo $alt; ?>">
                                            </a>
                                            <div class="cart-item__info">
                                                <a href="<?php echo get_the_permalink($product_id); ?>" class="cart-item__title title-h5">
                                                <h2><?php echo get_the_title($product_id); ?></h2>
                                                </a>
                                                <?php if ( !empty( get_field('main_option',$product_id)['quantity_per_package'] ) ) : ?>
                                                    <span class="cart-item__package"><?php pll_e('Упаковка'); ?> <?php echo get_field('main_option',$product_id)['quantity_per_package']; ?> <?php pll_e('шт.'); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="product-card__content">
                                                <div class="product-card__price">
                                                    <?php if( !empty($cart_item['data']->get_sale_price()) and ( $cart_item['data']->get_sale_price() != $cart_item['data']->get_regular_price() ) ) : ?>
                                                        <div class="product-card__price--old">
                                                            <span><?php echo wc_price($cart_item['data']->get_regular_price()); ?></span>
                                                        </div>
                                                        <div class="product-card__price--new">
                                                            <span><?php echo wc_price($cart_item['data']->get_sale_price()) ?></span>
                                                        </div>
                                                        <?php $price_for_count = $product->get_sale_price(); ?>
                                                    <?php else : ?>
                                                        <div class="product-card__price--new">
                                                            <span><?php echo wc_price($cart_item['data']->get_price()); ?></span>
                                                        </div>
                                                        <?php $price_for_count = $product->get_price(); ?>
                                                    <?php endif; ?>
                                                </div>
                                                    <div class="product-card__package">
                                                        <p><?php echo number_format((float)$price_for_count / get_field('main_option',$product_id)['quantity_per_package'], 2, '.', ''); ?> <?php pll_e('за од.'); ?></p>
                                                    </div>
                                            </div>
                                            <div class="quantity">
                                                <button type="button" class="quantity__btn" data-direction="minus">
                                                <svg width="14" height="4" viewBox="0 0 14 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect y="4" width="4" height="14" transform="rotate(-90 0 4)" fill="#E0E0E0"></rect>
                                                </svg>
                                                </button>
                                                <input class="quantity__input" type="number" min="1" max="" value="<?php echo $cart_item['quantity']; ?>" readonly="" name="Quantity">
                                                <button type="button" class="quantity__btn" data-direction="plus">
                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="5" width="4" height="14" fill="#E0E0E0"></rect>
                                                    <rect y="9" width="4" height="14" transform="rotate(-90 0 9)" fill="#E0E0E0"></rect>
                                                </svg>
                                                </button>
                                            </div>
                                            <div class="cart-item__total"><?php echo wc_price($cart_item['line_subtotal']); ?></div>
                                            <button class="cart-item__close" type="button">
                                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 1L12 12M12 1L1 12" stroke="#333333" stroke-width="2"/>
                                                </svg>
                                            </button>
                                        </article>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <h3><?php pll_e('В вашій корзині пусто'); ?></h3>
                                <?php endif; ?>
                            </div>
                            <div class="cart__bottom">
                                <a class="arrow-back" href="<?php echo Catalog_Page::get_url(); ?>">
                                    <div class="arrow-back__img">
                                        <img src="<?=TEMPLATE_PATH?>/img/ico-back.svg" alt="arrow-back">
                                    </div>
                                    <span><?php echo pll__('Повернутися до каталогу продукції'); ?></span>
                                </a>
                                <div class="cart__price">
                                    <div class="cart__total total">
                                        <?php echo pll__('Вартість кошика'); ?>: <span><?php echo wc_price(WC()->cart->cart_contents_total); ?></span>
                                    </div>
                                  <?php if ( WC()->cart->get_subtotal() < get_field('cart_option','option')['the_minimum_amount_in_cart'] || WC()->cart->get_cart_contents_count() == 0 ) : ?>
                                      <div class="cart__price--min">
                                        <img src="<?=TEMPLATE_PATH?>/img/ico-attention.svg" alt="ico-attention">
                                        <div class="section-text">
                                            <p><span><?php echo pll__('Зверніть увагу'); ?>:</span> <?php echo pll__('мінімальна вартість кошика для оформлення замовлення'); ?> <span>— <?php echo get_field('cart_option','option')['the_minimum_amount_in_cart']; ?> ₴</span> </p>
                                        </div>
                                        </div>
                                        <a class="btn disabled"><?php echo pll__('Оформити замовлення'); ?></a>
                                    <?php else : ?>
                                          <?php if ( pll_current_language() == 'en' ) : ?>
                                              <?php $checkout_link = '/en/checkout/'; ?>
                                          <?php else : ?>
                                              <?php $checkout_link = '/checkout/'; ?>
                                          <?php endif; ?>
                                        <a href="<?php echo $checkout_link; ?>" class="btn"><?php echo pll__('Оформити замовлення'); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </main>

        <?php
    }
}