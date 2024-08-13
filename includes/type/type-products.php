<?php

class Type_Products
{

    public static function init()
    {
        add_action('acf/init', [__CLASS__, 'acf_add_local_field_group']);
        add_action('init', [__CLASS__, 'pll_strings']);

        add_action('wp_ajax_nopriv_add_to_cart', [__CLASS__, 'add_to_cart']);
        add_action('wp_ajax_add_to_cart', [__CLASS__, 'add_to_cart']);

        add_action('wp_ajax_nopriv_add_to_cart_wholesale', [__CLASS__, 'add_to_cart_wholesale']);
        add_action('wp_ajax_add_to_cart_wholesale', [__CLASS__, 'add_to_cart_wholesale']);

        add_action('save_post', [__CLASS__, 'add_post_meta_for_search']);
        add_action('save_post', [__CLASS__, 'add_needed_post_meta_to_terms']);

        //        add_action('save_post', [__CLASS__, 'addNeededPostMetaRangeAttributes']);

        //        add_action('delete_term', [__CLASS__, 'clearTermOnDelete'], 10, 4 );
    }


    public static function pll_strings()
    {
        pll_register_string('product-1', 'Упаковка', 'product');
        pll_register_string('product-2', 'шт.', 'product');
        pll_register_string('product-3', 'за од.', 'product');
        pll_register_string('product-4', 'Акція', 'product');
        pll_register_string('product-5', 'в упаковці', 'product');
        pll_register_string('product-6', 'У кошик', 'product');
        pll_register_string('product-7', 'Замовити оптову кількість', 'product');
        pll_register_string('product-8', 'Схожі товари', 'product');
        pll_register_string('product-9', 'Новий', 'product');
        pll_register_string('product-10', 'Акція', 'product');
        pll_register_string('product-11', 'До обраного', 'product');
        pll_register_string('product-12', 'за одиницю', 'product');
        pll_register_string('product-13', 'Повідомити про появу', 'product');
        pll_register_string('product-14', 'Додати до кошика', 'product');
        pll_register_string('product-15', 'Немає у наявності', 'product');
        pll_register_string('product-16', 'Коли з’явиться?', 'product');
        pll_register_string('product-17', 'В наявності на складі', 'product');
        pll_register_string('product-18', 'Додати до замовлення', 'product');
        pll_register_string('product-19', 'SKU', 'product');
        pll_register_string('product-20', 'Ви отримаєте на Email сповіщення, коли даний товар з’явиться у продажу', 'product');
    }

    public static function add_post_meta_for_search($post_ID)
    {
        $post = get_post($post_ID);

        if ($post->post_type == 'product') :

            update_post_meta($post_ID, 'title_for_search', get_the_title($post_ID));

            $product_code = '';
            $main_option = get_field('main_option', $post_ID);

            if (!empty($main_option['product_code'])) :
                $product_code = $main_option['product_code'];
            endif;

            update_post_meta($post_ID, 'product_code', $product_code);

            $product = wc_get_product($post_ID);

            $description = '';
            $new_description = $product->get_description();

            if (!empty($new_description)) :
                $description = $new_description;
            endif;

            update_post_meta($post_ID, 'description_for_search', $description);

        endif;
    }

    public static function add_needed_post_meta_to_terms($post_ID)
    {

        $post = get_post($post_ID);

        if ($post->post_type !== 'product') :
            return;
        endif;

        $product = wc_get_product($post_ID);

        if ($post->post_status == 'publish') :

            $price = $product->get_price();

            $type_terms = get_the_terms($post_ID, 'type');
            $appointment_terms = get_the_terms($post_ID, 'appointment');

            $type_terms = !empty($type_terms) ? $type_terms : [];
            $appointment_terms = !empty($appointment_terms) ? $appointment_terms : [];

            if (!empty($type_terms) and !empty($appointment_terms)) :

                foreach ($type_terms as $item) :

                    $type_term_appointment_meta = get_term_meta($item->term_id, 'appointment_terms');
                    $type_term_appointment_meta = !empty($type_term_appointment_meta) ? $type_term_appointment_meta : [];



                    foreach ($appointment_terms as $subitem) :

                        if (!in_array($subitem->term_id, $type_term_appointment_meta)) :
                            add_term_meta($item->term_id, 'appointment_terms', $subitem->term_id);
                        endif;


                        $appointment_term_type_meta = get_term_meta($subitem->term_id, 'type_terms');
                        $appointment_term_type_meta = !empty($appointment_term_type_meta) ? $appointment_term_type_meta : [];


                        if (!in_array($item->term_id, $appointment_term_type_meta)) :
                            add_term_meta($subitem->term_id, 'type_terms', $item->term_id);
                        endif;

                    endforeach;

                endforeach;

            endif;

            $terms = array_merge($type_terms, $appointment_terms);
            $product_characteristics = get_field('characteristics', $post_ID);

            if (!empty($terms)) :

                foreach ($terms as $main_key => $item) :

                    self::update_prices($item);

                    foreach ($product_characteristics as $key => $characteristic) :

                        $previous_key_value = get_post_meta($post_ID, 'previous_' . $key, true);

                        if (!empty($characteristic)) {

                            if (str_contains($key, '_value') and !str_contains($key, '_additional')) :

                                if ((!empty($previous_key_value) and ($previous_key_value != $characteristic)) or empty(get_term_meta($item->ID, 'min_' . $key))) :

                                    self::updateTermRanges($item, $key);

                                endif;

                                $possible_range_keys = get_post_meta($post_ID, 'possible_range_keys');
                                $possible_range_keys = !empty($possible_range_keys) ? $possible_range_keys : [];

                                if (!in_array($key, $possible_range_keys)) :

                                    add_post_meta($post_ID, 'possible_range_keys', $key);

                                endif;

                            else :

                                $key_term_values = get_term_meta($item->term_id, $key);
                                $key_term_values = !empty($key_term_values) ? $key_term_values : [];

                                if (!in_array($characteristic, $key_term_values)) :

                                    add_term_meta($item->term_id, $key, $characteristic);

                                endif;

                                if (!empty($previous_key_value) and ($previous_key_value != $characteristic)) :

                                    $product_to_check = get_posts([
                                        'post_type' => 'product',
                                        'post_status' => 'publish',
                                        'numberposts' => 1,
                                        'tax_query' => [
                                            [
                                                'taxonomy' => $item->taxonomy,
                                                'field' => 'slug',
                                                'terms' => $item->slug
                                            ]
                                        ],
                                        'meta_query' => [
                                            [
                                                'key' => 'characteristics_' . $key,
                                                'value' => $previous_key_value
                                            ]
                                        ]
                                    ]);

                                    if (empty($product_to_check)) :

                                        delete_metadata('term', $item->term_id, $key, $previous_key_value);

                                    endif;

                                endif;

                            endif;

                            update_post_meta($post_ID, 'previous_' . $key, $characteristic);
                        }

                    endforeach;

                endforeach;

            endif;

        endif;


        $previous_post_status = get_post_meta($post_ID, 'previous_status', true);

        if ($previous_post_status == 'publish' and ($post->post_status != 'publish')) :

            $type_terms = get_the_terms($post_ID, 'type');
            $type_terms = !empty($type_terms) ? $type_terms : [];
            $appointment_terms = get_the_terms($post_ID, 'appointment');
            $appointment_terms = !empty($appointment_terms) ? $appointment_terms : [];

            $terms = array_merge($type_terms, $appointment_terms);
            $product_characteristics = get_field('characteristics', $post_ID);
            $product_price = $product->get_price();

            foreach ($terms as $item) :

                foreach ($product_characteristics as $key => $characteristic) :

                    if (str_contains($key, '_value') and !str_contains($key, '_additional')) :

                        self::updateTermRanges($item, $key);
                        delete_post_meta($post_ID, 'possible_range_keys', $key);

                    else :

                        $key_term_values = get_term_meta($item->term_id, $key);
                        $key_term_values = !empty($key_term_values) ? $key_term_values : [];

                        if (!empty($characteristic) and in_array($characteristic, $key_term_values)) :

                            $product_to_check = get_posts([
                                'post_type' => 'product',
                                'post_status' => 'publish',
                                'numberposts' => 1,
                                'tax_query' => [
                                    [
                                        'taxonomy' => $item->taxonomy,
                                        'field' => 'slug',
                                        'terms' => $item->slug
                                    ]
                                ],
                                'meta_query' => [
                                    [
                                        'key' => 'characteristics_' . $key,
                                        'value' => $characteristic
                                    ]
                                ]
                            ]);

                            if (empty($product_to_check)) :

                                delete_metadata('term', $item->term_id, $key, $characteristic);

                            endif;


                        endif;

                    endif;

                    update_post_meta($post_ID, 'previous_' . $key, $characteristic);

                endforeach;

                $min_term_price = get_term_meta($item->term_id, 'min_price', true);
                $max_term_price = get_term_meta($item->term_id, 'max_price', true);


                if ($min_term_price == $product_price or ($max_term_price == $product_price)) :

                    $term_products = get_posts([
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'numberposts' => -1,
                        'exclude' => [$post_ID],
                        'tax_query' => [
                            [
                                'taxonomy' => $item->taxonomy,
                                'field' => 'slug',
                                'terms' => $item->slug
                            ]
                        ]
                    ]);


                    if (!empty($term_products)) :

                        $prices = [];

                        foreach ($term_products as $subitem) :

                            $subitem_product = wc_get_product($subitem->ID);
                            $prices[] = $subitem_product->get_price();

                        endforeach;

                        update_term_meta($item->term_id, 'min_price', min($prices));
                        update_term_meta($item->term_id, 'max_price', max($prices));

                    endif;

                endif;

            endforeach;

            if (!empty($type_terms) and !empty($appointment_terms)) :

                foreach ($type_terms as $item) :

                    foreach ($appointment_terms as $subitem) :

                        $product_to_check = get_posts([
                            'post_type' => 'product',
                            'post_status' => 'publish',
                            'numberposts' => 1,
                            'tax_query' => [
                                'relation' => 'AND',
                                [
                                    'taxonomy' => $item->taxonomy,
                                    'field' => 'slug',
                                    'terms' => $item->slug
                                ],
                                [
                                    'taxonomy' => $subitem->taxonomy,
                                    'field' => 'slug',
                                    'terms' => $subitem->slug
                                ]
                            ]
                        ]);

                        if (empty($product_to_check)) :
                            delete_metadata('term', $subitem->term_id, 'type_terms', $item->term_id);
                            delete_metadata('term', $item->term_id, 'appointment_terms', $subitem->term_id);
                        endif;

                    endforeach;


                endforeach;
            endif;

        endif;


        update_post_meta($post_ID, 'previous_status', $post->post_status);
    }

    public static function updateTermRanges($term, $key)
    {

        $term_products = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => $term->taxonomy,
                    'field' => 'slug',
                    'terms' => $term->slug
                ]
            ]
        ]);


        if (!empty($term_products)) :

            $values = [];

            foreach ($term_products as $subitem) :

                $subitem_characteristic_value = get_field('characteristics', $subitem->ID)[$key];
                if (!empty($subitem_characteristic_value)) :
                    $values[] = $subitem_characteristic_value;
                endif;

            endforeach;

            if (!empty($values)) {
                update_term_meta($term->term_id, 'min_' . $key, min($values));
            }
            if (!empty($values)) {
                update_term_meta($term->term_id, 'max_' . $key, max($values));
            }

        endif;
    }


    /**
     * @param $subitem
     * @param $price
     * @return void
     */
    public static function update_prices($term)
    {

        $term_products = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => $term->taxonomy,
                    'field' => 'slug',
                    'terms' => $term->slug
                ]
            ]
        ]);


        if (!empty($term_products)) :

            $prices = [];

            foreach ($term_products as $subitem) :

                $subitem_product = wc_get_product($subitem->ID);
                $prices[] = $subitem_product->get_price();

            endforeach;

            update_term_meta($term->term_id, 'min_price', min($prices));
            update_term_meta($term->term_id, 'max_price', max($prices));

        endif;
    }

    public static function add_to_cart()
    {

        if (!empty($_POST['quantity']) and !empty($_POST['product_id'])) :

            WC()->cart->add_to_cart($_POST['product_id'], $_POST['quantity']);

            wp_send_json_success(get_mini_cart_content(), 200);
        endif;
    }

    public static function add_to_cart_wholesale()
    {
        if (!empty($_POST['quantity']) and !empty($_POST['product_id'])) :

            $post_id = $_POST['product_id'];

            $post_language = pll_get_post_language($post_id);

            foreach (pll_languages_list() as $item) :

                if ($item != $post_language) :

                    $translated_post = pll_get_post($post_id, $item);

                    if (!empty($post)) :
                        $posts[$item] = $translated_post;
                    endif;

                endif;

            endforeach;

            $posts[$post_language] = $post_id;

            if (is_user_logged_in()) :
                $user = wp_get_current_user();

                foreach ($posts as $key => $item) :
                    $old_product_quantity = 0;

                    if (!empty(get_user_meta($user->data->ID, 'wholesale_products_' . $key)) and !empty(get_user_meta($user->data->ID, 'wholesale_products_' . $key)[0])) {

                        foreach (get_user_meta($user->data->ID, 'wholesale_products_' . $key) as $added_item) {
                            if ($added_item['product_id'] == $post_id) {
                                $old_product_quantity = $added_item['quantity'];
                                delete_user_meta($user->data->ID, 'wholesale_products_' . $key, ['product_id' => $post_id, 'quantity' => $old_product_quantity]);
                            }
                        }
                        add_user_meta($user->data->ID, 'wholesale_products_' . $key, ['product_id' => $item, 'quantity' => $_POST['quantity'] + $old_product_quantity]);
                    } else {
                        update_user_meta($user->data->ID, 'wholesale_products_' . $key, ['product_id' => $item, 'quantity' => $_POST['quantity'] + $old_product_quantity]);
                    }

                endforeach;


            else :
                foreach ($posts as $key => $item) :
                    set_wholesale_products(['product_id' => $item, 'quantity' => $_POST['quantity']], $key);
                endforeach;

            endif;

            wp_send_json_success(get_mini_cart_content(true), 200);

        endif;
    }


    public function render_product_article($product_id)
    {

        $_SESSION['current_post'] = $product_id;
        wp_reset_postdata();
        setup_postdata($product_id);
        // var_dump($product_id);die;
        // the_post();

        $product = wc_get_product($product_id);
        $product_group = get_field('main_option', $product_id);
        if ($product->is_on_sale()) :
            $price_for_count = $product->get_sale_price();
        else :
            $price_for_count = $product->get_price();
        endif;

        $characteristics = get_field('characteristics', $product_id);


        $article_characteristics = [];

        $type_main_category = get_terms([
            'taxonomy' => 'type',
            'parent' => 0,
            'object_ids' => [$product_id]
        ]);

        if (!empty($type_main_category)) :

            $children_type_category = get_terms([
                'taxonomy' => 'type',
                'parent' => $type_main_category[0]->term_id,
                'object_ids' => [$product_id]
            ]);

        endif;

        if (!empty($children_type_category)) :

            $term_characteristics = get_field('category_characteristics', $children_type_category[0]);

        elseif (!empty($type_main_category)) :

            $term_characteristics = get_field('category_characteristics', $type_main_category[0]);

        endif;

        $possible_range_keys = get_post_meta($product_id, 'possible_range_keys');


        foreach ($characteristics as $key => $characteristic) :

            foreach ($term_characteristics as $subitem) :
                $subitem_key_without_spaces = str_replace(' ', '', $subitem['characteristic_type']);
                if (($key == $subitem['characteristic_type'] or $key == $subitem_key_without_spaces) and !empty($subitem['is_on_card'])) :

                    $article_characteristics[$subitem['characteristic_type']] = ['value' => $characteristic, 'image' => $subitem['image']];
                endif;

                if (in_array($key, $possible_range_keys)) :

                    $key_to_search = str_replace(['_value', '_'], '', $key);
                    $key_without_value_string = str_replace('_value', '', $key);

                    if (($key_to_search == $subitem['characteristic_type'] or $key_to_search == $subitem_key_without_spaces) and !empty($subitem['is_on_card'])) :

                        if (!empty($characteristics[$key_without_value_string . '_additional_value'])) :

                            $value = $characteristics[$key_without_value_string . '_additional_value'];
                            $measurement = '';
                        else :

                            $value = $characteristics[$key_without_value_string . '_value'];
                            $measurement = get_option('measurement_' . $subitem_key_without_spaces);

                        endif;

                        $article_characteristics[$subitem['characteristic_type']] = [
                            'value' => $value,
                            'measurement' => $measurement,
                            'image' => $subitem['image']
                        ];

                    endif;
                endif;

            endforeach;

        endforeach; ?>

        <?php if ($product->is_in_stock()) : ?>
            <article class="product-card" data-product-id="<?php echo $product_id ?>" style="z-index: 999;">
                <div class="product-card__top">
                    <div class="product-card__tags">
                        <?php if (in_array('new', $product_group['product_tag'])) : ?>
                            <span class="tag tag--new"><?php echo pll__('Новий'); ?></span>
                        <?php endif; ?>
                        <?php if (in_array('sale', $product_group['product_tag'])) : ?>
                            <span class="tag"><?php echo pll__('Акція'); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (is_post_favorite($product_id)) : ?>
                        <a href="" class="js-add-favorite product-card__favorite is-added" title="<?php echo pll__('Видалити вибраних'); ?>" alt="<?php echo pll__('Видалити вибраних'); ?>">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5 1L16.2038 8.77852L24.4371 8.9463L17.8749 13.9215L20.2595 21.8037L13.5 17.1L6.74047 21.8037L9.12514 13.9215L2.56285 8.9463L10.7962 8.77852L13.5 1Z" stroke="#333333" stroke-linejoin="round" />
                                <rect class="cross-line" x="1.23438" y="20.458" width="29" height="1" transform="rotate(-38.0739 1.23438 20.458)" fill="#333333" />
                                <rect class="cross-line" y="18.8838" width="29" height="2" transform="rotate(-38.0739 0 18.8838)" fill="white" />
                            </svg>
                        </a>
                    <?php else : ?>
                        <a href="" class="js-add-favorite product-card__favorite" title="<?php echo pll__('Додати до вибраних'); ?>" alt="<?php echo pll__('Додати до вибраних'); ?>">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5 1L16.2038 8.77852L24.4371 8.9463L17.8749 13.9215L20.2595 21.8037L13.5 17.1L6.74047 21.8037L9.12514 13.9215L2.56285 8.9463L10.7962 8.77852L13.5 1Z" stroke="#333333" stroke-linejoin="round" />
                                <rect class="cross-line" x="1.23438" y="20.458" width="29" height="1" transform="rotate(-38.0739 1.23438 20.458)" fill="#333333" />
                                <rect class="cross-line" y="18.8838" width="29" height="2" transform="rotate(-38.0739 0 18.8838)" fill="white" />
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
                <a href="<?php echo get_the_permalink($product_id); ?>" class="product-card__link">
                    <?php $post_thumbnail = get_the_post_thumbnail_url($product_id); ?>
                    <?php $thumbnail_id = get_post_thumbnail_id($product_id);
                    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                    <div class="product-card__img">
                        <?php if (!empty($post_thumbnail)) : ?>
                            <img src="<?php echo $post_thumbnail; ?>" alt="<?php echo $alt; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="product-card__info">
                        <div class="product-card__title">
                            <h3><?php echo get_the_title($product_id); ?></h3>
                        </div>
                        <div class="product-card__about">
                            <?php if (!empty($article_characteristics)) : ?>
                                <ul class="product-card__spec">
                                    <?php foreach ($article_characteristics as $key => $subitem) : ?>
                                        <?php if (!empty($subitem['value'])) : ?>
                                            <li alt="<?php echo $key; ?>" title="<?php echo $key; ?>">
                                                <?php if (!empty($subitem['image'])) : ?>
                                                    <img src="<?php echo $subitem['image']; ?>">
                                                <?php endif; ?>
                                                <span><?php echo $subitem['value']; ?><?php echo !empty($subitem['measurement']) ? ' ' . $subitem['measurement'] : ''; ?></span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <div class="product-card__content">
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
                                <?php if (!empty($product_group['quantity_per_package'])) : ?>
                                    <div class="product-card__package">
                                        <p><?php echo $product_group['quantity_per_package']; ?> <?php echo pll__('шт.'); ?>
                                            × <?php echo number_format((float)$price_for_count / $product_group['quantity_per_package'], 2, '.', ''); ?>
                                            ₴ <br><?php echo pll__('в упаковці'); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="product-card__buttons">
                    <div class="product-card__row">
                        <button class="btn btn--medium product__btn-buy"><?php echo pll__('У кошик'); ?></button>
                        <div class="product-card__quantity quantity">
                            <button type="button" class="quantity__btn" data-direction="minus">
                                <svg width="14" height="4" viewBox="0 0 14 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect y="4" width="4" height="14" transform="rotate(-90 0 4)" fill="#E0E0E0"></rect>
                                </svg>
                            </button>
                            <input class="quantity__input" type="text" min="1" max="" value="1" readonly="" name="Quantity">
                            <button type="button" class="quantity__btn" data-direction="plus">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="5" width="4" height="14" fill="#E0E0E0"></rect>
                                    <rect y="9" width="4" height="14" transform="rotate(-90 0 9)" fill="#E0E0E0"></rect>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <?php if (get_field('wholesale_options', $product_id)['is_wholesale']) : ?>
                        <a href="<?php echo get_the_permalink($product_id); ?>?wholesale=1" class="section-link"><?php echo pll__('Замовити оптову кількість'); ?></a>
                    <?php endif; ?>
                </div>
            </article>

        <?php else : ?>

            <article class="product-card is-unavailable" data-product-id="<?php echo $product_id ?>" style="z-index: 999;">
                <div class="product-card__top">
                    <div class="product-card__tags">
                        <?php if (in_array('new', $product_group['product_tag'])) : ?>
                            <span class="tag tag--new"><?php echo pll__('Новий'); ?></span>
                        <?php endif; ?>
                        <?php if (in_array('sale', $product_group['product_tag'])) : ?>
                            <span class="tag"><?php echo pll__('Акція'); ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if (is_post_favorite($product_id)) : ?>
                        <a href="" class="js-add-favorite product-card__favorite is-added" title="<?php echo pll__('Видалити вибраних'); ?>" alt="<?php echo pll__('Видалити вибраних'); ?>">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5 1L16.2038 8.77852L24.4371 8.9463L17.8749 13.9215L20.2595 21.8037L13.5 17.1L6.74047 21.8037L9.12514 13.9215L2.56285 8.9463L10.7962 8.77852L13.5 1Z" stroke="#333333" stroke-linejoin="round" />
                                <rect class="cross-line" x="1.23438" y="20.458" width="29" height="1" transform="rotate(-38.0739 1.23438 20.458)" fill="#333333" />
                                <rect class="cross-line" y="18.8838" width="29" height="2" transform="rotate(-38.0739 0 18.8838)" fill="white" />
                            </svg>
                        </a>
                    <?php else : ?>
                        <a href="" class="js-add-favorite product-card__favorite" title="<?php echo pll__('Додати до вибраних'); ?>" alt="<?php echo pll__('Додати до вибраних'); ?>">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5 1L16.2038 8.77852L24.4371 8.9463L17.8749 13.9215L20.2595 21.8037L13.5 17.1L6.74047 21.8037L9.12514 13.9215L2.56285 8.9463L10.7962 8.77852L13.5 1Z" stroke="#333333" stroke-linejoin="round" />
                                <rect class="cross-line" x="1.23438" y="20.458" width="29" height="1" transform="rotate(-38.0739 1.23438 20.458)" fill="#333333" />
                                <rect class="cross-line" y="18.8838" width="29" height="2" transform="rotate(-38.0739 0 18.8838)" fill="white" />
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
                <a href="<?php echo get_the_permalink($product_id); ?>" class="product-card__link">
                    <?php $post_thumbnail = get_the_post_thumbnail_url($product_id); ?>
                    <?php $thumbnail_id = get_post_thumbnail_id($product_id);
                    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                    <div class="product-card__img">
                        <?php if (!empty($post_thumbnail)) : ?>
                            <img src="<?php echo $post_thumbnail; ?>" alt="<?php echo $alt; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="product-card__info">
                        <div class="product-card__title">
                            <h3><?php echo get_the_title($product_id); ?></h3>
                        </div>
                        <div class="product-card__about">
                            <?php if (!empty($article_characteristics)) : ?>
                                <ul class="product-card__spec">
                                    <ul class="product-card__spec">
                                        <?php foreach ($article_characteristics as $key => $subitem) : ?>
                                            <?php if (!empty($subitem['value'])) : ?>
                                                <li alt="<?php echo $key; ?>" title="<?php echo $key; ?>">
                                                    <?php if (!empty($subitem['image'])) : ?>
                                                        <img src="<?php echo $subitem['image']; ?>">
                                                    <?php endif; ?>
                                                    <span><?php echo $subitem['value']; ?><?php echo !empty($subitem['measurement']) ? ' ' . $subitem['measurement'] : ''; ?></span>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </ul>
                            <?php endif; ?>
                            <div class="product-card__content">
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
                                <?php if (!empty($product_group['quantity_per_package'])) : ?>
                                    <div class="product-card__package">
                                        <p><?php echo $product_group['quantity_per_package']; ?> <?php echo pll__('шт.'); ?>
                                            × <?php echo number_format((float)$price_for_count / $product_group['quantity_per_package'], 2, '.', ''); ?>
                                            ₴ <br><?php echo pll__('в упаковці'); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="product-card__buttons">
                    <div class="product-card__unavailable">
                        <span><?php pll_e('Немає у наявності'); ?></span>
                    </div>
                    <?php if (!is_page_template('wishlist-page.php')) : ?>
                        <div class="product-card__row">
                            <?php if (is_user_logged_in()) : ?>
                                <button class="btn btn--medium btn--white js-add-favorite is-unavailable"><?php pll_e('Коли з’явиться?'); ?></button>
                            <?php else : ?>
                                <a href="#login-popup" data-fancybox class="btn btn--medium btn--white"><?php pll_e('Коли з’явиться?'); ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </article>

<?php endif;
        wp_reset_postdata();
    }

    public static function acf_add_local_field_group()
    {

        if (function_exists('acf_add_local_field_group')) :

            acf_add_local_field_group(array(
                'key' => 'group_6490c709cfbdf',
                'title' => 'Products',
                'fields' => array(
                    array(
                        'key' => 'field_6490c7eb4dc2b',
                        'label' => 'Main option',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_6490c7f94dc2c',
                        'label' => '',
                        'name' => 'main_option',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_6490c70ad34bb',
                                'label' => 'Quantity per package',
                                'name' => 'quantity_per_package',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'translations' => 'translate',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6502047e54ebc',
                                'label' => 'Product code',
                                'name' => 'product_code',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'translations' => 'translate',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_65020a0c53a93',
                                'label' => 'Related products',
                                'name' => 'related_products',
                                'aria-label' => '',
                                'type' => 'relationship',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'post_type' => array(
                                    0 => 'product',
                                ),
                                'post_status' => array(
                                    0 => 'publish',
                                ),
                                'taxonomy' => '',
                                'filters' => array(
                                    0 => 'search',
                                    1 => 'taxonomy',
                                ),
                                'return_format' => 'id',
                                'translations' => 'copy_once',
                                'min' => '',
                                'max' => '',
                                'elements' => '',
                            ),
                            array(
                                'key' => 'field_65020b2bb9e35',
                                'label' => 'Product tag',
                                'name' => 'product_tag',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'sale' => 'Sale',
                                    'new' => 'New',
                                ),
                                'default_value' => '',
                                'return_format' => 'value',
                                'translations' => 'copy_once',
                                'allow_null' => 0,
                                'other_choice' => 0,
                                'layout' => 'vertical',
                                'save_other_choice' => 0,
                            ),
                            array(
                                'key' => 'field_65034647325d0',
                                'label' => 'Related files',
                                'name' => 'related_files',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'layout' => 'table',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_65034656325d1',
                                        'label' => 'File',
                                        'name' => 'file',
                                        'aria-label' => '',
                                        'type' => 'file',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'url',
                                        'library' => 'all',
                                        'translations' => 'copy_once',
                                        'min_size' => '',
                                        'max_size' => '',
                                        'mime_types' => '',
                                        'parent_repeater' => 'field_65034647325d0',
                                    ),
                                    array(
                                        'key' => 'field_65034663325d2',
                                        'label' => 'Name',
                                        'name' => 'name',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'translations' => 'translate',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_65034647325d0',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_6505789934383',
                        'label' => 'Characteristics',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_650578a534384',
                        'label' => '',
                        'name' => 'characteristics',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_65245f010879a',
                                'label' => 'Ширина',
                                'name' => '1',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    32 => '32',
                                ),
                                'default_value' => array(),
                                'return_format' => 'value',
                                'translations' => 'copy_once',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 0,
                                'save_custom' => 0,
                                'custom_choice_button_text' => 'Add new choice',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_6511c1af64805',
                        'label' => 'Wholesale options',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_6511c1d564806',
                        'label' => '',
                        'name' => 'wholesale_options',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_6511c1e964807',
                                'label' => 'Show wholesale option?',
                                'name' => 'is_wholesale',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 1,
                                'translations' => 'copy_once',
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_6511c20964808',
                                'label' => 'Above the price text',
                                'name' => 'above_text',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'translations' => 'translate',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6511c22064809',
                                'label' => 'Price text',
                                'name' => 'price_text',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'translations' => 'translate',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6511c2566480a',
                                'label' => 'Under the price text',
                                'name' => 'under_the_price_text',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'translations' => 'translate',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6564650f01b4b',
                                'label' => 'Stock status',
                                'name' => 'stock_status',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'В навності' => 'В наявності',
                                ),
                                'default_value' => false,
                                'return_format' => 'value',
                                'multiple' => 0,
                                'translations' => 'copy_once',
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_6572410a0f745',
                        'label' => 'Admin options',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_657241410f746',
                        'label' => '',
                        'name' => 'admin_options',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_657241520f747',
                                'label' => 'Notes',
                                'name' => 'notes',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'translations' => 'translate',
                                'maxlength' => '',
                                'rows' => '',
                                'placeholder' => '',
                                'new_lines' => '',
                            ),
                            array(
                                'key' => 'field_657241560f748',
                                'label' => 'Files',
                                'name' => 'files',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'layout' => 'table',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Додати рядок',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_657241620f749',
                                        'label' => 'File',
                                        'name' => 'file',
                                        'aria-label' => '',
                                        'type' => 'file',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'array',
                                        'library' => 'all',
                                        'translations' => 'copy_once',
                                        'min_size' => '',
                                        'max_size' => '',
                                        'mime_types' => '',
                                        'parent_repeater' => 'field_657241560f748',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'product',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ));

        endif;
    }
}
