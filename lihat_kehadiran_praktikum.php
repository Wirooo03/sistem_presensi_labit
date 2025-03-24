<?php
    include 'connection.php';
    if (isset($_GET['id'])) {
        $id_kelas = $_GET['id'];
    } 

    // Query untuk mengambil semua data dari tabel praktikum
    $sql = "SELECT * FROM pertemuan WHERE id_kelas = ?";
    $stmt_pertemuan = $conn->prepare($sql);
    $stmt_pertemuan->bind_param("i", $id_kelas);
    $stmt_pertemuan->execute();
    $result = $stmt_pertemuan->get_result();

    // Query untuk mengambil data kelas tertentu
    $sql_kelas = "SELECT * FROM kelas WHERE id_kelas = ?";
    $stmt_kelas = $conn->prepare($sql_kelas);
    $stmt_kelas->bind_param("i", $id_kelas);
    $stmt_kelas->execute();
    $result_kelas = $stmt_kelas->get_result();
    $stmt_kelas->close(); // Tutup statement
    $row_kelas = $result_kelas->fetch_assoc();
    $result_kelas->data_seek(0);
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
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            background-color: #f5f5f5;
            color: white;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 1000px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            text-decoration: none;
            color: white;
            text-align: center;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 5px;
        }
        form label {
            margin: 10px 0 5px 0;
            width: 100%;
        }
        form input {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 4px;
            width: 48%;
        }
        form button {
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }
        form button:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        a {
            color: #ffffff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lihat Kehadiran Praktikum Kelas: <?php echo "{$row_kelas['matkul']} {$row_kelas['kelas']}" ?> </h1>
            
        <table>
            <tr>
                <th>Id</th>
                <th>Pertemuan-ke</th>
                <th>Modul</th>
                <th>Kegiatan</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
            <tr>
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id_pertemuan']}</td>
                                    <td>{$row['pertemuan_ke']}</td>
                                    <td>{$row['modul']}</td>
                                    <td>{$row['kegiatan']}</td>
                                    <td>{$row['tanggal']}</td>
                                    <td>{$row['keterangan']}</td>
                                    <td>
                                        <a href='lihat_data_kehadiran.php?id_pertemuan={$row['id_pertemuan']}'>Detail</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Tidak ada data</td></tr>";
                    }
                ?>
            </tr>
        </table>
        <a href="lihat_lebih_detail.php?id=<?php echo "{$row_kelas['id_kelas']}"?>" class="btn">Kembali ke Lihat Lebih Detail</a>
    </div>
</body>
</html>

<?php
    // Tutup koneksi
    $conn->close();
?>