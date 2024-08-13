<?php 

class Footer_Widget extends WP_Widget {

    public static function init() {
        add_action('widgets_init', [__CLASS__, 'widgets_init']);
        add_action('acf/init', [__CLASS__, 'acf_add_local_field_group']);
        add_action('init', [__CLASS__, 'polylang_translate']);
    }

    public static function polylang_translate() {
        
    }

    public static function widgets_init() {
        register_sidebar( [
            'name'          => 'Footer',
            'id'            => 'footer',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
            ] );
        register_widget(__CLASS__);
    }

    function __construct() {
        parent::__construct('footer-widget', 'Section footer', []);
    }

    function widget($args, $instance) { 
        $logo = get_field('logo', 'widget_' . $args['widget_id']);
        $first_menu = get_field('first_menu', 'widget_' . $args['widget_id']);
        $second_menu = get_field('second_menu', 'widget_' . $args['widget_id']);
        $second_menu_title = get_field('second_menu_title', 'widget_' . $args['widget_id']);
        $main_office_title = get_field('main_office_title', 'widget_' . $args['widget_id']);
        $main_office_address = get_field('main_office_address', 'widget_' . $args['widget_id']);
        $mail = get_field('mail', 'widget_' . $args['widget_id']);
        $group_composition_title = get_field('group_composition_title', 'widget_' . $args['widget_id']);
        $group_composition_address = get_field('group_composition_address', 'widget_' . $args['widget_id']);
        $retail_warehouse_title = get_field('retail_warehouse_title', 'widget_' . $args['widget_id']);
        $retail_warehouse_address = get_field('retail_warehouse_address', 'widget_' . $args['widget_id']);
        $facebook_link = get_field('facebook_link', 'widget_' . $args['widget_id']);
        $instagram_link = get_field('instagram_link', 'widget_' . $args['widget_id']);
        $twitter_link = get_field('twitter_link', 'widget_' . $args['widget_id']);
        $telegram_link = get_field('telegram_link', 'widget_' . $args['widget_id']);
        $whatsapp_link = get_field('whatsapp_link', 'widget_' . $args['widget_id']);
        $payment_methods = get_field('payment_methods', 'widget_' . $args['widget_id']);
        $phone_numbers = get_field('phone_numbers', 'widget_' . $args['widget_id']);
        $copyright = get_field('copyright', 'widget_' . $args['widget_id']);
        $site_made_logo = get_field('site_made_logo', 'widget_' . $args['widget_id']);
        $site_made_text = get_field('site_made_text', 'widget_' . $args['widget_id']);
        $site_made_link = get_field('site_made_link', 'widget_' . $args['widget_id']);
    
        ?>
   
            <footer class="footer" >
                <div class="container">
                    <?php if( !empty( $logo ) ) : ?>
                        <div class="footer__logo">
                            <a href="<?php echo home_url(); ?>">
                                <img src="<?php echo $logo; ?>" width="204" height="63" alt="Logo">
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="footer__container">
                        <div class="footer__block">
                            <div class="footer__row">
                                <?php if( !empty( $first_menu ) ) : ?>
                                    <div class="footer__info">
                                        <?php foreach( $first_menu as $item ) : ?>
                                            <div class="footer__title title-h6">
                                                <a href="<?php echo $item['link']['url']; ?>"><?php echo $item['link']['title']; ?></a>
                                            </div>
                                        <?php endforeach; ?>                                    
                                    </div>
                                <?php endif; ?>
                            <div class="footer__info">
                                <?php if( !empty( $second_menu_title ) ) : ?>
                                    <div class="footer__title title-h6">
                                        <a href="<?php echo $second_menu_title['url']; ?>"><?php echo $second_menu_title['title']; ?></a>
                                    </div>
                                <?php endif; ?>
                                <?php if( !empty( $second_menu ) ) : ?>
                                    <ul class="footer__list">
                                        <?php foreach( $second_menu as $item ) : ?>
                                            <li>
                                                <a href="<?php echo $item['link']['url']; ?>"><?php echo $item['link']['title']; ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            </div>
                        </div>
                        <div class="footer__block footer__nav--mobile">
                            <div class="footer__row">
                                <div class="footer__info">
                                    <?php if( !empty( $main_office_title ) ) : ?>
                                        <div class="footer__title title-h6">
                                            <h3><?php echo $main_office_title?></h3>
                                        </div>
                                    <?php endif; ?>
                                    <ul class="footer__list">
                                        <?php if( !empty( $main_office_address ) ) : ?>
                                            <li>
                                                <address><?php echo $main_office_address; ?></address>
                                            </li>
                                        <?php endif; ?>
                                        <?php if( !empty( $mail ) ) : ?>
                                            <li>
                                                <a href="mailto:<?php echo $mail; ?>"><?php echo $mail; ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="footer__info">
                                    <?php if( !empty( $group_composition_title ) ) : ?>
                                        <div class="footer__title title-h6">
                                            <h3><?php echo $group_composition_title?></h3>
                                        </div>
                                    <?php endif; ?>
                                    <ul class="footer__list">
                                        <?php if( !empty( $group_composition_address ) ) : ?>
                                            <li>
                                                <address><?php echo $group_composition_address; ?></address>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="footer__info">
                                    <?php if( !empty( $retail_warehouse_title ) ) : ?>
                                        <div class="footer__title title-h6">
                                            <h3><?php echo $retail_warehouse_title?></h3>
                                        </div>
                                    <?php endif; ?>
                                    <ul class="footer__list">
                                        <?php if( !empty( $retail_warehouse_address ) ) : ?>
                                            <li>
                                                <address><?php echo $retail_warehouse_address; ?></address>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="footer__row">
                                <div class="footer__contact">
                                    <?php foreach( $phone_numbers as $item ) : ?>
                                        <div class="footer__phone">
                                            <a href="tel:<?= preg_replace('/[^\d]/', '', $item['number']); ?>"><?php echo $item['number']; ?></a>
                                            <?php if( !empty( $item['text_under_number'] ) ) : ?>
                                                <span><?php echo $item['text_under_number']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <ul class="footer__socials socials">
                                <?php if( !empty( $facebook_link ) ) : ?>
                                    <li>
                                        <a href="<?php echo $facebook_link; ?>">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4 16C4 21.9333 8.33333 26.8667 14 27.8667L14.0671 27.813C14.0447 27.8087 14.0224 27.8043 14 27.7998V19.3332H11V15.9998H14V13.3332C14 10.3332 15.9333 8.6665 18.6667 8.6665C19.5333 8.6665 20.4667 8.79984 21.3333 8.93317V11.9998H19.8C18.3333 11.9998 18 12.7332 18 13.6665V15.9998H21.2L20.6667 19.3332H18V27.7998C17.9776 27.8043 17.9553 27.8087 17.9329 27.813L18 27.8667C23.6667 26.8667 28 21.9333 28 16C28 9.4 22.6 4 16 4C9.4 4 4 9.4 4 16Z" fill="#BDBDBD"/>
                                            </svg>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if( !empty( $instagram_link ) ) : ?>
                                    <li>
                                        <a href="<?php echo $instagram_link; ?>">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.0005 4.36377C12.8404 4.36377 12.4438 4.37759 11.2026 4.43407C9.96384 4.4908 9.11829 4.68692 8.37843 4.97468C7.61312 5.27189 6.96393 5.66947 6.31716 6.3165C5.66991 6.96328 5.27235 7.6125 4.97417 8.37759C4.6857 9.11771 4.48934 9.96353 4.43358 11.2018C4.37807 12.443 4.36353 12.8399 4.36353 16.0001C4.36353 19.1604 4.37759 19.5558 4.43383 20.797C4.49079 22.0358 4.68691 22.8813 4.97442 23.6212C5.27186 24.3866 5.66943 25.0358 6.31644 25.6826C6.96296 26.3298 7.61215 26.7284 8.37698 27.0256C9.11732 27.3133 9.96311 27.5095 11.2016 27.5662C12.4428 27.6227 12.8391 27.6365 15.9991 27.6365C19.1594 27.6365 19.5548 27.6227 20.796 27.5662C22.0347 27.5095 22.8813 27.3133 23.6216 27.0256C24.3867 26.7284 25.0349 26.3298 25.6814 25.6826C26.3287 25.0358 26.7262 24.3866 27.0244 23.6215C27.3105 22.8813 27.5068 22.0355 27.565 20.7972C27.6208 19.556 27.6353 19.1604 27.6353 16.0001C27.6353 12.8399 27.6208 12.4433 27.565 11.2021C27.5068 9.96328 27.3105 9.11771 27.0244 8.37783C26.7262 7.6125 26.3287 6.96328 25.6814 6.3165C25.0342 5.66922 24.3869 5.27165 23.6209 4.97468C22.8791 4.68692 22.0331 4.4908 20.7943 4.43407C19.5531 4.37759 19.158 4.36377 15.9969 4.36377H16.0005ZM14.9567 6.46074C15.2665 6.46025 15.6122 6.46074 16.0005 6.46074C19.1073 6.46074 19.4756 6.47189 20.7024 6.52765C21.8369 6.57953 22.4527 6.7691 22.8628 6.92838C23.4059 7.13928 23.793 7.39141 24.2 7.79868C24.6073 8.20595 24.8594 8.59383 25.0708 9.13686C25.23 9.54656 25.4199 10.1623 25.4715 11.2969C25.5272 12.5235 25.5394 12.892 25.5394 15.9975C25.5394 19.1029 25.5272 19.4714 25.4715 20.6981C25.4196 21.8326 25.23 22.4484 25.0708 22.8581C24.8599 23.4011 24.6073 23.7878 24.2 24.1948C23.7928 24.6021 23.4061 24.8542 22.8628 25.0651C22.4532 25.2251 21.8369 25.4142 20.7024 25.4661C19.4758 25.5218 19.1073 25.5339 16.0005 25.5339C12.8934 25.5339 12.5252 25.5218 11.2986 25.4661C10.1641 25.4137 9.54834 25.2241 9.13793 25.0649C8.59491 24.8539 8.20705 24.6018 7.79978 24.1946C7.39252 23.7873 7.14041 23.4004 6.92902 22.8571C6.76976 22.4474 6.57994 21.8316 6.52831 20.6971C6.47255 19.4704 6.4614 19.1019 6.4614 15.9946C6.4614 12.8872 6.47255 12.5206 6.52831 11.294C6.58019 10.1594 6.76976 9.54365 6.92902 9.13347C7.13993 8.59044 7.39252 8.20256 7.79978 7.79528C8.20705 7.38801 8.59491 7.13589 9.13793 6.9245C9.5481 6.7645 10.1641 6.57541 11.2986 6.52328C12.372 6.4748 12.788 6.46025 14.9567 6.45783V6.46074ZM22.2117 8.39286C21.4408 8.39286 20.8154 9.01759 20.8154 9.78874C20.8154 10.5596 21.4408 11.1851 22.2117 11.1851C22.9826 11.1851 23.608 10.5596 23.608 9.78874C23.608 9.01783 22.9826 8.39237 22.2117 8.39237V8.39286ZM16.0005 10.0244C12.7005 10.0244 10.0249 12.7 10.0249 16.0001C10.0249 19.3003 12.7005 21.9747 16.0005 21.9747C19.3005 21.9747 21.9751 19.3003 21.9751 16.0001C21.9751 12.7 19.3003 10.0244 16.0003 10.0244H16.0005ZM16.0005 12.1213C18.1425 12.1213 19.8792 13.8578 19.8792 16.0001C19.8792 18.1422 18.1425 19.8789 16.0005 19.8789C13.8583 19.8789 12.1218 18.1422 12.1218 16.0001C12.1218 13.8578 13.8583 12.1213 16.0005 12.1213Z" fill="#BDBDBD"/>
                                            </svg>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if( !empty( $twitter_link ) ) : ?>
                                    <li>
                                        <a href="<?php echo $twitter_link; ?>">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M26.3622 10.7852C26.3713 11.0183 26.3743 11.2514 26.3743 11.4846C26.3743 18.5542 21.4012 26.7157 12.3071 26.7157C9.51351 26.7157 6.91591 25.8253 4.72729 24.3096C5.11417 24.352 5.50708 24.3839 5.90602 24.3839C8.22225 24.3839 10.3556 23.5252 12.0478 22.0837C9.88432 22.0519 8.05746 20.4939 7.4274 18.374C7.72987 18.4376 8.04136 18.4695 8.35991 18.4695C8.80909 18.4695 9.2452 18.406 9.66323 18.2788C7.39924 17.7912 5.69399 15.6291 5.69399 13.0323C5.69399 13.0005 5.69399 12.9898 5.69399 12.9686C6.36123 13.3608 7.12492 13.6045 7.93585 13.6363C6.60741 12.6717 5.73416 11.0289 5.73416 9.17408C5.73416 8.19897 5.97734 7.2768 6.40542 6.48187C8.84324 9.7252 12.488 11.8556 16.5969 12.0782C16.5125 11.6861 16.4693 11.2728 16.4693 10.8594C16.4693 7.90229 18.683 5.50684 21.4142 5.50684C22.8361 5.50684 24.1204 6.15347 25.0217 7.19219C26.1502 6.95901 27.2073 6.51387 28.164 5.89913C27.7932 7.14982 27.0104 8.19892 25.9874 8.85606C26.9883 8.72887 27.9429 8.44291 28.8282 8.01895C28.164 9.08946 27.3279 10.0326 26.3622 10.7852Z" fill="#BDBDBD"/>
                                            </svg>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if( !empty( $telegram_link ) ) : ?>
                                    <li>
                                        <a href="<?php echo $telegram_link; ?>">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M24.4339 7.4839L5.16841 14.9146C3.8535 15.4422 3.8613 16.1756 4.92874 16.5026L9.73361 18.0023L11.5721 23.6389C11.7956 24.2558 11.6854 24.5005 12.3332 24.5005C12.8332 24.5005 13.0549 24.2725 13.3334 24.0005C13.5105 23.8273 14.5618 22.8051 15.7357 21.6638L20.7337 25.3565C21.6533 25.8639 22.3174 25.601 22.5464 24.5023L25.8272 9.04205C26.1631 7.69538 25.3138 7.08454 24.4339 7.4839ZM10.4877 17.6578L21.3179 10.8249C21.8585 10.497 22.3543 10.6733 21.9472 11.0346L12.6738 19.4016L12.3127 23.2528L10.4877 17.6578Z" fill="#BDBDBD"/>
                                            </svg>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if( !empty( $whatsapp_link ) ) : ?>
                                    <li>
                                        <a href="<?php echo $whatsapp_link; ?>">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.0949 3.94445C20.0666 4.09051 22.8358 5.31597 24.9508 7.43239C27.2068 9.6898 28.4485 12.6905 28.4473 15.8816C28.4446 22.4668 23.0837 27.8248 16.4982 27.8248C14.0217 27.8248 12.0537 27.0639 10.783 26.3711L4.44849 28.0319L6.14374 21.843C5.09805 20.0317 4.54782 17.9772 4.54868 15.8722C4.55132 9.28716 9.91174 3.92969 16.498 3.92969L17.0949 3.94445ZM11.0764 24.209L11.4391 24.4241C12.964 25.3285 14.7119 25.8071 16.4941 25.8078H16.4981C21.9722 25.8078 26.4276 21.3545 26.4298 15.881C26.4308 13.2285 25.3987 10.7345 23.5236 8.85814C21.6484 6.98171 19.1548 5.94775 16.5021 5.94688C11.0238 5.94688 6.56837 10.3997 6.56619 15.8728C6.56544 17.7486 7.0905 19.5753 8.08468 21.1557L8.32082 21.5314L7.3175 25.1945L11.0764 24.209ZM22.518 18.7217C22.4434 18.5971 22.2444 18.5225 21.9458 18.3731C21.6473 18.2237 20.1794 17.5018 19.9057 17.4021C19.632 17.3027 19.4329 17.2529 19.2339 17.5516C19.0349 17.8503 18.4627 18.5225 18.2885 18.7217C18.1144 18.9208 17.9402 18.9458 17.6417 18.7964C17.3431 18.6471 16.3811 18.3319 15.2407 17.3152C14.3531 16.5239 13.7538 15.5467 13.5797 15.2479C13.4055 14.9492 13.5611 14.7876 13.7106 14.6389C13.8449 14.5051 14.0092 14.2903 14.1585 14.116C14.3078 13.9418 14.3575 13.8173 14.457 13.6182C14.5565 13.419 14.5068 13.2448 14.4321 13.0954C14.3575 12.946 13.7604 11.4771 13.5116 10.8795C13.2692 10.2977 13.023 10.3765 12.8398 10.3673C12.6658 10.3586 12.4666 10.3567 12.2676 10.3567C12.0685 10.3567 11.7451 10.4314 11.4714 10.7302C11.1977 11.0289 10.4264 11.751 10.4264 13.2197C10.4264 14.6887 11.4963 16.1076 11.6456 16.3068C11.7949 16.5061 13.7509 19.5202 16.746 20.8129C17.4583 21.1204 18.0145 21.304 18.4481 21.4415C19.1633 21.6686 19.8142 21.6366 20.3287 21.5598C20.9023 21.4741 22.0951 20.8378 22.3439 20.1409C22.5927 19.4436 22.5927 18.8462 22.518 18.7217Z" fill="#BDBDBD"/>
                                            </svg>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="footer__bottom">
                        <?php if( !empty( $copyright ) ) : ?>
                            <span><?php echo $copyright; ?></span>
                        <?php endif; ?>
                        <?php if( !empty( $payment_methods ) ) : ?>
                            <div class="footer__bottom--img">
                                <?php foreach( $payment_methods as $item ) : ?>
                                <img src="<?php echo $item['image']; ?>" width="48" height="30" alt="card-pay">
                                <?php endforeach; ?> 
                            </div>
                        <?php endif; ?>
                        <div class="footer__bottom-block">
                            <a class="footer__bottom--create" href="<?php echo $site_made_link; ?>" target="__blank">
                                <?php if( !empty( $site_made_logo ) ) : ?>
                                    <img src="<?php echo $site_made_logo; ?>" width="106" height="29" alt="logo">
                                <?php endif; ?>
                                <?php if( !empty( $site_made_text ) )  : ?>
                                    <span><?php echo $site_made_text; ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </footer>

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
                'key' => 'group_647c6ac502b4a',
                'title' => 'Footer',
                'fields' => array(
                    array(
                        'key' => 'field_647c6ac5d6a88',
                        'label' => 'Logo',
                        'name' => 'logo',
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
                        'key' => 'field_647c6af8d6a89',
                        'label' => 'First menu',
                        'name' => 'first_menu',
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
                                'key' => 'field_647c6b01d6a8a',
                                'label' => '',
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
                                'parent_repeater' => 'field_647c6af8d6a89',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_647c6b0bd6a8b',
                        'label' => 'Second menu title',
                        'name' => 'second_menu_title',
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
                        'default_value' => '',
                        'translations' => 'translate',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                    array(
                        'key' => 'field_647c6b22d6a8c',
                        'label' => 'Second menu',
                        'name' => 'second_menu',
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
                                'key' => 'field_647c6b29d6a8d',
                                'label' => '',
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
                                'parent_repeater' => 'field_647c6b22d6a8c',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_647c6b32d6a8e',
                        'label' => 'Main office title',
                        'name' => 'main_office_title',
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
                        'key' => 'field_647c6b4bd6a8f',
                        'label' => 'Main office address',
                        'name' => 'main_office_address',
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
                        'key' => 'field_647c6b57d6a90',
                        'label' => 'Mail',
                        'name' => 'mail',
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
                        'key' => 'field_647c6b75d6a91',
                        'label' => 'Group composition title',
                        'name' => 'group_composition_title',
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
                        'key' => 'field_647c6b7ad6a92',
                        'label' => 'Group composition address',
                        'name' => 'group_composition_address',
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
                        'key' => 'field_647c6b8ad6a93',
                        'label' => 'Retail warehouse title',
                        'name' => 'retail_warehouse_title',
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
                        'key' => 'field_647c6b8fd6a94',
                        'label' => 'Retail warehouse address',
                        'name' => 'retail_warehouse_address',
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
                        'key' => 'field_647c6bb0d6a95',
                        'label' => 'Phone numbers',
                        'name' => 'phone_numbers',
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
                                'key' => 'field_647c6bbed6a96',
                                'label' => 'Number',
                                'name' => 'number',
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
                                'parent_repeater' => 'field_647c6bb0d6a95',
                            ),
                            array(
                                'key' => 'field_647c6bc4d6a97',
                                'label' => 'Text under number',
                                'name' => 'text_under_number',
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
                                'parent_repeater' => 'field_647c6bb0d6a95',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_647c6bd0d6a98',
                        'label' => 'Payment methods',
                        'name' => 'payment_methods',
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
                                'key' => 'field_647c6be5d6a9a',
                                'label' => '',
                                'name' => 'image',
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
                                'parent_repeater' => 'field_647c6bd0d6a98',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_647c6bdfd6a99',
                        'label' => 'Copyright',
                        'name' => 'copyright',
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
                        'key' => 'field_647c6c01d6a9b',
                        'label' => 'Facebook link',
                        'name' => 'facebook_link',
                        'aria-label' => '',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'translations' => 'copy_once',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_647c6c38d6a9c',
                        'label' => 'Instagram link',
                        'name' => 'instagram_link',
                        'aria-label' => '',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'translations' => 'copy_once',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_647c6c42d6a9d',
                        'label' => 'Twitter link',
                        'name' => 'twitter_link',
                        'aria-label' => '',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'translations' => 'copy_once',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_647c6c55d6a9e',
                        'label' => 'Telegram link',
                        'name' => 'telegram_link',
                        'aria-label' => '',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'translations' => 'copy_once',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_647c6c8ad6a9f',
                        'label' => 'Whatsapp link',
                        'name' => 'whatsapp_link',
                        'aria-label' => '',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'translations' => 'copy_once',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_647c7asf238c6d1ad',
                        'label' => 'Site made link',
                        'name' => 'site_made_link',
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
                        'key' => 'field_647c73796d1ac',
                        'label' => 'Site made logo',
                        'name' => 'site_made_logo',
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
                        'key' => 'field_647c738c6d1ad',
                        'label' => 'Site made text',
                        'name' => 'site_made_text',
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
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'widget',
                            'operator' => '==',
                            'value' => 'footer-widget',
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