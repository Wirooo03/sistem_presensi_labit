<?php
include 'connection.php';

date_default_timezone_set('Asia/Jakarta');
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// Konversi nama hari dari Bahasa Inggris ke Bahasa Indonesia
$days = [
    "Sunday" => "Minggu",
    "Monday" => "Senin",
    "Tuesday" => "Selasa",
    "Wednesday" => "Rabu",
    "Thursday" => "Kamis",
    "Friday" => "Jumat",
    "Saturday" => "Sabtu"
];
$current_day = $days[date('l')]; // Nama hari dalam Bahasa Indonesia

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_SESSION['rfid']) || isset($_SESSION['rfid1'])) {
    if (isset($_POST['rfid']) || isset($_POST['rfid1'])) {
        $_SESSION['rfid'] = $_POST['rfid'] ?? null; // Simpan rfid di sesi
        $_SESSION['rfid1'] = $_POST['rfid1'] ?? null;
    }
    $rfid = $_SESSION['rfid'] ?? null;
    $rfid1 = $_SESSION['rfid1'] ?? null;

    if (!$rfid && !$rfid1) {
        echo "<script>alert('RFID tidak ditemukan.'); window.location.href='dashboard.php';</script>";
        exit;
    }

    // Cek apakah asisten terdaftar di kelas yang aktif dan sesuai dengan hari ini
    $sql = "SELECT ka.id_kelas, ka.id_asisten
            FROM kelas_asisten ka
            JOIN kelas k ON ka.id_kelas = k.id_kelas
            WHERE ka.id_asisten = ? AND k.status_presensi = 1 AND k.hari = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $rfid, $current_day);
    $stmt->execute();
    $result = $stmt->get_result();

    $sql1 = "SELECT ka.id_kelas, ka.id_asisten
            FROM kelas_asisten ka
            JOIN kelas k ON ka.id_kelas = k.id_kelas
            WHERE ka.id_asisten = ? AND k.status_checkout = 1 AND k.hari = ?";
    
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param('is', $rfid1, $current_day);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    if ($result->num_rows > 0) { //checkin
        $row = $result->fetch_assoc();
        $id_kelas = $row['id_kelas'];

        // Cek apakah sudah ada pertemuan hari ini untuk kelas tersebut
        $sql = "SELECT id_pertemuan FROM pertemuan WHERE id_kelas = ? AND tanggal = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $id_kelas, $current_date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_pertemuan = $row['id_pertemuan'];

            // Cek apakah asisten sudah pernah presensi di pertemuan ini hari ini
            $sql = "SELECT * FROM kehadiran_asisten WHERE id_asisten = ? AND id_pertemuan = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $rfid, $id_pertemuan);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) { //ada pertemuan
                echo "<script>alert('Anda sudah melakukan presensi untuk kelas ini hari ini.');</script>";
            } else {
                // Tambahkan data kehadiran
                $sql = "INSERT INTO kehadiran_asisten (id_asisten, id_pertemuan, keterangan, waktu_masuk, waktu_keluar) 
                        VALUES (?, ?, 'Hadir', ?, NULL)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('iis', $rfid, $id_pertemuan, $current_time);
                $stmt->execute();

                echo "<script>alert('Data kehadiran berhasil disimpan.');window.location.href='dashboard.php';</script>";
            }
        } else {
            // Tidak ada pertemuan, arahkan ke tambah_data_pertemuan.php
            echo "  <form id='redirectForm' action='tambah_data_pertemuan.php' method='POST'>
                        <input type='hidden' name='rfid' value='$rfid'>
                    </form>
                    <script>document.getElementById('redirectForm').submit();</script>";
        }
    } else if ($result1->num_rows > 0) { //checkout
        $row1 = $result1->fetch_assoc();
        $id_kelas1 = $row1['id_kelas'];

        // Cek apakah sudah ada pertemuan hari ini untuk kelas tersebut
        $sql1 = "SELECT id_pertemuan FROM pertemuan WHERE id_kelas = ? AND tanggal = ? LIMIT 1";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param('is', $id_kelas1, $current_date);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        //echo "afsdffw";
        if ($result1->num_rows > 0) { //ada pertemuan
            $row1 = $result1->fetch_assoc();
            $id_pertemuan = $row1['id_pertemuan'];

            // Cek apakah asisten sudah pernah presensi di pertemuan ini hari ini
            $sql1 = "   SELECT ka.* 
                        FROM kehadiran_asisten ka
                        JOIN pertemuan p ON ka.id_pertemuan = p.id_pertemuan
                        JOIN kelas k ON p.id_kelas = k.id_kelas
                        WHERE ka.id_asisten = ? AND ka.id_pertemuan = ? AND k.status_checkout IS NULL";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param('ii', $rfid1, $id_pertemuan);
            $stmt1->execute();
            $result1 = $stmt1->get_result();

            if ($result1->num_rows > 0) {
                echo "<script>alert('Anda sudah melakukan checkout untuk kelas ini hari ini.');</script>";
            } else {
                // Update data checkout
                $sql1 = "   UPDATE kehadiran_asisten 
                            SET waktu_keluar = ? 
                            WHERE id_asisten = ? AND id_pertemuan = ?";

                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param('sii', $current_time, $rfid1, $id_pertemuan);
                $stmt1->execute();

                echo "<script>alert('Data checkout berhasil disimpan.');window.location.href='dashboard.php';</script>";
            }
        } 
    } else {
        echo "<script>alert('Tidak ada kelas yang aktif untuk asisten ini.'); window.location.href='dashboard.php';</script>";
    }

    $stmt->close();
    $stmt1->close();
} else {
    echo "<script>alert('Metode pengiriman tidak valid.'); window.location.href='dashboard.php';</script>";
}

$conn->close();
?>
