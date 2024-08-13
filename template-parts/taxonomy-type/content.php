<?php

class Taxonomy_Type_Content
{

    public function __construct()
    {

        foreach ($_GET as $key => $item) :

            if (str_contains($item, ',')) :

                $_GET[$key] = explode(',', $item);

            endif;

        endforeach;

        $this->queried_object = get_queried_object();
        $this->main_page_link = home_url();
        $this->add_args = $_GET;

        $this->filters = get_field('category_characteristics', 'term_' . $this->queried_object->term_id);

        $this->paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 16,
            'paged' => $this->paged
        ];

        $this->posts_per_page = $args['posts_per_page'];

        $args['tax_query'][] = [
            'taxonomy' => 'type',
            'field' => 'slug',
            'terms' => [$this->queried_object->slug]
        ];

        $this->appointment_array = [];

        if (isset($_GET['appointment_array']) and !empty($_GET['appointment_array'])) :

            $this->appointment_array = $_GET['appointment_array'];

            if (is_string($this->appointment_array)) :
                $this->appointment_array = explode(',', $this->appointment_array);
            endif;
            $args['tax_query']['relation'] = 'AND';

            $args['tax_query'][] = [
                'taxonomy' => 'appointment',
                'field' => 'slug',
                'terms' => $this->appointment_array
            ];

            unset($_GET['appointment_array']);

        endif;

        $meta_query = ['relation' => 'AND'];

        if (!empty($_GET['price_from']) and !empty($_GET['price_to'])) :
            $meta_query[] = [
                'key' => '_price',
                'value' => array($_GET['price_from'], $_GET['price_to']),
                'compare' => 'BETWEEN'
            ];

            $this->price_from = $_GET['price_from'];
            $this->price_to = $_GET['price_to'];

            unset($_GET['price_from']);
            unset($_GET['price_to']);

        endif;

        if (!empty($_GET['orderby'])) :
            if ($_GET['orderby'] == 'price') :
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'ASC';
            elseif ($_GET['orderby'] == 'price-desc') :
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'DESC';
            endif;

//            $_GET['orderby1'] = $_GET['orderby'];
            unset($_GET['orderby']);

        endif;

        unset($_GET['taxonomy']);
        unset($_GET['slug']);

        if (!empty($_GET)) :
            foreach ($_GET as $key => $item) :


                if (str_contains($key, 'from')) :

                    $key = str_replace('_from', '', $key);

                    $array = [
                        'key' => 'characteristics_' . $key . '_value',
                        'value' => [$_GET[$key . '_from'], $_GET[$key . '_to']],
                        'type'    => 'numeric',
                        'compare' => 'BETWEEN',
                    ];

                elseif (!str_contains($key, '_to')) :
                    $array = ['relation' => 'OR'];

                    if (is_string($item)) :
                        $item = [$item];
                        $_GET[$key] = $item;
                    endif;

                    foreach ($item as $subitem) :

                        $array[] = [
                            'key' => 'characteristics_' . $key,
                            'value' => $subitem,
//                            'compare' => 'LIKE'
                        ];

                    endforeach;
                endif;

                $meta_query[] = $array;

            endforeach;
        endif;

        $args['meta_query'] = $meta_query;
        $this->query = new WP_Query($args);


        $this->appointment_terms = get_term_meta($this->queried_object->term_id, 'appointment_terms');
        $this->text_on_left_side = get_field('text_on_left_side', $this->queried_object);
        $this->text_on_right_side = get_field('text_on_right_side', $this->queried_object);

        $this->term_range_attributes = get_term_meta($this->queried_object->term_id, 'term_range_attributes', true);
    }

    public function render()
    {
?>

        <section class="product-list">
            <div class="container container--big">
                <ul class="breadcrumbs">
                    <li>
                        <a href="<?php echo (new Catalog_Page())::get_url(); ?>"><?php echo pll__('Каталог'); ?></a>
                    </li>
                    <?php if ($this->queried_object->parent != 0) : ?>
                        <li><?php echo get_term_by('term_id', $this->queried_object->parent, 'type')->name; ?></li>
                    <?php endif; ?>
                    <li>
                        <h1><?php echo $this->queried_object->name; ?></h1>
                    </li>
                </ul>
                <div class="product-list__container">
                    <?php if ($this->query->have_posts()) : ?>
                        <aside class="product-list__aside">
                            <form class="product-list__filters">
                                <input type="hidden" name="action" value="update_filters">
                                <input type="hidden" name="taxonomy" value="type">
                                <input type="hidden" name="slug" value="<?php echo $this->queried_object->slug; ?>">
                                <?php

                                $max_price = get_term_meta($this->queried_object->term_id, 'max_price', true);

                                if (empty($max_price)) :

                                    global $wpdb;

                                    $query = "
                                    SELECT MAX(meta_value+0)
                                    FROM {$wpdb->prefix}postmeta
                                    WHERE meta_key='_price'
                                    AND post_id IN (
                                    SELECT ID FROM {$wpdb->prefix}posts WHERE post_type='product' AND post_status='publish'
                                    )
                                ";
                                    $max_price = $wpdb->get_var($query);

                                endif;

                                $min_price = get_term_meta($this->queried_object->term_id, 'min_price', true);
                                $min_price = !empty($min_price) ? $min_price : 0;

                                $max_to_check = explode('.', $max_price);
                                $min_to_check = explode('.', $min_price);

                                if (isset($max_to_check[1])) :
                                    $max_price = (strlen($max_to_check[1]) == 1) ? $max_price . '0' : $max_price;
                                endif;

                                if (isset($min_to_check[1])) :
                                    $min_price = (strlen($min_to_check[1]) == 1) ? $min_price . '0' : $min_price;
                                endif;


                                ?>
                                <?php if ($this->query->have_posts()) : ?>
                                    <div class="product-list__filter">
                                        <div class="product-list__filter-slider" data-min="1" data-max="<?php echo $max_price; ?>" data-value-min="<?php echo (!empty($this->price_from) ? $this->price_from : $min_price); ?>" data-value-max="<?php echo (!empty($this->price_to) ? $this->price_to : $max_price); ?>" data-step="1"></div>
                                        <div class="product-list__filter-name">
                                            <span><?php echo pll__('Ціна'); ?></span>
                                            <span class="product-list__filter-output">
                                                <input type="hidden" name="price_from" value="<?php echo (!empty($this->price_from) ? $this->price_from : ''); ?>" data-default="<?php echo $min_price; ?>" class="js-slider-min">
                                                <input type="hidden" name="price_to" value="<?php echo (!empty($this->price_to) ? $this->price_to : ''); ?>" data-default="<?php echo $max_price; ?>" class="js-slider-max">
                                                <span class="js-slider-min"></span>&nbsp;₴&nbsp;—&nbsp;<span class="js-slider-max"></span>&nbsp;₴
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php $current_language = pll_current_language(); ?>
                                <?php foreach ($this->filters as $key => $item) : ?>

                                    <?php if (in_array($item['characteristic_type'], $this->term_range_attributes) and !empty($item['show_in_filtration'])) : ?>
                                        <?php $item_without_spaces = str_replace(' ', '', $item['characteristic_type']); ?>
                                        <?php $measurement = get_option('measurement_' . $item_without_spaces); ?>
                                        <?php $min_value = get_term_meta($this->queried_object->term_id, 'min_' . $item_without_spaces . '_value', true); ?>
                                        <?php $max_value = get_term_meta($this->queried_object->term_id, 'max_' . $item_without_spaces . '_value', true); ?>

                                        <div class="product-list__filter">
                                            <div class="product-list__filter-slider" data-min="1" data-max="<?php echo $max_value; ?>" data-value-min="<?php echo $_GET[$item_without_spaces . '_from'] ?? $min_value; ?>" data-value-max="<?php echo $_GET[$item_without_spaces . '_to'] ?? $max_value; ?>" data-step="1"></div>
                                            <div class="product-list__filter-name">
                                                <span><?php echo $item['characteristic_type']; ?></span>
                                                <?php $type_key = str_replace(' ', '', $item['characteristic_type']); ?>
                                                <span class="product-list__filter-output">
                                                    <input type="hidden" name="<?= $type_key ?>_from" value="" class="js-slider-min" data-default="<?php echo $min_value; ?>">
                                                    <input type="hidden" name="<?= $type_key ?>_to" value="" class="js-slider-max" data-default="<?php echo $max_value; ?>">
                                                    <span class="js-slider-min"></span>&nbsp;<?php echo $measurement; ?>&nbsp;—&nbsp;<span class="js-slider-max"></span>&nbsp;<?php echo $measurement; ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <?php $item_for_key = str_replace(' ', '', $item['characteristic_type']); ?>
                                        <?php $item['characteristic_possible_values'] = get_term_meta($this->queried_object->term_id, $item_for_key); ?>
                                        <?php $item['characteristic_possible_values'] = array_filter($item['characteristic_possible_values']); ?>

                                        <?php $order_values = get_option('correct_values_order_'. $item_for_key); ?>
                                        <?php $select_characteristic = false; ?>
                                        <?php if( !empty( $order_values ) ) :?>
                                            <?php $select_characteristic = true; ?>
                                            <?php $correct_order = []; ?>
                                            <?php ?>
                                            <?php foreach ( $order_values as $value ) : ?>
                                                <?php
                                                    if( in_array( $value['value'], $item['characteristic_possible_values'] ) ) :
                                                        $correct_order[] = $value['value'];
                                                    endif;
                                                ?>
                                            <?php endforeach;?>
                                            <?php $item['characteristic_possible_values'] = $correct_order; ?>
                                        <?php endif; ?>
                                        <?php if ($item['show_in_filtration'] and !empty($item['characteristic_possible_values'])) : ?>
                                            <div class="product-list__filter">
                                                <div class="product-list__filter-name">
                                                    <span><?php echo $item['characteristic_type']; ?></span>
                                                </div>
                                                <div class="product-list__filter-content">
                                                    <?php foreach ($item['characteristic_possible_values'] as $subitem) : ?>
                                                        <?php if( $select_characteristic ) : ?>
                                                            <?php if( in_array( ['value' => $subitem], $order_values ) ) : ?>
                                                                <?php $subitem_for_value = str_replace(' ', '', $subitem); ?>
                                                                <?php $subitem_for_value = mb_strtolower($subitem_for_value); ?>
                                                                <label class="input input--checkbox">
                                                                    <?php if (!empty($_GET[$item_for_key]) and in_array($subitem_for_value, $_GET[$item_for_key])) : ?>
                                                                        <input type="checkbox" name="<?php echo $item_for_key; ?>[]" value="<?php echo $subitem_for_value; ?>" checked>
                                                                    <?php else : ?>
                                                                        <input type="checkbox" name="<?php echo $item_for_key; ?>[]" value="<?php echo $subitem_for_value; ?>">
                                                                    <?php endif; ?>
                                                                    <span><?php echo $subitem; ?></span>
                                                                </label>
                                                            <?php endif; ?>
                                                        <?php else : ?>
                                                            <label class="input input--checkbox">
                                                                <?php if (!empty($_GET[$item_for_key]) and in_array($subitem, $_GET[$item_for_key])) : ?>
                                                                    <input type="checkbox" name="<?php echo $item_for_key; ?>[]" value="<?php echo $subitem; ?>" checked>
                                                                <?php else : ?>
                                                                    <input type="checkbox" name="<?php echo $item_for_key; ?>[]" value="<?php echo $subitem; ?>">
                                                                <?php endif; ?>
                                                                <span><?php echo $subitem; ?></span>
                                                            </label>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if (!empty($this->appointment_terms)) : ?>
                                    <div class="product-list__filter">
                                        <div class="product-list__filter-name">
                                            <span><?php echo pll__('Призначення продукту'); ?></span>
                                        </div>
                                        <div class="product-list__filter-content">
                                            <?php foreach ($this->appointment_terms as $item) : ?>
                                                <?php $item = get_term($item); ?>
                                                <?php if (!empty($item)) : ?>
                                                    <label class="input input--checkbox">
                                                        <?php if (in_array($item->slug, $this->appointment_array)) : ?>
                                                            <input type="checkbox" name="appointment_array[]" value="<?php echo $item->slug ?>" checked>
                                                        <?php else : ?>
                                                            <input type="checkbox" name="appointment_array[]" value="<?php echo $item->slug ?>">
                                                        <?php endif; ?>
                                                        <span><?php echo $item->name; ?></span>
                                                    </label>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="product-list__btn mobile-show">
                                    <button type="submit" class="btn"><?php echo pll__('Застосувати'); ?></button>
                                </div>
                            </form>
                        </aside>
                    <?php endif; ?>
                    <div class="product-list__content">
                        <div class="product-list__top">
                            <button type="button" class="product-list__toggle">
                                <img src="<?= TEMPLATE_PATH ?>/img/ico-filter.svg" alt="Filter">
                                <span><?php echo pll__('Фільтр'); ?></span>
                            </button>
                            <div class="product-list__count">
                                <?php if ($this->posts_per_page >= $this->query->found_posts) : ?>
                                    <?php echo pll__('Показано') . ' 1 – ' . $this->query->found_posts . ' ' . pll__('із') . ' ' . $this->query->found_posts; ?>
                                <?php else : ?>
                                    <?php if ($this->paged == 1) : ?>
                                        <?php echo pll__('Показано') . ' 1 – ' . $this->posts_per_page . ' ' . pll__('із') . ' ' . $this->query->found_posts; ?>
                                    <?php elseif ($this->paged == $this->query->max_num_pages) : ?>
                                        <?php echo pll__('Показано') . ' ' . $this->posts_per_page * ($this->paged - 1) + 1 . ' – ' . $this->query->found_posts . ' ' .  pll__('із') . ' ' . $this->query->found_posts; ?>
                                    <?php else : ?>
                                        <?php echo pll__('Показано') . ' ' . $this->posts_per_page * ($this->paged - 1) + 1 . ' – ' . $this->posts_per_page * $this->paged . ' ' . pll__('із') . ' ' . $this->query->found_posts; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php
                            $selected_filters_arr = [];

                            foreach ($this->appointment_terms as $item) :
                                if (in_array($item->slug, $this->appointment_array)) :
                                    $selected_filters_arr[] = '<span class="btn btn--gray btn--smallest js-selected-btn" data-name="appointment_array[]" data-value="' . $item->slug . '">' . $item->name . ' <svg width="8" height="9" viewBox="0 0 8 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1.5L7 7.5M7 1.5L1 7.5" stroke="black" stroke-width="2"></path></svg></span>';
                                endif;
                            endforeach;

                            foreach ($this->filters as $key => $item) :
                                if ($item['show_in_filtration']) :
                                    $item_for_key = str_replace(' ', '', $item['characteristic_type']);
                                    foreach ($item['characteristic_possible_values'] as $subitem) :
                                        if (!empty($_GET[$item_for_key]) and in_array($subitem, $_GET[$item_for_key])) :
                                            $selected_filters_arr[] = '<span class="btn btn--gray btn--smallest js-selected-btn" data-name="' . $item_for_key . '" data-value="' . $subitem . '">' . $subitem . ' <svg width="8" height="9" viewBox="0 0 8 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1.5L7 7.5M7 1.5L1 7.5" stroke="black" stroke-width="2"></path></svg></span>';
                                        endif;
                                    endforeach;
                                endif;
                            endforeach;

                            if ((!empty($this->price_from) && $this->price_from != 1) || (!empty($this->price_to) && $this->price_to != $max_price)) :
                                $selected_filters_arr[] = '<span class="btn btn--gray btn--smallest js-selected-btn" data-name="price_from" data-value="">' . (!empty($this->price_from) ? $this->price_from : '') . '&nbsp;₴&nbsp;—&nbsp;' . (!empty($this->price_to) ? $this->price_to : $max_price) . '&nbsp;₴ <svg width="8" height="9" viewBox="0 0 8 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1.5L7 7.5M7 1.5L1 7.5" stroke="black" stroke-width="2"></path></svg></span>';
                            endif;
                            ?>
                            <div class="product-list__selected <?php echo (!empty($selected_filters_arr) ? 'is-visible' : '') ?>">
                                <div class="product-list__selected-inner">
                                    <?php
                                    if (!empty($selected_filters_arr)) {
                                        foreach ($selected_filters_arr as $item) :
                                            echo $item;
                                        endforeach;
                                    }
                                    ?>
                                </div>
                                <button type="button" class="btn btn--white btn--smallest js-remove-filters"><?php echo pll__('Скинути всі'); ?></button>
                            </div>
                            <div class="product-list__sort">
                                <?php woocommerce_catalog_ordering(); ?>
                            </div>
                        </div>
                        <div class="product-list__wrapper">
                            <div class="product-list__cards">
                                <?php
                                if ($this->query->have_posts()) {
                                    while ($this->query->have_posts()) {
                                        $this->query->the_post();
                                        (new Type_Products())->render_product_article(get_the_ID());
                                    }
                                } else { ?>
                                    <div class="product-list__nothing">
                                        <p><?php pll_e('Нічого не знайдено') ?></p>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                            <?php
                            $paginationHtml = paginate_links(array(
                                'total' => $this->query->max_num_pages,
                                'current' => $this->paged,
                                'prev_next' => false,
                                'end_size' => 1,
                                'mid_size' => 1,
                                'add_args' => $this->add_args
                            ));
                            if (!empty($paginationHtml)) { ?>
                                <div class="product-list__pagination pagination">
                                    <?php echo $paginationHtml; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="product-list__descr">
                            <div class="section-text">
                                <?php if (!empty($this->text_on_left_side)) : ?>
                                    <?php echo $this->text_on_left_side; ?>
                                <?php endif; ?>
                            </div>
                            <div class="section-text">
                                <?php if (!empty($this->text_on_right_side)) : ?>
                                    <?php echo $this->text_on_right_side; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

<?php
    }
}
