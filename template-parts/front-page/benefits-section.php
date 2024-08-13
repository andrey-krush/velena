<?php

class Front_Page_Benefits_Section
{

    public function __construct()
    {

        $benefits_section = get_field('benefits_section');
        $this->title = $benefits_section['title'];
        $this->text = $benefits_section['text'];
        $this->button = $benefits_section['button'];
        $this->benefits = $benefits_section['benefits'];
    }

    public function render()
    {
?>

        <section class="benefits">
            <div class="container">
                <div class="benefits__container">
                    <div class="benefits__info">
                        <?php if ( !empty( $this->title ) ) : ?>
                            <div class="benefits__title title-h2">
                                <h2><?php echo $this->title; ?></h2>
                            </div>
                        <?php endif; ?>
                        <?php if ( !empty( $this->text ) ) : ?>
                            <div class="section-text benefits__text">
                                <p><?php echo $this->text; ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ( !empty( $this->button ) ) : ?>
                        <div class="benefits__btn">
                            <a href="<?php echo $this->button['url']; ?>" class="btn"><?php echo $this->button['title']; ?></a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ( !empty( $this->benefits ) ) : ?>
                        <div class="benefits__content">
                            <?php foreach ( $this->benefits as $item ) : ?>
                                <article class="benefits__item">
                                    <?php if ( !empty( $item['image'] ) ) : ?>
                                        <div class="benefits__item-img">
                                            <img src="<?php echo $item['image']; ?>" width="48" height="47" alt="ico-benefits-1">
                                        </div>
                                    <?php endif; ?>
                                    <div class="benefits__item-descr">
                                        <?php if ( !empty( $item['title'] ) ) : ?>
                                            <div class="benefits__subtitle title-h5">
                                                <h3><?php echo $item['title']; ?></h3>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ( !empty( $item['text'] ) ) : ?>
                                            <div class="section-text section-text--small">
                                                <p><?php echo $item['text']; ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

<?php
    }
}
