<?php
    $conn = mysqli_connect("localhost", "root", "", "merch-shop");
    if(mysqli_connect_errno()){
    echo "Spoj na bazu neuspjesan.".mysqli_connect_error();
    }
?>