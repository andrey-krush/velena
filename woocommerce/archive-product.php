<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
do_action( 'woocommerce_before_main_content' );
    
    
    if (!empty($_GET['price_from']) and !empty($_GET['price_to'])) :
        $price_from = $_GET['price_from'];
        $price_to = $_GET['price_to'];
        
        unset($_GET['price_from']);
        unset($_GET['price_to']);
    
    endif;
?>
    <section class="product-list">
        <div class="container container--big">
            <ul class="breadcrumbs">
                <?php woocommerce_breadcrumb( [
                    'delimiter'   => '',
                    'wrap_before' => '',
                    'wrap_after'  => '',
                    'before'      => '<li>',
                    'after'       => '</li>',
                ] ); ?>
            </ul>
            <?php $attributes = get_field('attributes', 'option'); ?>
            <div class="product-list__container">
                <?php if (have_posts()) : ?>
                    <aside class="product-list__aside">
                        <form class="product-list__filters">
                            <input type="hidden" name="action" value="update_filters">
                            <?php $counter = 0; ?>
                            <?php $current_language = pll_current_language(); ?>
                            <?php foreach( $attributes as $key => $item ) : ?>
                                <?php if( $item['show_in_filtration_on_shop'] ) : ?>
                                    <?php
                                    if( $counter == 0 ) :
                                        global $wpdb;
                                        $sql = "SELECT MAX(meta_value), post_id from {$wpdb->prefix}postmeta where meta_key = '_price'";
                                        $result = $wpdb->get_results($sql);
                                        $product_with_max_price = wc_get_product($result[0]->post_id);
                                        $max_price = $product_with_max_price->get_price();

                                        $max_to_check = explode('.', $max_price);
                                        $min_to_check = explode('.', $min_price);
            
                                        if( isset( $max_to_check[1] ) ) : 
                                            $max_price = ( strlen( $max_to_check[1] ) == 1 ) ? $max_price . '0' : $max_price;
                                        endif; 
                                        
                                        if( isset( $min_to_check[1] ) ) : 
                                            $min_price = ( strlen( $min_to_check[1] ) == 1 ) ? $min_price . '0' : $min_price;
                                        endif;    
            
                                        ?>
                                        <div class="product-list__filter">
                                            <div class="product-list__filter-slider" data-min="1" data-max="<?php echo $max_price; ?>" data-value-min="<?php echo(!empty($this->price_from) ? $this->price_from : '0'); ?>" data-value-max="<?php echo(!empty($this->price_to) ? $this->price_to : $max_price); ?>" data-step="1"></div>
                                            <div class="product-list__filter-name">
                                                <span><?php echo pll__('Ціна'); ?></span>
                                                <span class="product-list__filter-output">
                                                    <input type="hidden" name="price_from" value="<?php echo(!empty($this->price_from) ? $this->price_from : ''); ?>" data-default="0" class="js-slider-min">
                                                    <input type="hidden" name="price_to" value="<?php echo(!empty($this->price_to) ? $this->price_to : ''); ?>" data-default="<?php echo $max_price; ?>" class="js-slider-max">
                                                    <span class="js-slider-min"></span>&nbsp;₴&nbsp;—&nbsp;<span class="js-slider-max"></span>&nbsp;₴
                                                </span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php $counter ++; ?>
                                    <?php $item_for_key = str_replace(' ', '', $item['attribute_name_' . $current_language]); ?>
                                    <?php if( $counter == 0 ) : ?>
                                        <div class="product-list__filter is-open">
                                    <?php else : ?>
                                            <div class="product-list__filter">
                                    <?php endif; ?>
                                        <div class="product-list__filter-name">
                                            <span><?php echo $item['attribute_name_' . $current_language];?></span>
                                        </div>
                                        <div class="product-list__filter-content">
                                            <?php foreach( $item['attribute_values_' . $current_language] as $subitem ) : ?>
                                                <label class="input input--checkbox">
                                                    <input type="checkbox" name="<?php echo $item_for_key; ?>[]" value="<?php echo $subitem['value']; ?>">
                                                    <span><?php echo $subitem['value']; ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <div class="product-list__btn mobile-show">
                                <button type="submit" class="btn"><?php echo pll__('Застосувати'); ?></button>
                            </div>
                        </form>
                    </aside>
                <?php endif; ?>
                <div class="product-list__content">
                    <?php if ( woocommerce_product_loop() ): ?>
                        <div class="product-list__top">
                            <button type="button" class="product-list__toggle">
                                <img src="<?=TEMPLATE_PATH?>/img/ico-filter.svg" alt="Filter">
                                <span><?php echo pll__('Фільтр'); ?></span>
                            </button>
                            <div class="product-list__count">
                                <?php woocommerce_result_count(); ?>
                            </div>
                            <div class="product-list__selected">
                                <div class="product-list__selected-inner">

                                </div>
                                <button type="button" class="btn btn--white btn--smallest js-remove-filters"><?php echo pll__('Скинути всі'); ?></button>
                            </div>
                            <div class="product-list__sort">
                                <?php woocommerce_catalog_ordering(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="product-list__wrapper">
                        <div class="product-list__cards">
                            <?php
                            if ( woocommerce_product_loop() ) {
                                if ( wc_get_loop_prop( 'total' ) ) {
                                    while ( have_posts() ) {
                                        the_post();

                                        /**
                                         * Hook: woocommerce_shop_loop.
                                         */
                                        do_action( 'woocommerce_shop_loop' );

                                        ( new Type_Products() )->render_product_article(get_the_ID());
                                    }
                                }
                            } else { ?>
                                <div class="product-list__nothing">
                                    <p><?php pll_e('Нічого не знайдено')?></p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php if (woocommerce_product_loop()) { ?>
                        <div class="product-list__pagination pagination">
                            <?php
                                /**
                                 * Hook: woocommerce_after_shop_loop.
                                 *
                                 * @hooked woocommerce_pagination - 10
                                 */
                                do_action('woocommerce_after_shop_loop');
                            ?>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="product-list__descr">
                        <div class="section-text">
                            <p>Надаємо своїм клієнтам високоякісну продукцію, виготовлену з&nbsp;дотриманням міжнародних стандартів. Ми&nbsp;тісно співпрацюємо у&nbsp;створенні оптимального дизайну банок та&nbsp;пляшок на&nbsp;ранніх стадіях процесу розробки нового продукту.</p>
                        </div>
                        <div class="section-text">
                            <p>Відвантаження продукції провадиться з&nbsp;нашого складу, розташованого в&nbsp;м.Київ, або безпосередньо зі скляних заводів. Здійснюємо доставку до&nbsp;всіх регіонів України за&nbsp;допомогою транспортних та&nbsp;поштових компаній.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php

get_footer(  );
