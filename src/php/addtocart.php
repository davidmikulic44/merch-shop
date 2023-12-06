<?php

session_start();
error_reporting(0);
include("connection.php");

$size = $_POST['size'];
$user_id = $_SESSION['ID'];

$query = mysqli_query($conn, 'SELECT username FROM user WHERE ID = "'.$user_id.'"');

if(strlen($_SESSION['ID']) != 0){
    $loggedIn = true;
}
else {
    $loggedIn = false;
}

if (isset($_GET['ID'])) {
    $ID = $_GET['ID'];

    session_start();
    error_reporting(0);

    $sql = "SELECT * FROM item WHERE ID = $ID";
    $result = $conn->query($sql);
    if($result->num_rows) {
        $row = $result->fetch_assoc();
        $pageTitle = $row['name_raw'];
        $price = $row['price'];
        $item_id = $ID;
    }
    else {
        header('location: index.php');
    }
}

if(isset($_POST['add-to-cart'])) {
    $size = $_POST['size'];
    $query = mysqli_query($conn, 'select * from cart where user_ID = '. $user_id . ' and is_paid = 0');
    $ret = mysqli_fetch_array($query);
    if($ret == 0){
        $query = mysqli_query($conn, 'insert into cart (user_ID, cost) values ("' . $user_id . '","'. $price .'")');
        $query = mysqli_query($conn, 'select ID from cart where user_ID = '. $user_id . ' and is_paid = 0');
        $ret = mysqli_fetch_array($query);
        $cart_id = $ret[0];


        $query = mysqli_query($conn, 'insert into cart_item (cart_id, item_id, size) values
        ("' . $cart_id . '","'. $item_id .'", "'. $size .'")');
        header('location: ../../tshirt.php?ID='.urlencode($item_id));
        
    
    }
    else {
        $query = mysqli_query($conn, 'select cost from cart where user_ID = '. $user_id . ' and is_paid = 0');
        $ret = mysqli_fetch_array($query);
        $cost = $ret[0] + $price;

        $query = mysqli_query($conn, 'update cart set cost = '. $cost .' where user_ID = '. $user_id . ' and is_paid = 0');
        $query = mysqli_query($conn, 'select ID from cart where user_ID = '. $user_id . ' and is_paid = 0');
        $ret = mysqli_fetch_array($query);
        $cart_id = $ret[0];
        $query = mysqli_query($conn, 'insert into cart_item (cart_id, item_id, size) values
        ("' . $cart_id . '","'. $item_id .'", "'. $size .'")');
        header('location: ../../tshirt.php?ID='.urlencode($item_id));
    }
    
}

?>