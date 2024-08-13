<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order register">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

        <div class="register__container success-message">
            <div class="success-message__title title-h1">
                <h1>Виникла помилка</h1>
            </div>
            <div class="success-message__text section-text section-text--big">
                <p><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
            </div>
            <div class="success-message__btn">
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
              <?php if ( is_user_logged_in() ) : ?>
                  <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn--white"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
              <?php endif; ?>
            </div>
        </div>
		<?php else : ?>

        <div class="register__container success-message">
            <div class="success-message__title title-h1">
                <h1><?php pll_e('Замовлення успішно сформовано')?></h1>
            </div>
            <div class="success-message__text section-text section-text--big">
                <p><?php pll_e('Дякуємо за ваше замовлення! Ми якнайшвидше розглянемо ваше замовлення. Чекайте на імейл про результати на вашій електронній поштовій скринці'); ?></p>
            </div>
            <?php if( get_post_meta( $order->get_id(), 'createaccount', true ) == 1 ) : ?>
                <div class="success-message__text section-text section-text--big">
                    <p><?php pll_e('Текст про успішне створення аккаунту'); ?></p>
                </div>
            <?php endif; ?>
            <?php if ( $order->get_payment_method() == 'cheque' ) :?>
            <div class="success-message__btn">
                    <img src="<?=TEMPLATE_PATH?>/img/ico-sketch.svg" alt="sketch">
                    <span>
                        <?php echo do_shortcode('[wcpdf_download_invoice link_text="'. pll__('Завантажити квитанцію') .'" order_id="'. $order->get_id() .'"]'); ?>
                    </span>
            </div>
            <?php endif; ?>
        </div>
<!--			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">--><?php //echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</p>-->
<!---->
<!--			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">-->
<!---->
<!--				<li class="woocommerce-order-overview__order order">-->
<!--					--><?php //esc_html_e( 'Order number:', 'woocommerce' ); ?>
<!--					<strong>--><?php //echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</strong>-->
<!--				</li>-->
<!---->
<!--				<li class="woocommerce-order-overview__date date">-->
<!--					--><?php //esc_html_e( 'Date:', 'woocommerce' ); ?>
<!--					<strong>--><?php //echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</strong>-->
<!--				</li>-->
<!---->
<!--				--><?php //if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
<!--					<li class="woocommerce-order-overview__email email">-->
<!--						--><?php //esc_html_e( 'Email:', 'woocommerce' ); ?>
<!--						<strong>--><?php //echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</strong>-->
<!--					</li>-->
<!--				--><?php //endif; ?>
<!---->
<!--				<li class="woocommerce-order-overview__total total">-->
<!--					--><?php //esc_html_e( 'Total:', 'woocommerce' ); ?>
<!--					<strong>--><?php //echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</strong>-->
<!--				</li>-->
<!---->
<!--				--><?php //if ( $order->get_payment_method_title() ) : ?>
<!--					<li class="woocommerce-order-overview__payment-method method">-->
<!--						--><?php //esc_html_e( 'Payment method:', 'woocommerce' ); ?>
<!--						<strong>--><?php //echo wp_kses_post( $order->get_payment_method_title() ); ?><!--</strong>-->
<!--					</li>-->
<!--				--><?php //endif; ?>
<!---->
<!--			</ul>-->

		<?php endif; ?>

<!--		--><?php //do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
<!--		--><?php //do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

<!--		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">--><?php //echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</p>-->

	<?php endif; ?>

</div>
