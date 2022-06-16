<?php
session_start();
$dbservername = 'localhost';
$dbname = 'db';
$dbusername = 'admin';
$dbpassword = 'admin';
$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//echo $_POST['detail'];
$detail = json_decode($_POST["detail"], true);
$cost = (int)$detail[0]["total"];
$error = "";
for ($i = 1; $i < count($detail); $i++) {
    $stmt = $conn->prepare('SELECT * from product where product_name=:food and product_shop=:shop_name');
    $stmt->execute(array('food' => $detail[$i]["meal"], 'shop_name' => $detail[0]["shop_name"]));
    if ($stmt->rowCount() == 0) {
        echo "Meal has been deleted！";
        exit();
    }
}
for ($i = 1; $i < count($detail); $i++) {
    $stmt = $conn->prepare('SELECT product_price from product where product_name=:food and product_shop=:shop_name');
    $stmt->execute(array('food' => $detail[$i]["meal"], 'shop_name' => $detail[0]["shop_name"]));
    $new_price = (int)($stmt->fetch()[0]);
    if ($new_price != (int)$detail[$i]["price"]) {
        echo "Meal's price has been updated！";
        exit();
    }
}
for ($i = 1; $i < count($detail); $i++) {
    $stmt = $conn->prepare('SELECT product_amount from product where product_name=:food and product_shop=:shop_name');
    $stmt->execute(array('food' => $detail[$i]["meal"], 'shop_name' => $detail[0]["shop_name"]));
    $remain = (int)($stmt->fetch()[0]);
    if ($remain < (int)$detail[$i]["quantity"]) {
        $error = $error . $detail[$i]["meal"] . "not enough!\n";
    }
}
try {
    if ($error) {
        throw new Exception($error);
    }
    if ($cost > $_SESSION['user_balance']) {
        throw new Exception("Insufficient Balance!");
    }

    for ($i = 1; $i < count($detail); $i++) {
        $stmt = $conn->prepare('UPDATE product set product_amount=product.product_amount-:num where product_name=:food and product_shop=:shop');
        $stmt->execute(array('food' => $detail[$i]["meal"], 'shop' => $detail[0]["shop_name"], 'num' => $detail[$i]["quantity"]));
    }
    $stmt = $conn->prepare('UPDATE user set user_balance=user_balance-:num where user_account=:user_account');
    $stmt->execute(array('num' => $cost, 'user_account' => $_SESSION['user_account']));

    $stmt = $conn->prepare('SELECT shop_owner from shop where shop_name=:shop_name');
    $stmt->execute(array('shop_name' => $detail[0]["shop_name"]));
    $shop_owner = $stmt->fetch()['shop_owner'];

    $stmt = $conn->prepare('UPDATE user set user_balance=user_balance+:num 
                                    WHERE user_account= :user_account
                              ');
    $stmt->execute(array('num' => $cost, 'user_account' => $shop_owner));


    $stmt = $conn->prepare('INSERT INTO orders (order_status,order_start_time,user_account,shop_name,order_price,order_detail) values (:status,:date,:user_account,:shop_name,:price,:detail)');
    $stmt->execute(array('status' => "undone", 'date' => date("Y-m-d H:i:s"), 'user_account' => $_SESSION['user_account'], "shop_name" => $detail[0]["shop_name"], "price" => $cost, "detail" => $_POST['detail']));



    $stmt = $conn->prepare('INSERT INTO transaction (user_accont,trader,tra_price,tra_time,tra_action) values 
                               (:user_account,:trader,:price,:tra_time,:type)');
    $stmt->execute(array('user_account' => $_SESSION['user_account'], 'type' => 'payment', 'tra_time' => date("Y-m-d H:i:s"), 'trader' => $detail[0]["shop_name"], 'price' => '-' . $cost));

    $stmt = $conn->prepare('SELECT user_account from user where user_account=:user_account');
    $stmt->execute(array('user_account' => $_SESSION['user_account']));
    $orderer_name = $stmt->fetch()['user_account'];

    $stmt = $conn->prepare('INSERT INTO transaction (user_accont,trader,tra_price,tra_time,tra_action) values 
                               (:user_account,:trader,:price,:tra_time,:type)');
    $stmt->execute(array('user_account' => $shop_owner, 'type' => 'receive', 'tra_time' => date("Y-m-d H:i:s"), 'trader' => $_SESSION['user_account'], 'price' => '+' . $cost));
    echo "Order Successfully!";

    $stmt = $conn->prepare('SELECT user_balance FROM user
                                    WHERE user_account = :user_account
                              ');
    $stmt->execute(array('user_account' => $_SESSION['user_account']));
    $_SESSION['user_balance'] = $stmt->fetch()['user_balance'];
} catch (Exception $e) {
    $msg = $e->getMessage();
    echo $msg;
}
?>