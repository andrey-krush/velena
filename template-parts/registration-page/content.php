<?php 

class Registration_Page_Content {

    public function __construct() {

        $this->ajax_url = admin_url( 'admin-ajax.php' );

        $this->title = get_the_title();
        $this->areas = get_field('areas');
        $this->privacy_policy_text = get_field('privacy_policy_text');

    }

    public function render() {
        ?>

        <main class="main">

            <section class="register">
                <div class="container show-hide-container">
                    <div class="register__container hide-on-success">
                        <?php if( !empty( $this->title ) ) : ?>
                            <div class="register__title title-h1">
                                <h1><?php echo $this->title; ?></h1>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo $this->ajax_url; ?>" class="register__form form">
                            <input type="hidden" name="action" value="register_form">
                            <div class="register__form-row">
                                <div class="form__row">
                                    <div class="form__input form__input--4">
                                        <div class="input input--required">
                                            <input type="email" name="Email" data-validation="email" placeholder="<?php echo pll__('E-mail адреса'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__row">
                                    <div class="form__input form__input--4">
                                        <div class="input input--required">
                                            <input type="password" name="Password" data-validation="password" placeholder="<?php echo pll__('Пароль'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__row">
                                    <div class="form__input form__input--4">
                                        <div class="input input--required">
                                            <input type="password" name="Password_again" data-validation="password_repeat" placeholder="<?php echo pll__('Пароль повторно'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="register__form-row">
                                <div class="form__row">
                                    <div class="form__input form__input--3">
                                        <div class="input input--required">
                                            <input type="text" name="Name" placeholder="<?php echo pll__('Ім’я'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form__input form__input--3">
                                        <div class="input input--required">
                                            <input type="text" name="Surname" placeholder="<?php echo pll__('Прізвище'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__row">
                                    <div class="form__input form__input--2">
                                        <div class="input input--required">
                                            <input type="tel" name="Phone" placeholder="<?php echo pll__('Телефон'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="section-text section-text--small">
                                    <p><?php echo pll__('Якщо ви є представником компанії / ФОП — заповніть, будь ласка, поля:'); ?></p>
                                </div>
                                <div class="form__row">
                                    <div class="form__input form__input--4">
                                        <div class="input">
                                            <input type="text" name="Name company" placeholder="<?php echo pll__('Назва компанії / ФОП'); ?>">
                                        </div>
                                    </div>
                                    <div class="form__input form__input--2">
                                        <div class="input">
                                            <input type="text" name="EDRPOU" placeholder="<?php echo pll__('ЄДРПОУ'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="register__form-row">
                                <div class="section-text section-text--small">
                                    <p><?php echo pll__('Адресу вашого розташування буде використано при доставці кур’єром'); ?></p>
                                </div>
                                <div class="form__row">
                                    <div class="form__input form__input--3" >
                                        <div class="input input--select input--required">
                                            <input type="text" readonly="" class="output_text" value="<?php echo pll__('Область'); ?>">
                                            <input type="hidden" name="Area" class="output_value" required="">
                                            <ul class="input__dropdown">
                                                <?php foreach( $this->areas as $item ) : ?>
                                                    <li data-value="<?php echo $item['code']; ?>"><?php echo $item['item']; ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <div class="input__arrow">
                                                <img src="<?=TEMPLATE_PATH?>/img/ico-dropdown.svg" alt="arrow">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form__input form__input--2">
                                        <div class="input input--required">
                                            <input type="text" name="Index" placeholder="<?php echo pll__('Індекс'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__row">
                                    <div class="form__input form__input--3">
                                        <div class="input input--required">
                                            <input type="text" name="City" placeholder="<?php echo pll__('Місто'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__row">
                                    <div class="form__input form__input--5">
                                        <div class="input input--required">
                                            <input type="text" name="Home" placeholder="<?php echo pll__('Вулиця, дім, квартира, офіс, тощо'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="register__btn">
                                <button class="btn" type="submit"><?php echo pll__('Створити акаунт'); ?></button>
                            </div>
                            <?php if( !empty( $this->privacy_policy_text ) ) : ?>
                                <div class="section-text section-text--small">
                                    <p><?php echo $this->privacy_policy_text; ?></p>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="register__container show-on-success success-message">
                      <div class="success-message__title title-h1">
                        <span><?php pll_e('Заявку на реєстрацію оформлено'); ?></span>
                      </div>
                      <div class="success-message__text section-text section-text--big">
                        <p><?php pll_e('Дякуємо за вашу заявку! Ми якнайшвидше розглянемо вашу заявку. Чекайте на імейл про резульнати на вашій електронній поштовій скринці'); ?></p>
                      </div>
                      <div class="success-message__btn">
                        <a href="#login-popup" data-fancybox class="btn"><?php pll_e('Логін'); ?></a>
                      </div>
                    </div>
                </div>
            </section>

        </main>

        <?php
    }

}