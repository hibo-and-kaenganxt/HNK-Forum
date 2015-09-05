<?php
if (!isset($_POST['username']) || !isset($_POST['password']) ) {
    echo json_encode(array("error" => "notSetParameter"));
    exit();
}
session_start();
$pass = hash("sha256", $_POST['password']);
$sql = "";

