<?php
session_start();
if (!isset($_SESSION["client"])){
    echo "Access denied";
    die();
}