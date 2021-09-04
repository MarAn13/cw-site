<?php
session_start();
if (!isset($_SESSION['specialist_place_of_operation']) &&
!isset($_SESSION['client_order_video_type'])){
    echo "Access denied";
    die();
}
$data = file_get_contents('php://input');
$data = json_decode($data);

require_once "sql.php";

if(invalid_polygons($data)){
    echo "error";
    die();
}
$data = to_pg_array($data);
$data = preg_replace(array('/\)\),/', '/^\(/', '/\)$/'), array('))","', '{"', '"}'), $data);
if (isset($_SESSION['specialist_place_of_operation'])){
update_specialist_place_of_operation($_SESSION['specialist'], $data);
unset($_SESSION['specialist_place_of_operation']);
echo "success";
die();
}else{
    $_SESSION['client_order_polygon_data'] = $data;
    echo "client_success";
    die();
}
