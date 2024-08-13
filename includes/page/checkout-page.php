<?php

class Checkout_Page {

    public static function init() {

        add_action('init', [__CLASS__, 'pll_strings']);
        add_action('woocommerce_checkout_order_processed', [__CLASS__, 'update_order_meta']);
    }

    public static function update_order_meta( $order_id ) {

        if( !$order_id ) :
            return;
        endif;

        update_post_meta($order_id, 'language', $_POST['language']);

        update_post_meta($order_id, '_shipping_first_name','' );
        update_post_meta($order_id, '_shipping_last_name','' );
        update_post_meta($order_id, '_shipping_company','' );
        $order = wc_get_order($order_id);
        if ( $order->get_shipping_method() == 'Local pickup' ) {
            update_post_meta($order_id, '_shipping_address_1','Самовивіз: '.$_POST['local_pickup_place'] );
            update_post_meta($order_id, '_shipping_city','' );   
        }
        if ( $order->get_shipping_method() == 'Нова Пошта' ) {
            $shipping_address = get_post_meta($order->get_id(),'_shipping_address_1',true);
            update_post_meta($order_id, '_shipping_address_1','Нова пошта: '.$shipping_address );  
        }


        if( $_POST['createaccount'] == 1 ) :
            update_post_meta($order_id, 'createaccount', $_POST['createaccount']);
        endif;
        $payment_method = isset($_POST["payment_method"]) ? $_POST["payment_method"] : "on_receive";
        $total_amount = $order->get_total();
        if($payment_method === "liqpay-webplus") {
            require_once __DIR__ . "/../LiqPay.php";
            $settings = get_option('woocommerce_liqpay-webplus_settings');
            $liqp = array(
                'action' => 'pay',
                'amount' => $total_amount,
                'currency' => 'UAH',
                'description' => 'Оплата замовлення №' . $order_id,
                'order_id' => $order_id,
                'language' => 'ru',
                'version' => '3',
                'server_url' => 'http://velena.page.ua/wp-admin/admin-ajax.php?action=set_new_order_status' ,
                'result_url' => Page_Liqpay_Success::get_url() . '?order='. base64_encode( $order_id ) . '&new_user='. $new_user ,
                'type' => 'buy'
            );
            $liqPay = new LiqPay($settings["public_key"], $settings["private_key"]);
            $link = $liqPay->cnb_link($liqp);
            wp_send_json_success(['result'=>'success','redirect'=>$link],200);
            exit;
        }
    }

    public static function pll_strings() {

        pll_register_string('checkout-1', 'Оплата', 'Checkout' );
        pll_register_string('checkout-2', 'Самовивіз із нашого складу: ', 'Checkout' );        
        pll_register_string('checkout-3', 'Доставка Новою Поштою', 'Checkout' );
        pll_register_string('checkout-4', 'Готівкою або карткою при отриманні', 'Checkout' );
        pll_register_string('checkout-5', 'Оплата зараз карткою онлайн', 'Checkout' );
        pll_register_string('checkout-5', 'Безготівковий розрахунок для юр. та фіз. осіб', 'Checkout' );
        pll_register_string('checkout-6', 'Ваші особисті дані будуть використані для обробки вашого замовлення, підтримки вашого досвіду на цьому веб-сайті та інших цілях, описаних у нашій <a href="">політиці конфіденційності</a>', 'Checkout' );
        pll_register_string('checkout-7', 'Замовлення підтверджую', 'Checkout' );        
        pll_register_string('checkout-8', 'Checkout', 'Checkout' );  
        pll_register_string('checkout-9', 'Промокод на знижку', 'Checkout' );        
        pll_register_string('checkout-10', 'Додати', 'Checkout' );        
        pll_register_string('checkout-11', 'Закрити', 'Checkout' );       
        pll_register_string('checkout-12', 'Доставка', 'Checkout' );        
        pll_register_string('checkout-13', 'Оплата', 'Checkout' );    
        pll_register_string('checkout-14', 'Повернутися до кошику', 'Checkout' );  
        pll_register_string('checkout-15', 'Оформлення замовлення', 'Checkout' ); 
        pll_register_string('checkout-16', 'Вже замовляли у нас?', 'Checkout' ); 
        pll_register_string('checkout-17', 'Натисніть сюди, щоб увійти ', 'Checkout' ); 
        pll_register_string('checkout-18', 'від', 'Checkout' ); 
        pll_register_string('checkout-19', 'Розгорнути', 'Checkout' ); 
        pll_register_string('checkout-20', 'Позицій:', 'Checkout' ); 
        pll_register_string('checkout-21', 'Сума:', 'Checkout' ); 
        pll_register_string('checkout-22', 'Сума замовлення', 'Checkout' );

        pll_register_string('checkout-23', 'Local pickup', 'Checkout' );
        pll_register_string('checkout-24', 'Нова Пошта', 'Checkout' );
        pll_register_string('checkout-25', 'Код купону', 'Checkout' );
        pll_register_string('checkout-26', 'Застосувати купон', 'Checkout' );
        pll_register_string('checkout-27', 'Нотатки до замовлення', 'Checkout' );
        pll_register_string('checkout-28', 'Платіжні дані', 'Checkout' );
        pll_register_string('checkout-29', 'Ваше замовлення', 'Checkout' );
        pll_register_string('checkout-30', 'Проміжний підсумок', 'Checkout' );
        pll_register_string('checkout-31', 'Загалом', 'Checkout' );
        pll_register_string('checkout-32', 'Скасовано', 'Checkout' );
        pll_register_string('checkout-33', 'На утриманні', 'Checkout' );
        pll_register_string('checkout-34', 'В обробці', 'Checkout' );
        pll_register_string('checkout-35', 'Рахунок-фактура', 'Checkout' );
        pll_register_string('checkout-36', 'Отримувач', 'Checkout' );
        pll_register_string('checkout-37', 'Виберіть адресу самовивозу', 'Checkout' );
        pll_register_string('checkout-38', 'Виконано', 'Checkout' );
        pll_register_string('checkout-41', 'Повернено', 'Checkout' );
        pll_register_string('checkout-42', 'Не вдалося', 'Checkout' );
        pll_register_string('checkout-43', 'Очікування оплати', 'Checkout' );


        pll_register_string('checkout-39', 'LiqPay', 'Checkout' );
        pll_register_string('checkout-40', 'Текст про успішне створення аккаунту', 'Checkout' );



        pll_register_string('thank-you-page-1', 'Замовлення успішно сформовано', 'Thank you page' ); 
        pll_register_string('thank-you-page-2', 'Дякуємо за ваше замовлення! Ми якнайшвидше розглянемо ваше замовлення. Чекайте на імейл про результати на вашій електронній поштовій скринці', 'Thank you page' ); 
        pll_register_string('thank-you-page-3', 'Завантажити квитанцію', 'Thank you page' ); 
        
    }


}