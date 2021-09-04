<?php
require_once("php/auth.inc.php");
require_once("../php/sql.php");

if (!isset($_POST['order_info'])) {
    echo "Access denied";
    die();
}
$order_id = trim(htmlspecialchars($_POST['order_info']));
if (admin_invalid_reported_order($order_id)) {
    header("location: orderHistory.php");
    die();
}
$order = admin_reported_order_info($order_id);
if (empty($order['order_completion_date'])) {
    $order['order_completion_date'] = '-';
}
$order['order_place_info'] = to_array($order['order_place_info']);
if ($order['order_video_type'] === 'video') {
    $video_source = "../../" . $order['order_video_source'];
} elseif ($order['order_video_type'] === 'livestream') {
    $video_source = $order['order_video_source'];
}
$video_source_type = explode('.', $video_source);
$video_source_type = end($video_source_type);
if ($order['order_video_type'] === 'video') {
    $video_source_type = "video/" . $video_source_type;
} elseif ($order['order_video_type'] === 'livestream') {
    $video_source_type = "application/x-mpegURL";
}
if (empty($order['order_report_message'])) {
    $report_message = false;
} else {
    $report_message = $order['order_report_message'];
}
$_SESSION['admin_current_order_id'] = $order_id;
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
    <link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet">
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

    #star1,
    #star2,
    #star3,
    #star4,
    #star5 {
        cursor: pointer;
    }

    textarea {
        resize: none;
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
                <div class="row fs-5">
                    <p>Completion date: <span class="border rounded"><?php
                                                                        echo $order['order_completion_date'];
                                                                        ?></span></p>
                </div>
                <div class="row">
                    <div class="col-3 border-1 border-top border-bottom border-light text-center my-auto">
                        <?php
                        if ($report_message !== false) {
                            echo
                            "<div class='row mx-5 mt-3'>
                            <textarea rows='5' class='bg-dark text-white' readonly>" . $report_message . "</textarea>
                            </div>
                            ";
                        }
                        ?>
                        <form action='php/orderInfo.inc.php' method='post'>
                            <div class='row'>
                                <div class='col'>
                                    <button name='approve_button' type='submit' class='btn btn-dark btn-outline-success my-3'>Approve</button>
                                </div>
                                <div class='col'>
                                    <button name='decline_button' type='submit' class='btn btn-dark btn-outline-danger my-3'>Decline</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-5">
                    <video-js id="my-video" class="video-js vjs-16-9 vjs-fill vjs-big-play-centered" controls preload="auto">
                            <!--<source src="MY_VIDEO.webm" type="video/webm" />-->
                            <p class="vjs-no-js">
                                To view this video please enable JavaScript, and consider upgrading to a
                                web browser that
                                <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                            </p>
                        </video-js>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                        <a href="reportHistory.php"><button type="button" class="btn btn-dark btn-outline-light mb-3">Back</button></a>
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
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <!-- Video js -->
    <script src="https://vjs.zencdn.net/7.11.4/video.min.js"></script>
    <!-- own js -->
    <script>
        var data_polygons =
            <?php
            echo json_encode($order['order_place_info']);
            ?>;
    </script>
    <script src="../../js/admin/orderInfo.js"></script>
    <script src="../../js/nav.js"></script>
    <script>
        let video_source = "<?php echo $video_source; ?>";
        let video_source_type = "<?php echo $video_source_type; ?>";
        var player = playerSet(video_source, video_source_type);
    </script>
</body>

</html>