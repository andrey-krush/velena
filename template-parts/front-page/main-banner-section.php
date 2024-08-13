<?php

class Front_Page_Main_Banner_Section {

    public function __construct() {
        
        $main_banner_section = get_field('main_banner_section');
        $this->info_cards = $main_banner_section['info_cards'];
        

    }

    public function render() {
        ?>
            <?php if( !empty( $this->info_cards ) ) : ?>
                <div class="main-banner">
                    <div class="container container--full">
                        <div class="main-banner__container">
                            <?php foreach( $this->info_cards as $item ) : ?>
                            <article class="main-banner__item">
                                <?php if( !empty( $item['background_image'] ) ) : ?>
                                    <div class="main-banner__item-bg">
                                        <img src="<?php echo $item['background_image']; ?>" alt="Промислові поставки">
                                    </div>
                                <?php endif; ?>
                                <div class="main-banner__item-content">
                                    <div class="main-banner__item-title">
                                        <?php if( !empty( $item['title'] ) ) : ?>
                                            <div class="title-h3">
                                                <h2><?php echo $item['title']; ?></h2>
                                            </div>
                                        <?php endif; ?>
                                        <?php if( !empty( $item['button'] ) ) : ?>
                                            <div class="title-link">
                                                <a href="<?php echo $item['button']['url']; ?>" class="btn">
                                                    <img src="<?=TEMPLATE_PATH?>/img/ico-section-linksvg.svg" alt="Arrow">
                                                    <span><?php echo $item['button']['title']; ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="main-banner__item-hidden">
                                    <?php if( !empty( $item['title'] ) ) : ?>
                                        <div class="title-h2 mobile-show">
                                            <h2><?php echo $item['tile']; ?></h2>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( !empty( $item['text'] ) ) : ?>
                                        <div class="section-text section-text--big">
                                            <p><?php echo $item['text']; ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( !empty( $item['button'] ) ) : ?>
                                        <div class="main-banner__item-hidden__btn mobile-show">
                                            <a class="btn" href="<?php echo $item['button']['url']; ?>"><?php echo $item['button']['title']; ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php
    }
}