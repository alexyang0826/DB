<?php
session_start();
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

try {
    if (empty($_POST['product_price']) || empty($_POST['product_amount'])) {
        $error="";
        if (empty($_POST['product_price'])){
            $error=$error."Price".'\n';
        }
        else{
            $error=$error."Quantity".'\n';
        }
        throw new Exception('Please fill :'."$error");
    }
    if (!ctype_digit($_POST['product_price']) || !ctype_digit($_POST['product_amount']) || $_POST['product_price'] < 0 || $_POST['product_amount'] < 0) {
        throw new Exception("Wrong formatt !! Must be all natural numbers !");
    }
    else {

        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $PID=$_POST['PID'];
        $product_price=$_POST['product_price'];
        $product_amount=$_POST['product_amount'];

        $stmt = $conn->prepare("UPDATE product SET product_price=:product_price, product_amount=:product_amount where PID=:PID");
        $stmt->execute(array( 'product_price' => $product_price, 'product_amount' => $product_amount, 'PID' => $PID));
        echo <<<EOT
            <!DOCTYPE html>
            <html lang="en-us">
                <body>
                    <script>
                        alert("Edit product successfully.");
                        window.location.replace("../nav.php#shop");
                    </script>
                </body>
            </html>
EOT;
        exit();
    }
}
catch(Exception $e){

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
