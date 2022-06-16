<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'db';
    $dbusername = 'admin';
    $dbpassword = 'admin';

    $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $OID_all = json_decode($_POST["OID"]);
    for($i=0 ; $i<count($OID_all) ; $i++){
        $OID = $OID_all[$i];
        $stmt = $conn->prepare("SELECT order_status from `orders` where OID=:OID");
        $stmt->execute(array('OID' => $OID));
        $row = $stmt->fetch();
        if($row['order_status']!='undone'){
            echo "Failed to cancel order , the order has been finished or canceled";
            exit();
        }
    }

    for($i=0 ; $i<count($OID_all) ; $i++){
        $OID = $OID_all[$i];
        $stmt = $conn->prepare("SELECT order_status,shop_name,user_account,order_price,order_detail from `orders` where OID=:OID");
        $stmt->execute(array('OID' => $OID));
        $row = $stmt->fetch();

        $order_detail = json_decode($row['order_detail'],true);//return array
        for($j=0 ; $j<count($order_detail) ; $j++){
            $stmt = $conn->prepare('UPDATE product set product_amount=product_amount+:amount where product_name=:p_name and product_shop=:p_shop');
            $stmt->execute(array('amount' => $order_detail[$j]["product_amount"] , 'p_name' => $order_detail[$j]["product_name"],'p_shop'=>$row['shop_name']));
        }

        //normal user
        $stmt = $conn->prepare('UPDATE user set user_balance=user_balance+:money where user_account=:u_account');
        $stmt->execute(array('money' =>(int)$row['order_price'],'u_account'=>$row['user_account']));

        //shop owner
        $stmt = $conn->prepare('UPDATE user set balance=balance-:money where user_account=
                        (SELECT user_account from user join shop on user.user_name =shop.shop_owner where shop_name =:shop)');
        $stmt->execute(array('money' =>(int)$row['order_price'],'shop'=>$row["shop_name"]));

        $time = date("Y-m-d H:i:s");

        $stmt = $conn->prepare('UPDATE `order` set order_status="cancel", order_finish_time=:finish where OID=:OID');
        $stmt->execute(array('OID' => $OID,'finish' => $time));

        //order data
        $stmt = $conn->prepare('SELECT shop_name,order_price,user_account from `orders` where OID=:OID');
        $stmt->execute(array('OID' => $OID));
        $row = $stmt->fetch();

        //user data
        $stmt = $conn->prepare('SELECT user_account from `user` where user_account=:user_account');
        $stmt->execute(array('user_account'=>$row['user_account']));
        $row_u = $stmt->fetch();

        //shop data
        $stmt = $conn->prepare('SELECT shop_owner from `shop` where shop_name=:shop_name');
        $stmt->execute(array('shop_name' => $row['shop_name']));
        $row_s = $stmt->fetch();


        $tra_user = $row_u['user_account'];
        $tra_shop = $row_s['shop_name'];
        $tra_money = $row['order_price'];

        //user receive
        while(1){
            $TID = rand(0,10000);
            $stmt = $conn->prepare('SELECT * from `transaction` where TID=:TID');
            $stmt->execute(array('TID' =>$TID));
            if($stmt->rowCount()==0){
                break;
            }
        }

        $stmt = $conn->prepare('INSERT INTO `transaction` (TID,user_account,trader,tra_price,tra_time, tra_action) values 
                                   (:TID,:account,:trader,:val,:time,:type)');
        $stmt->execute(array('TID' => $TID,'account' =>$tra_user,'trader'=>$tra_shop  ,'val'=>'+'.$tra_money ,'time'=>$time , 'type' => 'receive'));

        //payment
        while(1){
            $TID = rand(0,10000);
            $stmt = $conn->prepare('SELECT * from `transaction` where TID=:TID');
            $stmt->execute(array('TID' =>$TID));
            if($stmt->rowCount()==0){
                break;
            }
        }

        $stmt = $conn->prepare('INSERT INTO `transaction` (TID,user_account,trader,tra_price,tra_time, tra_action) values 
                                   (:TID,:account,:trader,:val,:time,:type)');
        $stmt->execute(array('TID' => $TID,'account' =>$tra_shop ,'trader'=>$tra_user ,'val'=>'-'.$tra_money ,'time'=>$time , 'type' => 'receive'));

    }
?>
