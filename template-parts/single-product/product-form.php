<?php

class Single_Product_Product_Form
{

    public function __construct()
    {

        $this->product_id = get_the_ID();
        $this->product = wc_get_product($this->product_id);
        $this->title = $this->product->get_name();

        $this->main_options = get_field('main_option');

        $this->attributes = get_terms([
            'taxonomy' => 'attribute',
            'object_ids' => [$this->product_id],
            'parent' => 0
        ]);

        $this->product_description = $this->product->get_description();

        $this->characteristics = get_field('characteristics');

        $type_main_category = get_terms([
            'taxonomy' => 'type',
            'parent' => 0,
            'object_ids' => [get_the_ID()]
        ]);

        if (!empty($type_main_category)) :

            $children_type_category = get_terms([
                'taxonomy' => 'type',
                'parent' => $type_main_category[0]->term_id,
                'object_ids' => [get_the_ID()]
            ]);

        endif;

        if (!empty($children_type_category)) :

            $term_characteristics = get_field('category_characteristics', $children_type_category[0]);

        elseif (!empty($type_main_category)) :

            $term_characteristics = get_field('category_characteristics', $type_main_category[0]);

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
        $main_options = get_field('main_options');

        $this->wholesale_options = get_field('wholesale_options');
        $this->is_wholesale = $this->wholesale_options['is_wholesale'];

        $this->availability = $this->product->get_availability();

        $this->term_characteristics = $term_characteristics;
    }

    public function render()
    {
?>

        <form class="product__form" data-product-id="<?php echo $this->product_id; ?>">
            <div class="mobile-hide">
                <?php if (!empty($this->main_options['product_code'])) : ?>
                    <div class="product__code">
                        <span><?php pll_e('SKU'); ?></span>
                        <?php echo $this->main_options['product_code']; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($this->title)) : ?>
                    <div class="product__title title-h1">
                        <h1><?php echo $this->title; ?></h1>
                    </div>
                <?php endif; ?>
                <div class="product__info">
                    <?php if (!empty($this->page_charatecristics)) : ?>
                        <?php foreach ($this->page_charatecristics as $key => $item) : ?>
                            <?php if (!empty($item)) : ?>
                                <?php $key = str_replace('_', ' ', $key); ?>
                                <div class="product__code product__code--small">
                                    <span><?php echo $key; ?></span>
                                    <?php if (is_array($item) and isset($item['measurement'])) : ?>
                                        <?php echo $item['value'] . ' ' . $item['measurement']; ?>
                                    <?php elseif(is_array($item)) : ?>
                                        <?php echo $item[0]; ?>
                                    <?php else : ?>
                                        <?php echo $item; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (is_post_favorite(get_the_ID())) : ?>
                        <a href="" class="js-add-favorite product-card__favorite is-added" title="<?php echo pll__('Видалити вибраних'); ?>" alt="<?php echo pll__('Видалити вибраних'); ?>" data-text-add="<?php echo pll__('До обраного'); ?>" data-text-added="<?php echo pll__('В обраних'); ?>">
                            <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.5 1L14.1971 8.78783L22.4371 8.9463L15.8639 13.9179L18.2595 21.8037L11.5 17.0885L4.74047 21.8037L7.13608 13.9179L0.56285 8.9463L8.80295 8.78783L11.5 1Z" stroke="#BDBDBD" stroke-linejoin="round" />
                            </svg>
                            <span><?php echo pll__('В обраних'); ?></span>
                        </a>
                    <?php else : ?>
                        <a href="" class="js-add-favorite product-card__favorite" title="<?php echo pll__('Додати до вибраних'); ?>" alt="<?php echo pll__('Додати до вибраних'); ?>" data-text-add="<?php echo pll__('До обраного'); ?>" data-text-added="<?php echo pll__('В обраних'); ?>">
                            <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.5 1L14.1971 8.78783L22.4371 8.9463L15.8639 13.9179L18.2595 21.8037L11.5 17.0885L4.74047 21.8037L7.13608 13.9179L0.56285 8.9463L8.80295 8.78783L11.5 1Z" stroke="#BDBDBD" stroke-linejoin="round" />
                            </svg>
                            <span><?php echo pll__('До обраного'); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($this->attributes)) : ?>
                <div class="product__choises">
                    <?php foreach ($this->attributes as $item) : ?>
                        <?php $subcategories = get_terms([
                            'taxonomy' => 'attribute',
                            'hide_empty' => true,
                            'parent' => $item->term_id
                        ]); ?>
                        <?php $short_name = get_field('short_name', $item); ?>
                        <?php if (!empty($subcategories)) : ?>
                            <div class="product__choises-item">
                                <?php if (!empty($short_name)) : ?>
                                    <div class="product__choises-title">
                                        <span><?php echo $short_name; ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (get_field('is_colour', $item)) : ?>
                                    <ul class="product__options product__options--colors">
                                        <?php foreach ($subcategories as $subitem) : ?>
                                            <?php $subcategory_post = get_posts([
                                                'post_type' => 'product',
                                                'post_status' => 'publish',
                                                'numberposts' => 1,
                                                'fields' => 'ids',
                                                'tax_query' => [
                                                    [
                                                        'taxonomy' => 'attribute',
                                                        'field' => 'slug',
                                                        'terms' => [$subitem->slug]
                                                    ]
                                                ]
                                            ]);
                                            if (!empty($subcategory_post)) : ?>
                                                <li>
                                                    <label>
                                                        <a <?php echo ($subcategory_post[0] != $this->product_id ? 'href="' . get_the_permalink($subcategory_post[0]) . '"' : ''); ?> class="input--radio">
                                                            <?php if ($subcategory_post[0] == $this->product_id) : ?>
                                                                <input type="radio" name="radio-<?php echo $item->term_id; ?>" checked>
                                                            <?php else : ?>
                                                                <input type="radio" name="radio-<?php echo $item->term_id; ?>">
                                                            <?php endif; ?>
                                                            <div class="input--radio__btn">
                                                                <span style="background: <?php echo get_field('colour_choise', $subitem); ?>;"></span>
                                                                <span><?php echo $subitem->name; ?></span>
                                                            </div>
                                                        </a>
                                                    </label>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else : ?>
                                    <ul class="product__options">
                                        <?php foreach ($subcategories as $subitem) : ?>
                                            <?php $subcategory_post = get_posts([
                                                'post_type' => 'product',
                                                'post_status' => 'publish',
                                                'numberposts' => 1,
                                                'fields' => 'ids',
                                                'tax_query' => [
                                                    [
                                                        'taxonomy' => 'attribute',
                                                        'field' => 'slug',
                                                        'terms' => [$subitem->slug]
                                                    ]
                                                ]
                                            ]);
                                            if (!empty($subcategory_post)) : ?>
                                                <li>
                                                    <label>
                                                        <a <?php echo ($subcategory_post[0] != $this->product_id ? 'href="' . get_the_permalink($subcategory_post[0]) . '"' : ''); ?> class="input--radio">
                                                            <?php if ($subcategory_post[0] == $this->product_id) : ?>
                                                                <input type="radio" name="radio-<?php echo $item->term_id; ?>" checked>
                                                            <?php else : ?>
                                                                <input type="radio" name="radio-<?php echo $item->term_id; ?>">
                                                            <?php endif; ?>
                                                            <span class="input--radio__btn"><?php echo $subitem->name; ?></span>
                                                        </a>
                                                    </label>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <ul class="product__variants">
                <!--
                status-available
                status-not-available
                status-wait
                -->
                <?php if ($this->availability['class'] == 'in-stock') : ?>
                    <li class="product-regular status-available">
                    <?php elseif ($this->availability['class'] == 'out-of-stock') : ?>
                    <li class="product-regular status-not-available">
                    <?php else : ?>
                    <li class="product-regular status-wait">
                    <?php endif; ?>
                    <div class="product__variants-row">
                        <label class="input--radio">
                            <?php if (isset($_GET['wholesale'])) : ?>
                                <input type="radio" name="Variants" value="1">
                            <?php else : ?>
                                <input type="radio" name="Variants" value="1" checked>
                            <?php endif; ?>
                            <span class="input--radio__check"><?php echo pll__('Упаковка'); ?> <?php echo $this->main_options['quantity_per_package']; ?> <?php echo pll__('шт.'); ?></span>
                            <div class="product__variants-info">
                                <div class="product-card__content">
                                    <?php if ($this->product->is_on_sale()) : ?>
                                        <?php $price_for_count = $this->product->get_sale_price(); ?>
                                        <div class="product-card__price">
                                            <div class="product-card__price--old">
                                                <span><?php echo $this->product->get_regular_price(); ?> ₴</span>
                                            </div>
                                            <div class="product-card__price--new">
                                                <span><?php echo $this->product->get_sale_price(); ?> ₴</span>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <?php $price_for_count = $this->product->get_price(); ?>
                                        <div class="product-card__price">
                                            <div class="product-card__price--new">
                                                <span><?php echo $this->product->get_price(); ?> ₴</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="product-card__package">
                                        <p><?php echo number_format((float)$price_for_count / $this->main_options['quantity_per_package'], 2, '.', ''); ?> ₴ <?php echo pll__('за одиницю'); ?></p>
                                    </div>
                                </div>
                                <div class="product__variants-status">
                                    <div class="product__status">
                                        <?php if ($this->product->is_in_stock()) : ?>
                                            <p><?php pll_e('В наявності на складі'); ?></p>
                                        <?php else : ?>
                                            <p><?php pll_e('Немає у наявності'); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="quantity">
                                        <button type="button" class="quantity__btn" data-direction="minus">
                                            <svg width="14" height="4" viewBox="0 0 14 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect y="4" width="4" height="14" transform="rotate(-90 0 4)" fill="#E0E0E0"></rect>
                                            </svg>
                                        </button>
                                        <input class="quantity__input" type="text" min="1" max="" value="1" readonly="">
                                        <button type="button" class="quantity__btn" data-direction="plus">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="5" width="4" height="14" fill="#E0E0E0"></rect>
                                                <rect y="9" width="4" height="14" transform="rotate(-90 0 9)" fill="#E0E0E0"></rect>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="product__variants-btns">
                        <button class="btn product__btn-buy" type="button">
                            <?php echo pll__('Додати до кошика'); ?>
                        </button>
                        <?php if (is_user_logged_in()) : ?>
                            <button class="btn btn--white product__btn-message js-add-favorite is-unavailable" type="button">
                                <?php echo pll__('Повідомити про появу'); ?>
                            </button>
                        <?php else : ?>
                            <a href="#login-popup" data-fancybox class="btn btn--white product__btn-message">
                                <?php echo pll__('Повідомити про появу'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    </li>
                    <?php if ($this->is_wholesale) : ?>
                        <li class="product-wholesale status-available">
                            <div class="product__variants-row">
                                <label class="input--radio">
                                    <?php if (isset($_GET['wholesale'])) : ?>
                                        <input type="radio" name="Variants" value="1" checked>
                                    <?php else : ?>
                                        <input type="radio" name="Variants" value="1">
                                    <?php endif; ?>
                                    <?php if (!empty($this->wholesale_options['above_text'])) : ?>
                                        <span class="input--radio__check"><?php echo $this->wholesale_options['above_text']; ?></span>
                                    <?php endif; ?>
                                    <div class="product__variants-info">
                                        <div class="product-card__content">
                                            <?php if (!empty($this->wholesale_options['price_text'])) : ?>
                                                <div class="product-card__price">
                                                    <div class="product-card__price--new">
                                                        <span><?php echo $this->wholesale_options['price_text']; ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($this->wholesale_options['under_the_price_text'])) : ?>
                                                <span class="cart-item__package"><?php echo $this->wholesale_options['under_the_price_text']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product__variants-status">
                                            <?php if (!empty($this->wholesale_options['stock_status'])) : ?>

                                                <?php foreach (get_field('wholesale_options', 'option') as $item) :
                                                    if ($this->wholesale_options['stock_status'] == $item['status_text_' . pll_current_language()]) :
                                                        $color = $item['text_color'];
                                                    endif;
                                                endforeach; ?>
                                                <div class="product__status">
                                                    <p style="color: <?php echo $color; ?>"><?php echo $this->wholesale_options['stock_status']; ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <div class="quantity">
                                                <button type="button" class="quantity__btn" data-direction="minus">
                                                    <svg width="14" height="4" viewBox="0 0 14 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect y="4" width="4" height="14" transform="rotate(-90 0 4)" fill="#E0E0E0"></rect>
                                                    </svg>
                                                </button>
                                                <input class="quantity__input" type="text" min="1" max="" value="1" readonly="">
                                                <button type="button" class="quantity__btn" data-direction="plus">
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="5" width="4" height="14" fill="#E0E0E0"></rect>
                                                        <rect y="9" width="4" height="14" transform="rotate(-90 0 9)" fill="#E0E0E0"></rect>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="product__variants-btns">
                                <button class="btn product__btn-buy" type="button">
                                    <?php echo pll__('Додати до замовлення'); ?>
                                </button>
                                <button class="btn btn--white product__btn-message js-add-favorite" type="button">
                                    <?php echo pll__('Повідомити про появу'); ?>
                                </button>
                            </div>
                        </li>
                    <?php endif; ?>
            </ul>
            <?php if (!empty($this->main_options['related_products'])) : ?>
                <div class="product__similiar">
                    <div class="product__similiar-title title-h5">
                        <h3><?php echo pll__('Супутні товари'); ?></h3>
                    </div>
                    <div class="product__similiar-container">
                        <?php foreach ($this->main_options['related_products'] as $item) : ?>
                            <?php $item_main_options = get_field('main_option', $item); ?>
                            <?php $product = wc_get_product($item); ?>
                            <article class="product-card similiar-card">
                                <div class="product-card__top">
                                    <div class="product-card__tags">
                                        <?php if (in_array('new', $item_main_options['product_tag'])) : ?>
                                            <span class="tag tag--new"><?php echo pll__('Новий'); ?></span>
                                        <?php endif; ?>
                                        <?php if (in_array('sale', $item_main_options['product_tag'])) : ?>
                                            <span class="tag"><?php echo pll__('Акція'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <a href="<?php echo get_the_permalink($item); ?>" class="similiar-card__link">
                                    <?php $post_thumbnail = get_the_post_thumbnail_url($item); ?>
                                    <?php $thumbnail_id = get_post_thumbnail_id($item);
                                    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                                    <?php if (!empty($post_thumbnail)) : ?>
                                        <div class="similiar-card__img">
                                            <img src="<?php echo $post_thumbnail; ?>" alt="<?php echo $alt; ?>">
                                        </div>
                                    <?php endif; ?>
                                    <div class="similiar-card__content">
                                        <div class="similiar-card__info">
                                            <div class="similiar-card__title">
                                                <h4><?php echo get_the_title($item); ?></h4>
                                            </div>
                                            <div class="similiar-card__content product-card__content mobile-hide">
                                                <?php if ($product->is_on_sale()) : ?>
                                                    <?php $price_for_count = $product->get_sale_price(); ?>
                                                    <div class="product-card__price">
                                                        <div class="product-card__price--old">
                                                            <span><?php echo $product->get_regular_price(); ?> ₴</span>
                                                        </div>
                                                        <div class="product-card__price--new">
                                                            <span><?php echo $product->get_sale_price(); ?> ₴</span>
                                                        </div>
                                                    </div>
                                                <?php else : ?>
                                                    <?php $price_for_count = $product->get_price(); ?>
                                                    <div class="product-card__price">
                                                        <div class="product-card__price--new">
                                                            <span><?php echo $product->get_price(); ?> ₴</span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($item_main_options['quantity_per_package'])) :  ?>
                                                    <div class="product-card__package">
                                                        <p><?php echo $item_main_options['quantity_per_package']; ?> <?php echo pll__('шт.'); ?> × <?php echo number_format((float)$price_for_count / $item_main_options['quantity_per_package'], 2, '.', ''); ?> ₴</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php $characteristics = get_field('characteristics', $item);

                                        $article_characteristics = [];

                                        $type_main_category = get_terms([
                                            'taxonomy' => 'type',
                                            'parent' => 0,
                                            'object_ids' => [$item]
                                        ]);

                                        if (!empty($type_main_category)) :

                                            $children_type_category = get_terms([
                                                'taxonomy' => 'type',
                                                'parent' => $type_main_category[0]->term_id,
                                                'object_ids' => [$item]
                                            ]);

                                        endif;

                                        if (!empty($children_type_category)) :

                                            $term_characteristics = get_field('category_characteristics', $children_type_category[0]);

                                        elseif (!empty($type_main_category)) :

                                            $term_characteristics = get_field('category_characteristics', $type_main_category[0]);

                                        endif;

                                        foreach ($characteristics as $key => $characteristic) :

                                            $key = str_replace('_', ' ', $key);

                                            foreach ($term_characteristics as $subitem) :

                                                $subitem_key_without_spaces = str_replace(' ', '', $subitem['characteristic_type']);
                                                if (($key == $subitem['characteristic_type'] or $key == $subitem_key_without_spaces) and !empty($subitem['is_on_card']) and is_array($characteristic)) :
                                                    $article_characteristics[$subitem['characteristic_type']] = ['value' => $characteristic[0], 'image' => $subitem['image']];
                                                endif;

                                            endforeach;

                                        endforeach; ?>

                                        <div class="product__row">
                                            <?php if (!empty($article_characteristics)) : ?>

                                                <ul class="product-card__spec">
                                                    <?php foreach ($article_characteristics as $key => $subitem) : ?>
                                                        <li alt="<?php echo $key; ?>" title="<?php echo $key; ?>">
                                                            <?php if (!empty($subitem['image'])) : ?>
                                                                <img src="<?php echo $subitem['image']; ?>" alt="ico-card-img">
                                                            <?php endif; ?>
                                                            <span><?php echo $subitem['value']; ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                            <div class="product-card__content mobile-show">
                                                <?php if ($product->is_on_sale()) : ?>
                                                    <div class="product-card__price">
                                                        <div class="product-card__price--old">
                                                            <span><?php echo $product->get_regular_price(); ?> ₴</span>
                                                        </div>
                                                        <div class="product-card__price--new">
                                                            <span><?php echo $product->get_sale_price(); ?> ₴</span>
                                                        </div>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="product-card__price">
                                                        <div class="product-card__price--new">
                                                            <span><?php echo $product->get_price(); ?> ₴</span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($item_main_options['quantity_per_package'])) :  ?>
                                                    <div class="product-card__package">
                                                        <p><?php echo $item_main_options['quantity_per_package']; ?> <?php echo pll__('шт.'); ?> × <?php echo number_format((float)$price_for_count / $item_main_options['quantity_per_package'], 2, '.', ''); ?> ₴</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="product__table">
                <?php if (!empty($this->main_options['related_files'])) : ?>
                    <?php foreach ($this->main_options['related_files'] as $item) : ?>
                        <a href="<?php echo $item['file']; ?>" class="product__sketch section-link">
                            <img src="<?= TEMPLATE_PATH ?>/img/ico-sketch.svg" alt="sketch">
                            <span><?php echo $item['name']; ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if (!empty($this->characteristics)) : ?>
                    <ul>
                        <?php foreach ($this->characteristics as $key => $item) : ?>
                            <?php $measurement = ''; ?>
                            <?php $update_measurement = ''; ?>
                            <?php if (!empty($item)) : ?>
                                <?php if( str_contains($key, '_value') ) : ?>

                                    <?php if( !str_contains($key, 'additional_value' ) ) : ?>
                                        <?php $update_measurement = true; ?>
                                    <?php endif;?>
                                    <?php $key = str_replace(['_value', '_additional'], ['', ''], $key); ?>
                                    <?php if( !empty($update_measurement) ) : ?>
                                        <?php $measurement = get_option('measurement_' . $key); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php foreach ($this->term_characteristics as $subitem) :

                                    $subitem_key_without_spaces = str_replace(' ', '', $subitem['characteristic_type']);

                                    if ($key == $subitem['characteristic_type'] or ($key == $subitem_key_without_spaces)) :
                                        $option_name = $subitem['characteristic_type'];
                                    endif;

                                endforeach; ?>
                                <li>
                                    <span><?php echo $option_name; ?></span>
                                    <?php if (is_array($item)) : ?>
                                        <span><?php echo $item[0]; ?></span>
                                    <?php else : ?>
                                        <span><?php echo $item; ?>
                                        <?php if( !empty( $measurement ) ) : ?>
                                            <?php echo ' ' . $measurement; ?>
                                        <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php if (!empty($this->product_description)) : ?>
                <div class="section-text section-text--big">
                    <?php echo $this->product_description; ?>
                </div>
            <?php endif; ?>
        </form>

<?php
    }
}
