<?php
    $host = "localhost"; 
    $username = "wiraydmy_admin"; // "wiraydmy_admin" untuk akses db server
    $password = "cav.dyrw123"; // "cav.dyrw123" untuk akses db server
    $dbname = "wiraydmy_praktikumdb"; // "wiraydmy_praktikumdb" nama db server

    // Buat koneksi
    $conn = new mysqli($host, $username, $password, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
?>
