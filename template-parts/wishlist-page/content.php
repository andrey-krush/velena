<?php

class Wishlist_Page_Content {

    public function __construct () {

        $content = get_field('content');
        $this->title = $content['title'];
        $this->subtitle = $content['subtitle'];

        $current_language = pll_current_language();

        if( is_user_logged_in() ) : 

            $user = wp_get_current_user();
            $this->posts = get_user_meta( $user->data->ID, 'liked_posts_' . $current_language );

        else : 

            $this->posts = isset($_COOKIE['wishlist_products_' . $current_language]) ? json_decode(stripslashes($_COOKIE['wishlist_products_' . $current_language]), true) : [];

        endif;

        if( !empty( $this->posts ) ) : 
            
            $args = array(
                'post_type' => 'product',
                'post__in' => $this->posts,
                'posts_per_page' => 4
            );

            $this->paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
            $args['paged'] = $this->paged;

            $this->query = new WP_Query($args);

        endif;

    }

    public function render()  {
        ?>

                <main class="main">
                    <section class="favourites">
                        <div class="container">
                            <?php if( !empty( $this->title ) ) : ?>
                                <div class="favourites__title title-h1">
                                    <h1><?php echo $this->title; ?></h1>
                                </div>
                            <?php endif; ?>
                            <?php if( !empty( $this->subtitle ) ) : ?>
                                <div class="section-text">
                                    <p><?php echo $this->subtitle; ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ( !empty( $this->posts ) and $this->query->have_posts() ) : ?>
                                <div class="favourites__container">
                                    <div class="favourites__cards remove-cards">
                                        <?php while ( $this->query->have_posts() ) : $this->query->the_post(); ?>
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
                            <?php endif; ?>
                        </div>
                    </section>
                </main>

        <?php
    }
}