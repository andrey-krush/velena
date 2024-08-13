<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.3.0
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
?>
<div class="account__form-row woocommerce-shipping-totals shipping">
  <div class="checkout__subtitle title-h2">
    <h2><?php echo wp_kses_post( $package_name ); ?></h2>
  </div>
	<div data-title="<?php echo esc_attr( $package_name ); ?>">
		<?php if ( $available_methods ) : ?>
			<div id="shipping_method" class="checkout__radio woocommerce-shipping-methods">
				<?php foreach ( $available_methods as $method ) : ?>
                  <div class="checkout__radio-item">
                    <div class="input input--radiocheck">
                      <?php
                        printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) ); // WPCS: XSS ok.
                    
                        if ( $method->id == 'local_pickup:4' ) {
                            printf( '<label for="shipping_method_%1$s_%2$s">%3$s</label>', $index, esc_attr( sanitize_title( $method->id ) ), pll__('Самовивіз із нашого складу') ); // WPCS: XSS ok.
                        }
                        if ( $method->id == 'nova_poshta_shipping:3' ) {
                            printf( '<label for="shipping_method_%1$s_%2$s">%3$s</label>', $index, esc_attr( sanitize_title( $method->id ) ), pll__('Доставка Новою Поштою') ); // WPCS: XSS ok.
                        }
                        do_action( 'woocommerce_after_shipping_rate', $method, $index );
                      ?>
                    </div>
                  </div>
                    <?php if( $method->id == 'local_pickup:4') { ?>
                    <div class="input input--select">
                        <div class="wcus-checkout-fields wcus-checkout-field-local" <?php echo($method->id != $chosen_method ? 'style="display: none;"' : ''); ?>>
                            <h3><?php echo pll__('Виберіть адресу самовивозу'); ?></h3>
                            <?php $local_pickup_option = get_field('local_pickup_options_' . pll_current_language(), 'option'); ?>
                            <?php if( !empty( $local_pickup_option ) ) : ?>
                                <input type="text" readonly="" class="output_text" value="<?php echo $local_pickup_option[0]['option']; ?>">
                                <input type="hidden" name="local_pickup_place" class="output_value" value="<?php echo $local_pickup_option[0]['option']; ?>" required="">
                                <ul class="input__dropdown">
                                    <?php foreach ( $local_pickup_option as $item ) : ?>
                                        <li data-value="<?php echo $item['option']; ?>"><?php echo $item['option']; ?></li>
                                    <?php endforeach; ?>
    <!--                                <li data-value="Kyiv">Київська</li>-->
    <!--                                <li data-value="Lviv">Львівська</li>-->
                                </ul>
                                <div class="input__arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 129 129"><path d="m121.3,34.6c-1.6-1.6-4.2-1.6-5.8,0l-51, 51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8,0-1.6, 1.6-1.6,4.2 0,5.8l53.9,53.9c0.8,0.8 1.8, 1.2 2.9,1.2 1,0 2.1-0.4 2.9-1.2l53.9-53.9c1.7-1.6 1.7-4.2 0.1-5.8z"></path></svg>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php } ?>
				<?php endforeach; ?>
                <div class="input">
                    <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
                </div>
            </div>
			<?php if ( is_cart() ) : ?>
				<p class="woocommerce-shipping-destination">
					<?php
					if ( $formatted_destination ) {
						// Translators: $s shipping destination.
						printf( esc_html__( 'Shipping to %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' );
						$calculator_text = esc_html__( 'Change address', 'woocommerce' );
					} else {
						echo wp_kses_post( apply_filters( 'woocommerce_shipping_estimate_html', __( 'Shipping options will be updated during checkout.', 'woocommerce' ) ) );
					}
					?>
				</p>
			<?php endif; ?>
			<?php
		elseif ( ! $has_calculated_shipping || ! $formatted_destination ) :
			if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
				echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) ) );
			} else {
				echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) );
			}
		elseif ( ! is_cart() ) :
			echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) );
		else :
			echo wp_kses_post(
				/**
				 * Provides a means of overriding the default 'no shipping available' HTML string.
				 *
				 * @since 3.0.0
				 *
				 * @param string $html                  HTML message.
				 * @param string $formatted_destination The formatted shipping destination.
				 */
				apply_filters(
					'woocommerce_cart_no_shipping_available_html',
					// Translators: $s shipping destination.
					sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ),
					$formatted_destination
				)
			);
			$calculator_text = esc_html__( 'Enter a different address', 'woocommerce' );
		endif;
		?>

		<?php if ( $show_package_details ) : ?>
			<?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
		<?php endif; ?>

		<?php if ( $show_shipping_calculator ) : ?>
			<?php woocommerce_shipping_calculator( $calculator_text ); ?>
		<?php endif; ?>
	</div>
</div>
