<?php
require_once("php/auth.inc.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" integrity="sha512-aEe/ZxePawj0+G2R+AaIxgrQuKT68I28qh+wgLrcAJOz3rxCP+TwrK5SPN+E5I+1IQjNtcfvb96HDagwrKRdBw==" crossorigin="anonymous" />
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

    #dateTimeWrap {
        position: relative!important;
    }

    span.bi{
        color: white;
    }
    span.bi.bi-arrow-up-short:hover, span.bi.bi-arrow-down-short:hover, span.timepicker-hour:hover, span.timepicker-minute:hover{
        background-color: #198754!important;
    }
    td.day:hover:not(.disabled){
        background-color: #198754!important;
    }
    td.day.active{
        background-color: #198754!important;
    }
    td.day.disabled.today::before{
        border-bottom-color: white!important;
    }
    td.day.new:not(.disabled), td.day.old:not(.disabled){
        color: white!important;
    }
    td.hour:hover, td.minute:hover{
        background-color: #198754!important;
    }
    th.prev.disabled:hover, th.next.disabled:hover{
        background-color: transparent!important;
    }
    th.prev:hover:not(.disabled), th.next:hover:not(.disabled){
        background-color: #198754!important;
    }
</style>

<body>
    <?php
    include_once('header.php');
    ?>
    <!-- Main Content -->
    <div class="container-fluid p-3 mainContentDiv">
        <div class="container-fluid bg-dark text-light rounded mainContentBgDiv">
            <h1 class="text-center">Order</h1>
            <form action="php/order.inc.php" method="post">
                <div class="mb-3">
                    <select name="videoTypeSelection" id="videoTypeSelection" class="form-select w-50" aria-label="Default select example">
                        <option value="" selected>Choose video type</option>
                        <option value="video">Video</option>
                        <option value="livestream">Livestream</option>
                    </select>
                </div>
                <div class="mb-3">
                    <select name="videoDaySelection" id="videoDaySelection" class="form-select w-50 d-none" aria-label="Default select example">
                        <option value="" selected>Choose order length</option>
                        <option value="3 days">3 days</option>
                        <option value="7 days">7 days</option>
                        <option value="30 days">7 - 30 days</option>
                    </select>
                </div>
                <div style="overflow:hidden;">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8 d-none" id="dateTimeWrap">
                                <input name="dateTime" id="datetimepicker" class="mb-3 d-none">
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="mb-3">
                    <p>Price: <span class="border rounded">totalPrice</span></p>
                </div>-->
                <button name="submit" type="submit" class="btn btn-dark btn-outline-success mb-3">Place order</button>
            </form>
        </div>
    </div>
    <!-- Footer -->
    <?php
    include_once('footer.html');
    ?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <!-- Moment js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous"></script>
    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous">
    </script>
    <!-- Datetimepicker js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha512-GDey37RZAxFkpFeJorEUwNoIbkTwsyC736KNSYucu1WJWFK9qTdzYub8ATxktr6Dwke7nbFaioypzbDOQykoRg==" crossorigin="anonymous"></script>
    <!-- own js -->
    <script src="../../js/client/order.js"></script>
    <script src="../../js/nav.js"></script>
</body>

</html>