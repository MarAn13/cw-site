<?php
session_start();
if (!isset($_SESSION["specialist"])){
    echo "Access denied";
    die();
}
if (isset($_SESSION['specialist_place_of_operation'])){
    header("location: ../choose.php");
    die();
}