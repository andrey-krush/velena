<?php 

class About_Page_Promo_Section {

    public function __construct() {
        $promo_section = get_field( 'promo_section' ); 
        $this->title = $promo_section['title'];
        $this->subtitle = $promo_section['subtitle'];
        $this->background_image = $promo_section['background_image'];
    }
    
    public function render() { 
        ?>


    <main class="main">

        <section class="page-promo">
            <?php if( !empty( $this->background_image ) ) : ?>
            <div class="page-promo__bg">
                <div class="page-promo__bg-img image-parallax" data-sens="0.05">
                    <picture>
                        <source media="(max-width: 768px)" srcset="<?php echo $this->background_image; ?>">
                        <img src="<?php echo $this->background_image; ?>" alt="bg" class="image-parallax__img">
                    </picture>
                </div>
            </div>
            <?php endif; ?>
            <div class="page-promo__container">
                <div class="container container--small">
                    <div class="page-promo__content">
                        <?php if( !empty( $this->title ) ) : ?>
                            <div class="page-promo__title title-h1">
                                <h1><?php echo $this->title; ?></h1>
                            </div>
                        <?php endif; ?>
                        <?php if( !empty( $this->subtitle ) ) : ?>
                            <div class="page-promo__text">
                                <p><?php echo $this->subtitle; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}