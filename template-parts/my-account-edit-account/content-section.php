<?php

class My_Account_Edit_Account_Content_Section {

    public function __construct() {
        $this->areas = get_field('areas',Registration_Page::get_ID());
        $user_id = wp_get_current_user()->ID;

        $this->billing_address_1 = get_user_meta( $user_id, 'billing_address_1', true );
        $this->billing_postcode = get_user_meta( $user_id, 'billing_postcode', true );
        $this->billing_city = get_user_meta( $user_id, 'billing_city', true );
        $this->billing_state = get_user_meta( $user_id, 'billing_state', true );
        $this->billing_phone = get_user_meta( $user_id, 'billing_phone', true );
        $this->billing_email = get_user_meta( $user_id, 'billing_email', true );
        $this->billing_first_name = get_user_meta( $user_id, 'billing_first_name', true );
        $this->billing_last_name = get_user_meta( $user_id, 'billing_last_name', true );
        $this->billing_company = get_user_meta( $user_id, 'billing_company', true );
        $this->edrpou = get_user_meta( $user_id, 'edrpou', true );
    }

    public function render() {
        ?>

            <div class="account__container">
                    <form action="" class="account__form form no-scroll dont-reset">
                        <input type="hidden" name="action" value="edit_account">
                        <div class="account__form-row">
                            <div class="account__form-title title-h2">
                                <h2><?php pll_e('Персональні дані'); ?></h2>
                            </div>
                            <div class="form__row">
                                <div class="form__input form__input--3">
                                    <div class="input">
                                        <input type="text" name="billing_first_name" placeholder="<?php echo pll__('Ім’я'); ?>" <?php echo ( !empty( $this->billing_first_name ) ) ? 'value="' . $this->billing_first_name. '"' : 'value=""' ?> autocomplete="off">
                                    </div>
                                </div>
                                <div class="form__input form__input--3">
                                    <div class="input">
                                        <input type="text" name="billing_last_name" placeholder="<?php echo pll__('Прізвище'); ?>" <?php echo ( !empty( $this->billing_last_name ) ) ? 'value="' . $this->billing_last_name. '"' : 'value=""' ?> autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="section-text section-text--small">
                                <p><?php echo pll__('Якщо ви є представником компанії / ФОП — заповніть, будь ласка, поля:'); ?></p>
                            </div>
                            <div class="form__row">
                                <div class="form__input form__input--4">
                                    <div class="input">
                                        <input type="text" name="billing_company" placeholder="<?php echo pll__('Назва компанії / ФОП'); ?>" <?php echo ( !empty( $this->billing_company ) ) ? 'value="' . $this->billing_company. '"' : 'value=""' ?> autocomplete="off">
                                    </div>
                                </div>
                                <div class="form__input form__input--2">
                                    <div class="input">
                                        <input type="text" name="edrpou" placeholder="<?php echo pll__('ЄДРПОУ'); ?>" <?php echo ( !empty( $this->edrpou ) ) ? 'value="' . $this->edrpou. '"' : 'value=""' ?> autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="account__form-row">
                            <div class="account__form-title title-h2">
                                <h2><?php pll_e('Контактні дані'); ?></h2>
                            </div>
                            <div class="form__row">
                                <div class="form__input form__input--3">
                                    <div class="input">
                                        <input type="tel" name="billing_phone" placeholder="<?php echo pll__('Телефон'); ?>" <?php echo ( !empty( $this->billing_phone ) ) ? 'value="' . $this->billing_phone. '"' : 'value=""' ?> autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form__row">
                                <div class="form__input form__input--4">
                                    <div class="input">
                                        <input type="email" name="billing_email" placeholder="<?php echo pll__('E-mail адреса'); ?>" <?php echo ( !empty( $this->billing_email ) ) ? 'value="' . $this->billing_email. '"' : 'value=""' ?> autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="account__form-row">
                            <div class="form__row">
                                <div class="form__input form__input--3" >
                                    <div class="input input--select">
                                        <input type="text" readonly="" class="output_text" placeholder="<?php echo pll__('Область'); ?>" value=""  autocomplete="off">
                                        <input type="hidden" name="area" class="output_value" autocomplete="off">
                                        <ul class="input__dropdown">
                                            <?php foreach( $this->areas as $item ) : ?>
                                                <li data-value="<?php echo $item['code']; ?>" <?php echo( !empty($this->billing_state) && $this->billing_state == $item['code'] ? 'class="is-selected"' : ''); ?> ><?php echo $item['item']; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="input__arrow">
                                            <img src="<?=TEMPLATE_PATH?>/img/ico-dropdown.svg" alt="arrow">
                                        </div>
                                    </div>
                                </div>
                                <div class="form__input form__input--2">
                                    <div class="input">
                                        <input type="text" name="billing_postcode" placeholder="<?php echo pll__('Індекс'); ?>" <?php echo ( !empty( $this->billing_postcode ) ) ? 'value="' . $this->billing_postcode. '"' : 'value=""' ?> autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form__row">
                                <div class="form__input form__input--3">
                                    <div class="input">
                                        <input type="text" name="billing_city" placeholder="<?php echo pll__('Місто'); ?>" <?php echo ( !empty( $this->billing_city ) ) ? 'value="' . $this->billing_city. '"' : 'value=""' ?> autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form__row">
                                <div class="form__input form__input--6">
                                    <div class="input ">
                                        <input type="text" name="billing_address_1" placeholder="<?php echo pll__('Вулиця, дім, квартира, офіс, тощо'); ?>" <?php echo ( !empty( $this->billing_address_1 ) ) ? 'value="' . $this->billing_address_1. '"' : 'value=""' ?> autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="account__form-row">
                            <div class="account__form-title title-h2">
                                <h2><?php pll_e('Заміна паролю'); ?></h2>
                            </div>
                            <div class="form__row">
                                <div class="form__input form__input--3">
                                    <div class="input input--required">
                                        <input type="password" name="old_password" data-validation="old_password" placeholder="<?php pll_e('Старий пароль'); ?>" value="" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form__row">
                                <div class="form__input form__input--3">
                                <div class="input input--required">
                                    <input type="password" name="new_password" data-validation="password" placeholder="<?php pll_e('Новий пароль'); ?>" value="" autocomplete="off">
                                </div>
                                </div>
                                <div class="form__input form__input--3">
                                <div class="input input--required">
                                    <input type="password" name="new_password_again" data-validation="password_repeat" placeholder="<?php pll_e('Новий пароль повторно'); ?>" value="" autocomplete="off">
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="account__form-btn">
                            <button class="btn" type="submit"><?php pll_e('Зберегти зміни'); ?></button>
                            <div class="account__form-success show-on-success">
                                <p><?php echo pll__('Ваші дані успішно збережені'); ?></p>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
        </section>

        <?php
    }
}