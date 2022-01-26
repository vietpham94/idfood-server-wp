<?php
add_action('woocommerce_thankyou', 'woocommerce_thankyou_change_order_status', 10, 1);
function woocommerce_thankyou_change_order_status($order_id)
{
    if (!$order_id) return;

    $handler_user_id = get_field('handler_user_id', $order_id);
    if (empty($handler_user_id)) {
        $handler_user_id = find_handler_user_id($order_id);
        update_field('handler_user_id', $handler_user_id, $order_id);
    }

    $order = wc_get_order($order_id);
    if ($order->get_status() == 'processing' && $order->get_payment_method() == 'cod') {
        $order->update_status('pending');
        push_notification($handler_user_id, $order_id);
    }
}

add_action('woocommerce_order_status_changed', 'action_woocommerce_order_status_changed', 10, 4);
function action_woocommerce_order_status_changed($order_id, $this_status_transition_from, $this_status_transition_to, $order)
{
    if (!$order_id) return;
    $handler_user_id = get_field('handler_user_id', $order_id);

    if ($this_status_transition_from == 'on-hold' && $this_status_transition_to == 'pending') {
        push_notification($handler_user_id, $order_id);
    }
}

// Push thông báo đơn hàng tới app của đại lý phân phối
function push_notification(int $handler_user_id, int $order_id)
{
    $apiAccessKey = 'AAAARK-0Buc:APA91bGjTxbafkFCxWDOYD_Q5dTaWFLvKxevuEqZp6b3zBomxl8pB-sTdWjmk3mNf--o3w9NNiXjlmZYg8Z8aMwDZ4S0kOWdl8MNNKPgLdeoGge0U6KNYVuYjS_j5SQNzU9DiGe5J77I';
    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    $firebase_token = get_user_meta($handler_user_id, 'firebase_token', true);

    $notification = [
        'title' => 'Đơn đặt hàng mới',
        'body' => 'Bạn có đơn một đặt hàng mới. Ấn vào để xử lý.',
        "click_action" => "FCM_PLUGIN_ACTIVITY",
    ];
    $extraNotificationData = ['order_id' => $order_id];

    $fcmNotification = [
        //'registration_ids' => $tokenList, //multiple token array
        'to' => $firebase_token, //single token
        'notification' => $notification,
        "priority" => "high",
        'data' => $extraNotificationData
    ];

    $headers = [
        'Authorization: Bearer ' . $apiAccessKey,
        'Content-Type: application/json; UTF-8'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    $result = curl_exec($ch);
    write_log($ch);
    curl_close($ch);
    write_log($result);

    $notification = array(
        'title' => $notification['title'],
        'body' => $notification['body'],
        'receiver_id' => $handler_user_id,
        'order_id' => $order_id
    );
    notifications_install_data($notification);

    return $result;
}

// Tìm cửa hàng để gán đơn
function find_handler_user_id(int $order_id): int
{
    write_log('Tìm kiếm nhà cung cấp');

    $order = wc_get_order($order_id);
    if (empty($order)) {
        return 0;
        write_log(0);
    }

    if ($order->get_customer_id() == get_current_user_id()) {
        write_log(1);
    }

    global $wpdb;
    $users_table_name = $wpdb->prefix . 'users';
    $users_meta_table_name = $wpdb->prefix . 'usermeta';
    $capabilities = $wpdb->prefix . 'capabilities';
    $sql = "SELECT `$users_table_name`.`ID`  FROM $users_table_name INNER JOIN $users_meta_table_name
            ON `$users_table_name`.`ID` = `$users_meta_table_name`.`user_id`
            WHERE `$users_meta_table_name`.`meta_key`='$capabilities'
            AND `$users_meta_table_name`.`meta_value` LIKE '%nha_cung_cap%'";
    $authors = $wpdb->get_results($sql, "ARRAY_A");
    $fullStrAddress = $order->get_shipping_address_1() . ' ' .  $order->get_shipping_address_2() . ' ' . $order->get_shipping_city() . ' ' . $order->get_shipping_state();
    $arrChars = explode(' ', $fullStrAddress);
    $result = [];
    foreach ($authors as $user) {
        $customer = new WC_Customer($user['ID']);
        $matchCount = 0;
        foreach ($arrChars as $char) {
            if (strpos($customer->get_billing_address_1(), $char)) {
                $matchCount += 1;
            }
        }
        $result[] = array(
            matchCount => $matchCount,
            ID => $user['ID']
        );
    }

    usort($result, 'cmp');
    write_log($result[0]['ID']);
    return $result[0]['ID'];
}

function cmp($a, $b)
{
    return strcmp($b['matchCount'], $a['matchCount']);
}

function notifications_install_data($data)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'notifications';
    $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));

    if ($wpdb->get_var($query) == $table_name) {
        $wpdb->insert(
            $table_name,
            $data
        );
    }
}