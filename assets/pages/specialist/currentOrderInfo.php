<?php
require_once("php/auth.inc.php");
require_once("../php/sql.php");

if (!isset($_POST['order_info'])) {
    echo "Access denied";
    die();
}
$order_id = trim(htmlspecialchars($_POST['order_info']));
if (specialist_invalid_current_order($order_id, $_SESSION['specialist'])) {
    header("location: currentOrders.php");
    die();
}
$order = specialist_current_order_info($_SESSION['specialist'], $order_id);
if (empty($order['order_completion_date'])) {
    $order['order_completion_date'] = '-';
}
$order['order_place_info'] = to_array($order['order_place_info']);
$_SESSION['specialist_current_order_id'] = $order_id;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <title>SiteMAP</title>
</head>
<style>
    html,
    body {
        height: 100%;
    }

    body {
        min-height: 100vh;
    }

    .mainContentDiv {
        /*background-attachment: fixed;
        background-image: url(assets/images/backgroundTest.jpg);
        background-size: cover;
        background-repeat: no-repeat;
        background-color: rgb(150, 150, 150);
        background-blend-mode: multiply;
        filter: none;
        -webkit-filter: none;*/
        background-color: silver;
    }

    #fileProgressBar {
        width: 75%;
    }

    #chatArea {
        height: 50vh !important;
        width: 40vw !important;
    }

    .client-message-div {
        min-height: 15% !important;
        width: 70% !important;
    }

    .specialist-message-div {
        min-height: 15% !important;
        width: 70% !important;
    }
</style>

<body onload="setInterval('checkChat(message_number)', 1000)">
    <?php
    include_once('header.php');
    ?>
    <!-- Main Content -->
    <div class="container-fluid p-3 mainContentDiv">
        <div class="container-fluid bg-dark text-light rounded mainContentBgDiv">
            <h1 class="text-center">Order info</h1>
            <div class="bg-secondary h-75 w-50 popover-body d-none" id="map_content">
                <div class="row h-100 w-100 m-0">
                    <div class="col-10">
                        <div id="map" class="h-100 w-100">
                        </div>
                    </div>
                    <div class="col-1 m-1">
                        <button onclick="close_map()" type="button" class="btn btn-floating text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-x-circle-fill float-end" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row" id="main_content">
                <div class="col">
                    <div class="row fs-5 ms-2">
                        <button onclick="show_map()" type="button" class="btn btn-dark btn-outline-success mb-3 w-25">
                            Place
                        </button>
                    </div>
                    <div class="row fs-5">
                        <p>Video type: <span class="border rounded"><?php
                                                                    echo $order['order_video_type'];
                                                                    ?></span></p>
                    </div>
                    <div class="row fs-5">
                        <p>Expire date: <span class="border rounded"><?php
                                                                        echo $order['order_expire_date'];
                                                                        ?></span></p>
                    </div>
                    <?php
                    if ($order['order_video_type'] === 'video') {
                        echo
                        "<form action='php/currentOrderInfo.inc.php' enctype='multipart/form-data' method='post' id='formFile'>
                    <div class='mb-3'>
                        <label for='formFile' class='form-label'>Upload your video file</label>
                        <input class='form-control w-50' type='file' name='file' accept='video/*' required>
                    </div>
                    <div class='row'>
                        <div class='col-2'>
                            <a href='currentOrders.php'><button type='button' class='btn btn-dark btn-outline-light mb-3'>Back</button></a>
                        </div>
                        <div class='col-2'>
                            <button name='accomplish_button' type='submit' class='btn btn-dark btn-outline-success mb-3'>Accomplish</button>
                        </div>
                </form>";
                    } else {
                        echo
                        "<form action='php/currentOrderInfo.inc.php' method='post' id='urlForm'>
                        <div class='mb-3'>
                            <input class='form-control w-50' type='text' name='urlInput' placeholder='m3u8 type url' required>
                        </div>
                        <div class='row'>
                            <div class='col-2'>
                                <a href='currentOrders.php'><button type='button' class='btn btn-dark btn-outline-light mb-3'>Back</button></a>
                            </div>
                            <div class='col-2'>
                                <button name='accomplish_button' type='submit' class='btn btn-dark btn-outline-success mb-3'>Accomplish</button>
                            </div>
                    </form>";
                    }
                    ?>
                    <form action="php/currentOrderInfo.inc.php" method="post" id="formCancel">
                        <div class="col-2">
                            <button onclick="cancelOrder()" type="button" class="btn btn-dark btn-outline-danger mb-3 float-end">Cancel</button>
                            <button name="cancel_button" type="submit" class="btn btn-dark btn-outline-danger mb-3 float-end d-none" id="cancelButton">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col" id="chatDiv">
                <div class="container bg-dark border border-1 border-white overflow-auto py-3" id="chatArea">
                    <?php $message_number = set_chat($order_id, 'specialist'); ?>
                </div>
                <div class="input-group justify-content-end px-5 my-1">
                    <input type="text" class="bg-dark text-white border border-1" placeholder="message" id="message_input">
                    <button onclick="sendMessage($('#message_input').val())" type="button" class="btn btn-dark btn-outline-success">Send</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Footer -->
    <?php
    include_once('footer.html');
    ?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- own js -->
    <script>
        var data_polygons =
            <?php
            echo json_encode($order['order_place_info']);
            ?>;
        var message_number = <?php
                                echo $message_number;
                                ?>
    </script>
    <script src="../../js/config.js"></script>
    <script src="../../js/specialist/currentOrderInfo.js"></script>
    <script src="../../js/nav.js"></script>
</body>

</html>