<?php

class Wholesale_Cart_Form_Section
{

    public function __construct()
    {
        $this->products = get_wholesale_products();

        $texts = get_field('texts');
        $this->privacy_policy_text = $texts['privacy_policy_text'];
        $this->text_right_to_form = $texts['text_right_to_form'];
    }

    public function render()
    {
?>
        <?php if (!empty($this->products)) : ?>
            <form class="cart__form">
                <div class="cart__form-inner">
                    <div class="cart__form-title title-h2">
                        <h2><?php echo pll__('Контактні дані'); ?></h2>
                    </div>
                    <?php if (!is_user_logged_in()) : ?>
                        <div class="cart__form-subtitle section-text">
                            <p><?php echo pll__('Вже замовляли у нас?'); ?> <a href="#login-popup" data-fancybox><?php echo pll__('Натисніть сюди, щоб увійти'); ?></a></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($this->text_right_to_form)) : ?>
                        <div class="cart__form-text section-text mobile-show">
                            <?php echo $this->text_right_to_form; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form">
                        <?php if (is_user_logged_in()) :
                            $user_id = get_current_user_id();
                            $user_data = get_userdata($user_id);
                            $user_first_name = $user_data->first_name;
                            $user_last_name = $user_data->last_name;
                            $user_email = $user_data->user_email;
                            $billing_phone = get_user_meta( $user_id, 'billing_phone', true );
                            $billing_company = $user_data->billing_company;
                            $edrpou = get_field('edrpou', 'user_' . $user_id);
                        endif;
                        ?>
                        <input type="hidden" name="action" value="wholesale_contact">
                        <div class="form__row">
                            <div class="form__input form__input--3">
                                <div class="input input--required">
                                    <?php if (is_user_logged_in()) : ?>
                                        <?php if ($user_first_name) : ?>
                                            <input type="text" name="billing_first_name" value="<?php echo $user_first_name; ?>" placeholder="<?php echo pll__('Ім’я'); ?>" required>
                                        <?php else : ?>
                                            <input type="text" name="billing_first_name" placeholder="<?php echo pll__('Ім’я'); ?>" required>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <input type="text" name="billing_first_name" placeholder="<?php echo pll__('Ім’я'); ?>" required>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form__input form__input--3">
                                <div class="input input--required">
                                    <?php if (is_user_logged_in()) : ?>
                                        <?php if ($user_last_name) : ?>
                                            <input type="text" name="billing_last_name" value="<?php echo $user_last_name; ?>" placeholder="<?php echo pll__('Ім’я'); ?>" required>
                                        <?php else : ?>
                                            <input type="text" name="billing_last_name" placeholder="<?php echo pll__('Прізвище'); ?>" required>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <input type="text" name="billing_last_name" placeholder="<?php echo pll__('Прізвище'); ?>" required>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="section-text section-text--small">
                            <p><?php echo pll__('Якщо ви є представником компанії / ФОП — заповніть, будь ласка, поля:'); ?></p>
                        </div>
                        <div class="form__row">
                            <div class="form__input form__input--4">
                                <div class="input">
                                    <?php if (is_user_logged_in()) : ?>
                                        <?php if ($billing_company) : ?>
                                            <input type="text" name="billing_company" placeholder="<?php echo pll__('Назва компанії / ФОП'); ?>" value="<?php echo $billing_company; ?>">
                                        <?php else : ?>
                                            <input type="text" name="billing_company" placeholder="<?php echo pll__('Назва компанії / ФОП'); ?>">
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <input type="text" name="billing_company" placeholder="<?php echo pll__('Назва компанії / ФОП'); ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form__input form__input--2">
                                <div class="input">
                                    <?php if (is_user_logged_in()) : ?>
                                        <?php if ($edrpou) : ?>
                                            <input type="text" name="edrpou" placeholder="<?php echo pll__('ЄДРПОУ'); ?>" value="<?php echo $edrpou; ?>">
                                        <?php else : ?>
                                            <input type="text" name="edrpou" placeholder="<?php echo pll__('ЄДРПОУ'); ?>">
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <input type="text" name="edrpou" placeholder="<?php echo pll__('ЄДРПОУ'); ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form__row">
                            <div class="form__input form__input--3">
                                <div class="input input--required">
                                    <?php if (is_user_logged_in()) : ?>
                                        <?php if ($billing_phone) : ?>
                                            <input type="tel" name="billing_phone" data-validation="phone" value="<?php echo $billing_phone; ?>" placeholder="<?php echo pll__('Телефон'); ?>" required>
                                        <?php else : ?>
                                            <input type="tel" name="billing_phone" data-validation="phone" placeholder="<?php echo pll__('Телефон'); ?>" required>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <input type="tel" name="billing_phone" data-validation="phone" placeholder="<?php echo pll__('Телефон'); ?>" required>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form__row">
                            <div class="form__input form__input--5">
                                <div class="input input--required">
                                    <?php if (is_user_logged_in()) : ?>
                                        <?php if ($user_email) : ?>
                                            <input type="email"  name="billing_email" value="<?php echo $user_email; ?>" placeholder="<?php echo pll__('E-mail адреса'); ?>" required>
                                        <?php else : ?>
                                            <input type="email" name="billing_email" data-validation="email" placeholder="<?php echo pll__('E-mail адреса'); ?>" required>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <input type="email" name="billing_email" data-validation="email" placeholder="<?php echo pll__('E-mail адреса'); ?>" required>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form__row">
                            <div class="form__textarea form__input form__input--6">
                                <div class="input">
                                    <textarea name="message" placeholder="<?php echo pll__('Ваш меседж'); ?>"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form__error"></div>
                        <div class="form__btn">
                            <button class="btn" type="submit"><?php echo pll__('Надіслати'); ?></button>
                        </div>
                    </div>
                    <?php if (!empty($this->privacy_policy_text)) : ?>
                        <div class="cart__form-privacy section-text section-text--small">
                            <p><?php echo $this->privacy_policy_text; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($this->text_right_to_form)) : ?>
                    <div class="cart__form-text section-text mobile-hide">
                        <?php echo $this->text_right_to_form; ?>
                    </div>
                <?php endif; ?>
            </form>
        <?php endif; ?>
        </div>
        </div>
        </section>

<?php
    }
}
