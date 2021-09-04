<?php
session_start();
if (!isset($_POST['accept_button']) || !isset($_SESSION['specialist'])){
    echo "Access denied";
    die();
}
if (!isset($_POST["order_id"])){
    header("location: ../orderDesk.php");
    die();
}
$order_id = trim(htmlspecialchars($_POST['order_id']));

require_once("../../php/sql.php");

if (specialist_order_limit($_SESSION['specialist'])){
    header("location: ../orderDesk.php?operation=orderLimit");
    die();
}
if (specialist_invalid_order($order_id)){
    header("location: ../orderDesk.php?operation=error");
    die();
}
specialist_accept_order($_SESSION['specialist'], $order_id);
$users_id = order_get_client_specialist_id($order_id);
create_chat_room($order_id, $users_id[0], $users_id[1]);
header("location: ../orderDesk.php?operation=success");
die();
