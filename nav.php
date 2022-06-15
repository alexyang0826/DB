<?php session_start();
try{
    if( $_SESSION['Authenticated'] != true){
        header("Location: index.php");
    }
}catch(Exception $e){
    header("Location: index.php");
}
?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<script>
    filter = {};
</script>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/check_shop_name.js"></script>
    <script src="js/search_list.js"></script>

    <title>VberEats</title>
</head>

<body>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand " href="nav.php">VberEats</a>
        </div>

    </div>
</nav>
<style>
    .c {
        display: inline;
    }
</style>
<div class="container">

    <ul class="nav nav-tabs">
        <li class="active"><a href="#home">Home</a></li>
        <li><a href="#shop">Shop</a></li>
        <li><a href="#my_order">My Order</a></li>
        <?php if($_SESSION['user_type'] == 'manger') echo '<li><a href="#shop_order">Shop Order</a></li>'?>
        <li><a href="#transaction_record">Transaction Record</a></li>
        <li><a href="php/logout.php">logout</a></li>
    </ul>
    <
    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <h3>Profile</h3>
            <div class="row">
                <div class="col-xs-12">
                    Accouont: <?php echo $_SESSION['user_name']; ?>,
                    <?php echo $_SESSION['user_type']; ?>,
                    PhoneNumber: <?php echo $_SESSION['user_phone']; ?>,
                    location: <?php echo $_SESSION['user_latitude']; ?>,
                    <?php echo $_SESSION['user_longitude']; ?>
                    <button type="button" style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
                            data-target="#location">edit location
                    </button>
                    <!--  -->
                    <div class="modal fade" id="location" data-backdrop="static" tabindex="-1" role="dialog"
                         aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-sm">
                            <form action="php/edit_location.php" method="post">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">edit location</h4>
                                    </div>
                                    <div class="modal-body">
                                        <label class="control-label " for="latitude">latitude</label>
                                        <input type="text" class="form-control" id="latitude" name="latitude"
                                               placeholder="enter latitude">
                                        <br>
                                        <label class="control-label " for="longitude">longitude</label>
                                        <input type="text" class="form-control" id="longitude" name="longitude"
                                               placeholder="enter longitude">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Edit</button>
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Edit</button> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!--  -->
                    walletbalance:<?php echo $_SESSION['user_balance']; ?>
                    <!-- Modal -->
                    <button type="button" style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
                            data-target="#addValue">Add value
                    </button>
                    <div class="modal fade" id="addValue" data-backdrop="static" tabindex="-1" role="dialog"
                         aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-sm">
                            <form action="php/add_balance.php" method="post">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Add value</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" class="form-control" id="value"
                                               placeholder="enter add value" name="value">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Add</button>
                                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Add</button>-->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <!--

                 -->
            <h3>Search</h3>
            <div class=" row  col-xs-8">
                <div class="form-group">
                    <label class="control-label col-sm-1" for="Shop">Shop</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" placeholder="Enter Shop name"
                               oninput="filter['shop_name'] = this.value">
                    </div>
                    <label class="control-label col-sm-1" for="distance">distance</label>
                    <div class="col-sm-5">


                        <select class="form-control" id="sel1" onchange="filter['distance'] = this.value">
                            <option>All</option>
                            <option>Near</option>
                            <option>Medium</option>
                            <option>Far</option>

                        </select>
                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-1" for="Price">Price</label>
                    <div class="col-sm-2">

                        <input type="text" class="form-control" oninput="filter['price_floor'] = this.value">

                    </div>
                    <label class="control-label col-sm-1" for="~">~</label>
                    <div class="col-sm-2">

                        <input type="text" class="form-control" oninput="filter['price_ceiling'] = this.value">

                    </div>
                    <label class="control-label col-sm-1" for="Meal">Meal</label>
                    <div class="col-sm-5">
                        <input type="text" list="meals" class="form-control" id="meal"
                               onchange="filter['meal'] = this.value"
                               placeholder="Enter meal">
                        <datalist id="meals">
                            <?php
                            $dbservername = 'localhost';
                            $dbname = 'db';
                            $dbusername = 'admin';
                            $dbpassword = 'admin';
                            $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = $conn->prepare("SELECT DISTINCT product_name FROM product");
                            $sql->execute();
                            $result = $sql->fetchAll();
                            foreach ($result as &$row) {
                                $meals = $row['product_name'];
                                echo '<option value="' . $meals . '">';
                            }
                            ?>
                        </datalist>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-1" for="category"> category</label>


                    <div class="col-sm-5">
                        <input type="text" list="categorys" class="form-control" id="category"
                               onchange="filter['category'] = this.value"
                               placeholder="Enter shop category">
                        <datalist id="categorys">
                            <?php
                            $dbservername = 'localhost';
                            $dbname = 'db';
                            $dbusername = 'admin';
                            $dbpassword = 'admin';
                            $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = $conn->query("SELECT DISTINCT shop_category FROM shop");
                            $result = $sql->fetchAll();
                            foreach ($result as &$row) {
                                $category = $row['shop_category'];
                                echo '<option value="' . $category . '">';
                            }
                            ?>
                        </datalist>
                    </div>
                    <button type="submit" style="margin-left: 18px;" class="btn btn-primary"
                            onclick="search_list(filter)">Search
                    </button>
                    <div class="row">
                        <div id="result-list" class="col-xs-8"></div>
                    </div>
                </div>

            </div>
        </div>
        <div id="shop" class="tab-pane fade">
            <form action="php/shop_register.php" method="post">
                <h3> Start a business </h3>
                <div class="form-group ">
                    <div class="row">
                        <div class="col-xs-2">
                            <label for="ex5">shop name</label>
                            <input class="form-control" id="ex5" name="shop_name"
                                   placeholder="<?php echo $_SESSION['shop_name']; ?>"
                                   type="text" <?php if ($_SESSION['user_type'] == 'manger') {
                                echo "disabled";
                            }; ?> oninput="check_shop_name(this.value);">
                            <label id="check_shop_name"></label>
                        </div>
                        <div class="col-xs-2">
                            <label for="ex5">shop category</label>
                            <input class="form-control" id="ex5" name="shop_category"
                                   placeholder="<?php echo $_SESSION['shop_category']; ?>"
                                   type="text" <?php if ($_SESSION['user_type'] == 'manger') {
                                echo "disabled";
                            }; ?>>
                        </div>
                        <div class="col-xs-2">
                            <label for="ex6">latitude</label>
                            <input class="form-control" id="ex6" name="shop_latitude"
                                   placeholder="<?php echo $_SESSION['shop_latitude']; ?>"
                                   type="text" <?php if ($_SESSION['user_type'] == 'manger') {
                                echo "disabled";
                            }; ?>>
                        </div>
                        <div class="col-xs-2">
                            <label for="ex8">longitude</label>
                            <input class="form-control" id="ex8" name="shop_longitude"
                                   placeholder="<?php echo $_SESSION['shop_longitude']; ?>"
                                   type="text" <?php if ($_SESSION['user_type'] == 'manger') {
                                echo "disabled";
                            }; ?>>
                        </div>
                    </div>
                </div>


                <div class=" row" style=" margin-top: 25px;">
                    <div class=" col-xs-3">
                        <button type="submit" class="btn btn-primary" <?php if ($_SESSION['user_type'] == 'manger') {
                            echo "disabled";
                        }; ?>>register
                        </button>
                    </div>
                </div>
                <hr>
            </form>

            <h3>ADD</h3>
            <!-- upload meal -->
            <form action="php/shop_add.php" method="post" class="form-group" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="ex3">meal name</label>
                        <input name="product_name" class="form-control" id="ex3"
                               type="text" <?php if ($_SESSION['user_type'] != 'manger') {
                            echo "disabled";
                        }; ?> >
                    </div>
                </div>
                <div class="row" style=" margin-top: 15px;">
                    <div class="col-xs-3">
                        <label for="ex7">price</label>
                        <input name="product_price" class="form-control" id="ex7"
                               type="text" <?php if ($_SESSION['user_type'] != 'manger') {
                            echo "disabled";
                        }; ?> >
                    </div>
                    <div class="col-xs-3">
                        <label for="ex4">quantity</label>
                        <input name="product_amount" class="form-control" id="ex4"
                               type="text" <?php if ($_SESSION['user_type'] != 'manger') {
                            echo "disabled";
                        }; ?> >
                    </div>
                </div>

                <div class="row" style=" margin-top: 25px;">

                    <div class=" col-xs-3">
                        <label for="ex12">上傳圖片</label>
                        <input id="myFile" type="file" name="myFile" multiple
                               class="file-loading" <?php if ($_SESSION['user_type'] != 'manger') {
                            echo "disabled";
                        }; ?> >
                    </div>

                    <div class=" col-xs-3">
                        <input style=" margin-top: 15px;" type="submit" class="btn btn-primary"
                               value="Add" <?php if ($_SESSION['user_type'] != 'manger') {
                            echo "disabled";
                        }; ?> >
                    </div>

                </div>
            </form>
            <div class="row">
                <div class="  col-xs-8">
                    <table class="table" style=" margin-top: 15px;">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Picture</th>
                            <th scope="col">meal name</th>

                            <th scope="col">price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $dbservername = 'localhost';
                        $dbname = 'db';
                        $dbusername = 'admin';
                        $dbpassword = 'admin';

                        $conn = new PDO(
                            "mysql:host=$dbservername;dbname=$dbname",
                            $dbusername, $dbpassword);

                        $conn->setAttribute(
                            PDO::ATTR_ERRMODE,
                            PDO::ERRMODE_EXCEPTION);
                        if ($_SESSION['user_type'] == 'manger') {
                            $stmt = $conn->prepare("select * from product where product_shop=:product_shop");
                            $stmt->execute(array('product_shop' => $_SESSION['shop_name']));
                            $order = 0;
                            unset($row);
                            while ($row = $stmt->fetch()) {
                                $order++;
                                $PID = $row['PID'];
                                $product_img_type = $row['product_img_type'];
                                $product_img = $row['product_img'];
                                $product_name = $row['product_name'];
                                $product_price = $row['product_price'];
                                $product_amount = $row['product_amount'];
                                echo <<<EOT
                                    <!DOCTYPE html>
                                    <tr>
                                        <th scope="row">$order</th>
                                        <td><img src="data:$product_img_type; base64,$product_img" max-width="100px" height="100px" alt="Hamburger"></td>
                                        <td>$product_name</td>
                                        <td>$product_price</td>
                                        <td>$product_amount</td>
                                        <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#Hamburger-1">
                                        Edit
                                        </button></td>
                                        <!-- Modal -->
                                            <div class="modal fade" id="Hamburger-1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">$product_name Edit</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        
                                                        <!--change_meal form-->
                                                        <form action="php/change.php" method="post">
                                                            <div class="modal-body">
                                                                <div class="row" >
                                                                    <div class="col-xs-6">
                                                                        <label for="ex71">Price</label>
                                                                        <input class="form-control" id="ex71" name="product_price" type="text">
                                                                    </div>
                                                                    <div class="col-xs-6">
                                                                        <label for="ex41">Quantity</label>
                                                                        <input class="form-control" id="ex41" name="product_amount" type="text">
                                                                    </div>
                                                                    <input type="hidden" name="PID" value="$PID">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-secondary">Edit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <form action="php/delete.php" method="post">
                                            <input type="hidden" name="PID" value="$PID">
                                            <td><button type="submit" class="btn btn-danger">Delete</button></td>
                                        </form>
                                    EOT;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div id="my_order" class="tab-pane fade in active">
            My Order
        </div>
        <div id="shop_order" class="tab-pane fade in active">
            Shop Order
        </div>
        <div id="transaction_record" class="tab-pane fade in active">
            Transaction Record
        </div>
    </div>
</div>
<script>
    search_list(filter);
</script>
<!-- Option 1: Bootstrap Bundle with Popper -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
<script>
    var hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
    if (hash) {
        $('.nav-tabs a[href="#' + hash + '"]').tab('show');
    }
    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    })
    $(document).ready(function () {
        $(".nav-tabs a").click(function () {
            $(this).tab('show');
        });
    });
</script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
  -->
</body>

</html>