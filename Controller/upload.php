<?php
include('../Model/model.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');
$time = date("Y-m-d H:i:s");
if (isset($_POST) && !empty($_FILES['file'])) {
    $duoi = explode('.', $_FILES['file']['name']);
    $duoi = $duoi[(count($duoi) - 1)];
    if ($duoi === 'jpg' || $duoi === 'png' || $duoi === 'gif') {
        $path = uniqid('photo-',true) . '.' . $duoi;
        if (move_uploaded_file($_FILES['file']['tmp_name'], '../media/' . $path)) {
            $data =  new Model;
            $data->uploadPhoto($path,$time);
        } 
        else {
            return false;
        }
    } 
    else {
        return false;
    }
} 
else {
    return false;
}