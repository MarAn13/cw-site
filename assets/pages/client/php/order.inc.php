<?php
session_start();
if (!isset($_SESSION['client_order_polygon_data'])) {
    if (
        !isset($_POST['submit']) || !isset($_POST['videoTypeSelection']) || !isset($_POST['videoDaySelection']) ||
        !isset($_POST['dateTime'])
    ) {
        header("location: ../order.php?error=error1");
        die();
    }
    $video_type = trim(htmlspecialchars($_POST['videoTypeSelection']));
    if (!empty($_POST['videoDaySelection'])) {
        $video_day = trim(htmlspecialchars($_POST['videoDaySelection']));
    } else {
        $video_day = trim(htmlspecialchars($_POST['dateTime']));
    }
    if (empty($video_type) || (empty($video_day))) {
        header("location: ../order.php?error=error2");
        die();
    }
    if (($video_type !== "video" && $video_type !== "livestream" &&
        ($video_type === "video" && $video_day !== "3 days" && $video_day !== "7 days" && $video_day !== "30 days"))) {
        header("location: ../order.php?error=error3");
        die();
    }
    if ($video_type === "livestream") {
        if (!$date_video = date_create($video_day)) {
            header("location: ../order.php?error=error4");
            die();
        }
        $date_now_string = date("Y/m/d H:i:s");
        $date_now = date_create($date_now_string);
        $date_min = date_create($date_now_string);
        date_time_set($date_min, 0, 0, 0);
        $date_max = date_create($date_now_string);
        $LIVESTREAM_DAYS_RANGE = "14 days";
        date_add($date_max, date_interval_create_from_date_string($LIVESTREAM_DAYS_RANGE));
        date_time_set($date_max, 23, 59, 59);
        if (!($date_min <= $date_video) || !($date_video <= $date_max)) {
            header("location: ../order.php?error=error5");
            die();
        }
    } else {
        $date_now_string = date("Y/m/d H:i:s");
        $date_now = date_create($date_now_string);
        $date_video = date_create($date_now_string);
        date_add($date_video, date_interval_create_from_date_string($video_day));
        $interval = date_diff($date_now, $date_video);
        if (!$interval->format('%R%a days') === "+" . $video_day) {
            header("location: ../order.php?error=error5");
            die();
        }
    }
    $_SESSION['client_order_video_type'] = $video_type;
    $_SESSION['client_order_date_of_creation'] = date_format($date_now, 'Y-m-d H:i:s');
    $_SESSION['client_order_expire_date'] = date_format($date_video, 'Y-m-d H:i:s');
    header("location: ../../choose.php");
    die();
} else {
    require_once("../../php/sql.php");

    $status = "waiting";
    create_order(
        $_SESSION['client'],
        $_SESSION['client_order_date_of_creation'],
        $_SESSION['client_order_polygon_data'],
        $status,
        $_SESSION['client_order_expire_date'],
        $_SESSION['client_order_video_type']
    );
    unset(
        $_SESSION['client_order_date_of_creation'],
        $_SESSION['client_order_polygon_data'],
        $_SESSION['client_order_expire_date'],
        $_SESSION['client_order_video_type']
    );
    header("location: ../userMain.php");
    die();
}
