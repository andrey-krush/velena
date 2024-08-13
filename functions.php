<?php

mb_internal_encoding("UTF-8");
define('TEMPLATE_PATH', get_template_directory_uri());
$WHOLESALE_CART = get_wholesale_products();

add_action('after_setup_theme', function () {
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
});

add_filter('big_image_size_threshold', '__return_zero');

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('admin-style', TEMPLATE_PATH . '/styles/admin-style.css');
}, 99);

add_filter('wp_mail_from', function ($original_email_address) {
    return 'no-reply@blackbook.dev';
});

add_filter('wp_mail_from_name', function ($original_email_from) {
    return 'Velena';
});

add_action('pre_get_posts', 'change_posts_per_page_category');

function change_posts_per_page_category($query)
{

    if (is_category()) :
        $query->query_vars['posts_per_page'] = 1;
    endif;
}

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter('gutenberg_use_widgets_block_editor', '__return_false', 100);

// Disables the block editor from managing widgets. renamed from wp_use_widgets_block_editor
add_filter('use_widgets_block_editor', '__return_false');


function woocommerce_form_field($key, $args, $value = null)
{
    $default_country = 'UA';
    $default_city = 'Київ';

    $defaults = array(
        'type' => 'text',
        'label' => '',
        'description' => '',
        'placeholder' => '',
        'maxlength' => false,
        'required' => false,
        'autocomplete' => false,
        'id' => $key,
        'class' => array(),
        'label_class' => array(),
        'input_class' => array(),
        'return' => false,
        'options' => array(),
        'custom_attributes' => array(),
        'validate' => array(),
        'default' => '',
        'autofocus' => '',
        'priority' => '',
        'size' => '',
    );

    $args = wp_parse_args($args, $defaults);
    $args = apply_filters('woocommerce_form_field_args', $args, $key, $value);

    if (is_string($args['class'])) {
        $args['class'] = array($args['class']);
    }
    $required = '';
    $required_attr = '';
    if ($args['required']) {
        $args['class'][] = 'input--required';
        $required = '';
        $required_attr = 'required';
    }

    if (is_string($args['label_class'])) {
        $args['label_class'] = array($args['label_class']);
    }

    if (is_null($value)) {
        $value = $args['default'];
    }

    // Custom attribute handling.
    $custom_attributes = array();
    $args['custom_attributes'] = array_filter((array)$args['custom_attributes'], 'strlen');

    if ($args['maxlength']) {
        $args['custom_attributes']['maxlength'] = absint($args['maxlength']);
    }

    if (!empty($args['autocomplete'])) {
        $args['custom_attributes']['autocomplete'] = $args['autocomplete'];
    }

    if (true === $args['autofocus']) {
        $args['custom_attributes']['autofocus'] = 'autofocus';
    }

    if ($args['description']) {
        $args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
    }

    if (!empty($args['custom_attributes']) && is_array($args['custom_attributes'])) {
        foreach ($args['custom_attributes'] as $attribute => $attribute_value) {
            $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
        }
    }

    if (!empty($args['validate'])) {
        foreach ($args['validate'] as $validate) {
            $args['class'][] = 'validate-' . $validate;
        }
    }
    $size = '';
    $hidden = '';
    if ($args['size']) {
        $size = ' form__input--' . $args['size'];
    }
    if ($args['priority'] == -1) {
        $hidden = ' form__input--hidden';
    }

    $field = '';
    $label_id = $args['id'];
    $sort = $args['priority'] ? $args['priority'] : '';
    $field_container = '<div class="form__input' . $size . $hidden . '"><div class="input %1$s" id="%2$s" data-priority="' . esc_attr($sort) . '">%3$s</div></div>';

    switch ($args['type']) {
        case 'country':
            $countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

            if (1 === count($countries)) {

                $field .= '<strong>' . current(array_values($countries)) . '</strong>';

                $field .= '<input type="hidden" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="' . current(array_keys($countries)) . '" ' . implode(' ', $custom_attributes) . ' class="country_to_state" readonly="readonly" />';
            } elseif ($default_country) {
                $field .= '<input type="hidden" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="' . $default_country . '" ' . implode(' ', $custom_attributes) . ' class="country_to_state" readonly="readonly" />';
            } else {
                $data_label = !empty($args['label']) ? 'data-label="' . esc_attr($args['label']) . '"' : '';
                $args['class'][] = 'input--select';

                $selected_text = '';

                if ($value != '') {
                    $selected_text = $countries[$value];
                }

                $field = '
          <select value="' . $value . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="country_to_state output_value country_select ' . esc_attr(implode(' ', $args['input_class'])) . '" ' . implode(' ', $custom_attributes) . ' ' . $required_attr . ' placeholder="' . wp_kses_post($args['label']) . $required . '">
           <option value="">' . esc_html__('Select a country / region&hellip;', 'woocommerce') . '</option>';

                foreach ($countries as $ckey => $cvalue) {
                    if ($value == $ckey) {
                        $li_selected = 'selected';
                    } else {
                        $li_selected = '';
                    }
                    $field .= '<option value="' . esc_attr($ckey) . '" ' . $li_selected . '>' . esc_html($cvalue) . '</li>';
                }
                $field .= '
          </select>
          <input type="text" class="output_text" readonly="" placeholder="' . wp_kses_post($args['label']) . $required . '" value="' . $selected_text . '">
          <div class="input__arrow">
              <img src="' . TEMPLATE_PATH . '/img/ico-dropdown.svg" alt="arrow">
          </div>
          <ul class="input__dropdown">
              <li data-value="">' . esc_html__('Select a country / region&hellip;', 'woocommerce') . '</li>';

                foreach ($countries as $ckey => $cvalue) {
                    if ($value == $ckey) {
                        $li_selected = 'class="is-selected"';
                    } else {
                        $li_selected = '';
                    }
                    $field .= '<li data-value="' . esc_attr($ckey) . '" ' . $li_selected . '>' . esc_html($cvalue) . '</li>';
                }

                $field .= '</ul>';

                $field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__('Update country / region', 'woocommerce') . '">' . esc_html__('Update country / region', 'woocommerce') . '</button></noscript>';
            }

            break;
        case 'state':
            /* Get country this state field is representing */
            $for_country = isset($args['country']) ? $args['country'] : WC()->checkout->get_value('billing_state' === $key ? 'billing_country' : 'shipping_country');
            $states = WC()->countries->get_states($for_country);

            if (is_array($states) && empty($states)) {

                $field_container = '<p class="form-row %1$s" id="%2$s" style="display: none">%3$s</p>';

                $field .= '<input type="hidden" class="hidden" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="" ' . implode(' ', $custom_attributes) . ' placeholder="' . esc_attr($args['placeholder']) . '" readonly="readonly" data-input-classes="' . esc_attr(implode(' ', $args['input_class'])) . '"/>';
            } elseif (!is_null($for_country) && is_array($states)) {
                $data_label = !empty($args['label']) ? 'data-label="' . esc_attr($args['label']) . '"' : '';

                $args['class'][] = 'input--select';

                $selected_text = '';

                if ($value != '') {
                    $selected_text = $states[$value];
                }

                $field = '
          <select value="' . $value . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="state_select output_value ' . esc_attr(implode(' ', $args['input_class'])) . '" ' . implode(' ', $custom_attributes) . ' ' . $required_attr . ' placeholder="' . wp_kses_post($args['label']) . $required . '">
           <option value="">' . esc_html__('Select a country / region&hellip;', 'woocommerce') . '</option>';

                foreach ($states as $ckey => $cvalue) {
                    if ($value == $ckey) {
                        $li_selected = 'selected';
                    } else {
                        $li_selected = '';
                    }
                    $field .= '<option value="' . esc_attr($ckey) . '" ' . $li_selected . '>' . esc_html($cvalue) . '</li>';
                }
                $field .= '
          </select>
          <input type="text" class="output_text" readonly="" placeholder="' . wp_kses_post($args['label']) . $required . '" value="' . $selected_text . '">
          <div class="input__arrow">
              <img src="' . TEMPLATE_PATH . '/img/ico-dropdown.svg" alt="arrow">
          </div>
          <ul class="input__dropdown">
              <li data-value="">' . esc_html__('Select an option&hellip;', 'woocommerce') . '</li>';

                foreach ($states as $ckey => $cvalue) {
                    if ($value == $ckey) {
                        $li_selected = 'class="is-selected"';
                    } else {
                        $li_selected = '';
                    }
                    $field .= '<li data-value="' . esc_attr($ckey) . '" ' . $li_selected . '>' . esc_html($cvalue) . '</li>';
                }

                $field .= '</ul>';
            } else {

                $field .= '<input type="text" class="input-text ' . esc_attr(implode(' ', $args['input_class'])) . '" value="' . esc_attr($value) . '"  placeholder="' . esc_attr($args['placeholder']) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" ' . implode(' ', $custom_attributes) . ' data-input-classes="' . esc_attr(implode(' ', $args['input_class'])) . '"/>';
            }

            break;
        case 'checkbox':
            $args['class'][] = 'input--radio';
            $field = '
    <label class="' . implode(' ', $args['label_class']) . '" ' . implode(' ', $custom_attributes) . '>
      <input type="' . esc_attr($args['type']) . '" class="input-checkbox ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="1" ' . checked($value, 1, false) . '>
      <span>' . $args['label'] . $required . '</span>
    </label>
    ';
            break;
        case 'textarea':
            $field .= '<textarea data-validation="' . $args['type'] . '" class="' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" placeholder="' . wp_kses_post($args['label']) . $required . '" ' . implode(' ', $custom_attributes) . ' ' . $required_attr . '>' . esc_attr($value) . '</textarea>';
            break;
        case 'text':
        case 'password':
        case 'datetime':
        case 'datetime-local':
        case 'date':
        case 'month':
        case 'time':
        case 'week':
        case 'number':
        case 'email':
        case 'url':
        case 'tel':
            if ($key == 'billing_city' && $default_city) {
                $field .= '<input type="hidden" class="' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" placeholder="' . wp_kses_post($args['label']) . $required . '"  value="' . $default_city . '" ' . implode(' ', $custom_attributes) . ' ' . $required_attr . ' />';
            } else {
                $field .= '<input type="' . esc_attr($args['type']) . '" data-validation="' . $args['type'] . '" class="' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" placeholder="' . wp_kses_post($args['label']) . $required . '"  value="' . esc_attr($value) . '" ' . implode(' ', $custom_attributes) . ' ' . $required_attr . ' />';
            }
            break;
        case 'hidden':
            $field .= '<input type="' . esc_attr($args['type']) . '" class="input-hidden ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="' . esc_attr($value) . '" ' . implode(' ', $custom_attributes) . ' />';

            break;
        case 'select':
            $field = '';
            $options = '';

            if (!empty($args['options'])) {
                foreach ($args['options'] as $option_key => $option_text) {
                    if ('' === $option_key) {
                        // If we have a blank option, select2 needs a placeholder.
                        if (empty($args['placeholder'])) {
                            $args['placeholder'] = $option_text ? $option_text : pll__('Select');
                        }
                        $custom_attributes[] = 'data-allow_clear="true"';
                    }
                    $options .= '<option value="' . esc_attr($option_key) . '" ' . selected($value, $option_key, false) . '>' . esc_html($option_text) . '</option>';
                }

                $field .= '<select name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="select ' . esc_attr(implode(' ', $args['input_class'])) . '" ' . implode(' ', $custom_attributes) . ' data-placeholder="' . esc_attr($args['placeholder']) . '">
          ' . $options . '
        </select>';
            }

            break;
        case 'radio':
            $label_id .= '_' . current(array_keys($args['options']));

            if (!empty($args['options'])) {
                foreach ($args['options'] as $option_key => $option_text) {
                    $field .= '<input type="radio" class="input-radio ' . esc_attr(implode(' ', $args['input_class'])) . '" value="' . esc_attr($option_key) . '" name="' . esc_attr($key) . '" ' . implode(' ', $custom_attributes) . ' id="' . esc_attr($args['id']) . '_' . esc_attr($option_key) . '"' . checked($value, $option_key, false) . ' />';
                    $field .= '<label for="' . esc_attr($args['id']) . '_' . esc_attr($option_key) . '" class="radio ' . implode(' ', $args['label_class']) . '">' . esc_html($option_text) . '</label>';
                }
            }

            break;
    }

    if (!empty($field)) {
        $field_html = '';

        $field_html .= $field;

        if ($args['description']) {
            $field_html .= '<span class="description" id="' . esc_attr($args['id']) . '-description" aria-hidden="true">' . wp_kses_post($args['description']) . '</span>';
        }

        $field_html .= '';

        $container_class = esc_attr(implode(' ', $args['class']));
        $container_id = esc_attr($args['id']) . '_field';
        $field = sprintf($field_container, $container_class, $container_id, $field_html);
    }

    /**
     * Filter by type.
     */
    $field = apply_filters('woocommerce_form_field_' . $args['type'], $field, $key, $args, $value);

    /**
     * General filter on form fields.
     *
     * @since 3.4.0
     */
    $field = apply_filters('woocommerce_form_field', $field, $key, $args, $value);

    if ($args['return']) {
        return $field;
    } else {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $field;
    }
}

add_filter('woocommerce_checkout_fields', 'woocommerce_fields_priority');
function woocommerce_fields_priority($checkout_fields)
{
    $checkout_fields['billing']['billing_first_name']['priority'] = 5;
    $checkout_fields['billing']['billing_first_name']['size'] = 3;
    $checkout_fields['billing']['billing_last_name']['priority'] = 5;
    $checkout_fields['billing']['billing_last_name']['size'] = 3;

    $checkout_fields['billing']['billing_company']['priority'] = 10;
    $checkout_fields['billing']['billing_company']['size'] = 4;

    $checkout_fields['billing']['edrpou'] = [
        "label" => pll__('ЄДРПОУ'),
        "required" => false,
        "priority" => 10,
        "size" => 2
    ];

    $checkout_fields['billing']['billing_country']['priority'] = -1;
    $checkout_fields['billing']['billing_city']['priority'] = -1;

    unset($checkout_fields['billing']['billing_state']);
    unset($checkout_fields['billing']['billing_address_1']);
    unset($checkout_fields['billing']['billing_address_2']);
    unset($checkout_fields['billing']['billing_postcode']);
    // unset($checkout_fields['billing']['billing_city']);

    $checkout_fields['billing']['billing_phone']['priority'] = 30;
    $checkout_fields['billing']['billing_phone']['size'] = 3;

    $checkout_fields['billing']['billing_email']['priority'] = 35;
    $checkout_fields['billing']['billing_email']['size'] = 4;


    $checkout_fields['order']['order_comments']['size'] = 6;

    return $checkout_fields;
}

  
  class Blackbook_Theme_AutoLoader
{

    public static function init_auto()
    {
        self::load(TEMPLATEPATH . '/template-parts/*.php');
        self::load(TEMPLATEPATH . '/template-parts/*/*.php');
        self::load(TEMPLATEPATH . '/includes/*.php');
        self::load(TEMPLATEPATH . '/includes/*/*.php');
    }

    private static function load($pattern)
    {
        $files = glob($pattern);
        if (!$files) return;

        foreach ($files as $file) {
            require_once $file;

            $name = basename($file);

            /** Compose classname from filename */
            $class_name = array_reduce(explode('-', str_replace('.php', '', $name)), function ($full, $part) {
                $full[] = ucfirst($part);
                return $full;
            }, []);

            $class_name = implode('_', $class_name);

            if (method_exists($class_name, 'init')) $class_name::init();
        }
    }
}

Blackbook_Theme_AutoLoader::init_auto();


function my_acf_init()
{

    acf_update_setting('google_api_key', 'AIzaSyDWfYtTqhLP1aaIr5GN00I3xA62l0kJnzc');
}

add_action('acf/init', 'my_acf_init');

// function excerpt_length( $length ) {
// 	  return 10;
// }
// add_filter( 'excerpt_length', 'excerpt_length', 999 );

function polylang_language_switcher()
{
    global $wp;

    if (function_exists('pll_the_languages')) {
        $languages = pll_the_languages(array('raw' => 1));

        if (!empty($languages)) {
            echo '<ul>';

            foreach ($languages as $language) {
                $code = $language['language_code'];
                $name = $language['name'];
                $active_class = $language['current_lang'] ? ' class="is-active"' : '';

                if (is_edit_account_page()) :
                    $language['url'] .= 'edit-account';
                endif;

                if (is_account_page() and isset($wp->query_vars['orders'])) :
                    $language['url'] .= 'orders';
                endif;

                echo '<li><a href="' . esc_url($language['url']) . '"' . $active_class . '>' . esc_html($name) . '</a></li>';
            }

            echo '</ul>';
        }
    }
}

function polylang_language_switcher_menu()
{
    global $wp;

    if (function_exists('pll_the_languages')) {
        $languages = pll_the_languages(array('raw' => 1));

        if (!empty($languages)) {

            foreach ($languages as $language) {
                $code = $language['language_code'];
                $name = $language['name'];
                $active_class = $language['current_lang'] ? 'is-active' : '';
                if (is_edit_account_page()) :
                    $language['url'] .= 'edit-account';
                endif;

                if (is_account_page() and isset($wp->query_vars['orders'])) :
                    $language['url'] .= 'orders';
                endif;
                echo '<li class="is-small ' . $active_class . '"><a href="' . esc_url($language['url']) . '">' . esc_html($name) . '</a></li>';
            }
        }
    }
}

function pluralQuantityLocale($count, $words)
{
    $lastDigit = $count % 10;
    $lastTwoDigits = $count % 100;

    if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
        $word = $words[2];
    } elseif ($lastDigit === 1) {
        $word = $words[0];
    } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
        $word = $words[1];
    } else {
        $word = $words[2];
    }
    return $count . ' ' . $word;
}

function is_post_favorite($post_id = 0)
{
    $current_language = pll_current_language();

    if (is_user_logged_in()) :
        $user = get_current_user_id();
        $posts = get_user_meta($user, 'liked_posts_' . $current_language);
    else :
        $posts = isset($_COOKIE['wishlist_products_' . $current_language]) ? json_decode(stripslashes($_COOKIE['wishlist_products_' . $current_language]), true) : [];
    endif;
    if (!empty($posts)) :
        return in_array($post_id, $posts);
    else :
        return false;
    endif;
}

function get_shipping_method()
{
    $current_shipping_method = WC()->session->get('chosen_shipping_methods');
    $packages = WC()->shipping()->get_packages();
    $package = $packages[0];
    $available_methods = $package['rates'];

    foreach ($available_methods as $key => $method) {
        if ($current_shipping_method[0] == $method->id) {
            return ['label' => pll__($method->label), 'cost' => $method->cost];
        }
    }
}

function get_mini_cart_content($is_wholesale = false)
{
    $cart_items = [];

    if ($is_wholesale) {
        foreach (get_wholesale_products() as $cart_item) :
            $product_id = $cart_item['product_id'];
            $item_info['product_id'] = $cart_item['product_id'];
            $item_info['title'] = get_the_title($product_id);
            $item_info['permalink'] = get_the_permalink($product_id);
            $item_info['thumbnail_url'] = get_the_post_thumbnail_url($product_id);

            $wholesale_options = get_field('wholesale_options', $product_id);
            $item_info['price'] = get_float_from_wc_price($wholesale_options['price_text']) == 0 ? pll__('Ціна договірна') : wc_price(get_float_from_wc_price($wholesale_options['price_text']));

            $item_info['quantity'] = $cart_item['quantity'];

            $cart_items[] = $item_info;
        endforeach;
    } else {
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
            $product_id = $cart_item['product_id'];
            $item_info['product_id'] = $cart_item['product_id'];
            $item_info['title'] = get_the_title($product_id);
            $item_info['permalink'] = get_the_permalink($product_id);
            $item_info['thumbnail_url'] = get_the_post_thumbnail_url($product_id);


            if (!empty($cart_item['data']->get_sale_price()) and ($cart_item['data']->get_sale_price() != $cart_item['data']->get_regular_price())) :
                $item_info['price'] = wc_price($cart_item['data']->get_sale_price());
            else :
                $item_info['price'] = wc_price($cart_item['data']->get_price());
            endif;

            $item_info['quantity'] = $cart_item['quantity'];

            $cart_items[] = $item_info;
        endforeach;
    }

    return ['cart' => $cart_items, 'cart_total' => ($is_wholesale ? '' : wc_price(WC()->cart->cart_contents_total))];
}

function get_wholesale_products($key = false)
{
    if (!$key) {
        $key = pll_current_language();
    }
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $user_info = get_user_meta($user->ID, 'wholesale_products_' . $key);
        if (!empty($user_info)) {
            return $user_info;
        } else {
            return [];
        }
    } else {
        if ( isset($_COOKIE['wholesale_products_' . $key]) ) {
            $cart_cookies_info = $_COOKIE['wholesale_products_' . $key];
            if (!empty($cart_cookies_info)) {
                return json_decode(stripslashes($_COOKIE['wholesale_products_' . $key]), true);
            } else {
                return [];
            }
        } else {
            return [];
        }
      
    }
}

// function get_wholesale_products$key = false)
// {
//     if (!$key) {
//         $key = pll_current_language();
//     }
//     if (is_user_logged_in()) {
//         $user = wp_get_current_user();
//         $user_info = get_user_meta($user->ID, 'wholesale_products_' . $key);
//         if (empty($user_info) or empty($user_info[0])) {
//             return null;
//         } else {
//             foreach ($user_info as $key => $item) {
//                 if ($item instanceof stdClass) {
//                     $user_info[] = [
//                         'product_id' => $item->product_id,
//                         'quantity' => $item->quantity
//                     ];

//                     unset($user_info[$key]);
//                 }
//             }
//             if ( empty( $user_info ) or empty( $user_info[0] ) or ( $user_info[0] == '[]' ) ) {
//                 return [];
//             } else {
//                 return $user_info;
//             }
//         }
//     } else {
//         if (!empty($GLOBALS['WHOLESALE_CART'])) {
//             return $GLOBALS['WHOLESALE_CART'];
//         } else {
//             return isset($_COOKIE['wholesale_products_' . $key]) ? json_decode(stripslashes($_COOKIE['wholesale_products_' . $key]), true) : [];
//         }
//     }
// }

function set_wholesale_products($array_product, $key = false, $addQuantity = true)
{
    if (!$key) {
        $key = pll_current_language();
    }
    $added_products = get_wholesale_products($key);

    foreach ($added_products as $added_product_key => $added_product) :
        if ($added_product['product_id'] == $array_product['product_id']) :
            if ($addQuantity) {
                $array_product['quantity'] += $added_product['quantity'];
            }
            unset($added_products[$added_product_key]);
            $added_products[$added_product_key] = $array_product;
        endif;
    endforeach;

    // $added_products[] = $array_product;
    ksort($added_products);
    if (is_user_logged_in()) {
        update_user_meta(get_current_user_id(), 'wholesale_products_' . $key, json_encode($added_products));
    }
    //    } else {
    setcookie('wholesale_products_' . $key, json_encode($added_products), time() + (30 * 86400), '/');
    $_COOKIE['wholesale_products_' . $key] = json_encode($added_products);
    //    }
}

// function set_wholesale_products($array_product, $key = false, $addQuantity = true)
// {
//     if (!$key) {
//         $key = pll_current_language();
//     }
//     $added_products = get_wholesale_products($key);


//     foreach ($added_products as $added_product_key => $added_product) :
//         if ($added_product['product_id'] == $array_product['product_id']) :
//             if ($addQuantity) {
//                 $array_product['quantity'] += $added_product['quantity'];
//             }
//             unset($added_products[$added_product_key]);
//         endif;
//     endforeach;

//     $added_products[] = $array_product;
//     $GLOBALS['WHOLESALE_CART'] = $added_products;

//     if (is_user_logged_in()) {
//         update_user_meta(get_current_user_id(), 'wholesale_products_' . $key, json_encode($added_products));
//     } else {
//         setcookie('wholesale_products_' . $key, json_encode($added_products), time() + (30 * 86400), '/');
//     }
// }

function remove_wholesale_product($product_id, $key = false)
{
    if (!$key) {
        $key = pll_current_language();
    }
    $added_products = get_wholesale_products($key);

    if (!empty($added_products)) :

        foreach ($added_products as $index => $product) :
            if ($product['product_id'] == $product_id) :
                unset($added_products[$index]);
            endif;

        endforeach;

    endif;

    if (is_user_logged_in()) {
        update_user_meta(get_current_user_id(), 'wholesale_products_' . $key, json_encode($added_products));
    } else {
        setcookie('wholesale_products_' . $key, json_encode($added_products), time() + (30 * 86400), '/');
        $_COOKIE['wholesale_products_' . $key] = json_encode($added_products);
    }

    return $added_products;
}

// function remove_wholesale_product($product_id, $key = false)
// {
//     if (!$key) {
//         $key = pll_current_language();
//     }
//     $added_products = get_wholesale_products($key);

//     if (!empty($added_products)) :

//         foreach ($added_products as $index => $product) :
//             if ($product['product_id'] == $product_id) :
//                 unset($added_products[$index]);
//             endif;

//         endforeach;

//     endif;
//     $GLOBALS['WHOLESALE_CART'] = $added_products;

//     if (is_user_logged_in()) {
//         update_user_meta(get_current_user_id(), 'wholesale_products_' . $key, json_encode($added_products));
//     } else {
//         setcookie('wholesale_products_' . $key, json_encode($added_products), time() + (30 * 86400), '/');
//     }

//     return $added_products;
// }
function get_wholesale_subtotal($cart_item)
{
    $product_id = $cart_item['product_id'];
    $wholesale_options = get_field('wholesale_options', $product_id);

    return get_float_from_wc_price($wholesale_options['price_text']) * $cart_item['quantity'];
}
function get_wholesale_total()
{
    $products = $GLOBALS['WHOLESALE_CART'];
    $cart_total = 0;

    foreach ($products as $cart_item) :
        $cart_total += get_wholesale_subtotal($cart_item);
    endforeach;

    return $cart_total;
}

function get_float_from_wc_price($htmlString)
{
    $pattern = '/(\d+([\s,]?\d+)*(\.\d+)?)/';

    if (preg_match_all($pattern, $htmlString, $matches)) {
        $numbers = $matches[0];

        // Шукаємо перше число, яке є дійсним
        foreach ($numbers as $number) {
            // Прибираємо коми, пробіли та інші роздільники
            $number = preg_replace('/[\s,]/', '', $number);
            $floatValue = (float)$number;
            if (!is_nan($floatValue)) {
                return $floatValue;
            }
        }
        return false;
    } else {
        return false;
    }
}

function bb_cart_totals_coupon_label($coupon, $echo = true)
{
    if (is_string($coupon)) {
        $coupon = new WC_Coupon($coupon);
    }

    /* translators: %s: coupon code */
    $label = apply_filters('woocommerce_cart_totals_coupon_label', sprintf(esc_html__('Coupon: %s', 'woocommerce'), ''), $coupon);

    if ($echo) {
        echo $label . '<span>' . $coupon->get_code() . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    } else {
        return $label . '<span>' . $coupon->get_code() . '</span>';
    }
}

function splitArrayIntoPairs($array)
{
    $pairs = array();
    for ($i = 0; $i < count($array); $i += 2) {
        $pair = array();
        if (isset($array[$i])) {
            $pair[] = $array[$i];
        }
        if (isset($array[$i + 1])) {
            $pair[] = $array[$i + 1];
        }
        $pairs[] = $pair;
    }
    return $pairs;
}


function get_parent_categories_without_children($tax)
{
    $args = array(
        'taxonomy' => $tax,
        'parent' => 0,
        'hide_empty' => false,
    );

    $parent_categories = get_terms($args);

    $categories_without_children = array();

    foreach ($parent_categories as $category) {
        $children = get_term_children($category->term_id, $args['taxonomy']);
        if (empty($children)) {
            $categories_without_children[] = $category;
        }
    }

    return $categories_without_children;
}

add_filter('acf/load_field/key=field_650578a534384', 'add_characteristics_fields_to_post');

function add_characteristics_fields_to_post($field)
{

//    return $field;

    unset($field['sub_fields'][0]);

    if (is_admin() or is_singular('product')) :
        $id = get_the_ID();
    else :
        $id = $_SESSION['current_post'];
    endif;

    $type_main_category = get_terms([
        'taxonomy' => 'type',
        'parent' => 0,
        'object_ids' => [$id]
    ]);


    if (!empty($type_main_category)) :

        $children_type_category = get_terms([
            'taxonomy' => 'type',
            'parent' => $type_main_category[0]->term_id,
            'object_ids' => [$id]
        ]);

    endif;

    if (!empty($children_type_category)) :

        $characteristics = get_field('category_characteristics', $children_type_category[0]);
        $range_term_characteristics = get_term_meta($children_type_category[0]->term_id, 'term_range_attributes', true);

    elseif (!empty($type_main_category)) :

        $characteristics = get_field('category_characteristics', $type_main_category[0]);
        $range_term_characteristics = get_term_meta($type_main_category[0]->term_id, 'term_range_attributes', true);

    endif;

    $current_language = pll_current_language();

    $options_characteristics = get_field('attributes', 'option');
    $range_term_characteristics = !empty( $range_term_characteristics ) ? $range_term_characteristics : [];

    foreach ( $options_characteristics as $key => $item ) :

        if( !empty( $characteristics ) ) :

            foreach ( $characteristics as $subkey => $subitem ) :

                if( $subitem['characteristic_type'] == $item['attribute_name_' . $current_language] ) :

                    $array = [];

                    if( !empty( $item['attribute_values_' . $current_language] ) ) :

                        foreach ( $item['attribute_values_' . $current_language] as $subsubitem ) :

                            $array[] = $subsubitem['value'];

                        endforeach;

                    endif;

                    $characteristics[$subkey]['characteristic_possible_values'] = $array;

                endif;

            endforeach;

        endif;

    endforeach;

    $adding = 0;

    if (!empty($characteristics)) :

        foreach ($characteristics as $key => $item) :

            $item_for_name = str_replace(' ', '', $item['characteristic_type']);

            if( in_array($item['characteristic_type'], $range_term_characteristics) ) :

                $field['sub_fields'][$key+$adding] = [
                    "ID" => (int) $key + 1,
                    "key" => "field_65245f01120879a" . $key,
                    "label" => $item['characteristic_type'] . ' value',
                    "name" => $item_for_name . '_value',
                    "aria-label" => "",
                    "prefix" => "acf",
                    "type" => "number",
                    "value" => NULL,
                    "menu_order" => 1,
                    "instructions" => "Unit of measurement is: " . get_option('measurement_' . $item_for_name),
                    "required" => 0,
                    "id" => "",
                    "class" => "",
                    "conditional_logic" => 0,
                    "parent" => "field_650578a534384",
                    "wrapper" => [
                        "width" => "50%",
                        "class" => "",
                        "id" => ""
                    ],
                    "default_value" => "",
                    "translations" => "translate",
                    "maxlength" => "",
                    "placeholder" => "",
                    "prepend" => "",
                    "append" => "",
                    "_name" => $item_for_name . '_value',
                    "_valid" => 1
                ];

                $field['sub_fields'][$key+1+$adding] = [
                    "ID" => (int) $key + 2,
                    "key" => "field_65245f011220879a" . $key,
                    "label" => $item['characteristic_type'] . ' additional value',
                    "name" => $item_for_name . '_additional_value',
                    "aria-label" => "",
                    "prefix" => "acf",
                    "type" => "text",
                    "value" => NULL,
                    "menu_order" => 1,
                    "instructions" => "Additional value for: " . $item['characteristic_type'],
                    "required" => 0,
                    "id" => "",
                    "class" => "",
                    "conditional_logic" => 0,
                    "parent" => "field_650578a534384",
                    "wrapper" => [
                        "width" => "50%",
                        "class" => "",
                        "id" => ""
                    ],
                    "default_value" => "",
                    "translations" => "translate",
                    "maxlength" => "",
                    "placeholder" => "",
                    "prepend" => "",
                    "append" => "",
                    "_name" => $item_for_name . '_additional_value',
                    "_valid" => 1
                ];

                $adding += 1;

            else :

                if( !empty( $item['characteristic_possible_values']  ) ) :

                    $field['sub_fields'][$key+$adding] = array(
                        "ID" => (int) $key + 1,
                        "key" => "field_65245f010879a" . $key,
                        "label" => $item['characteristic_type'],
                        "name" => $item_for_name,
                        "aria-label" => "",
                        "prefix" => "acf",
                        "type" => "select",
                        "value" => NULL,
                        "menu_order" => $key + 1,
                        "instructions" => "",
                        "required" => 0,
                        "id" => "",
                        "class" => "",
                        "conditional_logic" => 0,
                        "parent" => "field_650578a534384",
                        "wrapper" => array(
                            "width" => "",
                            "class" => "",
                            "id" => ""
                        ),
                        "default_value" => array(),
                        "return_format" => "label",
                        "translations" => "copy_once",
                        "allow_custom" => 0,
                        "layout" => "vertical",
                        "toggle" => 0,
                        "save_custom" => 0,
                        "custom_choice_button_text" => "Add new choice",
                        "_name" => $item_for_name,
                        "_valid" => 1
                    );


                    $field['sub_fields'][$key+$adding]['choices'] = [];
                    $field['sub_fields'][$key+$adding]['choices'][''] = 'Not selected';
                    foreach ($item['characteristic_possible_values'] as $subitem) :

                        $subitem_name_for_key = mb_strtolower($subitem);
                        $subitem_name_for_key = str_replace(' ', '', $subitem_name_for_key);

                        $field['sub_fields'][$key+$adding]['choices'][$subitem_name_for_key] = $subitem;

                    endforeach;

                else :

                    $field['sub_fields'][$key+$adding] = [
                        "ID" => (int) $key + 1,
                        "key" => "field_65245f010879a" . $key,
                        "label" => $item['characteristic_type'],
                        "name" => $item_for_name,
                        "aria-label" => "",
                        "prefix" => "acf",
                        "type" => "text",
                        "value" => NULL,
                        "menu_order" => 1,
                        "instructions" => "",
                        "required" => 0,
                        "id" => "",
                        "class" => "",
                        "conditional_logic" => 0,
                        "parent" => "field_650578a534384",
                        "wrapper" => [
                            "width" => "",
                            "class" => "",
                            "id" => ""
                        ],
                        "default_value" => "",
                        "translations" => "translate",
                        "maxlength" => "",
                        "placeholder" => "",
                        "prepend" => "",
                        "append" => "",
                        "_name" => $item_for_name,
                        "_valid" => 1
                    ];


                endif;

            endif;

        endforeach;

    endif;

    return $field;
}


add_filter('woocommerce_payment_complete_order_status', 'snippetpress_payment_complete_status_payment_method', 10, 3);
function snippetpress_payment_complete_status_payment_method($status, $order_id, $order)
{
    $payment_method = $order->get_payment_method();
    if ($payment_method == 'cheque') {
        $status = 'processed';
    }
    return $status;
}

add_filter('acf/load_field/key=field_65245639fc473', 'add_characteristics_type_fields_to_term');


function add_characteristics_type_fields_to_term($field)
{

    $current_language = pll_current_language();

    $attributes = get_field('attributes', 'option');

    $field['choices'] = [];

    foreach ($attributes as $item) :

        $item_name_for_key = str_replace(' ', '', $item['attribute_name_' . $current_language]);

        $field['choices'][$item_name_for_key] = $item['attribute_name_' . $current_language];

    endforeach;

    return $field;
}

//add_filter('acf/load_field/key=field_65245455fc472', 'add_characteristics_possible_values_to_term');
//
//function add_characteristics_possible_values_to_term($field)
//{
//
//    $attributes = get_field('attributes', 'option');
//    $current_language = pll_current_language();
//    foreach ($attributes as $key => $item) :
//
//        $item_name_for_key = str_replace(' ', '', $item['attribute_name_' . $current_language]);
//
//        //        $dzioba = 'asdКККК' . 3;
//        //        $dzioba = str_replace(' ', '', $dzioba);
//
//        $field['sub_fields'][$key + 5] = [
//            "ID" => 520 + (int) $key,
//            "key" => "field_652456a2fc474" . $key,
//            "label" => "Characteristic possible values " . $item['attribute_name_' . $current_language],
//            "name" => "characteristic_possible_values",
//            "aria-label" => "",
//            "prefix" => "acf",
//            "type" => "checkbox",
//            "value" => NULL,
//            "menu_order" => $key + 5,
//            "instructions" => "",
//            "required" => 0,
//            "id" => "",
//            "class" => "",
//            "conditional_logic" => array(
//                array(
//                    array(
//                        "field" => "field_65245639fc473",
//                        "operator" => "==",
//                        "value" => $item_name_for_key
//                    )
//                )
//            ),
//            "parent" => 518,
//            "wrapper" => array(
//                "width" => "",
//                "class" => "",
//                "id" => ""
//            ),
//            "default_value" => array(),
//            "return_format" => "value",
//            "translations" => "copy_once",
//            "allow_custom" => 0,
//            "layout" => "vertical",
//            "toggle" => 0,
//            "save_custom" => 0,
//            "custom_choice_button_text" => "Add new choice",
//            "_name" => "characteristic_possible_values",
//            "_valid" => 1,
//            "parent_repeater" => "field_65245455fc472"
//        ];
//
//        if (!empty($item['attribute_values_' . $current_language])) {
//            foreach ($item['attribute_values_' . $current_language] as $subitem) :
//
//                // $subitem_name_for_key = str_replace(' ', '', $subitem['value']);
//                $field['sub_fields'][$key + 5]['choices'][$subitem['value']] = $subitem['value'];
//
//            endforeach;
//
//        } else {
//            $field['sub_fields'][$key + 5]['choices'] = [];
//        }
//
//    endforeach;
//
//    return $field;
//}

add_filter('woocommerce_catalog_orderby', 'remove_filters');

function remove_filters($orderby)
{

    unset($orderby['popularity']);
    unset($orderby['rating']);
    unset($orderby['date']);

    return $orderby;
}


function remove_processing_status()
{
    global $pagenow, $post;

    if ($pagenow === 'post.php' && isset($post->post_type) && $post->post_type === 'shop_order') {
        $order_status = get_post_status($post->ID);

        if ($order_status === 'wc-on-hold') {
            wp_update_post(array('ID' => $post->ID, 'post_status' => 'wc-processing'));
        }
    }
}

add_action('admin_init', 'remove_processing_status');

// Додаємо селекти сортування за категоріями кастомних товарів
function custom_sorting_dropdown()
{
    // Отримуємо усі категорії кастомних товарів
    $type = get_terms(array(
        'taxonomy'   => 'type', // Замініть на вашу першу таксономію
        'hide_empty' => false,
    ));

    $appointment = get_terms(array(
        'taxonomy'   => 'appointment', // Замініть на вашу другу таксономію
        'hide_empty' => false,
    ));

    $selected_category1 = (isset($_GET['custom_type'])) ? $_GET['custom_type'] : '';
    $selected_category2 = (isset($_GET['custom_appointment'])) ? $_GET['custom_appointment'] : '';

    echo '<select name="custom_type">
        <option value="" ' . selected('', $selected_category1, false) . '>Фільтрувати за типом</option>';

    foreach ($type as $category1) {
        echo '<option value="' . $category1->slug . '" ' . selected($category1->slug, $selected_category1, false) . '>' . $category1->name . '</option>';
    }

    echo '</select>';

    echo '<select name="custom_appointment">
        <option value="" ' . selected('', $selected_category2, false) . '>Фільтрувати за призначенням</option>';

    foreach ($appointment as $category2) {
        echo '<option value="' . $category2->slug . '" ' . selected($category2->slug, $selected_category2, false) . '>' . $category2->name . '</option>';
    }

    echo '</select>';
}
add_action('restrict_manage_posts', 'custom_sorting_dropdown');

// Обробник сортування за категоріями кастомних товарів
function custom_sort_by_custom_category_columns($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if (isset($query->query['post_type']) && $query->query['post_type'] == 'product') {
        if (isset($_GET['custom_type']) && $_GET['custom_type'] !== '') {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'type', // Замініть на вашу першу таксономію
                    'field'    => 'slug',
                    'terms'    => $_GET['custom_type'],
                ),
            ));
        }

        if (isset($_GET['custom_appointment']) && $_GET['custom_appointment'] !== '') {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'appointment', // Замініть на вашу другу таксономію
                    'field'    => 'slug',
                    'terms'    => $_GET['custom_appointment'],
                ),
            ));
        }
    }
}
add_action('parse_query', 'custom_sort_by_custom_category_columns');

function remove_default_category_dropdown_script()
{
    if (isset($_GET['post_type']) && $_GET['post_type'] === 'product') { ?>
        <script>
            jQuery(document).ready(function($) {
                $('#product_cat').hide();
            });
            jQuery(document).ready(function($) {
                $('#dropdown_product_type').hide();
            });
        </script>
<?php }
}

add_action('admin_footer', 'remove_default_category_dropdown_script');

add_filter('woocommerce_product_stock_status_options', 'change_stock_statuses');

function change_stock_statuses($array)
{
    unset($array['onbackorder']);
    return $array;
}

add_filter('acf/load_field/key=field_6564650f01b4b', 'add_wholesale_stock_statuses_choices');

function add_wholesale_stock_statuses_choices($field)
{

    $field['choices'] = array();

    $current_language = pll_current_language();

    $wholesale_options = get_field('wholesale_options', 'option');

    foreach ($wholesale_options as $key => $item) :

        $field['choices'][$item['status_text_' . $current_language]] = $item['status_text_' . $current_language];

    endforeach;

    return $field;
}


// Зберігаємо корзину при розлогіні
function save_cart_on_logout()
{
    if (is_user_logged_in()) {
        global $woocommerce;
        $woocommerce->session->set_customer_session_cookie(true);
    }
}
add_action('wp_logout', 'save_cart_on_logout');

// Запобігаємо очищенню корзини при розлогіні
function prevent_cart_clear_on_logout()
{
    return false;
}
add_filter('woocommerce_clear_cart_after_logout', 'prevent_cart_clear_on_logout');

function add_language_slug_to_url($url) {


    if ( is_checkout() and empty( $_GET['key'] ) ) {
  
        if (function_exists('pll_current_language') || function_exists('icl_object_id')) {
            $current_language = function_exists('pll_current_language') ? pll_current_language() : ICL_LANGUAGE_CODE;

            if ($current_language !== get_option('WPLANG')) {
                $url = trailingslashit($url) . $current_language . '/';
            }
        }

        return $url;
    } else {
        return $url;
    }
}

if (!is_admin()) {
    add_filter('home_url', 'add_language_slug_to_url');
    add_filter('site_url', 'add_language_slug_to_url');
    add_filter('post_link', 'add_language_slug_to_url');
    add_filter('page_link', 'add_language_slug_to_url');
    add_filter('term_link', 'add_language_slug_to_url');
}


function custom_redirects()
{
    if (strstr($_SERVER['REQUEST_URI'], 'order-received')) {
        if (strstr($_SERVER['HTTP_REFERER'], '/en')) :
            if (strstr($_SERVER['REQUEST_URI'], '/en') and empty($_GET['re']) ) {
                $redirect_url =  home_url() .'/en'. str_replace('/en','',$_SERVER['REQUEST_URI']) .'&re=1';
                wp_redirect($redirect_url);
            }
        endif;
    }
}

add_action('template_redirect', 'custom_redirects');


add_action( 'wp_ajax_nopriv_set_new_order_status', 'set_new_order_status' );
add_action( 'wp_ajax_set_new_order_status', 'set_new_order_status' );

function set_new_order_status() 
{
    $settings = get_option('woocommerce_liqpay-webplus_settings');
    $signature = $_POST['signature'];
    $data = $_POST['data'];

    $log_file = get_stylesheet_directory() . '/liqpay-log.json'; // Змініть шлях до файлу, якщо потрібно
    
    $file_date = [
        'sign' => $sign,
        'signature' => $signature,
        'data' => $data
    ];

    file_put_contents($log_file, json_encode($file_date) . PHP_EOL, FILE_APPEND);

    $sign = base64_encode( sha1( 
    $settings["private_key"] .  
    $data . 
    $settings["private_key"] 
    , 1 ));

    if ( $sign == $signature ) {
        $order_id = json_decode( base64_decode($data), true )['order_id'];
        $order = wc_get_order($order_id);
        $order->update_status('processing');
    }

    wp_send_json_success( '', 200 );

}

