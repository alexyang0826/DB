<?php
session_start();

$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';

$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$missed = null;

function bad_format(){
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_amount = $_POST['product_amount'];

    if (!preg_match("#^[a-zA-Z0-9_ .\-]+$#", $product_name)) {
        $GLOBALS['missed'] = "product_name";
        return true;
    }
    else if(!preg_match("/^(?:[1-9][0-9]*|0)$/",$product_price)){
        $GLOBALS['missed'] = "product_price";
        return true;
    }
    else if(!preg_match("/^(?:[1-9][0-9]*|0)$/",$product_amount)){
        $GLOBALS['missed'] = "product_amount";
        return true;
    }
    else{
        return false;
    }
}

function readimg(){
    $file = fopen($_FILES["myFile"]["tmp_name"], "rb");
    $fcontent = fread($file, filesize($_FILES["myFile"]["tmp_name"]));
    fclose($file);
    return base64_encode($fcontent);
}

function read_picture_type(){
    //read img file type
    return $_FILES["myFile"]["type"];

}

try{
    if(!isset($_POST['product_name'])||!isset($_POST['product_price'])||!isset($_POST['product_amount'])||!isset($_FILES['myFile'])){
        header("Location: nav.php");
        exit();
    }
    if(empty($_POST['product_name']) ||empty($_POST['product_price']) ||empty($_POST['product_amount']) ||($_FILES['myFile']['size']==0)){
        throw new Exception('Please input all the field!');
    }
    else if(bad_format()){
        throw new Exception("Wrong format:".$GLOBALS['missed']);
    }

    $product_img = readimg();
    $product_img_type = read_picture_type();

    $product_name = $_POST['product_name'];
    $stmt = $conn->prepare("SELECT product_name from product where product_name = :product_name");
    $stmt->execute(array('product_name' => $product_name));

    if ($stmt->rowCount() != 0) {
        throw new Exception("Product name has been register!!");
    }
    else {
        $stmt = $conn->prepare("INSERT INTO product (product_name, product_price,product_amount,product_img,product_img_type,product_shop) 
                        VALUES (:product_name,:product_price,:product_amount,:product_img,:product_img_type,:shop_name)");

        $stmt->execute(array('product_name'=>$_POST['product_name'], 'product_price'=>$_POST['product_price'],
            'product_amount'=>$_POST['product_amount'], 'product_img'=>$product_img,
            'product_img_type'=>$product_img_type, 'shop_name'=>$_SESSION['shop_name']
        ));
        echo <<<EOT
            <!DOCTYPE html>
            <html lang="en-us">
                <body>
                    <script>
                        alert("Start a business successfully.");
                        window.location.replace("../nav.php");
                    </script>
                </body>
            </html>
EOT;
        exit();
    }

}
catch (Exception $e) {
    $msg = $e->getMessage();
    echo <<<EOT
        <!DOCTYPE html>
        <html lang="en-us">
            <body>
                <script>
                alert("$msg");
                window.location.replace("../nav.php#menu1");
                </script>
            </body>
        </html>
EOT;
}
?>
