<?php

class Header_Widget extends WP_Widget {

    public static function init() {
        add_action('widgets_init', [__CLASS__, 'widgets_init']);
        add_action('acf/init', [__CLASS__, 'acf_add_local_field_group']);
        add_action('init', [__CLASS__, 'polylang_translate']);
    }

    public static function polylang_translate() {
        pll_register_string('log-in', 'Вхід', 'header' );
        pll_register_string('catalog', 'Каталог продукції', 'header' );
        pll_register_string('search', 'Пошук', 'header' );
        pll_register_string('research', 'Шукати', 'header' );
        pll_register_string('research', 'Обрати призначення', 'header' );
        pll_register_string('research', 'Згорнути', 'header' );
    }

    public static function widgets_init() {
        register_sidebar( [
            'name'          => 'Header',
            'id'            => 'header',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
            ] );
        register_widget(__CLASS__);
    }

    function __construct() {
        parent::__construct('header-widget', 'Section header', []);
    }

    function widget($args, $instance) {
        $menu = get_field('menu', 'widget_' . $args['widget_id']);
        $logo = get_field('logotype', 'widget_' . $args['widget_id']);
        $destination_title = get_field('destination_title', 'widget_' . $args['widget_id']);
        $destination_text = get_field('destination_text', 'widget_' . $args['widget_id']);
        $appointment = get_terms( array(
            'taxonomy'   => 'appointment',
            'hide_empty' => false,
        ) );
        $type = get_terms( array(
            'taxonomy'   => 'type',
            'hide_empty' => false,
            'parent' => 0
        ) );

        $wholesale_cart = get_wholesale_products();
        $regular_cart = WC()->cart->get_cart();
        

        $header_size = '';
        if( is_front_page() ){
            $header_size = 'header--big';
        }
        $container_size = '';
        if ( is_shop() || is_category() ) {
            $container_size = 'container--big';
        }

        $archive_post_link = (new Archive_Post_Page())::get_url();
        ?>
   
     
        <header class="header <?php echo $header_size; ?>">
            <div class="header__static">
                <div class="container <?php echo $container_size; ?>">
                    <div class="header__container mobile-hide">
                        <?php if ( !empty( $logo ) ) : ?>
                            <a href="<?php echo home_url(); ?>" class="header__logo" title="Velena">
                                <img src="<?php echo $logo; ?>" alt="Velena" width="294" height="85">
                            </a>
                        <?php endif; ?>
                        <div class="header__content">
                            <div class="header__row">
                                <?php if ( !empty( $menu ) ) : ?>
                                    <nav class="header__nav">
                                        <ul>
                                            <?php foreach ( $menu as $item ) : ?>
                                                <?php if ( strstr( $item['link']['url'], $_SERVER['REQUEST_URI'] ) and !is_front_page() ) : ?>
                                                    <li class="is-active">
                                                <?php elseif(  $item['link']['url'] == $archive_post_link and ( is_category() or is_singular('post') ) ) : ?>
                                                    <li class="is-active">
                                                <?php else : ?>
                                                    <li>
                                                <?php endif; ?>
                                                    <a href="<?php echo $item['link']['url']; ?>"><?php echo $item['link']['title']; ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                                <div class="header__btns">
                                    <?php if ( is_user_logged_in() ) : ?>
                                        <div class="header__btn dropdown">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
                                                <defs>
                                                <clipPath id="clip-path-1">
                                                    <rect width="20" height="20" transform="translate(983 -296)" fill="#fff"/>
                                                </clipPath>
                                                </defs>
                                                <g transform="translate(-983 296)" clip-path="url(#clip-path-1)">
                                                <path d="M7.445,9.559l-.433-.9a1,1,0,0,0-.529,1.176Zm-3.891,0,.962.275a1,1,0,0,0-.529-1.176ZM2,15l-.962-.275A1,1,0,0,0,2,16Zm7,0v1a1,1,0,0,0,.962-1.275ZM7.878,10.46A5.5,5.5,0,0,0,11,5.5H9A3.5,3.5,0,0,1,7.013,8.657ZM11,5.5A5.5,5.5,0,0,0,5.5,0V2A3.5,3.5,0,0,1,9,5.5ZM5.5,0A5.5,5.5,0,0,0,0,5.5H2A3.5,3.5,0,0,1,5.5,2ZM0,5.5a5.5,5.5,0,0,0,3.122,4.96l.866-1.8A3.5,3.5,0,0,1,2,5.5Zm2.962,9.775L4.516,9.834,2.593,9.284,1.038,14.725ZM9,14H2v2H9ZM6.484,9.834l1.555,5.441,1.923-.549L8.407,9.284Z" transform="translate(987.5 -294)" class="hover-fill" fill="#4f4f4f"/>
                                                </g>
                                            </svg>
                                            <span><?php pll_e('Кабінет клієнта'); ?></span>
                                            <ul>
                                                <li><a href="<?php echo wc_get_account_endpoint_url( 'edit-account' ); ?>"><?php pll_e('Налаштування акаунту'); ?></a></li>
                                                <li><a href="<?php echo wc_get_account_endpoint_url( 'orders' ); ?>"><?php pll_e('Історія замовлень'); ?></a></li>
                                                <?php if( is_tax() ) : ?>
                                                    <?php $redirect_link = get_term_link(get_queried_object()); ?>
                                                <?php else : ?>
                                                    <?php $redirect_link = get_the_permalink(); ?>
                                                <?php endif; ?>
                                                <li class="separated"><a href="<?php echo wp_logout_url( $redirect_link ); ?>"><?php pll_e('Вихід'); ?></a></li>
                                            </ul>
                                        </div>
                                    <?php else : ?>
                                        <a href="#login-popup" class="header__btn" data-fancybox>
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
                                                <defs>
                                                <clipPath id="clip-path-1">
                                                    <rect width="20" height="20" transform="translate(983 -296)" fill="#fff"/>
                                                </clipPath>
                                                </defs>
                                                <g transform="translate(-983 296)" clip-path="url(#clip-path-1)">
                                                <path d="M7.445,9.559l-.433-.9a1,1,0,0,0-.529,1.176Zm-3.891,0,.962.275a1,1,0,0,0-.529-1.176ZM2,15l-.962-.275A1,1,0,0,0,2,16Zm7,0v1a1,1,0,0,0,.962-1.275ZM7.878,10.46A5.5,5.5,0,0,0,11,5.5H9A3.5,3.5,0,0,1,7.013,8.657ZM11,5.5A5.5,5.5,0,0,0,5.5,0V2A3.5,3.5,0,0,1,9,5.5ZM5.5,0A5.5,5.5,0,0,0,0,5.5H2A3.5,3.5,0,0,1,5.5,2ZM0,5.5a5.5,5.5,0,0,0,3.122,4.96l.866-1.8A3.5,3.5,0,0,1,2,5.5Zm2.962,9.775L4.516,9.834,2.593,9.284,1.038,14.725ZM9,14H2v2H9ZM6.484,9.834l1.555,5.441,1.923-.549L8.407,9.284Z" transform="translate(987.5 -294)" class="hover-fill" fill="#4f4f4f"/>
                                                </g>
                                            </svg>
                                            <span><?php pll_e('Вхід'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    <div class="header__btn dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
                                        <defs>
                                            <clipPath id="clip-path-2">
                                            <rect width="20" height="20" transform="translate(901 -276)" fill="#fff"/>
                                            </clipPath>
                                        </defs>
                                        <g transform="translate(-901 276)" clip-path="url(#clip-path-2)">
                                            <g transform="translate(902.589 -274.641)">
                                            <path d="M8.661,15.617a7.123,7.123,0,0,0,7.25-6.988,7.123,7.123,0,0,0-7.25-6.988,7.123,7.123,0,0,0-7.25,6.988A7.123,7.123,0,0,0,8.661,15.617Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="2"/>
                                            <path d="M10.016,6.5c.221-1.471,2.047-.845,4.02-1.614L15,6.5V9.058l-.5,2.526-1.308,2.238c1.4-3.076-.93-1.72-1.719-4.019C11.035,8.528,9.651,8.928,10.016,6.5Z" class="hover-fill" fill="#4f4f4f"/>
                                            <path d="M5.583,11.584c-.221-1.471-1.17-.457-3.083-1.031v.5l1,1.993,1.749,1.308L7,15.036C7.438,13.762,5.947,14.012,5.583,11.584Z" class="hover-fill" fill="#4f4f4f"/>
                                            <path d="M5.249,6.014c.716-1.279,2.763-2.073,1.063-3.433L4,4.076,2.5,6.5,2,8.435C2.438,7.16,4.125,8.021,5.249,6.014Z" class="hover-fill" fill="#4f4f4f"/>
                                            <path d="M10.016,6.5c.221-1.471,2.047-.845,4.02-1.614L15,6.5V9.058l-.5,2.526-1.308,2.238c1.4-3.076-.93-1.72-1.719-4.019C11.035,8.528,9.651,8.928,10.016,6.5Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="1"/>
                                            <path d="M5.583,11.584c-.221-1.471-1.17-.457-3.083-1.031v.5l1,1.993,1.749,1.308L7,15.036C7.438,13.762,5.947,14.012,5.583,11.584Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="1"/>
                                            <path d="M5.249,6.014c.716-1.279,2.763-2.073,1.063-3.433L4,4.076,2.5,6.5,2,8.435C2.438,7.16,4.125,8.021,5.249,6.014Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="1"/>
                                            </g>
                                        </g>
                                        </svg>
                                        <span><?php echo strtoupper(pll_current_language( 'slug' ));?></span>
                                        <?php polylang_language_switcher(); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="header__row">
                                <div class="header__btns header__btns--big">
                                    <button type="button" class="header__btn header-catalog-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                        <defs>
                                            <clipPath id="clip-path-3">
                                            <rect width="30" height="30" transform="translate(924 -182)" fill="#fff"/>
                                            </clipPath>
                                            <clipPath id="clip-path-4">
                                            <rect width="15" height="15" transform="translate(0 1)" fill="#fff"/>
                                            </clipPath>
                                        </defs>
                                        <g transform="translate(-924 182)" clip-path="url(#clip-path-3)">
                                            <g transform="translate(931.5 -175)" clip-path="url(#clip-path-4)">
                                            <rect width="4" height="4" transform="translate(1 2)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"/>
                                            <rect width="4" height="4" transform="translate(1 11)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"/>
                                            <rect width="4" height="4" transform="translate(10 2)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"/>
                                            <rect width="4" height="4" transform="translate(10 11)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"/>
                                            </g>
                                        </g>
                                        </svg>
                                        <span><?php pll_e('Каталог продукції'); ?></span>
                                    </button>
                                    <?php if( is_product() ) : ?>
                                        <?php $main_terms = get_terms([
                                            'taxonomy' => 'type',
                                            'object_ids' => get_the_ID(),
                                            'parent' => 0
                                        ]); ?>
                                        <?php if( !empty( $main_terms ) ) : ?>
                                            <ul class="breadcrumbs">
                                                <?php $secondary_terms = get_terms([
                                                    'taxonomy' => 'type',
                                                    'object_ids' => get_the_ID(),
                                                    'parent' => $main_terms[0]->term_id
                                                ]); ?>
                                                <?php if( !empty( $secondary_terms ) ) : ?>
                                                    <li></li>
                                                    <li><?php echo $main_terms[0]->name; ?></li>
                                                    <li><a href="<?php echo get_term_link($secondary_terms[0]); ?>"><?php echo $secondary_terms[0]->name; ?></a></li>
                                                <?php else : ?>
                                                    <li></li>
                                                    <li><a href="<?php echo get_term_link($main_terms[0]); ?>"><?php echo $main_terms[0]->name; ?></a></li>
                                                <?php endif; ?>
                                            </ul>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="header__btns header__btns--big">
                                    <div class="header__search">
                                        <form class="header__search-form" action="<?php echo Search_Page::get_url(); ?>" method="GET">
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
                                            <input type="text" name="search" placeholder="<?php pll_e('Шукати'); ?>..." required>
                                            <button type="submit" class="btn btn--small"><?php pll_e('Шукати'); ?></button>
                                            <div class="header__search-list"><ul></ul></div>
                                        </form>
                                        <button type="button" class="header__search-btn header__btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                                <defs>
                                                <clipPath id="clip-path-5">
                                                    <rect width="30" height="30" transform="translate(944 -278)" fill="#fff"/>
                                                </clipPath>
                                                </defs>
                                                <g transform="translate(-944 278)" clip-path="url(#clip-path-5)">
                                                <path d="M16.222,14.8a1,1,0,1,0-1.415,1.413ZM1,9.5H1ZM9.5,1V1Zm3.138,4.23-.77-.638-1.276,1.54.77.638ZM13,10v1h2V10Zm7.708,9.294L16.222,14.8l-1.415,1.413,4.486,4.494ZM17,9.5a7.5,7.5,0,0,1-2.2,5.3l1.414,1.414A9.5,9.5,0,0,0,19,9.5Zm-2.2,5.3A7.5,7.5,0,0,1,9.5,17v2a9.5,9.5,0,0,0,6.718-2.782ZM9.5,17a7.5,7.5,0,0,1-5.3-2.2L2.782,16.218A9.5,9.5,0,0,0,9.5,19ZM4.2,14.8A7.5,7.5,0,0,1,2,9.5H0a9.5,9.5,0,0,0,2.782,6.718ZM2,9.5A7.5,7.5,0,0,1,4.2,4.2L2.782,2.782A9.5,9.5,0,0,0,0,9.5ZM4.2,4.2A7.5,7.5,0,0,1,9.5,2V0A9.5,9.5,0,0,0,2.782,2.782ZM9.5,2a7.5,7.5,0,0,1,5.3,2.2l1.414-1.414A9.5,9.5,0,0,0,9.5,0Zm5.3,2.2A7.5,7.5,0,0,1,17,9.5h2a9.5,9.5,0,0,0-2.782-6.718ZM11.362,6.77A4.218,4.218,0,0,1,13,10h2a6.214,6.214,0,0,0-2.362-4.77Z" transform="translate(949 -273)" class="hover-fill" fill="#333"/>
                                                </g>
                                            </svg>
                                            <span><?php pll_e('Пошук'); ?></span>
                                        </button>
                                    </div>
                                    
                                    <button type="button" class="header__btn header-favorite" data-show-mini-cart="favorite" data-btn-url="<?php echo ( new Wishlist_Page())::get_url(); ?>" data-added-title="Додано до вподобань" data-added-btn="Усі вподобання">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                        <defs>
                                            <clipPath id="clip-path-6">
                                            <rect width="30" height="30" transform="translate(868 -278)" fill="#fff" opacity="0.997"/>
                                            </clipPath>
                                        </defs>
                                        <g id="Mask_Group_4" data-name="Mask Group 4" transform="translate(-868 278)" clip-path="url(#clip-path-6)">
                                            <path d="M12,1.5l2.47,7.6h7.992L16,13.8l2.47,7.6L12,16.7,5.534,21.4,8,13.8,1.538,9.1H9.53Z" transform="translate(871.462 -274)" fill="none" class="hover-stroke" stroke="#333" stroke-linejoin="round" stroke-width="2"/>
                                        </g>
                                        </svg>
                                    </button>
                                    <button type="button" class="header__btn header-wholesale <?php echo(empty( $wholesale_cart ) ? 'wholesale-empty' : '' ) ?>" data-show-mini-cart="wholesale" data-btn-url="<?php echo (new Wholesale_Cart_Page())::get_url(); ?>" data-added-title="Додано до замовлень" data-added-btn="Усі замовлення">
                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M22.5 6H8.5C7.11929 6 6 7.11929 6 8.5V24H20V8.5C20 7.11929 21.1193 6 22.5 6V6C23.8807 6 25 7.11929 25 8.5V12H20.5M9 10H16M9 14H13M9 18H15" class="hover-stroke" stroke="#333333" stroke-width="2" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="header__btn header-regular <?php echo(sizeof( $regular_cart ) == 0 ? 'cart-empty' : '' ) ?>" data-show-mini-cart data-btn-url="<?php echo wc_get_cart_url(); ?>" data-added-title="Додано до кошику" data-added-btn="Дивитися кошик">
                                        <svg class="cart-not-empty" width="31" height="30" viewBox="0 0 31 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 7H8L10.5 20H23.5M25 8H26L24 16H10.5M13 13V7M17 13V10M21 13V9" class="hover-stroke" stroke="#333333" stroke-width="2" stroke-linejoin="round"/>
                                            <circle cx="11" cy="24" r="1" class="hover-stroke" stroke="#333333" stroke-width="2"/>
                                            <circle cx="23" cy="24" r="1" class="hover-stroke" stroke="#333333" stroke-width="2"/>
                                        </svg>
                                        <svg class="cart-empty" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                            <defs>
                                                <clipPath id="clip-path-7">
                                                    <rect width="30" height="30" transform="translate(815 -278)" fill="#fff"/>
                                                </clipPath>
                                            </defs>
                                            <g transform="translate(-815 278)" clip-path="url(#clip-path-7)">
                                                <g id="Frame_326" data-name="Frame 326" transform="translate(820 -274)">
                                                    <path d="M0,2H3L5.5,15h13M4,3H20.5l-2,8H5.5" fill="none" stroke="#333" class="hover-stroke" stroke-linejoin="round" stroke-width="2"/>
                                                    <circle cx="1" cy="1" r="1" transform="translate(5 18)" fill="none" stroke="#333" class="hover-stroke" stroke-width="2"/>
                                                    <circle cx="1" cy="1" r="1" transform="translate(17 18)" fill="none" stroke="#333" class="hover-stroke" stroke-width="2"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="header-mobile mobile-show">
                        <button class="burger" type="button">
                            <img src="<?=TEMPLATE_PATH?>/img/burger-open.svg" alt="burger-open">
                            <img src="<?=TEMPLATE_PATH?>/img/burger-close.svg" alt="burger-open">
                        </button>
                        <?php if ( !empty( $logo ) ) : ?>
                            <a href="<?php echo home_url(); ?>" class="header-mobile__logo" title="Velena">
                                <img src="<?php echo $logo; ?>" alt="Velena" width="294" height="85">
                            </a>
                        <?php endif; ?>
                        <div class="header__btns">
                            <div class="header__btn header-mobile__search">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                    <defs>
                                        <clipPath id="clip-path-5">
                                        <rect width="30" height="30" transform="translate(944 -278)" fill="#fff"/>
                                        </clipPath>
                                    </defs>
                                    <g transform="translate(-944 278)" clip-path="url(#clip-path-5)">
                                        <path d="M16.222,14.8a1,1,0,1,0-1.415,1.413ZM1,9.5H1ZM9.5,1V1Zm3.138,4.23-.77-.638-1.276,1.54.77.638ZM13,10v1h2V10Zm7.708,9.294L16.222,14.8l-1.415,1.413,4.486,4.494ZM17,9.5a7.5,7.5,0,0,1-2.2,5.3l1.414,1.414A9.5,9.5,0,0,0,19,9.5Zm-2.2,5.3A7.5,7.5,0,0,1,9.5,17v2a9.5,9.5,0,0,0,6.718-2.782ZM9.5,17a7.5,7.5,0,0,1-5.3-2.2L2.782,16.218A9.5,9.5,0,0,0,9.5,19ZM4.2,14.8A7.5,7.5,0,0,1,2,9.5H0a9.5,9.5,0,0,0,2.782,6.718ZM2,9.5A7.5,7.5,0,0,1,4.2,4.2L2.782,2.782A9.5,9.5,0,0,0,0,9.5ZM4.2,4.2A7.5,7.5,0,0,1,9.5,2V0A9.5,9.5,0,0,0,2.782,2.782ZM9.5,2a7.5,7.5,0,0,1,5.3,2.2l1.414-1.414A9.5,9.5,0,0,0,9.5,0Zm5.3,2.2A7.5,7.5,0,0,1,17,9.5h2a9.5,9.5,0,0,0-2.782-6.718ZM11.362,6.77A4.218,4.218,0,0,1,13,10h2a6.214,6.214,0,0,0-2.362-4.77Z" transform="translate(949 -273)" class="hover-fill" fill="#333"/>
                                    </g>
                                </svg>
                            </div>
                            <button type="button" class="header__btn header-wholesale <?php echo(empty( $wholesale_cart ) ? 'wholesale-empty' : '' ) ?>" data-show-mini-cart="wholesale" data-btn-url="<?php echo (new Wholesale_Cart_Page())::get_url(); ?>" data-added-title="Додано до замовлень" data-added-btn="Усі замовлення">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.5 6H8.5C7.11929 6 6 7.11929 6 8.5V24H20V8.5C20 7.11929 21.1193 6 22.5 6V6C23.8807 6 25 7.11929 25 8.5V12H20.5M9 10H16M9 14H13M9 18H15" class="hover-stroke" stroke="#333333" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button type="button" class="header__btn header-regular <?php echo(sizeof( $regular_cart ) == 0 ? 'cart-empty' : '' ) ?>" data-show-mini-cart data-btn-url="<?php echo wc_get_cart_url(); ?>" data-added-title="Додано до кошику" data-added-btn="Дивитися кошик">
                                <svg class="cart-not-empty" width="31" height="30" viewBox="0 0 31 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 7H8L10.5 20H23.5M25 8H26L24 16H10.5M13 13V7M17 13V10M21 13V9" class="hover-stroke" stroke="#333333" stroke-width="2" stroke-linejoin="round"/>
                                    <circle cx="11" cy="24" r="1" class="hover-stroke" stroke="#333333" stroke-width="2"/>
                                    <circle cx="23" cy="24" r="1" class="hover-stroke" stroke="#333333" stroke-width="2"/>
                                </svg>
                                <svg class="cart-empty" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                    <defs>
                                        <clipPath id="clip-path-7">
                                            <rect width="30" height="30" transform="translate(815 -278)" fill="#fff"/>
                                        </clipPath>
                                    </defs>
                                    <g transform="translate(-815 278)" clip-path="url(#clip-path-7)">
                                        <g id="Frame_326" data-name="Frame 326" transform="translate(820 -274)">
                                            <path d="M0,2H3L5.5,15h13M4,3H20.5l-2,8H5.5" fill="none" stroke="#333" class="hover-stroke" stroke-linejoin="round" stroke-width="2"/>
                                            <circle cx="1" cy="1" r="1" transform="translate(5 18)" fill="none" stroke="#333" class="hover-stroke" stroke-width="2"/>
                                            <circle cx="1" cy="1" r="1" transform="translate(17 18)" fill="none" stroke="#333" class="hover-stroke" stroke-width="2"/>
                                        </g>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header__fixed">
                <div class="container <?php echo $container_size; ?>">
                    <div class="header__container mobile-hide">
                        <?php if ( !empty( $logo ) ) : ?>
                            <a href="<?php echo home_url(); ?>" class="header__logo" title="Velena">
                                <img src="<?php echo $logo; ?>" alt="Velena" width="294" height="85">
                            </a>
                        <?php endif; ?>
                        <div class="header__content">
                            <div class="header__row">
                                <?php if ( !empty( $menu ) ) : ?>
                                    <nav class="header__nav">
                                        <ul>
                                        <?php foreach ( $menu as $item ) : ?>
                                            <?php if ( strstr( $item['link']['url'], $_SERVER['REQUEST_URI'] ) and !is_front_page()) : ?>
                                                <li class="is-active">
                                            <?php else : ?>
                                                <li>
                                            <?php endif; ?>
                                                <a href="<?php echo $item['link']['url']; ?>"><?php echo $item['link']['title']; ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                                <div class="header__btns">
                                    <?php if ( is_user_logged_in() ) : ?>
                                        <div class="header__btn dropdown">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
                                                <defs>
                                                <clipPath id="clip-path-1">
                                                    <rect width="20" height="20" transform="translate(983 -296)" fill="#fff"/>
                                                </clipPath>
                                                </defs>
                                                <g transform="translate(-983 296)" clip-path="url(#clip-path-1)">
                                                <path d="M7.445,9.559l-.433-.9a1,1,0,0,0-.529,1.176Zm-3.891,0,.962.275a1,1,0,0,0-.529-1.176ZM2,15l-.962-.275A1,1,0,0,0,2,16Zm7,0v1a1,1,0,0,0,.962-1.275ZM7.878,10.46A5.5,5.5,0,0,0,11,5.5H9A3.5,3.5,0,0,1,7.013,8.657ZM11,5.5A5.5,5.5,0,0,0,5.5,0V2A3.5,3.5,0,0,1,9,5.5ZM5.5,0A5.5,5.5,0,0,0,0,5.5H2A3.5,3.5,0,0,1,5.5,2ZM0,5.5a5.5,5.5,0,0,0,3.122,4.96l.866-1.8A3.5,3.5,0,0,1,2,5.5Zm2.962,9.775L4.516,9.834,2.593,9.284,1.038,14.725ZM9,14H2v2H9ZM6.484,9.834l1.555,5.441,1.923-.549L8.407,9.284Z" transform="translate(987.5 -294)" class="hover-fill" fill="#4f4f4f"/>
                                                </g>
                                            </svg>
                                            <span><?php pll_e('Кабінет клієнта'); ?></span>
                                            <ul>
                                                <li><a href="<?php echo wc_get_account_endpoint_url( 'edit-account' ); ?>"><?php pll_e('Налаштування акаунту'); ?></a></li>
                                                <li><a href="<?php echo wc_get_account_endpoint_url( 'orders' ); ?>"><?php pll_e('Історія замовлень'); ?></a></li>
                                                <?php if( is_tax() ) : ?>
                                                    <?php $redirect_link = get_term_link(get_queried_object()); ?>
                                                <?php else : ?>
                                                    <?php $redirect_link = get_the_permalink(); ?>
                                                <?php endif; ?>
                                                <li class="separated"><a href="<?php echo wp_logout_url( $redirect_link ); ?>"><?php pll_e('Вихід'); ?></a></li>
                                            </ul>
                                        </div>
                                    <?php else : ?>
                                        <a href="#login-popup" class="header__btn" data-fancybox>
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
                                                <defs>
                                                <clipPath id="clip-path-1">
                                                    <rect width="20" height="20" transform="translate(983 -296)" fill="#fff"/>
                                                </clipPath>
                                                </defs>
                                                <g transform="translate(-983 296)" clip-path="url(#clip-path-1)">
                                                <path d="M7.445,9.559l-.433-.9a1,1,0,0,0-.529,1.176Zm-3.891,0,.962.275a1,1,0,0,0-.529-1.176ZM2,15l-.962-.275A1,1,0,0,0,2,16Zm7,0v1a1,1,0,0,0,.962-1.275ZM7.878,10.46A5.5,5.5,0,0,0,11,5.5H9A3.5,3.5,0,0,1,7.013,8.657ZM11,5.5A5.5,5.5,0,0,0,5.5,0V2A3.5,3.5,0,0,1,9,5.5ZM5.5,0A5.5,5.5,0,0,0,0,5.5H2A3.5,3.5,0,0,1,5.5,2ZM0,5.5a5.5,5.5,0,0,0,3.122,4.96l.866-1.8A3.5,3.5,0,0,1,2,5.5Zm2.962,9.775L4.516,9.834,2.593,9.284,1.038,14.725ZM9,14H2v2H9ZM6.484,9.834l1.555,5.441,1.923-.549L8.407,9.284Z" transform="translate(987.5 -294)" class="hover-fill" fill="#4f4f4f"/>
                                                </g>
                                            </svg>
                                            <span><?php pll_e('Вхід'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    <div class="header__btn dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
                                            <defs>
                                                <clipPath id="clip-path-2">
                                                <rect width="20" height="20" transform="translate(901 -276)" fill="#fff"/>
                                                </clipPath>
                                            </defs>
                                            <g transform="translate(-901 276)" clip-path="url(#clip-path-2)">
                                                <g transform="translate(902.589 -274.641)">
                                                <path d="M8.661,15.617a7.123,7.123,0,0,0,7.25-6.988,7.123,7.123,0,0,0-7.25-6.988,7.123,7.123,0,0,0-7.25,6.988A7.123,7.123,0,0,0,8.661,15.617Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="2"/>
                                                <path d="M10.016,6.5c.221-1.471,2.047-.845,4.02-1.614L15,6.5V9.058l-.5,2.526-1.308,2.238c1.4-3.076-.93-1.72-1.719-4.019C11.035,8.528,9.651,8.928,10.016,6.5Z" class="hover-fill" fill="#4f4f4f"/>
                                                <path d="M5.583,11.584c-.221-1.471-1.17-.457-3.083-1.031v.5l1,1.993,1.749,1.308L7,15.036C7.438,13.762,5.947,14.012,5.583,11.584Z" class="hover-fill" fill="#4f4f4f"/>
                                                <path d="M5.249,6.014c.716-1.279,2.763-2.073,1.063-3.433L4,4.076,2.5,6.5,2,8.435C2.438,7.16,4.125,8.021,5.249,6.014Z" class="hover-fill" fill="#4f4f4f"/>
                                                <path d="M10.016,6.5c.221-1.471,2.047-.845,4.02-1.614L15,6.5V9.058l-.5,2.526-1.308,2.238c1.4-3.076-.93-1.72-1.719-4.019C11.035,8.528,9.651,8.928,10.016,6.5Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="1"/>
                                                <path d="M5.583,11.584c-.221-1.471-1.17-.457-3.083-1.031v.5l1,1.993,1.749,1.308L7,15.036C7.438,13.762,5.947,14.012,5.583,11.584Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="1"/>
                                                <path d="M5.249,6.014c.716-1.279,2.763-2.073,1.063-3.433L4,4.076,2.5,6.5,2,8.435C2.438,7.16,4.125,8.021,5.249,6.014Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="1"/>
                                                </g>
                                            </g>
                                        </svg>
                                        <span><?php echo strtoupper(pll_current_language( 'slug' ));?></span>
                                        <?php polylang_language_switcher(); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="header__row">
                                <div class="header__btns header__btns--big">
                                    <button type="button" class="header__btn header-catalog-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                            <defs>
                                                <clipPath id="clip-path-3">
                                                <rect width="30" height="30" transform="translate(924 -182)" fill="#fff"/>
                                                </clipPath>
                                                <clipPath id="clip-path-4">
                                                <rect width="15" height="15" transform="translate(0 1)" fill="#fff"/>
                                                </clipPath>
                                            </defs>
                                            <g transform="translate(-924 182)" clip-path="url(#clip-path-3)">
                                                <g transform="translate(931.5 -175)" clip-path="url(#clip-path-4)">
                                                <rect width="4" height="4" transform="translate(1 2)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"/>
                                                <rect width="4" height="4" transform="translate(1 11)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"/>
                                                <rect width="4" height="4" transform="translate(10 2)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"/>
                                                <rect width="4" height="4" transform="translate(10 11)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"/>
                                                </g>
                                            </g>
                                        </svg>
                                        <span><?php pll_e('Каталог продукції'); ?></span>
                                    </button>
                                </div>
                                <div class="header__btns header__btns--big">
                                    <div class="header__search">
                                        <form class="header__search-form" action="<?php echo Search_Page::get_url(); ?>" method="GET">
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
                                            <input type="text" name="search" placeholder="<?php pll_e('Шукати'); ?>..." required>
                                            <button type="submit" class="btn btn--small"><?php pll_e('Шукати'); ?></button>
                    
                                            <div class="header__search-list"><ul></ul></div>
                                        </form>
                                        <button type="button" class="header__search-btn header__btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                                <defs>
                                                <clipPath id="clip-path-5">
                                                    <rect width="30" height="30" transform="translate(944 -278)" fill="#fff"/>
                                                </clipPath>
                                                </defs>
                                                <g transform="translate(-944 278)" clip-path="url(#clip-path-5)">
                                                <path d="M16.222,14.8a1,1,0,1,0-1.415,1.413ZM1,9.5H1ZM9.5,1V1Zm3.138,4.23-.77-.638-1.276,1.54.77.638ZM13,10v1h2V10Zm7.708,9.294L16.222,14.8l-1.415,1.413,4.486,4.494ZM17,9.5a7.5,7.5,0,0,1-2.2,5.3l1.414,1.414A9.5,9.5,0,0,0,19,9.5Zm-2.2,5.3A7.5,7.5,0,0,1,9.5,17v2a9.5,9.5,0,0,0,6.718-2.782ZM9.5,17a7.5,7.5,0,0,1-5.3-2.2L2.782,16.218A9.5,9.5,0,0,0,9.5,19ZM4.2,14.8A7.5,7.5,0,0,1,2,9.5H0a9.5,9.5,0,0,0,2.782,6.718ZM2,9.5A7.5,7.5,0,0,1,4.2,4.2L2.782,2.782A9.5,9.5,0,0,0,0,9.5ZM4.2,4.2A7.5,7.5,0,0,1,9.5,2V0A9.5,9.5,0,0,0,2.782,2.782ZM9.5,2a7.5,7.5,0,0,1,5.3,2.2l1.414-1.414A9.5,9.5,0,0,0,9.5,0Zm5.3,2.2A7.5,7.5,0,0,1,17,9.5h2a9.5,9.5,0,0,0-2.782-6.718ZM11.362,6.77A4.218,4.218,0,0,1,13,10h2a6.214,6.214,0,0,0-2.362-4.77Z" transform="translate(949 -273)" class="hover-fill" fill="#333"/>
                                                </g>
                                            </svg>
                                            <span><?php pll_e('Пошук'); ?></span>
                                        </button>
                                    </div>
        
                                    <button type="button" class="header__btn header-favorite" data-show-mini-cart="favorite" data-btn-url="<?php echo ( new Wishlist_Page())::get_url(); ?>" data-added-title="Додано до вподобань" data-added-btn="Усі вподобання">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                            <defs>
                                                <clipPath id="clip-path-6">
                                                    <rect width="30" height="30" transform="translate(868 -278)" fill="#fff" opacity="0.997"/>
                                                </clipPath>
                                            </defs>
                                            <g id="Mask_Group_4" data-name="Mask Group 4" transform="translate(-868 278)" clip-path="url(#clip-path-6)">
                                                <path d="M12,1.5l2.47,7.6h7.992L16,13.8l2.47,7.6L12,16.7,5.534,21.4,8,13.8,1.538,9.1H9.53Z" transform="translate(871.462 -274)" fill="none" class="hover-stroke" stroke="#333" stroke-linejoin="round" stroke-width="2"/>
                                            </g>
                                        </svg>
                                    </button>
                                    <button type="button" class="header__btn header-wholesale <?php echo(empty( $wholesale_cart ) ? 'wholesale-empty' : '' ) ?>" data-show-mini-cart="wholesale" data-btn-url="<?php echo (new Wholesale_Cart_Page())::get_url(); ?>" data-added-title="Додано до замовлень" data-added-btn="Усі замовлення">
                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M22.5 6H8.5C7.11929 6 6 7.11929 6 8.5V24H20V8.5C20 7.11929 21.1193 6 22.5 6V6C23.8807 6 25 7.11929 25 8.5V12H20.5M9 10H16M9 14H13M9 18H15" class="hover-stroke" stroke="#333333" stroke-width="2" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="header__btn header-regular <?php echo(sizeof( $regular_cart ) == 0 ? 'cart-empty' : '' ) ?>" data-show-mini-cart data-btn-url="<?php echo wc_get_cart_url(); ?>" data-added-title="Додано до кошику" data-added-btn="Дивитися кошик">
                                        <svg class="cart-not-empty" width="31" height="30" viewBox="0 0 31 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 7H8L10.5 20H23.5M25 8H26L24 16H10.5M13 13V7M17 13V10M21 13V9" class="hover-stroke" stroke="#333333" stroke-width="2" stroke-linejoin="round"/>
                                            <circle cx="11" cy="24" r="1" class="hover-stroke" stroke="#333333" stroke-width="2"/>
                                            <circle cx="23" cy="24" r="1" class="hover-stroke" stroke="#333333" stroke-width="2"/>
                                        </svg>
                                        <svg class="cart-empty" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                            <defs>
                                                <clipPath id="clip-path-7">
                                                    <rect width="30" height="30" transform="translate(815 -278)" fill="#fff"/>
                                                </clipPath>
                                            </defs>
                                            <g transform="translate(-815 278)" clip-path="url(#clip-path-7)">
                                                <g id="Frame_326" data-name="Frame 326" transform="translate(820 -274)">
                                                    <path d="M0,2H3L5.5,15h13M4,3H20.5l-2,8H5.5" fill="none" stroke="#333" class="hover-stroke" stroke-linejoin="round" stroke-width="2"/>
                                                    <circle cx="1" cy="1" r="1" transform="translate(5 18)" fill="none" stroke="#333" class="hover-stroke" stroke-width="2"/>
                                                    <circle cx="1" cy="1" r="1" transform="translate(17 18)" fill="none" stroke="#333" class="hover-stroke" stroke-width="2"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="header-mobile mobile-show">
                        <button class="burger" type="button">
                            <img src="<?=TEMPLATE_PATH?>/img/burger-open.svg" alt="burger-open">
                            <img src="<?=TEMPLATE_PATH?>/img/burger-close.svg" alt="burger-open">
                        </button>
                        <?php if ( !empty( $logo ) ) : ?>
                            <a href="<?php echo home_url(); ?>" class="header-mobile__logo" title="Velena">
                                <img src="<?php echo $logo; ?>" alt="Velena" width="294" height="85">
                            </a>
                        <?php endif; ?>
                        <div class="header__btns">
                            <div class="header__btn header-mobile__search">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                    <defs>
                                        <clipPath id="clip-path-5">
                                        <rect width="30" height="30" transform="translate(944 -278)" fill="#fff"/>
                                        </clipPath>
                                    </defs>
                                    <g transform="translate(-944 278)" clip-path="url(#clip-path-5)">
                                        <path d="M16.222,14.8a1,1,0,1,0-1.415,1.413ZM1,9.5H1ZM9.5,1V1Zm3.138,4.23-.77-.638-1.276,1.54.77.638ZM13,10v1h2V10Zm7.708,9.294L16.222,14.8l-1.415,1.413,4.486,4.494ZM17,9.5a7.5,7.5,0,0,1-2.2,5.3l1.414,1.414A9.5,9.5,0,0,0,19,9.5Zm-2.2,5.3A7.5,7.5,0,0,1,9.5,17v2a9.5,9.5,0,0,0,6.718-2.782ZM9.5,17a7.5,7.5,0,0,1-5.3-2.2L2.782,16.218A9.5,9.5,0,0,0,9.5,19ZM4.2,14.8A7.5,7.5,0,0,1,2,9.5H0a9.5,9.5,0,0,0,2.782,6.718ZM2,9.5A7.5,7.5,0,0,1,4.2,4.2L2.782,2.782A9.5,9.5,0,0,0,0,9.5ZM4.2,4.2A7.5,7.5,0,0,1,9.5,2V0A9.5,9.5,0,0,0,2.782,2.782ZM9.5,2a7.5,7.5,0,0,1,5.3,2.2l1.414-1.414A9.5,9.5,0,0,0,9.5,0Zm5.3,2.2A7.5,7.5,0,0,1,17,9.5h2a9.5,9.5,0,0,0-2.782-6.718ZM11.362,6.77A4.218,4.218,0,0,1,13,10h2a6.214,6.214,0,0,0-2.362-4.77Z" transform="translate(949 -273)" class="hover-fill" fill="#333"/>
                                    </g>
                                </svg>
                            </div>
                            <button type="button" class="header__btn header-wholesale <?php echo(empty( $wholesale_cart ) ? 'wholesale-empty' : '' ) ?>" data-show-mini-cart="wholesale" data-btn-url="<?php echo (new Wholesale_Cart_Page())::get_url(); ?>" data-added-title="Додано до замовлень" data-added-btn="Усі замовлення">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.5 6H8.5C7.11929 6 6 7.11929 6 8.5V24H20V8.5C20 7.11929 21.1193 6 22.5 6V6C23.8807 6 25 7.11929 25 8.5V12H20.5M9 10H16M9 14H13M9 18H15" class="hover-stroke" stroke="#333333" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button type="button" class="header__btn header-regular <?php echo(sizeof( $regular_cart ) == 0 ? 'cart-empty' : '' ) ?>" data-show-mini-cart data-btn-url="<?php echo wc_get_cart_url(); ?>" data-added-title="Додано до кошику" data-added-btn="Дивитися кошик">
                                <svg class="cart-not-empty" width="31" height="30" viewBox="0 0 31 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 7H8L10.5 20H23.5M25 8H26L24 16H10.5M13 13V7M17 13V10M21 13V9" class="hover-stroke" stroke="#333333" stroke-width="2" stroke-linejoin="round"/>
                                    <circle cx="11" cy="24" r="1" class="hover-stroke" stroke="#333333" stroke-width="2"/>
                                    <circle cx="23" cy="24" r="1" class="hover-stroke" stroke="#333333" stroke-width="2"/>
                                </svg>
                                <svg class="cart-empty" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                    <defs>
                                        <clipPath id="clip-path-7">
                                            <rect width="30" height="30" transform="translate(815 -278)" fill="#fff"/>
                                        </clipPath>
                                    </defs>
                                    <g transform="translate(-815 278)" clip-path="url(#clip-path-7)">
                                        <g id="Frame_326" data-name="Frame 326" transform="translate(820 -274)">
                                            <path d="M0,2H3L5.5,15h13M4,3H20.5l-2,8H5.5" fill="none" stroke="#333" class="hover-stroke" stroke-linejoin="round" stroke-width="2"/>
                                            <circle cx="1" cy="1" r="1" transform="translate(5 18)" fill="none" stroke="#333" class="hover-stroke" stroke-width="2"/>
                                            <circle cx="1" cy="1" r="1" transform="translate(17 18)" fill="none" stroke="#333" class="hover-stroke" stroke-width="2"/>
                                        </g>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header__menu">
                <div class="container">
                    <div class="header__menu-inner">
                        <div class="header__menu-row">
                            <?php $count = 1;?>
                            <?php foreach ( $type as $item ) : ?>
                                <?php
                                    $child_terms = get_terms( array(
                                        'taxonomy'   => 'type',
                                        'hide_empty' => false,
                                        'parent' => $item->term_id
                                    ) ); ?>
                                <?php if ( !empty( $child_terms ) ) : ?>
                                    <div class="header__menu-item" data-sub="<?php echo $count; ?>">
                                        <?php if ( !empty( get_field('main_settings','term_'.$item->term_id)['header_image'] ) ) : ?>
                                            <div class="header__menu-item__img">
                                                <img src="<?php echo get_field('main_settings','term_'.$item->term_id)['header_image']; ?>" alt="<?php echo $item->name; ?>">
                                            </div>
                                        <?php endif; ?>
                                        <div class="header__menu-item__title">
                                            <span><?php echo $item->name; ?></span>
                                        </div>
                                    </div>
                                    <?php $count++; ?>
                                <?php else : ?>
                                    <a class="header__menu-item" href="<?php echo get_category_link( $item->term_id ); ?>">
                                        <?php if ( !empty( get_field('main_settings','term_'.$item->term_id)['header_image'] ) ) : ?>
                                            <div class="header__menu-item__img">
                                                <img src="<?php echo get_field('main_settings','term_'.$item->term_id)['header_image']; ?>" alt="<?php echo $item->name; ?>">
                                            </div>
                                        <?php endif; ?>
                                        <div class="header__menu-item__title">
                                            <span><?php echo $item->name; ?></span>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
    
                        <div class="header__menu-subs">
                            <?php $count = 1; ?>
                            <?php foreach ( $type as $item ) : ?>
                                <?php
                                $child_terms = get_terms( array(
                                    'taxonomy'   => 'type',
                                    'hide_empty' => false,
                                    'parent' => $item->term_id
                                ) );
                                ?>
                                <?php if ( !empty( $child_terms ) ) : ?>
                                    <div class="header__menu-sub" data-sub="<?php echo $count; ?>">
                                    <?php foreach ( $child_terms as $sub_item ) : ?>
                                        <a class="header__menu-item" href="<?php echo get_category_link( $sub_item->term_id ); ?>">
                                            <?php if ( !empty( get_field('main_settings','term_'.$sub_item->term_id)['header_image'] ) ) : ?>
                                                <div class="header__menu-item__img">
                                                    <img src="<?php echo get_field('main_settings','term_'.$sub_item->term_id)['header_image']; ?>" alt="<?php echo get_term( $sub_item->term_id )->name; ?>">
                                                </div>
                                            <?php endif; ?>
                                            <div class="header__menu-item__title">
                                                <span><?php echo get_term( $sub_item->term_id )->name; ?></span>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                    </div>
                                <?php $count++; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
    
                        <div class="header__menu-use">
                            <div class="header__menu-content">
                                <?php if ( !empty( $destination_title ) ) : ?>
                                    <div class="title-h5">
                                        <span><?php echo $destination_title; ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ( !empty( $destination_text ) ) : ?>
                                    <div class="header__menu-text">
                                        <p><?php echo $destination_text; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ( !empty( $appointment ) ) : ?>
                                <ul class="header__menu-list">
                                    <?php foreach ( $appointment as $item ) : ?>
                                        <li><a href="<?php echo get_category_link( $item->term_id ); ?>"><?php echo $item->name; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="menu mobile-show">
            <div class="menu__inner">
                <div class="menu__btns">
                    <button type="button" class="header__btn header-catalog-btn-mob">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                            <defs>
                                <clipPath id="clip-path-3">
                                <rect width="30" height="30" transform="translate(924 -182)" fill="#fff"></rect>
                                </clipPath>
                                <clipPath id="clip-path-4">
                                <rect width="15" height="15" transform="translate(0 1)" fill="#fff"></rect>
                                </clipPath>
                            </defs>
                            <g transform="translate(-924 182)" clip-path="url(#clip-path-3)">
                                <g transform="translate(931.5 -175)" clip-path="url(#clip-path-4)">
                                <rect width="4" height="4" transform="translate(1 2)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"></rect>
                                <rect width="4" height="4" transform="translate(1 11)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"></rect>
                                <rect width="4" height="4" transform="translate(10 2)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"></rect>
                                <rect width="4" height="4" transform="translate(10 11)" fill="none" class="hover-stroke" stroke="#333" stroke-width="2"></rect>
                                </g>
                            </g>
                        </svg>
                        <span><?php pll_e('Каталог продукції'); ?></span>
                    </button>
                    <div class="header__menu-inner">
                        <?php
                        $result = splitArrayIntoPairs($type);
                        $count_row = 1;
                        $count_sub = 1;

                        ?>
                        <?php foreach ( $result as $array ) : ?>
                            <div class="header__menu-row">
                                <?php foreach ( $array as $item ) : ?>
                                    <?php
                                    $child_terms = get_terms( array(
                                        'taxonomy'   => 'type',
                                        'hide_empty' => false,
                                        'parent' => $item->term_id
                                    ) ); ?>
                                    <?php if ( !empty( $child_terms ) ) : ?>
                                        <div class="header__menu-item" data-sub="<?php echo $count_row; ?>">
                                            <?php if ( !empty( get_field('main_settings','term_'.$item->term_id)['header_image'] ) ) : ?>
                                                <div class="header__menu-item__img">
                                                    <img src="<?php echo get_field('main_settings','term_'.$item->term_id)['header_image']; ?>" alt="<?php echo $item->name; ?>">
                                                </div>
                                            <?php endif; ?>
                                            <div class="header__menu-item__title">
                                                <span><?php echo $item->name; ?></span>
                                            </div>
                                        </div>
                                        <?php $count_row++; ?>
                                    <?php else : ?>
                                        <a class="header__menu-item" href="<?php echo get_category_link( $item->term_id ); ?>">
                                            <?php if ( !empty( get_field('main_settings','term_'.$item->term_id)['header_image'] ) ) : ?>
                                                <div class="header__menu-item__img">
                                                    <img src="<?php echo get_field('main_settings','term_'.$item->term_id)['header_image']; ?>" alt="<?php echo $item->name; ?>">
                                                </div>
                                            <?php endif; ?>
                                            <div class="header__menu-item__title">
                                                <span><?php echo $item->name; ?></span>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="header__menu-subs">
                                <?php foreach ( $array as $item ) : ?>
                                    <?php
                                    $child_terms = get_terms( array(
                                        'taxonomy'   => 'type',
                                        'hide_empty' => false,
                                        'parent' => $item->term_id
                                    ) ); ?>
                                    <?php if ( !empty( $child_terms ) ) : ?>
                                        <div class="header__menu-sub" data-sub="<?php echo $count_sub; ?>">
                                        <?php foreach ( $child_terms as $sub_item ) : ?>
                                            <a class="header__menu-item" href="<?php echo get_category_link( $sub_item->term_id ); ?>">
                                                <?php if ( !empty( get_field('main_settings','term_'.$sub_item->term_id)['header_image'] ) ) : ?>
                                                    <div class="header__menu-item__img">
                                                        <img src="<?php echo get_field('main_settings','term_'.$sub_item->term_id)['header_image']; ?>" alt="<?php echo get_term( $sub_item->term_id )->name; ?>">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="header__menu-item__title">
                                                    <span><?php echo get_term( $sub_item->term_id )->name; ?></span>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                        </div>
                                    <?php $count_sub++; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
        
                        <div class="header__menu-use">
                            <div class="header__menu-content">
                                <?php if ( !empty( $destination_title ) ) : ?>
                                    <div class="title-h5">
                                        <span><?php echo $destination_title; ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ( !empty( $destination_text ) ) : ?>
                                    <div class="header__menu-text">
                                        <p><?php echo $destination_text; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ( !empty( $appointment ) ) : ?>
                                <ul class="header__menu-list">
                                    <?php foreach ( $appointment as $item ) : ?>
                                        <li><a href="<?php echo get_category_link( $item->term_id ); ?>"><?php echo $item->name; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="section-arrow">
                                    <span class="open-text"><?php pll_e('Обрати призначення'); ?></span>
                                    <span class="close-text"><?php pll_e('Згорнути'); ?></span>
                                    <img src="<?=TEMPLATE_PATH?>/img/ico-more-arrow.svg" alt="^">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <nav class="menu__nav header__nav">
                    <ul>
                        <li>
                            <?php if ( is_user_logged_in() ) : ?>
                                <!-- Розмітка для залогіненого юзера -->
                            <?php else : ?>
                                <a href="#login-popup" class="header__btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
                                    <defs>
                                        <clipPath id="clip-path-1">
                                        <rect width="20" height="20" transform="translate(983 -296)" fill="#fff"></rect>
                                        </clipPath>
                                    </defs>
                                    <g transform="translate(-983 296)" clip-path="url(#clip-path-1)">
                                        <path d="M7.445,9.559l-.433-.9a1,1,0,0,0-.529,1.176Zm-3.891,0,.962.275a1,1,0,0,0-.529-1.176ZM2,15l-.962-.275A1,1,0,0,0,2,16Zm7,0v1a1,1,0,0,0,.962-1.275ZM7.878,10.46A5.5,5.5,0,0,0,11,5.5H9A3.5,3.5,0,0,1,7.013,8.657ZM11,5.5A5.5,5.5,0,0,0,5.5,0V2A3.5,3.5,0,0,1,9,5.5ZM5.5,0A5.5,5.5,0,0,0,0,5.5H2A3.5,3.5,0,0,1,5.5,2ZM0,5.5a5.5,5.5,0,0,0,3.122,4.96l.866-1.8A3.5,3.5,0,0,1,2,5.5Zm2.962,9.775L4.516,9.834,2.593,9.284,1.038,14.725ZM9,14H2v2H9ZM6.484,9.834l1.555,5.441,1.923-.549L8.407,9.284Z" transform="translate(987.5 -294)" class="hover-fill" fill="#4f4f4f"></path>
                                    </g>
                                    </svg>
                                    <span><?php pll_e('Вхід'); ?></span>
                                </a>
                            <?php endif; ?>
                        </li>
                        <li>
                            <a href="<?php echo ( new Wishlist_Page())::get_url(); ?>" class="header__btn">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 30 30">
                                <defs>
                                    <clipPath id="clip-path-6">
                                    <rect width="30" height="30" transform="translate(868 -278)" fill="#fff" opacity="0.997"></rect>
                                    </clipPath>
                                </defs>
                                <g id="Mask_Group_4" data-name="Mask Group 4" transform="translate(-868 278)" clip-path="url(#clip-path-6)">
                                    <path d="M12,1.5l2.47,7.6h7.992L16,13.8l2.47,7.6L12,16.7,5.534,21.4,8,13.8,1.538,9.1H9.53Z" transform="translate(871.462 -274)" fill="none" class="hover-stroke" stroke="#333" stroke-linejoin="round" stroke-width="2"></path>
                                </g>
                                </svg>
                                <span>Обрані товари</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <nav class="menu__nav header__nav">
                    <ul>
                        <li>
                            <a href=""><span>Про компанію</span></a>
                        </li>
                        <li>
                            <a href=""><span>Публікації</span></a>
                        </li>
                        <li>
                            <a href=""><span>Оплата і доставка</span></a>
                        </li>
                        <li>
                            <a href=""><span>Контакти</span></a>
                        </li>
                    </ul>
                </nav>
                <nav class="menu__nav menu__nav--small header__nav">
                    <ul>
                        <li>
                            <a href="" class="header__btn">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
                                    <defs>
                                        <clipPath id="clip-path-2">
                                        <rect width="20" height="20" transform="translate(901 -276)" fill="#fff"></rect>
                                        </clipPath>
                                    </defs>
                                    <g transform="translate(-901 276)" clip-path="url(#clip-path-2)">
                                        <g transform="translate(902.589 -274.641)">
                                        <path d="M8.661,15.617a7.123,7.123,0,0,0,7.25-6.988,7.123,7.123,0,0,0-7.25-6.988,7.123,7.123,0,0,0-7.25,6.988A7.123,7.123,0,0,0,8.661,15.617Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="2"></path>
                                        <path d="M10.016,6.5c.221-1.471,2.047-.845,4.02-1.614L15,6.5V9.058l-.5,2.526-1.308,2.238c1.4-3.076-.93-1.72-1.719-4.019C11.035,8.528,9.651,8.928,10.016,6.5Z" class="hover-fill" fill="#4f4f4f"></path>
                                        <path d="M5.583,11.584c-.221-1.471-1.17-.457-3.083-1.031v.5l1,1.993,1.749,1.308L7,15.036C7.438,13.762,5.947,14.012,5.583,11.584Z" class="hover-fill" fill="#4f4f4f"></path>
                                        <path d="M5.249,6.014c.716-1.279,2.763-2.073,1.063-3.433L4,4.076,2.5,6.5,2,8.435C2.438,7.16,4.125,8.021,5.249,6.014Z" class="hover-fill" fill="#4f4f4f"></path>
                                        <path d="M10.016,6.5c.221-1.471,2.047-.845,4.02-1.614L15,6.5V9.058l-.5,2.526-1.308,2.238c1.4-3.076-.93-1.72-1.719-4.019C11.035,8.528,9.651,8.928,10.016,6.5Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="1"></path>
                                        <path d="M5.583,11.584c-.221-1.471-1.17-.457-3.083-1.031v.5l1,1.993,1.749,1.308L7,15.036C7.438,13.762,5.947,14.012,5.583,11.584Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="1"></path>
                                        <path d="M5.249,6.014c.716-1.279,2.763-2.073,1.063-3.433L4,4.076,2.5,6.5,2,8.435C2.438,7.16,4.125,8.021,5.249,6.014Z" fill="none" class="hover-stroke" stroke="#4f4f4f" stroke-width="1"></path>
                                        </g>
                                    </g>
                                </svg>
                                <span>Мова сайту</span>
                            </a>
                        </li>
                        <?php polylang_language_switcher_menu(); ?>
                    </ul>
                </nav>
            </div>
        </div>

        <?php
    }

    function form($instance) {
        /** do nothing */
    }

    private function get_menu($args) {
    }

    public static function acf_add_local_field_group() {

        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_64f78b2dd6130',
                'title' => 'Header',
                'fields' => array(
                    array(
                        'key' => 'field_64f78eafa044d',
                        'label' => 'Logotype',
                        'name' => 'logotype',
                        'aria-label' => '',
                        'type' => 'image',
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
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                        'preview_size' => 'medium',
                    ),
                    array(
                        'key' => 'field_64f78b2e5d4be',
                        'label' => 'Menu',
                        'name' => 'menu',
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
                                'key' => 'field_64f78b485d4bf',
                                'label' => 'Link',
                                'name' => 'link',
                                'aria-label' => '',
                                'type' => 'link',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => 'array',
                                'translations' => 'copy_once',
                                'parent_repeater' => 'field_64f78b2e5d4be',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_64f8e3005f50d',
                        'label' => 'Destination title',
                        'name' => 'destination_title',
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
                        'new_lines' => 'br',
                        'translations' => 'translate',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                    array(
                        'key' => 'field_64f8e3205f50e',
                        'label' => 'Destination text',
                        'name' => 'destination_text',
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
                        'new_lines' => 'br',
                        'maxlength' => '',
                        'placeholder' => '',
                        'rows' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'widget',
                            'operator' => '==',
                            'value' => 'header-widget',
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