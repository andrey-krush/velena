<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$orderby = isset($_GET['orderby1']) ? $_GET['orderby1'][0] : '';
unset($_GET['orderby1']);
?>

<div class="dropdown input input--select" aria-label="<?php esc_attr_e( 'Shop order', 'woocommerce' ); ?>">
    <ul>
        <?php
            $selected_id = false;
            $selected_name = '';
            foreach ( $catalog_orderby_options as $id => $name ) :
                if(selected( $orderby, $id, false )){
                    $selected_id = $id;
                    $selected_name = esc_html( $name );
                }
            endforeach;
            
            if(!$selected_id){
                $selected_id = array_keys($catalog_orderby_options)[0];
                $selected_name = array_values($catalog_orderby_options)[0];
            }
        ?>
        <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
            <li data-value="<?php echo esc_attr( $id ); ?>" <?php echo($selected_id == $id ? 'class="is-selected"' : '' ); ?>><?php echo esc_html( $name ); ?></li>
        <?php endforeach; ?>
    </ul>
    <span class="output_text"><?php echo $selected_name; ?></span>
    <input type="hidden" name="orderby" class="output_value">
</div>

