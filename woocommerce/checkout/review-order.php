<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="checkout__col shop_table woocommerce-checkout-review-order-table">
    <div class="checkout__col-inner">
        <div class="checkout__mobile">
            <div class="checkout__total">
                <span><a href="#" class="js-expand-mobile-order"><?php echo count(WC()->cart->get_cart()); ?> позиції</a> на суму</span>
                <span><?php wc_cart_totals_order_total_html(); ?></span>
            </div>
            <div class="checkout__mobile-btn">
                <button type="button" class="btn">Замовити</button>
            </div>
        </div>
        <div class=" woocommerce-checkout-review-order" id="order_review">
            <div class="checkout__subtitle js-expand-mobile-order title-h2">
                <h2 id="order_review_heading"><?php echo pll__('Ваше замовлення')?></h2>
            </div>
            <div class="checkout__review">
                <div class="checkout__review-back section-text section-text--small">
                    <p><a href="<?php echo wc_get_cart_url(); ?>"><img src="<?=TEMPLATE_PATH?>/img/ico-back-small.svg" alt="<"><?php pll_e('Повернутися до кошику'); ?></a></p>
                </div>
    
    
                <div class="checkout__review-list">
                  <?php
                    do_action( 'woocommerce_review_order_before_cart_contents' );
                    
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                      $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                      
                      if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        ?>
                          <article class="cart-item cart-item--small <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                              <a href="<?php echo get_the_permalink($_product->get_id()); ?>" target="_blank" class="cart-item__img">
                              <?php $thumbnail_id = get_post_thumbnail_id($_product->get_id());
                                $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                                  <img src="<?php echo get_the_post_thumbnail_url($_product->get_id());?>" alt="<?php echo $alt; ?>">
                              </a>
                              <div class="cart-item__info">
                                  <a href="<?php echo get_the_permalink($_product->get_id()); ?>" target="_blank" class="cart-item__title title-h5">
                                      <h3><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?> <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?></h3>
                                  </a>
                              </div>
                              <div class="cart-item__count"><?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></div>
                              <div class="cart-item__total"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></div>
                          </article>
                        <?php
                      }
                    }
                    
                    do_action( 'woocommerce_review_order_after_cart_contents' );
                  ?>
                </div>
    
    
                <div class="checkout__subtotal">
                    <span><?php echo pll__('Проміжний підсумок'); ?></span>
                    <span><?php wc_cart_totals_subtotal_html(); ?></span>
                </div>
    
                <ul class="checkout__review-info">
                    <?php
                    $selected_shipping = get_shipping_method();
                    ?>
                    <li class="shipping-price">
                        <span><?php pll_e('Доставка'); ?>: <span><?php echo $selected_shipping['label']; ?></span></span>
                        
                        <?php if($selected_shipping['cost'] > 0): ?>
                            <span>+<?php echo wc_price( $selected_shipping['cost'] ); ?></span>
                        <?php endif; ?>
                    </li>
                    <li class="payment-type">
                        <span><?php pll_e('Оплата'); ?>: <span class="js-payment-method"></span></span>
                    </li>
                  <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                      <div class="checkout__subtotal cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                          <span><?php bb_cart_totals_coupon_label( $coupon ); ?></span>
                          <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
                      </div>
                  <?php endforeach; ?>
                  
                  <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                      <li>
                          <span><?php echo esc_html( $fee->name ); ?></span>
                          <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
                      </li>
                  <?php endforeach; ?>
                  
                  <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                    <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                      <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                              <li class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                                  <span><?php echo esc_html( $tax->label ); ?></span>
                                  <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                              </li>
                      <?php endforeach; ?>
                    <?php else : ?>
                          <li class="tax-total">
                              <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                              <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                          </li>
                    <?php endif; ?>
                  <?php endif; ?>
                </ul>
    
                <div class="checkout__code checkout__total">
                    <div class="woocommerce-form-coupon-toggle">
                        <span><?php pll_e('Промокод на знижку'); ?></span>
                        <a href="#" class="show-coupon btn btn--small btn--white" data-text-open="<?php pll_e('Додати'); ?>" data-text-close="<?php pll_e('Закрити'); ?>"><?php pll_e('Додати'); ?></a>
                    </div>
    
                    <form class="checkout_coupon woocommerce-form-coupon" method="post" style="width: 100%">
                        <div class="checkout__code-input">
                            <input type="text" name="coupon_code" class="input-text" placeholder="<?php pll_e( 'Код купону' ); ?>" id="coupon_code" value="" />
                            <button type="submit" class="btn btn--small" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php pll_e( 'Застосувати купон' ); ?></button>
                        </div>
                    </form>
                </div>
    
                <script>
                    $(document).trigger('set-payment-text')
                </script>
    
                <div class="checkout__total">
                    <span><?php echo pll__('Загалом') ?></span>
                    <span><?php wc_cart_totals_order_total_html(); ?></span>
                </div>
    
                <div class="checkout__btn">
                    <button type="button" class="btn"><?php pll_e('Замовлення підтверджую'); ?></button>
                    <button type="submit" class="hidden"><?php pll_e('Checkout'); ?></button>
                </div>
                <div class="checkout__descr section-text section-text--small">
                    <p><?php pll_e('Ваші особисті дані будуть використані для обробки вашого замовлення, підтримки вашого досвіду на цьому веб-сайті та інших цілях, описаних у нашій <a href="">політиці конфіденційності</a>'); ?></p>
                </div>
            </div>
        </div>
            <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
            <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
    </div>
</div>