<?php
$host = "localhost";
$user = "root";      
$pass = "";         
$db_name = "obattepat"; 

// Membuat koneksi
$mysqli = new mysqli($host, $user, $pass, $db_name);

// Cek koneksi
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}
?>
