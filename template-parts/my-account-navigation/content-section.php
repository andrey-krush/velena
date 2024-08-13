<?php

class My_Account_Navigation_Content_Section {

    public function __construct() {
        
    }

    public function render() {
        ?>
        <section class="account">
        <div class="container">
            <div class="account__head page-head">
                <div class="account__title title-h1">
                    <h1><?php pll_e('Кабінет клієнта');?></h1>
                </div>
                <div class="page-head__link">
                    <?php if ( strstr(wc_get_account_endpoint_url( 'edit-account' ),$_SERVER['REQUEST_URI']) ) : ?>
                        <a class="active"><?php pll_e('Налаштування акаунту'); ?></a>
                    <?php else : ?>
                        <a href="<?php echo wc_get_account_endpoint_url( 'edit-account' ); ?>"><?php pll_e('Налаштування акаунту'); ?></a>
                    <?php endif; ?>
                    <?php if ( strstr(wc_get_account_endpoint_url( 'orders' ),$_SERVER['REQUEST_URI']) ) : ?>
                        <a class="active"><?php pll_e('Історія замовлень'); ?></a>
                    <?php else : ?>
                        <a href="<?php echo wc_get_account_endpoint_url( 'orders' ); ?>"><?php pll_e('Історія замовлень'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        <?php
    }
}