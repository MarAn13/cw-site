<?php
require_once("php/auth.inc.php");
require_once("../php/sql.php");

if (!isset($_POST['order_info'])) {
    echo "Access denied";
    die();
}
$order_id = trim(htmlspecialchars($_POST['order_info']));
if (specialist_invalid_order($order_id)) {
    header("location: orderDesk.php");
    die();
}
$order = specialist_order_info($_SESSION['specialist'], $order_id);
if (empty($order['order_completion_date'])) {
    $order['order_completion_date'] = '-';
}
$order['order_place_info'] = to_array($order['order_place_info']);
$order['specialist_place_info'] = to_array($order['specialist_place_info']);
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
</style>

<body>
    <?php
    include_once('header.php');
    ?>
    <!-- Main Content -->
    <div class="container-fluid p-3 mainContentDiv">
        <div class="container-fluid bg-dark text-light rounded mainContentBgDiv">
            <h1 class="text-center">Order info</h1>
            <div class="row h-75">
                <div class="col">
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
                    <form action="php/orderInfo.inc.php" method="post">
                        <div class="row">
                            <div class="col-2">
                                <a href="orderDesk.php"><button type="button" class="btn btn-dark btn-outline-light">Back</button></a>
                            </div>
                            <div class="col-2">
                                <input name="order_id" type="text" class="d-none" value="<?php
                                echo $order_id;
                                ?>">
                                <button name="accept_button" type="submit" class="btn btn-dark btn-outline-success">Accept</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col">
                    <div id="map" class="h-100 w-100">
                    </div>
                    <!--<div id="legend">
                        <h3>Legend</h3>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php
    include_once('footer.html');
    ?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <!-- own js -->
    <script>
        var client_data_polygons =
            <?php
            echo json_encode($order['order_place_info']);
            ?>;
        var specialist_data_polygons =
            <?php
            echo json_encode($order['specialist_place_info']);
            ?>;
    </script>
    <script src="../../../config.js"></script>
    <script src="../../js/specialist/orderInfo.js"></script>
    <script src="../../js/nav.js"></script>
</body>

</html>