<?php
session_start();

$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try {
    if (!isset($_POST['shop_name']) || !isset($_POST['shop_category']) ||
        !isset($_POST['shop_latitude']) || !isset($_POST['shop_longitude'])) {
        header("Location: ../nav.php");
        exit();
    }
    if (empty($_POST['shop_name']) || empty($_POST['shop_category']) ||
        empty($_POST['shop_latitude']) || empty($_POST['shop_longitude'])) {
        throw new Exception('Please input all the field!'); //is all the field filled?
    }

    $shop_name = $_POST['shop_name'];
    $shop_category = $_POST['shop_category'];
    $shop_latitude = $_POST['shop_latitude'];
    $shop_longitude = $_POST['shop_longitude'];

    if (!preg_match("#^[a-zA-Z0-9_ .]+$#", $shop_name) || !preg_match("#^[a-zA-Z0-9_ .]+$#", $shop_category)) {
        throw new Exception('shop_name or shop_category illegal format!');
    }
    if (!is_numeric($shop_latitude) || !is_numeric($shop_longitude)) {
        throw new Exception('latitude and longitude can only contains numbers!');
    }
    if((float)$shop_latitude>90.0 || (float)$shop_latitude<-90){
        throw new Exception('latitude must between -90~90');
    }
    if((float)$shop_longitude>180.0 || (float)$shop_longitude<-180){
        throw new Exception('longitude must between -180~180');
    }
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT shop_name from shop where shop_name = :shop_name");
    $stmt->execute(array('shop_name' => $shop_name));
    if ($stmt->rowCount() != 0) {
        throw new Exception("Shop name has been register!!");
    } else {
        $stmt = $conn->prepare("INSERT INTO shop(shop_name, shop_location, shop_category, shop_owner) values (:shop_name, ST_GeometryFromText(:shop_location), :shop_category, :shop_owner)");
        $stmt->execute(array('shop_name' => $shop_name, 'shop_category' => $shop_category, 'shop_location' => 'POINT(' . $shop_longitude . ' ' . $shop_latitude . ')', 'shop_owner' => $_SESSION['user_account']));
        $_SESSION['user_type'] = "manger";
        $stmt = $conn->prepare("UPDATE user SET user_type = 'manger' WHERE user_account = :user_account");
        $stmt->execute(array('user_account'=>$_SESSION['user_account']));
        $_SESSION['shop_name'] = $shop_name;
        $_SESSION['shop_longitude'] = $shop_longitude;
        $_SESSION['shop_latitude'] = $shop_latitude;
        $_SESSION['shop_category'] = $shop_category;
        echo <<<EOT
            <!DOCTYPE html>
            <html lang="en-us">
                <body>
                    <script>
                        alert("Start a business successfully.");
                        window.location.replace("../nav.php#shop");
                    </script>
                </body>
            </html>
            EOT;
        exit();
    }
}catch (Exception $e) {
    $msg = $e->getMessage();
    echo <<<EOT
        <!DOCTYPE html>
        <html lang="en-us">
            <body>
                <script>
                alert("$msg");
                window.location.replace("../nav.php#shop");
                </script>
            </body>
        </html>
EOT;
}
?>