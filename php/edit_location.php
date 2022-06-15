<?php
session_start();

$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try {
    if (!isset($_POST['latitude']) || !isset($_POST['longitude'])) {
        header("Location: ../nav.php");
        exit();
    }
    if (empty($_POST['latitude']) || empty($_POST['longitude'])) {
        throw new Exception('Please input latitude and longitude!');
    }
    if(!is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude'])){
        throw new Exception("latitude and longitude can only contains numbers!");
    }
    if((float)$_POST['latitude']>90.0 || (float)$_POST['latitude']<-90){
        throw new Exception('latitude must between -90~90');
    }
    if((float)$_POST['longitude']>180.0 || (float)$_POST['longitude']<-180){
        throw new Exception('longitude must between -180~180');
    }
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stat = $conn->prepare("UPDATE user SET user_location = ST_GeometryFromText(:location) WHERE user_account = :user_account");
    $stat->execute(array('location' => 'POINT(' . $longitude . ' ' . $latitude . ')', 'user_account' => $_SESSION['user_account']));
    $_SESSION['user_latitude'] = $latitude;
    $_SESSION['user_longitude'] = $longitude;
    echo <<<EOT
            <!DOCTYPE html>
            <html lang="en-us">
                <body>
                    <script>
                        alert("Update locaion successfully.");
                        window.location.replace("../nav.php");
                    </script>
                </body>
            </html>
EOT;
    exit();
}catch (Exception $e) {
    $msg = $e->getMessage();
    $_SESSION['model'] = "#home";
    echo <<<EOT
        <!DOCTYPE html>
        <html lang="en-us">
            <body>
                <script>
                alert("$msg");
                window.location.replace("../nav.php");
                </script>
            </body>
        </html>
EOT;
}
?>