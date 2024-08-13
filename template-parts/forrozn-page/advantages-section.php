<?php

class Forrozn_Page_Advantages_Section {

    public function __construct() {
        $advantages_section = get_field('advantages_section');
        $this->title = $advantages_section['title'];
        $this->image = $advantages_section['image'];
        $this->subtitle = $advantages_section['subtitle'];
        $this->advantages = $advantages_section['advantages'];
    }

    public function render() {
        ?>

        <section class="foropt-success for-success">
            <?php if( !empty( $this->image ) ) : ?>
                <div class="for-success__img">
                    <img src="<?php echo $this->image; ?>" width="471" height="740" alt="Bottle">
                </div>
            <?php endif; ?>
            <div class="container container--small">
                <div class="for-success__container">
                    <?php if( !empty( $this->title ) ) : ?>
                        <div class="for-success__title title-h2">
                            <h2><?php echo $this->title; ?></h2>
                        </div>
                    <?php endif; ?>
                    <?php if( !empty( $this->subtitle ) ) : ?>
                        <div class="section-text">
                            <p><?php echo $this->subtitle; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if( !empty( $this->advantages ) ) : ?>
                        <div class="for-success__benefits">
                            <?php foreach( $this->advantages as $item ) : ?>
                                <div class="for-success__content">
                                    <?php if( !empty( $item['title'] ) ) : ?>
                                        <div class="for-success__subtitle title-h5">
                                            <h3><?php echo $item['title']; ?></h3>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( !empty( $item['text'] ) ) : ?>
                                        <div class="section-text section-text--small ">
                                            <p><?php echo $item['text']; ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php
    }
}