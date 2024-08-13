<?php 

class About_Page_Gallery_Section {

    public function __construct() {
        $gallery_section = get_field( 'gallery_section' );
        $this->texts = $gallery_section['texts'];
        $this->images = $gallery_section['images'];
    }

    public function render() {
        ?>

            <section class="about-gallery">
                <div class="container container--small">
                    <?php if( !empty( $this->texts ) ) : ?>
                        <div class="about-gallery__text">
                            <?php foreach( $this->texts as $item ) : ?>
                                <div class="section-text">
                                    <p>
                                        <?php echo $item['text']; ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if( !empty( $this->images ) ) : ?>
                        <div class="about-gallery__gallery">
                            <div class="gallery">
                                <div class="gallery__container">
                                    <?php foreach( $this->images as $item ) : ?>
                                        <a href="<?php echo $item['image']; ?>" class="gallery__link" data-fancybox="gallery-1">
                                            <img src="<?php echo $item['image']; ?>" width="370" height="260" alt="gallery__img">
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

        </main>

        <?php
    }
}