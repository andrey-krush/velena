<?php

class Wholesale_Cart_Page
{

    public static function init()
    {
        add_action('init', [__CLASS__, 'pll_strings']);
        add_action('init', [__CLASS__, 'acf_add_local_field_group']);

        add_action('wp_ajax_nopriv_wholesale_delete_item', [__CLASS__, 'wholesale_delete_item']);
        add_action('wp_ajax_wholesale_delete_item', [__CLASS__, 'wholesale_delete_item']);

        add_action('wp_ajax_nopriv_wholesale_change_quantity', [__CLASS__, 'wholesale_change_quantity']);
        add_action('wp_ajax_wholesale_change_quantity', [__CLASS__, 'wholesale_change_quantity']);

        add_action('wp_ajax_nopriv_wholesale_contact', [__CLASS__, 'wholesale_contact']);
        add_action('wp_ajax_wholesale_contact', [__CLASS__, 'wholesale_contact']);
    }

    public static function pll_strings()
    {

        pll_register_string('wholesalecart-1', 'Оптове замовлення', 'wholesalecart');
        pll_register_string('wholesalecart-2', 'Натисніть сюди, щоб увійти', 'wholesalecart');
        pll_register_string('wholesalecart-3', 'Вже замовляли у нас?', 'wholesalecart');
    }
    public static function get_url()
    {
        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'wholesale-cart-page.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? get_the_permalink($page[0]->ID) : false;
    }

    public static function get_ID()
    {

        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'wholesale-cart-page.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? $page[0]->ID : false;
    }

    public static function wholesale_delete_item()
    {

        if (!empty($_POST['cart_id'])) :

            $post_language = pll_get_post_language($_POST['cart_id']);

            foreach (pll_languages_list() as $item) :

                if ($item != $post_language) :

                    $translated_post = pll_get_post($_POST['cart_id'], $item);

                    if (!empty($post)) :
                        $posts[$item] = $translated_post;
                    endif;

                endif;

            endforeach;

            $posts[$post_language] = $_POST['cart_id'];
            $products = false;

            if (is_user_logged_in()) :

                $user = wp_get_current_user();

                foreach (pll_languages_list() as $item) :

                    $user_wholesale_products = get_user_meta($user->ID, 'wholesale_products_' . $item);

                    if (!empty($user_wholesale_products)) :

                        foreach ($user_wholesale_products as $key => $product) :

                            if ($product['product_id'] == $posts[$item]) :
                                unset($user_wholesale_products[$key]);
                            endif;

                        endforeach;

                        delete_user_meta($user->ID, 'wholesale_products_' . $item);

                        foreach ($user_wholesale_products as $key => $product) :

                            add_user_meta($user->data->ID, 'wholesale_products_' . $item, ['product_id' => $product['product_id'], 'quantity' => $product['quantity']]);

                        endforeach;

                    endif;

                endforeach;

            else :

                foreach (pll_languages_list() as $item) :

                    if ($posts[$item]) {
                        $products = remove_wholesale_product($posts[$item], $item);
                    }

                endforeach;

            endif;

            if ($products === false) {
                $products = get_wholesale_products();
            }

            if (!empty($products)) :
                ob_start();
?>
                <div class="cart__body">
                    <?php foreach ($products as $item) : ?>
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
                </div>
            <?php
                $cart_html['html'] = ob_get_clean();
            else :
                $cart_html['html'] = '<h3>' . pll__('В вашій корзині пусто') . '</h3><script>document.querySelector(".cart__form").remove()</script>';
            endif;
            $mini_cart_content = get_mini_cart_content(true);
            wp_send_json_success($mini_cart_content + $cart_html, 200);
        endif;
        wp_send_json_success();
        die;
    }

    public static function wholesale_change_quantity()
    {

        if (!empty($_POST['cart_id']) and !empty($_POST['quantity'])) :

            $post_language = pll_get_post_language($_POST['cart_id']);

            foreach (pll_languages_list() as $item) :

                if ($item != $post_language) :

                    $translated_post = pll_get_post($_POST['cart_id'], $item);

                    if (!empty($post)) :
                        $posts[$item] = $translated_post;
                    endif;

                endif;

            endforeach;

            $posts[$post_language] = $_POST['cart_id'];

            if (is_user_logged_in()) :

                $user = wp_get_current_user();

                foreach (pll_languages_list() as $item) :

                    $user_wholesale_products = get_user_meta($user->ID, 'wholesale_products_' . $item);

                    if (!empty($user_wholesale_products)) :

                        foreach ($user_wholesale_products as $key => $product) :

                            if ($product['product_id'] == $posts[$item]) :
                                $user_wholesale_products[$key]['quantity'] = $_POST['quantity'];
                            endif;

                        endforeach;

                        delete_user_meta($user->ID, 'wholesale_products_' . $item);

                        foreach ($user_wholesale_products as $key => $product) :

                            add_user_meta($user->data->ID, 'wholesale_products_' . $item, ['product_id' => $product['product_id'], 'quantity' => $product['quantity']]);

                        endforeach;

                    endif;

                endforeach;

            else :

                foreach (pll_languages_list() as $item) :
                    $added_products = get_wholesale_products($item);
                    if (!empty($added_products)) :

                        foreach ($added_products as $key => $product) :

                            if ($product['product_id'] == $posts[$item]) :
                                $added_products[$key]['quantity'] = $_POST['quantity'];
                                set_wholesale_products($added_products[$key], $item, false);
                            endif;

                        endforeach;

                    endif;

                endforeach;

            endif;




            $products = get_wholesale_products();

            if (!empty($products)) :
                ob_start();
            ?>
                <div class="cart__body">
                    <?php foreach ($products as $item) : ?>
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
                </div>
<?php
                $cart_html['html'] = ob_get_clean();
            else :
                $cart_html['html'] = '<h3>' . pll__('В вашій корзині пусто') . '</h3>';
            endif;
            $mini_cart_content = get_mini_cart_content(true);
            wp_send_json_success($mini_cart_content + $cart_html, 200);

        else :
            wp_send_json_success();
        endif;

        die;
    }

    public static function wholesale_contact()
    {


        $title = "Нова оптова заявка - " . date('d.m.Y');

        $post_id = wp_insert_post(array(
            'post_title' => $title,
            'post_status'   => 'publish',
            'post_type' => 'whole_application',
        ));

        update_field('name', $_POST['billing_first_name'], $post_id);
        update_field('last_name', $_POST['billing_last_name'], $post_id);
        update_field('billing_company', $_POST['billing_company'], $post_id);
        update_field('edrpou', $_POST['edrpou'], $post_id);
        update_field('phone', $_POST['billing_phone'], $post_id);
        update_field('email', $_POST['billing_email'], $post_id);
        update_field('message', $_POST['message'], $post_id);


        if (is_user_logged_in()) :
            $user_id = get_current_user_id();
            update_user_meta($user_id, 'billing_company', $_POST['billing_company']);
            update_field('edrpou', $_POST['billing_phone'], 'user_' . $user_id);
        endif;


        $products_string_post = '';
        $products_string_mail = "Ім’я : " . $_POST['billing_first_name'] . "<br>
                Прізвище : " . $_POST['billing_last_name'] . "<br>
                ЄДРПОУ: " . $_POST['edrpou'] . "<br>
                Компанія : " . $_POST['billing_company'] . "<br>
                Телефон: " . $_POST['billing_phone'] . "<br>
                Email : " . $_POST['billing_email']   . "<br>
                Повідомлення : " . $_POST['message']  . "<br><br>";

        $products = get_wholesale_products();

        foreach ($products as $item) :
            $products_string_post .= "Product : " . get_the_title($item['product_id']) .
                "\n" . get_field('wholesale_options', $item['product_id'])['above_text'] .
                "\n" . get_field('wholesale_options', $item['product_id'])['price_text'] .
                "\n" . get_field('wholesale_options', $item['product_id'])['under_the_price_text'] .
                "\nQuantity: " . $item['quantity'] .
                " \n\n ";

            $products_string_mail .= "
            Product : " . get_the_title($item['product_id']) .
                "<br>" . get_field('wholesale_options', $item['product_id'])['above_text'] .
                "<br>" . get_field('wholesale_options', $item['product_id'])['price_text'] .
                "<br>" . get_field('wholesale_options', $item['product_id'])['under_the_price_text'] .
                "<br> Quantity: " . $item['quantity'] .
                " <br><br> ";
        endforeach;

        if (is_user_logged_in()) {
            delete_metadata('user', get_current_user_id(), 'wholesale_products_' . pll_current_language(), '', true);
        }

        setcookie('wholesale_products_' . pll_current_language(), json_encode([]), time() + (30 * 86400), '/');
        $_COOKIE['wholesale_products_' . pll_current_language()] = json_encode([]);

        update_post_meta($post_id, 'products', $products_string_post);

        $headers = array(
            'content-type: text/html',
        );
        wp_mail(get_field('emails', Wholesale_Cart_Page::get_ID())['emails'], $title, $products_string_mail, $headers);
        wp_send_json_success(['redirect_url' => Wholesale_Cart_Success_Page::get_url()]);
        die;
    }

    public static function acf_add_local_field_group()
    {

        if (function_exists('acf_add_local_field_group')) :

            acf_add_local_field_group(array(
                'key' => 'group_651b085500206',
                'title' => 'Wholesale cart',
                'fields' => array(
                    array(
                        'key' => 'field_651b08572230f',
                        'label' => 'Texts',
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
                        'key' => 'field_651b087122310',
                        'label' => '',
                        'name' => 'texts',
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
                                'key' => 'field_651b088322311',
                                'label' => 'Privacy policy text',
                                'name' => 'privacy_policy_text',
                                'aria-label' => '',
                                'type' => 'wysiwyg',
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
                                'tabs' => 'all',
                                'toolbar' => 'full',
                                'media_upload' => 0,
                                'delay' => 0,
                            ),
                            array(
                                'key' => 'field_651b089c22312',
                                'label' => 'Text right to form',
                                'name' => 'text_right_to_form',
                                'aria-label' => '',
                                'type' => 'wysiwyg',
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
                                'tabs' => 'all',
                                'toolbar' => 'full',
                                'media_upload' => 0,
                                'delay' => 0,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_651b085asf21qs72230f',
                        'label' => 'Emails',
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
                        'key' => 'field_6safas51b087122310',
                        'label' => '',
                        'name' => 'emails',
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
                                'key' => 'field_651b088asfasf322311',
                                'label' => 'Emails',
                                'name' => 'emails',
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
                                'tabs' => 'all',
                                'toolbar' => 'full',
                                'media_upload' => 0,
                                'delay' => 0,
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'page_template',
                            'operator' => '==',
                            'value' => 'wholesale-cart-page.php',
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
