<?php

class My_Account_Orders_Content_Section
{

    public function __construct()
    {

        $this->orders = wc_get_orders(array(
            'customer' => get_current_user_id(),
            'limit'    => -1,
        ));
    }

    public function render()
    {
?>
        <?php if (!empty($this->orders)) : ?>
            <div class="orders__accordion">
                <?php foreach ($this->orders as $order) : ?>
                    <div class="accordion">
                        <div class="accordion__head">
                            <div class="accordion__img">
                                <img src="<?= TEMPLATE_PATH ?>/img/ico-box.svg" alt="box">
                            </div>
                            <div class="accordion__name">
                                <div class="accordion__title">
                                    <h2><?php pll_e('Замовлення'); ?> № <?php echo $order->get_id(); ?></h2>
                                </div>
                                <time datetime="2023-11-23"><?php pll_e('від'); ?> <?php echo date_i18n('j F Y', strtotime($order->order_date)); ?></time>
                                <?php $order_status = $order->get_status();
                                ?>
                                <?php if ($order_status == 'cancelled') : ?>
                                    <?php $background_colour = get_field('order_account_status', 'option')['cancelled']; ?>
                                    <?php $order_status_text = pll__('Скасовано'); ?>
                                <?php elseif ($order_status == 'on-hold') : ?>
                                    <?php $background_colour = get_field('order_account_status', 'option')['on_hold'];; ?>
                                    <?php $order_status_text = pll__('На утриманні'); ?>
                                <?php elseif ($order_status == 'processing') : ?>
                                    <?php $background_colour = get_field('order_account_status', 'option')['processing'];; ?>
                                    <?php $order_status_text = pll__('В обробці'); ?>
                                <?php elseif ($order_status == 'completed') : ?>
                                    <?php $background_colour = get_field('order_account_status', 'option')['completed'];; ?>
                                    <?php $order_status_text = pll__('Виконано'); ?>
                                <?php elseif ($order_status == 'refunded') : ?>
                                    <?php $background_colour = get_field('order_account_status', 'option')['refunded'];; ?>
                                    <?php $order_status_text = pll__('Повернено'); ?>
                                <?php elseif ($order_status == 'failed') : ?>
                                    <?php $background_colour = get_field('order_account_status', 'option')['failed'];; ?>
                                    <?php $order_status_text = pll__('Не вдалося'); ?>
                                <?php elseif ($order_status == 'pending') : ?>
                                    <?php $background_colour = get_field('order_account_status', 'option')['pending'];; ?>
                                    <?php $order_status_text = pll__('Очікування оплати'); ?>
                                <?php endif; ?>
                                <?php /// var_dump($order_status); 
                                ?>
                                <div class="accordion__tags" style="background-color: <?php echo $background_colour; ?>"><?php echo $order_status_text; ?></div>
                            </div>
                            <button type="button" class="accordion__btn">
                                <span class="mobile-hide"><?php pll_e('Розгорнути'); ?></span>
                                <img src="<?= TEMPLATE_PATH ?>/img/accordion-btn.svg" alt="btn">
                            </button>
                        </div>
                        <?php $order_items = $order->get_items(); ?>
                        <div class="accordion__body">
                            <?php foreach ($order_items as $items_key => $items_value) : ?>
                                <?php $product = $items_value->get_product(); ?>
                                <article class="cart-item cart-item--small">
                                    <div class="cart-item__img">
                                        <?php $thumbnail_id = get_post_thumbnail_id($product->get_id());
                                        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                                        <img src="<?php echo get_the_post_thumbnail_url($product->get_id()); ?>" alt="<?php echo $alt; ?>">
                                    </div>
                                    <div class="cart-item__info">
                                        <a href="<?php echo get_the_permalink($product->get_id()); ?>" class="cart-item__title title-h5">
                                            <h2><?php echo $items_value['name']; ?></h2>
                                        </a>
                                    </div>
                                    <div class="cart-item__count">×<?php echo $items_value['qty']; ?></div>
                                    <div class="cart-item__total"><?php echo $items_value['line_total']; ?> ₴</div>
                                </article>
                            <?php endforeach; ?>
                            <?php $shipping_method = $order->get_shipping_method(); ?>
                            <?php if ($shipping_method == 'Local pickup') : ?>
                                <?php $shipping_method = pll__('Самовивіз із нашого складу'); ?>
                            <?php else : ?>
                                <?php $shipping_method = pll__('Доставка Новою Поштою'); ?>
                            <?php endif; ?>
                            <?php $payment_method = $order->get_payment_method_title(); ?>
                            <?php if ($payment_method == 'Check payments') : ?>
                                <?php $payment_method = pll__('Безготівковий розрахунок для юр. та фіз. осіб'); ?>
                            <?php elseif ($payment_method == 'Cash on delivery') : ?>
                                <?php $payment_method = pll__('Готівкою або карткою при отриманні'); ?>
                            <?php else : ?>
                                <?php $payment_method = pll__('LiqPay'); ?>
                            <?php endif; ?>
                            <div class="accordion__bottom">
                                <div class="accordion__delivery">
                                    <div class="accordion__delivery-row">
                                        <div class="accordion__delivery-street">
                                            <span class="accordion__text"><?php echo $shipping_method; ?></span>
                                            <?php if ($order->get_shipping_method() != 'Самовивіз') : ?>
                                                <address><?php echo str_replace('Самовивіз: ', '', str_replace('Нова пошта: ', '', $order->get_formatted_shipping_address())); ?></address>
                                            <?php endif; ?>
                                        </div>
                                        <!-- <div class="accordion__delivery-price">+153.53 ₴</div> -->
                                    </div>
                                    <span class="accordion__text"><?php echo $payment_method; ?></span>

                                    <?php if ($order->get_payment_method() == 'cheque') : ?>
                                        <?php echo do_shortcode('[wcpdf_download_invoice link_text="' . pll__('Завантажити квитанцію') . '" order_id="' . $order->get_id() . '" class="section-link"]'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="accordion__info info-closed">
                            <span class="accordion__text"><?php pll_e('Позицій:'); ?> <?php echo count($order_items); ?></span>
                            <span class="accordion__text"><?php echo $shipping_method; ?></span>
                            <span class="accordion__text"><?php echo $payment_method; ?></span>
                            <div class="total">
                                <?php pll_e('Сума:'); ?>
                                <span><?php echo $order->get_total(); ?> ₴</span>
                            </div>
                        </div>
                        <div class="accordion__info info-open">
                            <span class="accordion__text"><?php pll_e('Сума замовлення'); ?></span>
                            <div class="total">
                                <span><?php echo $order->get_total(); ?> ₴</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        </div>
        </section>

<?php
    }
}
