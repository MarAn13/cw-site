<?php
session_start();
if (!isset($_SESSION['specialist_place_of_operation']) &&
!isset($_SESSION['client_order_video_type'])){
    echo "Access denied";
    die();
}
?>
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
    <title>SiteMAP</title>
</head>
<style>
    html,
    body {
        height: 100%;
    }

    i {
        font-size: 8rem;
    }

    #map {
        height: 100%;
        width: 100%;
    }
</style>

<body>
    <div class="p-5 text-center bg-image bg-dark vw-100 vh-100">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="row h-100 w-100">
                <div class="col-9">
                    <div id="map">

                    </div>
                </div>
                <div class="col my-auto">
                    <div class="container p-0">
                        <div class="row mb-3">
                            <div class="col mb-3">
                                <h5 class="text-white">Choose your place of operation</h1>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col mb-3">
                                <input type="text" placeholder="Address" id="address">
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-dark btn-outline-success border-1 text-white" id="search">Search</button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <button onclick="undoDraw()" class="btn btn-floating text-secondary"
                                    id="undoButton"><svg xmlns="http://www.w3.org/2000/svg" width="1.5rem"
                                        height="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z" />
                                        <path
                                            d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z" />
                                    </svg></button>
                            </div>
                            <div class="col">
                                <button onclick="redoDraw()" class="btn btn-floating text-secondary"
                                    id="redoButton"><svg xmlns="http://www.w3.org/2000/svg" width="1.5rem"
                                        height="1.5rem" fill="currentColor" class="bi bi-arrow-clockwise"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                        <path
                                            d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                    </svg></button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <button onclick="clearMap()"
                                    class="btn btn-dark btn-outline-success border-1 text-white">Clear</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button onclick="choosePlaceOfOperation()" class="btn btn-dark btn-outline-success border-1 text-white">Choose</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- own js -->
    <script src="../js/choose.js"></script>
</body>

</html>