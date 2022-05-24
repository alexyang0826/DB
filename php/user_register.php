<?php
session_start();
$_SESSION['Authenticated'] = false;

$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try {
    if (!isset($_POST['name']) || !isset($_POST['phonenumber']) ||
        !isset($_POST['Account']) || !isset($_POST['password']) ||
        !isset($_POST['re-password']) || !isset($_POST['latitude']) || !isset($_POST['longitude'])) {
        header("Location: ../sign-up.php");
        exit();
    }
    if (empty($_POST['name']) || empty($_POST['phonenumber']) ||
        empty($_POST['Account']) || empty($_POST['password']) ||
        empty($_POST['re-password']) || empty($_POST['latitude']) || empty($_POST['longitude'])) {
        throw new Exception('Please input all the field!'); //is all the field filled?
    }
    $name = $_POST['name'];
    $phonenumber = $_POST['phonenumber'];
    $Account = $_POST['Account'];
    $password = $_POST['password'];
    $re_password = $_POST['re-password'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    if (!preg_match("#^[a-zA-Z0-9]+$#", $Account) || !preg_match("#^[a-zA-Z0-9]+$#", $password)) {
        throw new Exception('Account and password can only contains numbers and letters!');
        //Account and password only contains numbers and letters
    }
    if (!is_numeric($latitude) || !is_numeric($longitude)) {
        throw new Exception('latitude and longitude can only contains numbers!');
        //latitude and longitude only contains numbers and letters
    }
    if (mb_strlen($phonenumber) != 10) {
        throw new Exception('phone number needs 10 numbers.');
        //phone number needs 10 numbers.
    }
    if ($password != $re_password) {        //password equal re_password or not
        throw new Exception('Password not equal!');
    }
    if((float)$latitude>90.0 || (float)$latitude<-90){
        throw new Exception('latitude must between -90~90');
    }
    if((float)$longitude>180.0 || (float)$longitude<-180){
        throw new Exception('longitude must between -180~180');
    }
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT user_account from user where user_account = :user_account");
    $stmt->execute(array('user_account' => $Account));

    if ($stmt->rowCount() != 0) {   //check if account has been register
        throw new Exception("Account has been register!!");
    } else {
        $salt = strval(rand(1000,9999));
        $hashvalue = hash('sha256', $salt.$password);
        $stmt = $conn->prepare("INSERT INTO
            user (user_account, user_password, user_name, user_phone, user_location, user_type, user_balance, user_salt)
            values (:Account, :password,  :name, :phonenumber, ST_GeometryFromText(:location), :user_type, :user_balance, :user_salt)");
        $stmt->execute(array('Account'=>$Account, 'password'=>$hashvalue, 'phonenumber'=>$phonenumber,
            'name'=>$name,'location'=>'POINT('. $longitude . ' ' . $latitude . ')', 'user_type'=>'user', 'user_balance'=>0, 'user_salt'=>$salt));
        echo <<<EOT
            <!DOCTYPE html>
            <html lang="en-us">
                <body>
                    <script>
                        alert("Create an account successfully.");
                        window.location.replace("../index.php");
                    </script>
                </body>
            </html>
EOT;
    exit();
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
                window.location.replace("../sign-up.php");
                </script>
            </body>
        </html>
EOT;
}
?>