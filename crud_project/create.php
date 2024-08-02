<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data - DPR RI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <style>
        .navbar-custom {
            background-color: #bbb; /* Ganti dengan warna yang diinginkan */
        }
        .navbar-dprfont {
        color: #ffffff; /* Mengubah warna teks menjadi putih */
    }
    
    </style>
</head>


<body>
    <nav class="navbar navbar-custom">
        <span class="navbar-dprfont mb-0 h1">DPR RI</span>
    </nav>
    <div class="container mt-5">
        <h2>Mohon diisi data anda</h2>

        <?php
        session_start(); // Memulai sesi untuk menyimpan pesan
        include "database.php"; // Menghubungkan ke database

        // Cek apakah form telah dikirim dengan metode POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Mengambil data dari form dan mencegah serangan XSS
            $nama = htmlspecialchars(trim($_POST['nama'])); // Mengamankan input nama
            $jenis_kelamin = htmlspecialchars(trim($_POST['jenis_kelamin'])); // Mengamankan input jenis kelamin
            $umur = intval($_POST['umur']); // Mengubah input umur menjadi integer

            // Mempersiapkan query untuk menambahkan data ke database
            $sql = "INSERT INTO peserta (nama, jenis_kelamin, umur) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql); // Mempersiapkan statement
            $stmt->bind_param("ssi", $nama, $jenis_kelamin, $umur); // Mengikat parameter ke statement

            // Mengeksekusi statement dan mengecek keberhasilan
            if ($stmt->execute()) {
                $_SESSION['message'] = "Data berhasil ditambahkan"; // Menyimpan pesan sukses ke session
                header("Location: index.php"); // Mengarahkan ke halaman utama
                exit(); // Menghentikan eksekusi script
            } else {
                // Menampilkan pesan error jika terjadi kegagalan
                echo "<div class='alert alert-danger'>Gagal menambahkan data: " . $stmt->error . "</div>";
            }

            $stmt->close(); // Menutup statement
        }

        $conn->close(); // Menutup koneksi database
        ?>

        <!-- Form untuk menambah data peserta -->
        <form action="" method="post">
            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Umur:</label>
                <input type="number" name="umur" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
</body>
</html>
