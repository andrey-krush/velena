<?php dynamic_sidebar('footer') ?>
<?php if (is_page_template('contacts.php')) : ?>

    <?php $contact_page = get_field('content'); ?>
    <?php $main_office_location = $contact_page['main_office']['main_office_location']; ?>
    <?php $storages = $contact_page['storages']; ?>
    <script>
        const center_setting = new google.maps.LatLng(50.437555031354, 30.692406332324);
        const zoon_setting = 13;
        const TEMPLATE_PATH = '<?php echo get_template_directory_uri(); ?>'
        let markerImage = {
            url: TEMPLATE_PATH + '/img/marker.svg',
            scaledSize: new google.maps.Size(70, 104),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(0, 0)
        }

        const features = [];
        features.push({
            position: new google.maps.LatLng(<?php echo $main_office_location['lat']; ?>, <?php echo $main_office_location['lng']; ?>),
            itemId: '0'
        })
        <?php foreach ($storages as $key => $item) :  ?>
            features.push({
                position: new google.maps.LatLng(<?php echo $item['storage_location']['lat']; ?>, <?php echo $item['storage_location']['lng']; ?>),
                itemId: '<?php echo $key + 1; ?>'
            })
        <?php endforeach; ?>

        function initMap() {
            let map = new google.maps.Map(document.getElementById("map"), {
                center: center_setting,
                zoom: zoon_setting,
                styles: [{
                        featureType: "administrative",
                        elementType: "labels",
                        stylers: [{
                            visibility: "on",
                        }, ],
                    },
                    {
                        featureType: "administrative.country",
                        elementType: "geometry.stroke",
                        stylers: [{
                            visibility: "on",
                        }, ],
                    },
                    {
                        featureType: "administrative.province",
                        elementType: "geometry.stroke",
                        stylers: [{
                            visibility: "on",
                        }, ],
                    },
                    {
                        featureType: "landscape",
                        elementType: "geometry",
                        stylers: [{
                                visibility: "on",
                            },
                            {
                                color: "#F5F5F5",
                            },
                        ],
                    },
                    {
                        featureType: "landscape.natural",
                        elementType: "labels",
                        stylers: [{
                            visibility: "off",
                        }, ],
                    },
                    {
                        featureType: "poi",
                        elementType: "all",
                        stylers: [{
                            visibility: "off",
                        }, ],
                    },
                    {
                        featureType: "road",
                        elementType: "all",
                        stylers: [{
                            color: "#C9C9C9",
                        }, ],
                    },
                    {
                        featureType: "road",
                        elementType: "labels",
                        stylers: [{
                            visibility: "off",
                        }, ],
                    },
                    {
                        featureType: "transit",
                        elementType: "labels.icon",
                        stylers: [{
                            visibility: "off",
                        }, ],
                    },
                    {
                        featureType: "transit.line",
                        elementType: "geometry",
                        stylers: [{
                            visibility: "off",
                        }, ],
                    },
                    {
                        featureType: "transit.line",
                        elementType: "labels.text",
                        stylers: [{
                            visibility: "on",
                        }, ],
                    },
                    {
                        featureType: "transit.station.airport",
                        elementType: "geometry",
                        stylers: [{
                            visibility: "off",
                        }, ],
                    },
                    {
                        featureType: "transit.station.airport",
                        elementType: "labels",
                        stylers: [{
                            visibility: "off",
                        }, ],
                    },
                    {
                        featureType: "water",
                        elementType: "geometry",
                        stylers: [{
                            color: "#C9C9C9",
                        }, ],
                    },
                    {
                        featureType: "water",
                        elementType: "labels",
                        stylers: [{
                            visibility: "off",
                        }, ],
                    },
                ],
            });
            let marker;
            const addressList = $('.contact__place li');
            let bounds = new google.maps.LatLngBounds();

            for (let i = 0; i < features.length; i++) {
                marker = new google.maps.Marker({
                    position: features[i].position,
                    map: map,
                    icon: markerImage,
                    itemId: features[i].itemId
                });

                bounds.extend(marker.position);

                google.maps.event.addListener(marker, 'click', function() {
                    if (!this['itemId']) return;

                    map.panTo(this.position);
                    map.setZoom(15);

                    let thisItem = $('.contact__place [data-id="' + this['itemId'] + '"]')

                    thisItem.addClass('is-active')
                    thisItem.siblings().removeClass('is-active')
                });
            }

            map.fitBounds(bounds, 90);

            addressList.on('click', function() {
                let thisId = $(this).attr('data-id')
                $(this).addClass('is-active')
                $(this).siblings().removeClass('is-active')

                let thisMarker = features.filter(function(item) {
                    return item['itemId'] == thisId;
                })

                if (thisMarker.length) {
                    map.panTo(thisMarker[0].position);
                    map.setZoom(15);
                }
            })
        }

        if (document.getElementById("map"))
            initMap()
    </script>
<?php endif; ?>
<script>
    let validationErrors = {
        <?php if (is_user_logged_in()) : ?> "favorites_unavailable": "<?php pll_e('Ви отримаєте на Email сповіщення, коли даний товар з’явиться у продажу'); ?>",
        <?php endif; ?> "required": "<?php pll_e('This field is required'); ?>",
        "invalid": "<?php pll_e('This field is invalid'); ?>",
        "allowed_ext": "<?php pll_e('Allowed extensions: '); ?>",
        "max_size": "<?php pll_e('Maximum file size is '); ?>",

        "firstname": {
            "required": "<?php pll_e('First Name is required.'); ?>"
        },
        "lastname": {
            "required": "<?php pll_e('Last Name is required.'); ?>"
        },
        "email": {
            "regex": "<?php pll_e('The E-mail must be a valid email address.'); ?>",
            "required": "<?php pll_e('E-mail is required.'); ?>"
        },
        "zipcode": {
            "required": "<?php pll_e('Postcode is required.'); ?>"
        },
        "language": {
            "required": "<?php pll_e('Language is required.'); ?>"
        },
        "city": {
            "required": "<?php pll_e('City is required.'); ?>"
        },
        "phone": {
            "required": "<?php pll_e('Telephone is required.'); ?>"
        },
        "password": {
            "password": "<?php pll_e('Мінімальна кількість символів 8'); ?>"
        },
        "password_repeat": {
            "password_repeat": "<?php pll_e('Паролі не співпадають'); ?>"
        },
    }
</script>


<div style="display: none;">
    <div class="popup-search" id="popup-search">
        <div class="popup-search__inner">
            <div class="popup-search__content">
                <form class="header__search-form is-open" action="<?php echo Search_Page::get_url(); ?>" method="GET">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                        <defs>
                            <clipPath id="clip-path-5">
                                <rect width="30" height="30" transform="translate(944 -278)" fill="#fff"></rect>
                            </clipPath>
                        </defs>
                        <g transform="translate(-944 278)" clip-path="url(#clip-path-5)">
                            <path d="M16.222,14.8a1,1,0,1,0-1.415,1.413ZM1,9.5H1ZM9.5,1V1Zm3.138,4.23-.77-.638-1.276,1.54.77.638ZM13,10v1h2V10Zm7.708,9.294L16.222,14.8l-1.415,1.413,4.486,4.494ZM17,9.5a7.5,7.5,0,0,1-2.2,5.3l1.414,1.414A9.5,9.5,0,0,0,19,9.5Zm-2.2,5.3A7.5,7.5,0,0,1,9.5,17v2a9.5,9.5,0,0,0,6.718-2.782ZM9.5,17a7.5,7.5,0,0,1-5.3-2.2L2.782,16.218A9.5,9.5,0,0,0,9.5,19ZM4.2,14.8A7.5,7.5,0,0,1,2,9.5H0a9.5,9.5,0,0,0,2.782,6.718ZM2,9.5A7.5,7.5,0,0,1,4.2,4.2L2.782,2.782A9.5,9.5,0,0,0,0,9.5ZM4.2,4.2A7.5,7.5,0,0,1,9.5,2V0A9.5,9.5,0,0,0,2.782,2.782ZM9.5,2a7.5,7.5,0,0,1,5.3,2.2l1.414-1.414A9.5,9.5,0,0,0,9.5,0Zm5.3,2.2A7.5,7.5,0,0,1,17,9.5h2a9.5,9.5,0,0,0-2.782-6.718ZM11.362,6.77A4.218,4.218,0,0,1,13,10h2a6.214,6.214,0,0,0-2.362-4.77Z" transform="translate(949 -273)" class="hover-fill" fill="#333"></path>
                        </g>
                    </svg>
                    <input type="text" name="search" placeholder="Шукати..." required="">

                    <div class="header__search-list">
                        <ul></ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php if (is_page_template('contacts.php')) : ?>
        <div class="popup" id="contact-popup">
            <div class="popup__inner">
                <div class="popup__title title-h2">
                    <span><?php echo pll__('Напишіть нам'); ?></span>
                </div>
                <form class="login__form form" data-success-popup="#contact-success-popup">
                    <input type="hidden" name="action" value="contact">
                    <input type="hidden" name="page_title" value="<?php echo get_the_title(); ?>">
                    <div class="form__row">
                        <div class="form__input">
                            <div class="input input--required">
                                <input type="text" name="name" required placeholder="<?php echo pll__('Ваше ім’я'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form__row">
                        <div class="form__input">
                            <div class="input input--required">
                                <input type="email" name="email" data-validation="email" required placeholder="<?php echo pll__('Ваша електронна адреса'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form__row">
                        <div class="form__textarea form__input">
                            <div class="input input--required">
                                <textarea name="message" required placeholder="<?php echo pll__('Ваш меседж'); ?>"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form__btn">
                        <button class="btn" type="submit"><?php echo pll__('Надіслати'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
    <div class="popup popup--centered" id="login-popup">
        <div class="popup__inner">
            <div class="popup__title title-h2">
                <span><?php pll_e('Вхід у кабінет'); ?></span>
            </div>
            <div class="section-text section-text--small">
                <p><?php pll_e('Новий клієнт? Створити'); ?> <a href="<?php echo Registration_Page::get_url(); ?>"><?php pll_e('новий акаунт'); ?></a></p>
            </div>
            <form class="login__form form">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="is_popup" value="1">
                <?php if (is_tax()) : ?>
                    <?php $redirect_link = get_term_link(get_queried_object()); ?>
                <?php else : ?>
                    <?php if (is_front_page()) : ?>
                        <?php $redirect_link = home_url(); ?>
                    <?php else : ?>
                        <?php $redirect_link = get_the_permalink(); ?>
                    <?php endif; ?>
                <?php endif; ?>
                <input type="hidden" name="link" value="<?php echo $redirect_link; ?>">
                <div class="form__row">
                    <div class="form__input">
                        <div class="input">
                            <input type="email" name="login" placeholder="<?php pll_e('Логін'); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__input">
                        <div class="input">
                            <input type="password" name="password" placeholder="<?php pll_e('Пароль'); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="form__text section-text section-text--small">
                    <p><a href="#reset-pass-1" data-fancybox><?php pll_e('Забули пароль?'); ?></a></p>
                </div>
                <div class="form__error"></div>
                <div class="form__btn">
                    <button class="btn" type="submit"><?php pll_e('Увійти'); ?></button>
                </div>
            </form>
        </div>
    </div>
    <div class="popup popup--centered" id="reset-pass-1">
        <div class="popup__inner">
            <div class="popup__title title-h2">
                <span><?php pll_e('Відновлення паролю'); ?></span>
            </div>
            <div class="section-text section-text--small">
                <p><?php pll_e('Введіть свою електрону пошту, яку ви вказували при реєстрації'); ?></p>
            </div>
            <form class="reset-pass-1 form" data-success-popup="#reset-pass-1-success-popup">
                <input type="hidden" name="action" value="lost_password">
                <div class="form__row">
                    <div class="form__input">
                        <div class="input">
                            <input type="email" name="email" data-validation="email" placeholder="<?php pll_e('Логін'); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="form__error"></div>
                <div class="form__btn">
                    <button class="btn" type="submit"><?php pll_e('Відправити лист'); ?></button>
                </div>
            </form>
        </div>
    </div>
    <div class="popup popup--centered" id="reset-pass-2">
        <div class="popup__inner">
            <div class="popup__title title-h2">
                <span><?php pll_e('Відновлення паролю'); ?></span>
            </div>
            <div class="section-text section-text--small">
                <p><?php pll_e('Введіть новий пароль'); ?></p>
            </div>
            <form class="reset-pass-2 form" data-success-popup="#reset-pass-2-success-popup">
                <input type="hidden" name="action" value="reset_password">
                <input type="hidden" name="email" value="<?php echo $_GET['confirm'] ?>">
                <div class="form__row">
                    <div class="form__input">
                        <div class="input">
                            <input type="password" name="new_password" data-validation="password" required placeholder="<?php pll_e('Новий пароль'); ?>" value="">
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__input">
                        <div class="input">
                            <input type="password" name="new_password_again" data-validation="password_repeat" required placeholder="<?php pll_e('Новий пароль повторно'); ?>" value="">
                        </div>
                    </div>
                </div>
                <div class="form__error"></div>
                <div class="form__btn">
                    <button class="btn" type="submit"><?php pll_e('Зберегти'); ?></button>
                </div>
            </form>
        </div>
    </div>

    <div class="popup" id="contact-success-popup">
        <div class="popup__inner">
            <div class="success">
                <div class="success__img">
                    <img src="<?= TEMPLATE_PATH ?>/img/ico-success-round.svg" alt="Success">
                </div>
                <div class="success__content">
                    <div class="success__title title-h2">
                        <span><?php echo pll__('Повідомлення надіслано'); ?></span>
                    </div>
                    <div class="section-text">
                        <p><?php echo pll__('Дякуємо за ваше звернення! Ми якнайшвидше його розглянемо та дамо вам нашу відповідь'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="popup" id="reset-pass-1-success-popup">
        <div class="popup__inner">
            <div class="success">
                <div class="success__img">
                    <img src="<?= TEMPLATE_PATH ?>/img/ico-success-round.svg" alt="Success">
                </div>
                <div class="success__content">
                    <div class="success__title title-h2">
                        <span><?php echo pll__('Заяку на відновлення паролю надіслано!'); ?></span>
                    </div>
                    <div class="section-text">
                        <p><?php echo pll__('Для продовження дій перейдіть за посилання у листі, який надійшов на вашу пошту'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="popup" id="reset-pass-2-success-popup">
        <div class="popup__inner">
            <div class="success">
                <div class="success__img">
                    <img src="<?= TEMPLATE_PATH ?>/img/ico-success-round.svg" alt="Success">
                </div>
                <div class="success__content">
                    <div class="success__title title-h2">
                        <span><?php echo pll__('Пароль змінено'); ?></span>
                    </div>
                    <div class="section-text">
                        <p><?php echo pll__('Вітаємо пароль успішно змінено'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mini-cart mini-cart--regular <?php echo (WC()->cart->get_cart_contents_count() == 0 ? 'cart-empty' : ''); ?>">
    <div class="mini-cart__title title-h3">
        <span><?php echo pll__('Ваш кошик'); ?></span>
    </div>
    <div class="mini-cart__list">
        <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
            <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : ?>
                <?php $product_id = $cart_item['product_id']; ?>
                <?php $product = wc_get_product($product_id); ?>
                <div class="cart-item cart-item--smallest">
                    <a href="<?php echo get_the_permalink($product_id); ?>" class="cart-item__img">
                        <?php $thumbnail_id = get_post_thumbnail_id($product_id);
                        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                        <img src="<?php echo get_the_post_thumbnail_url($product_id); ?>" alt="<?php echo $alt ?>">
                    </a>
                    <div class="product-card__content">
                        <a href="<?php echo get_the_permalink($product_id); ?>" class="cart-item__title title-h5">
                            <span><?php echo get_the_title($product_id); ?></span>
                        </a>
                        <div class="cart-item__total">
                            <?php if (!empty($cart_item['data']->get_sale_price()) and ($cart_item['data']->get_sale_price() != $cart_item['data']->get_regular_price())) : ?>
                                <?php echo wc_price($cart_item['data']->get_sale_price()); ?>
                            <?php else : ?>
                                <?php echo wc_price($cart_item['data']->get_price()); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="cart-item__quantity">
                        <span>x<?php echo $cart_item['quantity']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <span class="mini-cart__list-empty"><?php pll_e('В вашій корзині пусто'); ?></span>
    <div class="mini-cart__total">
        <span><?php echo pll__('Вартість кошика'); ?>:</span>
        <div class="product-card__price--new">
            <?php echo wc_price(WC()->cart->cart_contents_total); ?>
        </div>
    </div>
    <div class="mini-cart__btn">
        <a href="<?php echo wc_get_cart_url(); ?>" class="btn">
            <span><?php echo pll__('Перейти до кошика'); ?></span>
        </a>
    </div>
</div>
<?php
$current_language = pll_current_language();
$favorite_text = 'В обраному &1';
// для англ мови: 'item,items,items'
$favorite_text_products = 'товар,товари,товарів';
$favorite_text_products = explode(',', $favorite_text_products);

if (is_user_logged_in()) :
    $user = wp_get_current_user();
    $liked_posts = get_user_meta($user->data->ID, 'liked_posts_' . $current_language);
else :
    $liked_posts = isset($_COOKIE['wishlist_products_' . $current_language]) ? json_decode(stripslashes($_COOKIE['wishlist_products_' . $current_language]), true) : [];
endif;

$liked_posts = !empty($liked_posts) ? count($liked_posts) : 0;

$liked_posts_text = str_replace('&1', pluralQuantityLocale($liked_posts, $favorite_text_products), $favorite_text);
?>
<div class="mini-cart mini-cart--favorite <?php echo ($liked_posts == 0 ? 'cart-empty' : ''); ?>">
    <div class="mini-cart__title title-h3">
        <span><?php echo pll__('Обране'); ?></span>
    </div>
    <span class="mini-cart__list-empty"><?php echo pll__('В обраному нічого немає'); ?></span>
    <span class="mini-cart__list-not-empty"><?php echo pll__('Кількість товарів в обраному:') . ' ' . $liked_posts; ?></span>
    <div class="mini-cart__btn">
        <a href="<?php echo (new Wishlist_Page)::get_url(); ?>" class="btn">
            <span><?php echo pll__('До вподобань'); ?></span>
        </a>
    </div>
</div>
<?php $products = get_wholesale_products(); ?>
<div class="mini-cart mini-cart--wholesale">
    <div class="mini-cart__title title-h3">
        <span><?php echo pll__('Оптове замовлення'); ?></span>
    </div>
    <div class="mini-cart__list">
        <?php
        $cart_total = 0.0;
        if (!empty($products)) : ?>
            <?php foreach ($products as $cart_item) : ?>
                <?php
                $product_id = $cart_item['product_id'];
                $wholesale_options = get_field('wholesale_options', $product_id);
                $cart_total = get_float_from_wc_price($wholesale_options['price_text']) * $cart_item['quantity'];
                ?>
                <div class="cart-item cart-item--smallest">
                    <?php if (get_the_post_thumbnail_url($product_id)) { ?>
                        <a href="<?php echo get_the_permalink($product_id); ?>" class="cart-item__img">
                            <?php $thumbnail_id = get_post_thumbnail_id($product_id);
                            $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                            <img src="<?php echo get_the_post_thumbnail_url($product_id); ?>" alt="<?php echo $alt; ?>">
                        </a>
                    <?php } ?>
                    <div class="product-card__content">
                        <a href="<?php echo get_the_permalink($product_id); ?>" class="cart-item__title title-h5">
                            <span><?php echo get_the_title($product_id); ?></span>
                        </a>
                        <div class="cart-item__total">
                            <?php echo (get_float_from_wc_price($wholesale_options['price_text']) == 0 ? pll__('Ціна договірна') : wc_price(get_float_from_wc_price($wholesale_options['price_text']))); ?>
                        </div>
                    </div>
                    <div class="cart-item__quantity">
                        <span>x<?php echo $cart_item['quantity']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <span class="mini-cart__list-empty"><?php pll_e('В вашій корзині пусто'); ?></span>
    <!-- <div class="mini-cart__total">
        <span><?php //echo pll__('Вартість кошика'); 
                ?>:</span>
        <div class="product-card__price--new">
            <?php //echo wc_price(get_wholesale_total()); 
            ?>
        </div>
    </div> -->
    <div class="mini-cart__btn">
        <a href="<?php echo (new Wholesale_Cart_Page())::get_url(); ?>" class="btn">
            <span><?php echo pll__('Перейти до кошика (опт)'); ?></span>
        </a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script src="<?= TEMPLATE_PATH ?>/js/main.js"></script>

</div>
<?php wp_footer(); ?>
</body>

</html>