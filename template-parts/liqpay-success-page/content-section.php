<?php

class Liqpay_Successs_Page_Content_Section
{

    public function __construct()
    {
        $this->order = wc_get_order(base64_decode($_GET['order']));
        $this->order_status = $this->order->get_status();

        if (!$this->order->has_status('failed')) :
            if ( $this->order_status != 'processing' ) :
                wp_delete_post($this->order->get_id());
                ?>
                    <?php if (get_post_meta($this->order->get_id(), 'createaccount', true) == 1) : ?>
                        <?php wp_delete_user($this->order->user_id); ?>
                    <?php endif; ?>
                    <script>
                        window.location.href="/cart";
                    </script> 
            <?php endif; 
        endif; 
    }

    public function render()
    {
        ?>

        <div class="woocommerce-order register">

            <?php
            if ($this->order) :

                do_action('woocommerce_before_thankyou', $this->order->get_id());
            ?>

                <?php if ($this->order->has_status('failed')) : ?>

                    <div class="register__container success-message">
                        <div class="success-message__title title-h1">
                            <h1><?php pll_e('Виникла помилка'); ?></h1>
                        </div>
                        <div class="success-message__text section-text section-text--big">
                            <p><?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?></p>
                        </div>
                        <div class="success-message__btn">
                            <a href="<?php echo esc_url($this->order->get_checkout_payment_url()); ?>" class="btn"><?php esc_html_e('Pay', 'woocommerce'); ?></a>
                            <?php if (is_user_logged_in()) : ?>
                                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="btn btn--white"><?php esc_html_e('My account', 'woocommerce'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <?php if ( $this->order_status != 'processing' ) : ?>
                        <?php 
                        wp_delete_post($this->order->get_id());
                        ?>
                            <?php if (get_post_meta($this->order->get_id(), 'createaccount', true) == 1) : ?>
                                <?php wp_delete_user($this->order->user_id); ?>
                            <?php endif; ?>
                            <script>
                                window.location.href="/cart";
                            </script>
                    <?php else : ?>
                        <?php 
                        global $woocommerce;
                             if ( ! $woocommerce->cart->is_empty() ) {
                                $woocommerce->cart->empty_cart();    
                             }
                        ?>
                        <div class="register__container success-message">
                            <div class="success-message__title title-h1">
                                <h1><?php pll_e('Замовлення успішно сформовано') ?></h1>
                            </div>
                            <div class="success-message__text section-text section-text--big">
                                <p><?php pll_e('Дякуємо за ваше замовлення! Ми якнайшвидше розглянемо ваше замовлення. Чекайте на імейл про результати на вашій електронній поштовій скринці'); ?></p>
                            </div>
                            <?php if (get_post_meta($this->order->get_id(), 'createaccount', true) == 1) : ?>
                                <div class="success-message__text section-text section-text--big">
                                    <p><?php pll_e('Текст про успішне створення аккаунту'); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                            
                <?php endif; ?>
            <?php else : ?>                                                                                                             
            <?php endif; ?>

        </div>


<?php
    }
}
