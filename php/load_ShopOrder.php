<?php
session_start();
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

$conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("Select * from `shop` WHERE shop_owner =:user_account");
$stmt->execute(array('user_account' => $_SESSION['user_account']));

if ($stmt->rowCount()==0){
    exit();
}

$stmt = $conn->prepare("Select * from `order` WHERE shop_name=:shop_name order by OID");
$stmt->execute(array('shop_name' => $_SESSION['shop_name']));

if ($stmt->rowCount()){
    $information = $stmt->fetchAll();
    echo  json_encode($information);
}

?>
