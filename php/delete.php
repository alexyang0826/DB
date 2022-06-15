<?php
session_start();

$PID = $_POST['PID'];
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try {
    $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = $db->prepare("DELETE FROM product WHERE PID = :PID");
    $sql->execute(array('PID' => $PID));
    echo <<<EOT
            <!DOCTYPE html>
            <html lang="en-us">
                <body>
                    <script>
                        alert("Delete product successfully.");
                        window.location.replace("../nav.php#shop");
                    </script>
                </body>
            </html>
EOT;
    exit();
}
catch (Exception $e) {
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