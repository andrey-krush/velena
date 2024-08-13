<?php

class Front_Page_Banner_Section {

    public function __construct() {
        
        $banner_section = get_field('banner_section');
        $this->title = $banner_section['title'];
        $this->subtitle = $banner_section['subtitle'];
        $this->background_image = $banner_section['background_image'];
        $this->image_cutout = $banner_section['image_cutout'];


    }

    public function render() {
        ?>

            <div class="banner-dist">
                <div class="container">
                    <div class="banner-dist__container">
                        <div class="banner-dist__img image-parallax" data-sens="-0.07">
                            <?php if( !empty( $this->image_cutout ) ) : ?>
                                <img src="<?php echo $this->image_cutout; ?>" alt="banner-dist__bg">
                            <?php endif; ?>
                            <?php if( !empty( $this->background_image ) ) : ?>
                                <img src="<?php echo $this->background_image; ?>"  alt="banner-dist__image" class="image-parallax__img">
                            <?php endif; ?>
                        </div>
                        <div class="banner-dist__info">
                            <?php if( !empty( $this->title ) ) : ?>
                                <div class="banner-dist__title title-h2">
                                    <h1><?php echo $this->title; ?></h1>
                                </div>
                            <?php endif; ?>
                            <?php if( !empty( $this->subtitle ) ) : ?>
                                <div class="banner-dist__text section-text">
                                    <p><?php echo $this->subtitle; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php
    }
}