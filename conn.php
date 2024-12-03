<?php
$host = "localhost";
$port = "root";
$dbname = "basis_data";
$user = "postgres";
$pass = "";

$conn = mysqli_connect($host, $port, $pass, $dbname);

if(!$conn){
    die("Koneksi Gagal : " . mysqli_connect_error());
}
?>