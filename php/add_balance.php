<?php
session_start();
ini_set('date.timezone','Asia/Taipei');

$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try {
    if (!isset($_POST['value'])) {
        header("Location: ../nav.php");
        exit();
    }
    if (empty($_POST['value']) ) {
        throw new Exception('Please enter value!');
    }
    if(!is_numeric($_POST['value'])){
        throw new Exception("Value can only contains numbers!");
    }
    if($_POST['value']  <= 0){
        throw new Exception("Value can only be positive!");
    }
    $value = (int)$_POST['value'] + $_SESSION['user_balance'];

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stat = $conn->prepare("UPDATE user SET user_balance = :user_balance WHERE user_account = :user_account");
    $stat->execute(array('user_balance' => $value, 'user_account' => $_SESSION['user_account']));
    $_SESSION['user_balance'] = $value;
    $stat = $conn->prepare("INSERT INTO transaction (user_accont, trader, tra_price, tra_time, tra_action) 
                                    VALUES (:user_account, :user_account, :tra_price, :tra_time, :tra_action)");
    $stat->execute(array('user_account'=>$_SESSION['user_account'], 'tra_price'=>'+'.$_POST['value'], 'tra_time' => date("Y-m-d H:i:s"), 'tra_action'=>'recharge'));
    echo <<<EOT
            <!DOCTYPE html>
            <html lang="en-us">
                <body>
                    <script>
                        alert("Add balance successfully.");
                        window.location.replace("../nav.php#home");
                    </script>
                </body>
            </html>
EOT;
    exit();
}catch (Exception $e) {
    $msg = $e->getMessage();
    echo <<<EOT
        <!DOCTYPE html>
        <html lang="en-us">
            <body>
                <script>
                alert("$msg");
                window.location.replace("../nav.php#home");
                </script>
            </body>
        </html>
EOT;
}
?>