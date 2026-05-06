<?php
$host = "mysql.railway.internal";
$user = "root";
$pass = "LZstNchgUtUIzpLIpzjvtJVusCMZscPX"; // Sesuaikan dengan MYSQL_ROOT_PASSWORD di gambar
$db   = "railway";
$port = 3306;

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
