<?php

class Contacts_Page_Content {

    public function __construct() {
        $this->title = get_the_title();
        $content = get_field('content');
        $main_office = $content['main_office']; 
        $this->main_office_title = $main_office['title'];
        $this->main_office_address = $main_office['address'];
        $this->main_office_mail = $main_office['mail'];
        $this->main_office_button = $main_office['button'];

        $this->storages = $content['storages'];

        $phone_numbers = $content['phone_numbers'];
        $this->phone_numbers_title = $phone_numbers['title'];
        $this->phone_numbers = $phone_numbers['numbers'];
    }

    public function render() {
        ?>


        <main class="main">

            <section class="contact">
                <div class="container">
                    <?php if( !empty( $this->title ) ) : ?>
                        <div class="contact__title title-h1">
                            <h1><?php echo $this->title; ?></h1>
                        </div>
                    <?php endif; ?>
                    <div class="contact__container">
                        <ul class="contact__place">
                            <li data-id="0">
                                <?php if( !empty( $this->main_office_title ) ) : ?>
                                    <div class="contact__subtitle">
                                        <h3><?php echo $this->main_office_title; ?></h3>
                                    </div>
                                <?php endif; ?>
                                <?php if( !empty( $this->main_office_address ) ) : ?>
                                    <address><?php echo $this->main_office_address; ?></address>
                                <?php endif; ?>
                                <?php if( !empty( $this->main_office_mail ) ) : ?>
                                    <div class="contact__mail">
                                        <a href="mailto:<?php echo $this->main_office_mail; ?>"><?php echo $this->main_office_mail; ?></a>
                                    </div>
                                <?php endif; ?>
                                <?php if( !empty( $this->main_office_button['title'] ) ) : ?>
                                    <div class="contact__btn">
                                        <a href="#contact-popup" data-fancybox class="btn"><?php echo $this->main_office_button['title']; ?></a>
                                    </div>
                                <?php endif; ?>
                            </li>
                            <?php if( !empty( $this->storages ) ) : ?>
                                <?php foreach( $this->storages as $key => $item ) : ?>
                                    <li data-id="<?=$key+1?>">
                                        <?php if ( !empty( $item['storage_type'] ) ) : ?>
                                            <div class="contact__subtitle">
                                                <h3><?php echo $item['storage_type']; ?></h3>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ( !empty( $item['address'] ) ) : ?>
                                            <address><?php echo $item['address']; ?></address>
                                        <?php endif; ?>
                                        <?php if( !empty( $item['link']['url'] ) and !empty( $item['link']['title'] ) ) : ?>
                                            <div class="contact__plan">
                                                <a href="<?php echo $item['link']['url'];  ?>" data-fancybox=""><?php echo $item['link']['title']; ?></a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if( !empty( $item['routine'] ) ) : ?>
                                            <div class="contact__info">
                                                <?php foreach( $item['routine'] as $subitem ) : ?>
                                                    <span><?php echo $subitem['text']; ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                           
                        </ul>
                        <div class="contact__phone">
                            <?php if( !empty( $this->phone_numbers_title ) ) : ?>
                                <div class="contact__subtitle">
                                    <h3><?php echo $this->phone_numbers_title; ?></h3>
                                </div>
                            <?php endif; ?>
                            <?php foreach( $this->phone_numbers as $item ) : ?>
                            <div class="contact__phone-number">
                                <?php if( !empty( $item['phone'] ) ) : ?>
                                    <a href="tel:<?= preg_replace('/[^\d]/', '', $item['phone']); ?>"><?php echo $item['phone']; ?></a>
                                <?php endif; ?>
                                <?php if( !empty( $item['info'] ) )  : ?>
                                    <span class="contact__info"><?php echo $item['info']; ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach ;?>
                        </div>
                    </div>
                </div>

                <div class="contact__map" id="map"></div>
            </section>

        </main>

        <?php
    }
}