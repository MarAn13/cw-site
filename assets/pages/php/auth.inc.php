<?php
session_start();
if (!isset($_SESSION["client"]) && !isset($_SESSION["specialist"]) && !isset($_SESSION["admin"])){
    echo "Access denied";
    die();
}