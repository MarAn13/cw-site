<?php
session_start();
if (!isset($_SESSION['specialist'])) {
    echo "Access denied";
    die();
}
$order_id = $_SESSION['specialist_current_order_id'];
if (isset($_POST['accomplish_button']) && isset($_FILES['file'])) {
    $fileTmpLoc = $_FILES["file"]["tmp_name"];
    $fileName = $_FILES["file"]["name"];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $fileFinishName = $order_id . '.' . $fileActualExt;
    if (!mkdir("../../../media/orders/order_" . $_SESSION['specialist_current_order_id'])) {
        header("location: ../currentOrders.php?error=error2");
        die();
    }
    $fileTarget = "../../../media/orders/order_" . $_SESSION['specialist_current_order_id'] . "/" . $fileFinishName;
    $video_source = "media/orders/order_" . $_SESSION['specialist_current_order_id'] . "/" . $fileFinishName;

    $fileCheck = mime_content_type($fileTmpLoc);
    if (!preg_match('/^video\//', $fileCheck)) {
        header("location: ../currentOrders.php?error=error3");
        die();
    }
    $MAXIMUM_FILE_SIZE = pow(10, 10); // 10 gb

    // Check file size
    if ($_FILES["file"]["size"] > $MAXIMUM_FILE_SIZE) {
        header("location: ../currentOrders.php?error=error4");
        die();
    }
    // Check if file already exists
    if (file_exists($fileTarget)) {
        header("location: ../currentOrders.php?error=error5");
        die();
    }

    if (!move_uploaded_file($fileTmpLoc, $fileTarget)) {
        header("location: ../currentOrders.php?error=error6");
        die();
    }

    require_once("../../php/sql.php");

    specialist_accomplish_order($order_id, $video_source);
    unset($_SESSION['specialist_current_order_id']);
    header("location: ../orderHistory.php?operation=success");
    die();
} elseif (isset($_POST['cancel_button'])) {
    require_once("../../php/sql.php");

    specialist_cancel_order($order_id);
    unset($_SESSION['specialist_current_order_id']);
    header("location: ../currentOrders.php?operation=cancel");
    die();
} elseif (isset($_POST['function'])) {
    if ($_POST['function'] === 'updateChat') {
        require_once("../../php/sql.php");

        $message = trim(htmlspecialchars($_POST['message']));
        $message_sender = 'specialist';
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
} elseif(isset($_POST['urlInput'])) {
    require_once("../../php/sql.php");

    $video_source = trim(htmlspecialchars($_POST['urlInput']));
    $video_source_type = explode('.', $video_source);
    $video_source_type = end($video_source_type);
    if (empty($video_source) || strtolower($video_source_type) !== 'm3u8'){
        header("location: ../currentOrders.php?error=error1");
    die();
    }
    specialist_accomplish_order($order_id, $video_source);
    unset($_SESSION['specialist_current_order_id']);
    header("location: ../orderHistory.php?operation=success");
    die();
}else{
    header("location: ../currentOrders.php?error=error1");
    die();
}
