<?php
if (isset($_GET['cek_kelas'])) {
    include 'connection.php';

    $response = ['presensi' => [], 'checkout' => []];

    $sqlPresensi = "SELECT matkul, kelas FROM kelas WHERE status_presensi = 1";
    $resultPresensi = $conn->query($sqlPresensi);

    if ($resultPresensi && $resultPresensi->num_rows > 0) {
        while ($row = $resultPresensi->fetch_assoc()) {
            $response['presensi'][] = $row['matkul'] . ' ' . $row['kelas'];
        }
    }

    $sqlCheckout = "SELECT matkul, kelas FROM kelas WHERE status_checkout = 1";
    $resultCheckout = $conn->query($sqlCheckout);

    if ($resultCheckout && $resultCheckout->num_rows > 0) {
        while ($row = $resultCheckout->fetch_assoc()) {
            $response['checkout'][] = $row['matkul'] . ' ' . $row['kelas'];
        }
    }

    $conn->close();

    echo json_encode($response);
    exit;
}
?>  

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            color: white;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 800px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            text-align: center;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        .btn-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        #clock {
            font-size: 24px;
            font-weight: bold;
            margin: 25px 0;
            color: #f5f5f5;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
        }
        .info-box {
            margin-top: 20px;
            width: 100%;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .info-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }
        form {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            width: 100%;
        }
        input {
            padding: 10px;
            border-radius: 4px;
            border: none;
            background-color: rgba(255, 255, 255, 0.8);
            margin: 5px 0;
            width: 200px;
        }
        button {
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #2980b9;
        }
        h1 {
            color: #f5f5f5;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #f5f5f5;
        }
        @media (min-width: 768px) {
            .info-container {
                flex-direction: row;
            }
            .form-container form {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>

        <div id="clock"></div>

        <div class="btn-container">
            <a href="lihat_data_asisten.php" class="btn">Lihat Data Asisten</a>
            <a href="lihat_data_kelas.php" class="btn">Lihat Data Kelas</a>
            <a href="lihat_data_praktikan.php" class="btn">Lihat Data Praktikan</a>
        </div>

        <div class="form-container">
            <form action="tambah_data_pertemuan_asisten.php" method="post">
                <h3>Asisten</h3>
                <div class="form-group">
                    <label for="rfid">Check-In</label>
                    <input type="number" id="rfid" name="rfid" placeholder="Masukan RFID Asisten">
                    <button type="submit">Check-In</button>
                </div>
                <div class="form-group">
                    <label for="rfid1">Check-Out</label>
                    <input type="number" id="rfid1" name="rfid1" placeholder="Masukan RFID Asisten">
                    <button type="submit">Check-Out</button>
                </div>
            </form>
            <form action="tambah_data_pertemuan_praktikan.php" method="post">
                <h3>Praktikan</h3>
                <div class="form-group">
                    <label for="fingerprint_id">Check-In</label>
                    <input type="number" id="fingerprint_id" name="fingerprint_id" placeholder="Masukan Fingerprint ID Praktikan">
                    <button type="submit">Check-in</button>
                </div>
                <div class="form-group">
                    <label for="fingerprint_id1">Check-Out</label>
                    <input type="number" id="fingerprint_id1" name="fingerprint_id1" placeholder="Masukan Fingerprint ID Praktikan">
                    <button type="submit">Check-out</button>
                </div>
            </form>
        </div>

        <div class="info-container">
            <div class="info-box" id="info-box-presensi">Tidak ada kelas yang aktif saat ini.</div>
            <div class="info-box" id="info-box-checkout">Tidak ada kelas yang membuka checkout saat ini.</div>
        </div>

        <div class="btn-container">
            <a href="index.html" class="btn">Kembali ke Login</a>
        </div>
    </div>

    <script>
        function updateClock() {
            let now = new Date();
            let year = now.getFullYear();
            let month = String(now.getMonth() + 1).padStart(2, '0');
            let day = String(now.getDate()).padStart(2, '0');
            let hours = String(now.getHours()).padStart(2, '0');
            let minutes = String(now.getMinutes()).padStart(2, '0');
            let seconds = String(now.getSeconds()).padStart(2, '0');

            let currentTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            document.getElementById("clock").innerText = currentTime;
        }

        setInterval(updateClock, 1000);
        updateClock();

        function checkClasses() {
            fetch(window.location.href + '?cek_kelas=true')
                .then(response => response.json())
                .then(data => {
                    const presensiBox = document.getElementById('info-box-presensi');
                    const checkoutBox = document.getElementById('info-box-checkout');

                    if (data.presensi.length > 0) {
                        presensiBox.innerHTML = `Checkin telah dibuka untuk kelas:<ul>${data.presensi.map(k => `<li>${k}</li>`).join('')}</ul>`;
                    } else {
                        presensiBox.innerHTML = "Tidak ada kelas yang membuka checkin saat ini.";
                    }

                    if (data.checkout.length > 0) {
                        checkoutBox.innerHTML = `Checkout telah dibuka untuk kelas:<ul>${data.checkout.map(k => `<li>${k}</li>`).join('')}</ul>`;
                    } else {
                        checkoutBox.innerHTML = "Tidak ada kelas yang membuka checkout saat ini.";
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        setInterval(checkClasses, 5000);
        checkClasses();
    </script>
</body>
</html>