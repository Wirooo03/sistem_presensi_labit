<?php
    include 'connection.php';
    if (isset($_GET['id'])) {
        $id_kelas = $_GET['id'];
    } else {
        die("ID tidak ditemukan!");
    }

    // Query untuk mengambil semua data dari tabel praktikum
    $sql = "SELECT * FROM praktikum";
    $result = $conn->query($sql);

    // Query untuk mengambil data kelas tertentu
    $sql_kelas = "SELECT * FROM kelas WHERE id_kelas = ?";
    $stmt_kelas = $conn->prepare($sql_kelas);
    $stmt_kelas->bind_param("i", $id_kelas);
    $stmt_kelas->execute();
    $result_kelas = $stmt_kelas->get_result();
    $stmt_kelas->close(); // Tutup statement
    $row_kelas = $result_kelas->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Kehadiran Praktikum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #f0f0f0;
            border: 1px solid #000;
            text-decoration: none;
            color: #000;
            text-align: center;
        }
        table {
            width: 60%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Lihat Kehadiran Praktikum Kelas: <?php echo "{$row_kelas['matkul']} {$row_kelas['kelas']}" ?> </h1>
    <h2>List Kehadiran Asisten </h2>    
    <table>
        <tr>
            <th>Id</th>
            <th>Nama</th>
            <th>Matkul</th>
            <th>Kelas</th>
            <th>Pertemuan-ke</th>
            <th>Modul</th>
            <th>Kegiatan</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
        </tr>
    </table>
    <h2>List Kehadiran Praktikan </h2>
    <table>
        <tr>
            <th>Id</th>
            <th>Nama</th>
            <th>Matkul</th>
            <th>Kelas</th>
            <th>Pertemuan-ke</th>
            <th>Modul</th>
            <th>Kegiatan</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
        </tr>
    </table>
    <a href="lihat_lebih_detail.php?id=<?php echo "{$row_kelas['id_kelas']}"?>" class="btn">Kembali ke Lihat Lebih Detail</a>
</body>
</html>

<?php
    // Tutup koneksi
    $conn->close();
?>