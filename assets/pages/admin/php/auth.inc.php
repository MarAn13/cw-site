<?php
session_start();
if (!isset($_SESSION["admin"])){
    echo "Access denied";
    die();
}