<?php

class Catalog_Page_Categories {

    public function __construct () {

        $this->categories = get_parent_categories_without_children('type');

    }

    public function render()  {
        ?>
            <?php if ( !empty( $this->categories ) ) : ?>
                <section class="crown-category">
                    <div class="container">
                        <?php foreach ( $this->categories as $item ) : ?>
                            <a href="<?php echo get_category_link( $item->term_id ); ?>" class="crown-category__container">
                                <div class="crown-category__content">
                                    <div class="crown-category__title title-h3">
                                        <h2><?php echo $item->name; ?></h2>
                                    </div>
                                    <div class="crown-category__info">
                                        <?php if ( !empty( get_field('main_settings','term_'.$item->term_id)['catalog_description'] ) ) : ?>
                                            <div class="crown-category__text section-text">
                                                <p><?php echo get_field('main_settings','term_'.$item->term_id)['catalog_description']; ?></p>
                                            </div>
                                        <?php endif; ?>
                                        <div class="crown-category__btn">
                                            <button class="btn btn--small"><?php pll_e('До списку продуктів'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="crown-category__bg">
                                    <img src="<?php echo get_field('main_settings','term_'.$item->term_id)['catalog_image']; ?>" alt="<?php echo $item->name; ?>">
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

        <?php
    }
}