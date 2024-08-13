<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
if (WC()->cart->get_subtotal() < get_field('cart_option', 'option')['the_minimum_amount_in_cart'] || WC()->cart->get_cart_contents_count() == 0) {
    wp_redirect(wc_get_cart_url());
}


?>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
    <input type="hidden" name="lang" value="<?php echo pll_current_language(); ?>">
  <script>
      let _post = <?php echo json_encode($_POST);?>;
  </script>
    <div class="container">
        <div class="checkout__title title-h1">
            <h1><?php pll_e('Оформлення замовлення'); ?></h1>
        </div>
        <div class="checkout__container">
            <?php if ($checkout->get_checkout_fields()) : ?>

                <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                <div class="checkout__col" id="customer_details">
                    <div class="account__form-row">
                        <div class="checkout__subtitle title-h2">
                            <!--              --><?php //if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
                            <!--                <h2>-->
                            <?php //esc_html_e( 'Billing &amp; Shipping', 'woocommerce' ); ?><!--</h2>-->
                            <!--              --><?php //else : ?>
                            <!--                <h2>-->
                            <?php //esc_html_e( 'Billing details', 'woocommerce' ); ?><!--</h2>-->
                            <!--              --><?php //endif; ?>
                            <h2><?php echo pll__('Платіжні дані'); ?></h2>
                        </div>
                        <?php if (!is_user_logged_in()) : ?>
                            <div class="checkout__text section-text section-text--big">
                                <p><?php pll_e('Вже замовляли у нас?'); ?> <a href="#login-popup"
                                                                              data-fancybox><?php pll_e('Натисніть сюди, щоб увійти'); ?></a>
                                </p>
                            </div>
                        <?php endif; ?>
                        <div class="woocommerce-billing-fields">
                            <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

                            <div class="woocommerce-billing-fields__field-wrapper">
                                <?php if (!is_user_logged_in()) : ?>
                                    <input style="display: none;" id="createaccount" checked="" type="checkbox"
                                           name="createaccount" value="1"/>
                                <?php endif; ?>
                                <?php
                                $fields = $checkout->get_checkout_fields('billing');
                                $prev_priority = 0;

                                foreach ($fields as $key => $field) {
//                      var_dump($key, $field);
                                    if ($field['priority'] != -1) {
                                        if ($prev_priority != 0 && $field['priority'] != $prev_priority) {
                                            echo '</div>';
                                        }
                                        if ($field['priority'] != $prev_priority) {
                                            if ($field['priority'] - $prev_priority > 5) {
                                                echo '<div class="form__row form__row--gap">';
                                            } else {
                                                echo '<div class="form__row">';
                                            }
                                        }

                                        $prev_priority = $field['priority'];
                                    }

                                    woocommerce_form_field($key, $field, $checkout->get_value($key));
                                }
                                echo '</div>';
                                ?>
                            </div>

                        </div>
                    </div>
                    <div class="account__form-row">
                        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

                            <?php do_action('woocommerce_review_order_before_shipping'); ?>

                            <?php wc_cart_totals_shipping_html(); ?>

                            <?php do_action('woocommerce_review_order_after_shipping'); ?>

                        <?php endif; ?>
                    </div>
                    <?php get_template_part('woocommerce/checkout/payment'); ?>
                    <div class="account__form-row">
                        <div class="woocommerce-additional-fields">
                            <?php do_action('woocommerce_before_order_notes', $checkout); ?>

                            <?php if (apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))) : ?>

                                <?php if (!WC()->cart->needs_shipping() || wc_ship_to_billing_address_only()) : ?>

                                    <h3><?php esc_html_e('Additional information', 'woocommerce'); ?></h3>

                                <?php endif; ?>

                                <div class="woocommerce-additional-fields__field-wrapper form__row">
                                    <?php foreach ($checkout->get_checkout_fields('order') as $key => $field) : ?>
                                        <?php if ($key == 'order_comments') : ?>
                                            <?php $field['label'] = pll__('Нотатки до замовлення'); ?>
                                        <?php endif; ?>
                                        <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                                    <?php endforeach; ?>
                                </div>

                            <?php endif; ?>

                            <?php do_action('woocommerce_after_order_notes', $checkout); ?>
                        </div>
                    </div>
                </div>

                <?php do_action('woocommerce_checkout_after_customer_details'); ?>

            <?php endif; ?>

            <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

            <?php do_action('woocommerce_checkout_before_order_review'); ?>

            <?php get_template_part('woocommerce/checkout/review-order'); ?>

            <?php do_action('woocommerce_checkout_after_order_review'); ?>
        </div>
    </div>
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
