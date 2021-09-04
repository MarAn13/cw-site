<?php
if (isset($_FILES["file"])){
$orderId = "1";
$fileTmpLoc = $_FILES["file"]["tmp_name"];
$fileError = $_FILES["file"]["error"];
$fileName = $_FILES["file"]["name"];
$fileExt = explode('.', $fileName);
$fileActualExt = strtolower(end($fileExt));
$fileFinishName = $orderId.'.'.$fileActualExt;
if ($fileError !== 0){
    echo "ErrorFile: ".$fileError;
    exit();
}
$moveStatus = move_uploaded_file($fileTmpLoc, "../../../media/".$fileFinishName);
if ($moveStatus){
    echo "success moving";
}else{
    echo "error moving";
}
}else{
    header('Location: currentOrder.php');
}