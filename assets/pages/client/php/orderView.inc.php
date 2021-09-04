<?php
session_start();
if (!isset($_SESSION['client'])) {
    echo "Access denied";
    die();
}
if (isset($_POST['rate_button']) && isset($_POST['star_rating'])) {
    $star_rating = trim(htmlspecialchars($_POST['star_rating']));
    if ($star_rating > 0 && $star_rating <= 5) {
        require_once("../../php/sql.php");

        $order_id = $_SESSION['client_current_order_id'];

        client_rate_order($order_id, $star_rating);
        unset($_SESSION['client_current_order_id']);
        header("location: ../userMain.php");
        die();
    } else {
        header("location: ../userMain.php");
        die();
    }
} elseif (isset($_POST['report_button']) && isset($_POST['report_area'])) {
    require_once("../../php/sql.php");

    $order_id = $_SESSION['client_current_order_id'];
    $report_message = trim(htmlspecialchars($_POST['report_area']));
    client_report_order($order_id, $report_message);
    unset($_SESSION['client_current_order_id']);
    header("location: ../userMain.php");
    die();
} else {
    header("location: ../userMain.php");
    die();
}
