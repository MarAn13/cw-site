<?php

$function = $_POST['function'];
$order_id = 1;

require_once("php/sql.php");

if ($_POST['function'] === 'updateChat') {
    $message = $_POST['message'];
    $message_sender = $_POST['message_sender'];
    update_chat($order_id, $message, $message_sender);
    echo "successfully updated";
    die();
} elseif ($_POST['function'] === 'checkChat') {
    $message_num = $_POST['message_num'];
    $response = check_chat($order_id, $message_num);
    if ($response !== false) {
        echo json_encode($response);
    } else {
        echo "no_new_messages";
    }
    die();
} else {
    echo "error";
}
