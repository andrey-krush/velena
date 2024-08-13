<?php

class Search_Page_Content {

    public function __construct() {

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 5,
        );

        if( isset( $_GET['search'] ) and !empty( $_GET['search'] ) ) :

            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'title_for_search',
                    'value' => $_GET['search'],
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'product_code',
                    'value' => $_GET['search'],
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'description_for_search',
                    'value' => $_GET['search'],
                    'compare' => 'LIKE'
                ),
            );

        endif;

        $this->paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args['paged'] = $this->paged;

        $this->query = new WP_Query($args);

    }

    public function render() {
        ?>

        <main class="main">
            <?php if( $this->query->have_posts() ) : ?>
            <section class="search-results">
                <div class="container">
                    <div class="search-results__title title-h1">
                        <h1><?php echo pll__('Результати пошуку'); ?> «<?php echo $_GET['search']; ?>»</h1>
                    </div>
                    <div class="search-results__container">
                        <div class="search-results__head">
                            <div class="search-results__text"><?php echo pll__('Кількість товарів у списку:'); ?><strong> <?php echo $this->query->found_posts; ?></strong></div>
                        </div>
                            <div class="search-results__cards">
                                <?php while( $this->query->have_posts() ) : $this->query->the_post(); ?>
                                    <?php ( new Type_Products() )->render_product_article(get_the_ID()); ?>
                                <?php endwhile; ?>
                            </div>

                    </div>
                    <div class="pagination">
                        <?php echo paginate_links(array(
                            'total' => $this->query->max_num_pages,
                            'current' => $this->paged,
                            'prev_next' => false,
                            'end_size' => 1,
                            'mid_size' => 1,
                        )); ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>
        </main>


        <?php
    }

}