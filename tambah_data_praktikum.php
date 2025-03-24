<?php
    include 'connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pertemuan_ke = $_POST['pertemuan_ke'];
        $modul = $_POST['modul'];
        $kegiatan = $_POST['kegiatan'];
        $tanggal = $_POST['tanggal'];

        // Query insert data
        $sql = "INSERT INTO pertemuan (nama, nim) VALUES ('$nama', '$nim')";

        if ($conn->query($sql) === TRUE) {
            echo "Data berhasil ditambahkan!";
            header("Location: lihat_data_asisten.php"); // Redirect ke halaman utama
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
?>
