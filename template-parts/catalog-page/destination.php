<?php

class Catalog_Page_Destination {

    public function __construct () {

        $this->title = get_field('destination_section')['title'];
        $this->text = get_field('destination_section')['text'];
        $this->categories = get_terms( array(
            'taxonomy'   => 'appointment',
            'hide_empty' => false,
        ) );

    }

    public function render()  {
        ?>
            <?php if ( !empty( $this->categories ) ) : ?>
                <section class="product-destination" id="product-purpose">
                    <div class="container">
                        <div class="product-destination__info">
                            <?php if ( !empty( $this->title ) ) : ?>
                                <div class="product-destination__title title-h2">
                                    <h2><?php echo $this->title; ?></h2>
                                </div>
                            <?php endif; ?>
                            <?php if ( !empty( $this->text ) ) : ?>
                                <div class="section-text">
                                    <p><?php echo $this->text; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="products products--small">
                        <div class="products__inner">
                            <div class="products__container">
                                <?php foreach ( $this->categories as $item ) : ?>
                                    <?php $term_id = $item->term_id; ?>
                                    <?php $main_image = get_field( 'main_page_settings', 'term_'.$term_id )['main_image']; ?>
                                    <?php $left_image = get_field( 'main_page_settings', 'term_'.$term_id )['left_image']; ?>
                                    <?php $right_image = get_field( 'main_page_settings', 'term_'.$term_id )['right_image']; ?>
                                    <?php $offset_left_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_left_image']; ?>
                                    <?php $offset_right_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_right_image']; ?>
                                    <article class="products__item">
                                        <a href="<?php echo get_term_link( $term_id ); ?>" class="products__content">
                                            <div class="products__img">
                                                <div class="products__picture products__picture--left" style="--translateY: <?php echo !empty($offset_left_image) ? $offset_left_image : '0%'; ?>">
                                                    <img src="<?php echo $left_image; ?>" width="316" height="241" alt="tomato">
                                                </div>
                                                <div class="products__picture products__picture--right" style="--translateY: <?php echo !empty($offset_right_image) ? $offset_right_image : '0%'; ?>">
                                                    <img src="<?php echo $right_image; ?>" width="316" height="241" alt="tomato">
                                                </div>
                                                <img src="<?php echo $main_image; ?>" alt="<?php echo $item->slug; ?>">
                                            </div>
                                            <div class="products__text">
                                                <h3><?php echo $item->name; ?></h3>
                                            </div>
                                        </a>
                                    </article>
                                <?php endforeach; ?>
                        </div>
                        </div>
                    </div>
                    </div>
                </section>
            <?php endif; ?>
        </main>
        <?php
    }
}