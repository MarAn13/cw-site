<?php
session_start();
if (!isset($_SESSION['client'])) {
    echo "Access denied";
    die();
}
if (isset($_POST['cancel_button'])) {
    require_once("../../php/sql.php");

    $order_id = $_SESSION['client_current_order_id'];
    client_cancel_order($order_id);
    unset($_SESSION['client_current_order_id']);
    header("location: ../userMain.php?operation=cancel");
    die();
} elseif (isset($_POST['function'])) {
    $order_id = $_SESSION['client_current_order_id'];
    if ($_POST['function'] === 'updateChat') {
        require_once("../../php/sql.php");

        $message = trim(htmlspecialchars($_POST['message']));
        $message_sender = 'client';
        if (empty($message)) {
            die();
        }
        update_chat($order_id, $message, $message_sender);
        echo "successfully updated";
        die();
    } elseif ($_POST['function'] === 'checkChat') {
        require_once("../../php/sql.php");

        $message_num = trim(htmlspecialchars($_POST['message_num']));
        if (!is_numeric($message_num)) {
            die();
        }
        $response = check_chat($order_id, $message_num);
        if ($response !== false) {
            echo json_encode($response);
        } else {
            echo "no_new_messages";
        }
        die();
    }
} else {
    header("location: ../userMain.php");
    die();
}
