<?php 

class Foropt_Page_Find_Section {

    public function __construct() {
        $find_block = get_field('find_block');
        $this->find_title = $find_block['title'];
        $this->find_subtitle = $find_block['subtitle'];
        $this->find_image = $find_block['image'];
        $this->find_button = $find_block['button'];

        $contact_us_block = get_field('contact_us_block');
        $this->contact_title = $contact_us_block['title'];
        $this->contact_subtitle = $contact_us_block['subtitle'];
        $this->contact_text_inside_of_form = $contact_us_block['text_inside_of_form'];

        $this->ajax_url = admin_url( 'admin-ajax.php' );

        $this->page_title = get_the_title();
    }

    public function render() {
        ?>

            <section class="foropt-find">
                <div class="container container--small">
                    <div class="foropt-find__container">
                        <div class="foropt-find__content">
                            <?php if( !empty( $this->find_title ) ) : ?>
                                <div class="foropt-find__title title-h2">
                                    <h2><?php echo $this->find_title; ?></h2>
                                </div>
                            <?php endif; ?>
                            <?php if( !empty( $this->find_title ) ) : ?>
                                <div class="foropt-find__text section-text">
                                    <p><?php echo $this->find_subtitle; ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if( !empty( $this->find_button ) ) : ?>
                                <div class="foropt-find__btn">
                                    <a href="<?php echo $this->find_button['url']; ?>" class="btn"><?php echo $this->find_button['title']; ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if( !empty( $this->find_image ) ) : ?>
                                <div class="foropt-find__img">
                                    <img src="<?php echo $this->find_image; ?>" width="563" height="685" alt="bottles">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="foropt-find__info show-hide-container no-scroll">
                            <?php if( !empty( $this->contact_title ) ) : ?>
                                <div class="foropt-find__title title-h2">
                                    <h2><?php echo $this->contact_title; ?></h2>
                                </div>
                            <?php endif; ?>
                            <?php if( !empty( $this->contact_subtitle ) ) : ?>
                                <div class="foropt-find__text section-text">
                                    <p><?php echo $this->contact_subtitle; ?></p>
                                </div>
                            <?php endif; ?>
                            <form action="<?php echo $this->ajax_url; ?>" class="foropt-find__form form">
                                <input type="hidden" name="action" value="for_opt_form">
                                <input type="hidden" name="page_title" value="<?php echo $this->page_title; ?>">
                                <div class="form__row">
                                  <div class="form__input">
                                      <div class="input input--required">
                                          <input type="text" name="name" required placeholder="<?php echo pll__('Ваше ім’я'); ?>">
                                      </div>
                                  </div>
                                </div>
                                <div class="form__row">
                                    <div class="form__input">
                                        <div class="input input--required">
                                            <input type="email" name="email" data-validation="email" required placeholder="<?php echo pll__('Ваша електронна адреса'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form__row">
                                    <div class="form__textarea form__input">
                                        <div class="input input--required">
                                            <textarea name="message" required placeholder="<?php echo pll__('Ваш меседж'); ?>"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php if( !empty( $this->contact_text_inside_of_form ) ) : ?>
                                    <div class="section-text section-text--small">
                                        <p><?php echo $this->contact_text_inside_of_form; ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="form__row">
                                    <div class="form__input">
                                        <div class="input">
                                            <input type="text" name="quantity" placeholder="<?php echo pll__('Кількість товару'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form__row">
                                    <div class="form__input form__file">
                                        <div class="input input--file">
                                            <span class="input__result" data-placeholder="<?php echo pll__('Креслення товару'); ?>"><?php echo pll__('Креслення товару'); ?></span>
                                            <label>
                                                <input type="file" name="file" data-maxsize="5">
                                                <span class="btn btn--small"><?php echo pll__('Обрати файл'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                              <div class="form__error"></div>
                                <div class="form__btn">
                                    <button class="btn" type="submit"><?php echo pll__('Надіслати'); ?></button>
                                </div>
                                <div class="form__success show-on-success">
                                    <div class="cart__price--min">
                                        <img src="<?=TEMPLATE_PATH?>/img/ico-success.svg" alt="ico-success">
                                        <div class="section-text">
                                            <p><span>Відправлено.</span> Ваше повідомлення було успішно відправлено. Ми зв`яжемось з Вами у найближчий час!</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <?php
    }
}