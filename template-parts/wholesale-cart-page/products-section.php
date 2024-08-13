<?php

class Wholesale_Cart_Products_Section
{

    public function __construct()
    {

        $this->products = get_wholesale_products();
    }

    public function render()
    {
?>

        <section class="cart cart--wholesale">
            <div class="container">
                <div class="cart__container">
                    <div class="cart__title title-h1">
                        <h1><?php echo pll__('Оптове замовлення'); ?></h1>
                    </div>

                    <div class="cart__body">
                        <?php if (!empty($this->products)) : ?>
                            <?php foreach ($this->products as $item) : ?>
                                <?php $permalink = get_the_permalink($item['product_id']); ?>
                                <?php $wholesale_options = get_field('wholesale_options', $item['product_id']); ?>
                                <?php $post_thumbnail = get_the_post_thumbnail_url($item['product_id']); ?>
                                <?php $thumbnail_id = get_post_thumbnail_id($item['product_id']);
                                $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                                <article class="cart-item product-wholesale" data-cart-id="<?php echo $item['product_id']; ?>">
                                    <?php if (!empty($post_thumbnail)) : ?>
                                        <a href="<?php echo $permalink; ?>" class="cart-item__img">
                                            <img src="<?php echo $post_thumbnail; ?>" alt="<?php echo $alt; ?>">
                                        </a>
                                    <?php endif; ?>
                                    <div class="cart-item__info">
                                        <a href="<?php echo $permalink; ?>" class="cart-item__title title-h5">
                                            <h2><?php echo get_the_title($item['product_id']); ?></h2>
                                        </a>
                                        <?php if (!empty($wholesale_options['above_text']) or !empty($wholesale_options['price_text'])) : ?>
                                            <span class="cart-item__package"><?php echo $wholesale_options['above_text'] . ', ' . $wholesale_options['price_text']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="quantity">
                                        <button type="button" class="quantity__btn" data-direction="minus">
                                            <svg width="14" height="4" viewBox="0 0 14 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect y="4" width="4" height="14" transform="rotate(-90 0 4)" fill="#E0E0E0"></rect>
                                            </svg>
                                        </button>
                                        <input class="quantity__input" type="number" min="1" max="" value="<?php echo $item['quantity']; ?>" readonly="" name="Quantity">
                                        <button type="button" class="quantity__btn" data-direction="plus">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="5" width="4" height="14" fill="#E0E0E0"></rect>
                                                <rect y="9" width="4" height="14" transform="rotate(-90 0 9)" fill="#E0E0E0"></rect>
                                            </svg>
                                        </button>
                                    </div>
                                    <button class="cart-item__close" type="button">
                                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 1L12 12M12 1L1 12" stroke="#333333" stroke-width="2"></path>
                                        </svg>
                                    </button>
                                </article>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <h3><?php pll_e('В вашій корзині пусто'); ?></h3>
                        <?php endif; ?>
                    </div>

            <?php
        }
    }
