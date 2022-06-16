<?php
session_start();
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbuserpassword = 'admin';

$action = $_REQUEST['tra_action'];
if($action == "All"){
    $action = "%%";
}
$conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare("Select * from transaction WHERE user_accont=:account AND tra_action LIKE :action order by tra_time DESC");
$stmt->execute(array('account' => $_SESSION['user_account'], 'action'=>$action));
if ($stmt->rowCount()==0){
    exit();
}
else{
    $data = $stmt->fetchAll();
    echo  json_encode($data);
}
?>