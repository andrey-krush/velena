<?php

class Catalog_Page_Subcategories {

    public function __construct () {

        $this->categories = get_terms( array(
            'taxonomy'   => 'type',
            'hide_empty' => false,
            'parent' => 0
        ) );

    }

    public function render()  {
        ?>
            <?php if ( !empty( $this->categories ) ) : ?>
                <?php foreach ( $this->categories as $item ) : ?>
                    <?php 
                    $child_terms = get_terms( array(
                        'taxonomy'   => 'type',
                        'hide_empty' => false,
                        'parent' => $item->term_id
                    ) ); ?>
                    <?php if ( !empty( $child_terms ) ) : ?>
                        <section class="crown-category crown-category--box">
                            <div class="container">
                                <div class="crown-category__container">
                                    <div class="crown-category__content">
                                        <div class="crown-category__title title-h3">
                                            <h2><?php echo $item->name; ?></h2>
                                        </div>
                                        <?php if ( !empty( get_field('main_settings','term_'.$item->term_id)['catalog_description'] ) ) : ?>
                                            <div class="crown-category__text section-text">
                                                <p><?php echo get_field('main_settings','term_'.$item->term_id)['catalog_description']; ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="crown-category__list">
                                        <?php foreach ( $child_terms as $subitem ) : ?>
                                            <article class="crown-category__item">
                                                <?php if ( !empty( get_field('main_settings','term_'.$subitem->term_id)['catalog_image'] ) ) : ?>
                                                    <div class="crown-category__item-img">
                                                        <img src="<?php echo get_field('main_settings','term_'.$subitem->term_id)['catalog_image']; ?>" alt="<?php echo get_term( $subitem->term_id )->name; ?>">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="crown-category__item-title">
                                                    <h3><?php echo get_term( $subitem->term_id )->name; ?></h3>
                                                </div>
                                                <a href="<?php echo get_category_link( $subitem->term_id ); ?>" class="crown-category__item-link"></a>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="crown-category__bg">
                                        <img src="<?php echo get_field('main_settings','term_'.$item->term_id)['catalog_image']; ?>" alt="<?php echo $item->name; ?>">
                                    </div>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php
    }
}