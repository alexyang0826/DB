<?php

session_start();
$_SESSION['Authenticated'] = false;

$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try {
    if (!isset($_POST['Account']) || !isset($_POST['password'])) {
        header("Location: ../index.php");
        exit();
    }
    if (empty($_POST['Account']) || empty($_POST['password'])) {
        throw new Exception('Please input Account and password!');
    }
    $Account = $_POST['Account'];
    $password = $_POST['password'];
    if (!preg_match("#^[a-zA-Z0-9]+$#", $Account) || !preg_match("#^[a-zA-Z0-9]+$#", $password)) {
        throw new Exception('Account and password can only contains numbers and letters!');
        //Account and password only contains numbers and letters(sql injection)
    }
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT user_account, user_password, user_name, user_phone, ST_Astext(user_location) as user_location, user_type, user_balance, user_salt from user where user_account = :user_account");
    $stmt->execute(array('user_account' => $Account));
    if ($stmt->rowCount() == 0) {
        throw new Exception('Login failed!!');
    } else {
        $row = $stmt->fetch();
        if ($row['user_password'] == hash('sha256', $row['user_salt'].$password)) {
            $_SESSION['user_account'] = $row['user_account'];
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['user_phone'] = $row['user_phone'];
            $sub = substr($row['user_location'], 6, -1);
            $point = explode(' ', $sub);
            $_SESSION['user_longitude'] = $point[0];
            $_SESSION['user_latitude'] = $point[1];
            $_SESSION['user_type'] = $row['user_type'];
            if($row['user_type'] == "user"){
                $_SESSION['shop_name'] = "macdonald";
                $_SESSION['shop_longitude'] = "121.00028167648875";
                $_SESSION['shop_latitude'] = "24.78472733371133";
                $_SESSION['shop_category'] = "fast food";
            }else{
                $stmt = $conn->prepare("SELECT shop_name, ST_Astext(shop_location) as shop_location, shop_category FROM shop WHERE shop_owner = :user_account");
                $stmt->execute(array('user_account'=>$row['user_account']));
                $row2 = $stmt->fetch();
                $_SESSION['shop_name'] = $row2['shop_name'];
                $sub = substr($row2['shop_location'], 6, -1);
                $point = explode(' ', $sub);
                $_SESSION['shop_longitude'] = $point[0];
                $_SESSION['shop_latitude'] = $point[1];
                $_SESSION['shop_category'] = $row2['shop_category'];
            }
            $_SESSION['user_balance'] = $row['user_balance'];
            $_SESSION['Authenticated'] = true;
            header("Location: ../nav.php#home");
            exit();
        } else {
            throw new Exception('Login failed!!');
        }
    }

} catch (Exception $e) {
    $msg = $e->getMessage();
    session_unset();
    session_destroy();
    echo <<<EOT
        <!DOCTYPE html>
        <html lang="en-us">
            <body>
                <script>
                alert("$msg");
                window.location.replace("../index.php");
                </script>
            </body>
        </html>
EOT;
}
