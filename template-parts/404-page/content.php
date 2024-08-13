<?php

class Page_404_Content {

    public function __construct() {
        $page_id = (new Page_404())::get_ID();

        $content = get_field('content', $page_id);
        $this->title = $content['title'];
        $this->image = $content['image'];
        $this->subtitle = $content['subtitle'];
        $this->main_page_link_text = $content['main_page_link_text'];
        $this->main_page_link = get_the_permalink(get_option('page_on_front'));
    }

    public function render() {
        ?>

            <main class="main">
                <section class="error">
                    <div class="container">
                        <?php if ( !empty( $this->image ) ) : ?>
                            <div class="error__bg">
                                <img src="<?php echo $this->image; ?>" alt="error-bg">
                            </div>
                        <?php endif; ?>
                        <div class="error__content">
                            <?php if( !empty( $this->title ) ) : ?>
                                <div class="error__title title-h1">
                                    <h1><?php echo $this->title; ?></h1>
                                </div>
                            <?php endif; ?>
                            <div class="error__img">
                                <img src="<?=TEMPLATE_PATH?>/img/error-bg.jpg" alt="Not found">
                            </div>
                            <div class="section-text error__text">
                                <?php if( !empty( $this->subtitle ) ) : ?>
                                    <p><?php echo $this->subtitle; ?></p>
                                <?php endif; ?>
                                <a class="section-link" href="<?php echo $this->main_page_link; ?>"><?php echo $this->main_page_link_text; ?></a>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

        <?php
    }

}