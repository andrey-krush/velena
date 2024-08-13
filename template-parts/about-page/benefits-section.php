<?php 

class About_Page_Benefits_Section {

    public function __construct() {
        $benefits_section = get_field('benefits_section');
        $this->background_image = $benefits_section['background_image'];
        $this->benefits = $benefits_section['benefits'];
    }

    public function render() {
        ?>

        <section class="about-benefits">
            <?php if( !empty( $this->background_image ) ) : ?>
                <div class="about-benefits__bg image-paralla" data-sens="-0.07">
                    <img src="<?php echo $this->background_image; ?>" class="image-parallax__img" alt="about-benefits-img">
                </div>
            <?php endif; ?>
            <?php if( !empty( $this->benefits ) ) : ?>
                <div class="container">
                    <div class="about-benefits__container">
                        <?php foreach( $this->benefits as $item ) : ?>
                            <article class="about-benefits__card">
                                <?php if( !empty( $item['title'] ) ) : ?>
                                    <div class="about-benefits__text">
                                        <p><?php echo $item['title']; ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if( !empty( $item['image'] ) ) : ?>
                                    <div class="about-benefits__img">
                                        <img src="<?php echo $item['image']; ?>" width="65" height="65" alt="about-benefits__img">
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <?php
    }
}