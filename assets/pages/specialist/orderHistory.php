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
    <title>Hello, world!</title>
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

    .orderInfoDiv:hover {
        background-color: #157347;
        transition: .3s all ease-in-out;
    }

    .orderInfoDivBlur {
        filter: blur(3px);
        opacity: .5;
        transform: scale(.98);
        box-shadow: none;
        transition: .5s all ease-in-out;
    }

    .page-link:hover {
        background-color: #157347 !important;
    }

    .active.page-link {
        background-color: #157347 !important;
    }
</style>

<body>
    <?php
    include_once('header.php');
    ?>
    <!-- Main Content -->
    <div class="container-fluid p-3 mainContentDiv">
        <div class="container-fluid bg-dark text-light rounded fs-5 mainContentBgDiv">
            <h1 class="text-center">Order desk</h1>
            <?php
            require_once("../php/sql.php");

            $total_pages = specialist_get_history_total_pages($_SESSION['specialist']);
            if (isset($_GET['page_num']) && $_GET['page_num'] > 0 && $_GET['page_num'] <= $total_pages) {
                $current_page = $_GET['page_num'];
            } else {
                $current_page = 1;
            }
            specialist_show_order_history($_SESSION['specialist'], ($current_page - 1));
            ?>
            <div>
                <?php
                echo
                "<nav class='float-end' aria-label='Page navigation example'>
                    <ul class='pagination'>";
                if ($current_page != 1) {
                    echo
                    "<li class='page-item'>
                            <a class='page-link bg-dark text-light' href='?page_num=1' aria-label='Previous'>
                                <span aria-hidden='true'>&laquo;</span>
                            </a>
                        </li>
                        <li class='page-item'>
                            <a class='page-link bg-dark text-light' href='?page_num=" . ($current_page - 1) . "' aria-label='Previous'>
                                <span aria-hidden='true'>&lsaquo;</span>
                            </a>
                        </li>";
                }
                if ($total_pages > 3 && $current_page > 2) {
                    echo
                    "<li class='page-item'>
                        <a id='page_" . ($current_page - 1) . "' class='page-link bg-dark text-light' href='?page_num=" . ($current_page - 1) . "'>" . ($current_page - 1) . "
                        </a>
                        </li>
                        <li class='page-item'>
                        <a id='page_" . ($current_page) . "' class='page-link bg-dark text-light' href='?page_num=" . ($current_page) . "'>" . ($current_page) . "
                        </a>
                        </li>";
                    if ($current_page != $total_pages) {
                        echo
                        "<li class='page-item'>
                        <a id='page_" . ($current_page + 1) . "' class='page-link bg-dark text-light' href='?page_num=" . ($current_page + 1) . "'>" . ($current_page + 1) . "
                        </a>
                        </li>";
                    }
                } elseif ($total_pages != 1) {
                    if ($total_pages <= 3) {
                        $page_range = $total_pages;
                    } else {
                        $page_range = 3;
                    }
                    for ($i = 1; $i <= $page_range; $i++) {
                        echo
                        "<li class='page-item'>
                        <a id='page_" . ($i) . "' class='page-link bg-dark text-light' href='?page_num=" . ($i) . "'>" . ($i) . "
                        </a>
                        </li>";
                    }
                }
                if ($current_page < $total_pages) {
                    echo
                    "
                    <li class='page-item'>
                            <a class='page-link bg-dark text-light' href='?page_num=" . ($current_page + 1) . "' aria-label='Previous'>
                                <span aria-hidden='true'>&rsaquo;</span>
                            </a>
                        </li>
                    <li class='page-item'>
                            <a class='page-link bg-dark text-light' href='?page_num=" . $total_pages . "' aria-label='Next'>
                                <span aria-hidden='true'>&raquo;</span>
                            </a>
                        </li>";
                }
                echo
                "</ul>
                </nav>";
                if ($total_pages > 1){
                echo
                "<script>document.getElementById('page_" . $current_page . "').classList.add('active');</script>";
                }
                ?>
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
    <script src="../../js/specialist/orderHistory.js"></script>
    <script src="../../js/nav.js"></script>
    <?php
    if (isset($_GET['operation'])) {
        echo
        "<script>
    $(document).ready(function(){";
        if ($_GET['operation'] == "success") {
            echo "alert('Order successfully accomplished');";
        }
        echo
        "});</script>";
    }
    ?>
</body>

</html>