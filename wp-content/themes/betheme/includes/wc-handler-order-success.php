<?php
add_action('woocommerce_order_status_completed', 'auto_update_orders_internal_status', 10, 2); // Optional (to be removed if not necessary)
add_action('woocommerce_order_status_processing', 'auto_update_orders_internal_status', 10, 2);
function auto_update_orders_internal_status($order_id, $order)
{
    update_field('handler_user_id', 1, $order_id);
}