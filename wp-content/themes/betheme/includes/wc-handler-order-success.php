<?php
add_action('woocommerce_thankyou', 'woocommerce_thankyou_change_order_status', 10, 1);
function woocommerce_thankyou_change_order_status($order_id)
{
    if (!$order_id) return;

    $handler_user_id = find_handler_user_id($order_id);
    update_field('handler_user_id', $handler_user_id, $order_id);

    $order = wc_get_order($order_id);
    if ($order->get_status() == 'processing' && $order->get_payment_method() == 'cod') {
        $order->update_status('pending');
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
    return 1;
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