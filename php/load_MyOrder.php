<?php
session_start();
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

$conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("Select * from `orders` WHERE user_account=:user_account order by OID");
$stmt->execute(array('user_account' => $_SESSION['user_account']));

if ($stmt->rowCount()){
    $information = $stmt->fetchAll();
    echo  json_encode($information );
}
?>
