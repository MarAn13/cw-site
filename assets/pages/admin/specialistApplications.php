<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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

    .orderInfoDivBlur{
        filter: blur(3px);
        opacity: .5;
        transform: scale(.98);
        box-shadow: none;
        transition: .5s all ease-in-out;
    }
    .page-link:hover{
        background-color: #157347!important;
    }
    .active.page-link{
        background-color: #157347!important;
    }
</style>

<body>
    <?php
    include_once('header.html');
    ?>
    <!-- Main Content -->
    <div class="container-fluid p-3 mainContentDiv">
        <div class="container-fluid bg-dark text-light rounded fs-5 mainContentBgDiv">
            <h1 class="text-center">Specialist applications</h1>
            <a href="" class="text-decoration-none text-light">
                <div class="row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv">
                    <div class="col border">
                        <span>Id</span>
                    </div>
                    <div class="col border">
                        <span>Specialist</span>
                    </div>
                    <div class="col-4 border">
                        <span>Place</span>
                    </div>
                    <div class="col border">
                        <span>VideoType</span>
                    </div>
                    <div class="col border">
                        <span>Start date</span>
                    </div>
                    <div class="col border">
                        <span>End date</span>
                    </div>
                    <div class="col border">
                        <span>Status</span>
                    </div>
                </div>
            </a>
            <a href="" class="text-decoration-none text-light">
                <div class="row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv">
                    <div class="col border">
                        <span>Id</span>
                    </div>
                    <div class="col border">
                        <span>Specialist</span>
                    </div>
                    <div class="col-4 border">
                        <span>Place</span>
                    </div>
                    <div class="col border">
                        <span>VideoType</span>
                    </div>
                    <div class="col border">
                        <span>Start date</span>
                    </div>
                    <div class="col border">
                        <span>End date</span>
                    </div>
                    <div class="col border">
                        <span>Status</span>
                    </div>
                </div>
            </a>
            <a href="" class="text-decoration-none text-light">
                <div class="row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv">
                    <div class="col border">
                        <span>Id</span>
                    </div>
                    <div class="col border">
                        <span>Specialist</span>
                    </div>
                    <div class="col-4 border">
                        <span>Place</span>
                    </div>
                    <div class="col border">
                        <span>VideoType</span>
                    </div>
                    <div class="col border">
                        <span>Start date</span>
                    </div>
                    <div class="col border">
                        <span>End date</span>
                    </div>
                    <div class="col border">
                        <span>Status</span>
                    </div>
                </div>
            </a>
            <a href="" class="text-decoration-none text-light">
                <div class="row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv">
                    <div class="col border">
                        <span>Id</span>
                    </div>
                    <div class="col border">
                        <span>Specialist</span>
                    </div>
                    <div class="col-4 border">
                        <span>Place</span>
                    </div>
                    <div class="col border">
                        <span>VideoType</span>
                    </div>
                    <div class="col border">
                        <span>Start date</span>
                    </div>
                    <div class="col border">
                        <span>End date</span>
                    </div>
                    <div class="col border">
                        <span>Status</span>
                    </div>
                </div>
            </a>
            <a href="" class="text-decoration-none text-light">
                <div class="row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv">
                    <div class="col border">
                        <span>Id</span>
                    </div>
                    <div class="col border">
                        <span>Specialist</span>
                    </div>
                    <div class="col-4 border">
                        <span>Place</span>
                    </div>
                    <div class="col border">
                        <span>VideoType</span>
                    </div>
                    <div class="col border">
                        <span>Start date</span>
                    </div>
                    <div class="col border">
                        <span>End date</span>
                    </div>
                    <div class="col border">
                        <span>Status</span>
                    </div>
                </div>
            </a>
            <a href="" class="text-decoration-none text-light">
                <div class="row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv">
                    <div class="col border">
                        <span>Id</span>
                    </div>
                    <div class="col border">
                        <span>Specialist</span>
                    </div>
                    <div class="col-4 border">
                        <span>Place</span>
                    </div>
                    <div class="col border">
                        <span>VideoType</span>
                    </div>
                    <div class="col border">
                        <span>Start date</span>
                    </div>
                    <div class="col border">
                        <span>End date</span>
                    </div>
                    <div class="col border">
                        <span>Status</span>
                    </div>
                </div>
            </a>
            <a href="" class="text-decoration-none text-light">
                <div class="row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv">
                    <div class="col border">
                        <span>Id</span>
                    </div>
                    <div class="col border">
                        <span>Specialist</span>
                    </div>
                    <div class="col-4 border">
                        <span>Place</span>
                    </div>
                    <div class="col border">
                        <span>VideoType</span>
                    </div>
                    <div class="col border">
                        <span>Start date</span>
                    </div>
                    <div class="col border">
                        <span>End date</span>
                    </div>
                    <div class="col border">
                        <span>Status</span>
                    </div>
                </div>
            </a>
            <a href="" class="text-decoration-none text-light">
                <div class="row shadow bg-gradient border rounded w-75 mx-auto mb-4 orderInfoDiv">
                    <div class="col border">
                        <span>Id</span>
                    </div>
                    <div class="col border">
                        <span>Specialist</span>
                    </div>
                    <div class="col-4 border">
                        <span>Place</span>
                    </div>
                    <div class="col border">
                        <span>VideoType</span>
                    </div>
                    <div class="col border">
                        <span>Start date</span>
                    </div>
                    <div class="col border">
                        <span>End date</span>
                    </div>
                    <div class="col border">
                        <span>Status</span>
                    </div>
                </div>
            </a>
            <nav class="float-end pt-4 d-none" aria-label="Page navigation example">
                <ul class="pagination">
                  <li class="page-item">
                    <a class="page-link bg-dark text-light" href="#" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>
                  <li class="page-item"><a class="page-link bg-dark text-light" href="#">1</a></li>
                  <li class="page-item" aria-current="page"><a class="page-link bg-dark text-light active" href="#">2</a></li>
                  <li class="page-item"><a class="page-link bg-dark text-light" href="#">3</a></li>
                  <li class="page-item">
                    <a class="page-link bg-dark text-light" href="#" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>
                </ul>
              </nav>
        </div>
    </div>
    <!-- Footer -->
    <?php
include_once('footer.html');
    ?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
        integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <!-- own js -->
    <script src="../../js/admin/specialistApplications.js"></script>
    <script src="../../js/nav.js"></script>
</body>

</html>