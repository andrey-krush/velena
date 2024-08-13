<?php

class Catalog_Page_Promo {

    public function __construct () {

        $this->title = get_field('promo_section')['title'];
        $this->left_text = get_field('promo_section')['left_text'];
        $this->right_text = get_field('promo_section')['right_text'];
        $this->button = get_field('promo_section')['button'];

    }

    public function render()  {
        ?>
        <main class="main">
            <section class="catalogue-product">
                <div class="container">
                    <?php if ( !empty( $this->title ) ) : ?>
                        <div class="catalogue-product__title title-h1">
                            <h1><?php echo $this->title; ?></h1>
                        </div>
                    <?php endif; ?>
                    <div class="catalogue-product__container">
                    <?php if ( !empty( $this->left_text ) ) : ?>
                        <div class="catalogue-product__content">
                            <div class="catalogue-product__text section-text">
                                <?php echo $this->left_text; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="catalogue-product__link">
                        <?php if ( !empty( $this->right_text ) ) : ?>
                            <div class="section-text">
                                <?php echo $this->right_text; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ( !empty( $this->button ) ) : ?>
                            <a href="<?php echo $this->button['url']; ?>" class="btn"><?php echo $this->button['title']; ?></a>
                        <?php endif; ?>
                    </div>
                    </div>
                </div>
            </section>

        <?php
    }
}