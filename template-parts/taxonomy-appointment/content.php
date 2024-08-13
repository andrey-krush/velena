<?php

class Taxonomy_Appointment_Content
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
            'taxonomy' => 'appointment',
            'field' => 'slug',
            'terms' => [$this->queried_object->slug]
        ];

        $this->type_array = [];

        if (isset($_GET['type_array']) and !empty($_GET['type_array'])) :

            $this->type_array = $_GET['type_array'];

            if (is_string($this->type_array)) :
                $this->type_array = [$this->type_array];
            endif;

            $args['tax_query']['relation'] = 'AND';

            $args['tax_query'][] = [
                'taxonomy' => 'type',
                'field' => 'slug',
                'terms' => $_GET['type_array']
            ];

            unset($_GET['type_array']);

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

            unset($_GET['orderby']);

        endif;

        if (!empty($_GET)) :
            foreach ($_GET as $key => $item) :

                if (str_contains($key, 'from')) :

                    $key = str_replace('_from', '', $key);

                    $array = [
                        'key' => 'characteristics_' . $key . '_value',
                        'value' => [$_GET[$key . '_from'], $_GET[$key . '_to']],
                        'type' => 'numeric',
                        'compare' => 'BETWEEN',
                    ];

                elseif (!str_contains($key, '_to')) :
                    $array = ['relation' => 'OR'];

                    if (is_string($item)) :
                        $_GET[$key] = [$item];
                    endif;

                    foreach ($item as $subitem) :

                        $array[] = [
                            'key' => 'characteristics_' . $key,
                            'value' => $subitem,
                            'compare' => 'LIKE'
                        ];

                    endforeach;
                endif;

                $meta_query[] = $array;

            endforeach;
        endif;

        $args['meta_query'] = $meta_query;

        $this->query = new WP_Query($args);

        $this->type = get_term_meta($this->queried_object->term_id, 'type_terms');

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
                        <li><?php echo get_term_by('term_id', $this->queried_object->parent, 'appointment')->name; ?></li>
                    <?php endif; ?>
                    <li><h1><?php echo $this->queried_object->name; ?></h1></li>
                </ul>
                <div class="product-list__container">
                    <?php if ($this->query->have_posts()) : ?>
                        <aside class="product-list__aside">
                            <form class="product-list__filters">
                                <input type="hidden" name="action" value="update_filters">
                                <input type="hidden" name="taxonomy" value="appointment">
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
                                        <div class="product-list__filter-slider" data-min="1"
                                            data-max="<?php echo $max_price; ?>"
                                            data-value-min="<?php echo(!empty($this->price_from) ? $this->price_from : $min_price); ?>"
                                            data-value-max="<?php echo(!empty($this->price_to) ? $this->price_to : ''); ?>"
                                            data-step="1"></div>
                                        <div class="product-list__filter-name">
                                            <span><?php echo pll__('Ціна'); ?></span>
                                            <span class="product-list__filter-output">
                                                            <input type="hidden" name="price_from"
                                                                value="<?php echo(!empty($this->price_from) ? $this->price_from : ''); ?>"
                                                                data-default="<?php echo $min_price; ?>"
                                                                class="js-slider-min">
                                                            <input type="hidden" name="price_to"
                                                                value="<?php echo(!empty($this->price_to) ? $this->price_to : ''); ?>"
                                                                data-default="<?php echo $max_price; ?>"
                                                                class="js-slider-max">
                                                            <span class="js-slider-min"></span>&nbsp;₴&nbsp;—&nbsp;<span
                                                        class="js-slider-max"></span>&nbsp;₴
                                                        </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($this->type)) : ?>
                                    <div class="product-list__filter is-open">
                                        <div class="product-list__filter-name">
                                            <span><?php echo pll__('Тип продукту'); ?></span>
                                        </div>
                                        <div class="product-list__filter-content">
                                            <?php foreach ($this->type as $item) : ?>
                                                <?php $item = get_term($item); ?>
                                                <?php if (!empty($item)) : ?>
                                                    <label class="input input--checkbox">

                                                        <?php if (in_array($item->slug, $this->type_array)) : ?>
                                                            <input type="checkbox" name="type_array[]"
                                                                value="<?php echo $item->slug ?>" checked>
                                                        <?php else : ?>
                                                            <input type="checkbox" name="type_array[]"
                                                                value="<?php echo $item->slug ?>">
                                                        <?php endif; ?>
                                                        <span><?php echo $item->name; ?></span>
                                                    </label>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php $current_language = pll_current_language(); ?>
                                <?php foreach ($this->filters as $key => $item) : ?>

                                    <?php if(  in_array($item['characteristic_type'], $this->term_range_attributes) and !empty($item['show_in_filtration']) ) : ?>
                                        <?php $item_without_spaces = str_replace(' ', '', $item['characteristic_type']); ?>
                                        <?php $measurement = get_option('measurement_' . $item_without_spaces); ?>
                                        <?php $min_value = get_term_meta($this->queried_object->term_id, 'min_' . $item_without_spaces . '_value', true); ?>
                                        <?php $max_value = get_term_meta($this->queried_object->term_id, 'max_' . $item_without_spaces . '_value', true); ?>

                                        <div class="product-list__filter">
                                            <div class="product-list__filter-slider" data-min="1" data-max="<?php echo $max_value; ?>" data-value-min="<?php echo $_GET[$item_without_spaces . '_from'] ?? $min_value; ?>" data-value-max="<?php echo $_GET[$item_without_spaces. '_to'] ?? $max_value; ?>" data-step="1"></div>
                                            <div class="product-list__filter-name">
                                                <span><?php echo $item['characteristic_type']; ?></span>
                                                <?php $type_key = str_replace(' ', '', $item['characteristic_type']); ?>
                                                <span class="product-list__filter-output">
                                                            <input type="hidden" name="<?=$type_key?>_from" value="" class="js-slider-min" data-default="<?php echo $min_value; ?>">
                                                            <input type="hidden" name="<?=$type_key?>_to" value="" class="js-slider-max" data-default="<?php echo $max_value; ?>">
                                                            <span class="js-slider-min"></span>&nbsp;<?php echo $measurement; ?>&nbsp;—&nbsp;<span class="js-slider-max"></span>&nbsp;<?php echo $measurement; ?>
                                                        </span>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <?php $item_for_key = str_replace(' ', '', $item['characteristic_type']); ?>
                                        <?php $item['characteristic_possible_values'] = get_term_meta($this->queried_object->term_id, $item_for_key); ?>
                                        <?php $item['characteristic_possible_values'] = array_filter($item['characteristic_possible_values']); ?>
                                        <?php if ($item['show_in_filtration'] and !empty( $item['characteristic_possible_values'] ) ) : ?>
                                            <div class="product-list__filter">
                                                <div class="product-list__filter-name">
                                                    <span><?php echo $item['characteristic_type']; ?></span>
                                                </div>
                                                <div class="product-list__filter-content">
                                                    <?php foreach ($item['characteristic_possible_values'] as $subitem) : ?>
                                                        <label class="input input--checkbox">
                                                            <?php if( !empty($_GET[$item_for_key]) and in_array( $subitem, $_GET[$item_for_key] ) ) : ?>
                                                                <input type="checkbox" name="<?php echo $item_for_key; ?>[]"
                                                                    value="<?php echo $subitem; ?>" checked>
                                                            <?php else : ?>
                                                                <input type="checkbox" name="<?php echo $item_for_key; ?>[]"
                                                                    value="<?php echo $subitem; ?>">
                                                            <?php endif; ?>
                                                            <span><?php echo $subitem; ?></span>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
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
                                        <?php echo pll__('Показано') . ' ' . $this->posts_per_page * ($this->paged - 1) + 1 . ' – ' . $this->query->found_posts . ' ' . pll__('із') . ' ' . $this->query->found_posts; ?>
                                    <?php else : ?>
                                        <?php echo pll__('Показано') . ' ' . $this->posts_per_page * ($this->paged - 1) + 1 . ' – ' . $this->posts_per_page * $this->paged . ' ' . pll__('із') . ' ' . $this->query->found_posts; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="product-list__selected">
                                <div class="product-list__selected-inner">

                                </div>
                                <button type="button"
                                        class="btn btn--white btn--smallest js-remove-filters"><?php echo pll__('Скинути всі'); ?></button>
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