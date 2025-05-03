<?php
$host = "localhost";
$user = "root";
$pwd = "";
$DB = "sistema_reportes_dual";


$connection = new mysqli($host, $user, $pwd, $DB);
$connection->set_charset("utf8");

    if($connection->connect_errno){
         die("Conexión fallida: " . mysqli_connect_error());

    }

    $db = mysqli_select_db($connection, $DB);
?>