<!DOCTYPE html>
<html>
<head>
    <title>Edit Data - DPR RI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <span class="navbar-brand mb-0 h1">DPR RI</span>
    </nav>
    <div class="container mt-4">
        <h2>Edit Data Peserta</h2>

        <?php
        session_start(); // Memulai sesi untuk menyimpan pesan
        include "database.php"; // Menghubungkan ke database

        // Cek apakah ID ada di URL
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']); // Mengambil ID dari URL dan mengubahnya menjadi integer
            $sql = "SELECT * FROM peserta WHERE id=?"; // Query untuk mengambil data berdasarkan ID
            $stmt = $conn->prepare($sql); // Mempersiapkan statement
            $stmt->bind_param("i", $id); // Mengikat parameter ID
            $stmt->execute(); // Mengeksekusi statement
            $result = $stmt->get_result(); // Mendapatkan hasil dari statement
            $data = $result->fetch_assoc(); // Mengambil data sebagai array asosiatif

            // Cek apakah form telah dikirim dengan metode POST
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Mengambil data dari form dan mencegah serangan XSS
                $nama = htmlspecialchars(trim($_POST['nama'])); // Mengamankan input nama
                $jenis_kelamin = htmlspecialchars(trim($_POST['jenis_kelamin'])); // Mengamankan input jenis kelamin
                $umur = intval($_POST['umur']); // Mengubah input umur menjadi integer
                    
                // Mempersiapkan query untuk memperbarui data
                $sql = "UPDATE peserta SET nama=?, jenis_kelamin=?, umur=? WHERE id=?";
                $stmt = $conn->prepare($sql); // Mempersiapkan statement
                $stmt->bind_param("ssii", $nama, $jenis_kelamin, $umur, $id); // Mengikat parameter ke statement

                // Mengeksekusi statement dan mengecek keberhasilan
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Data berhasil diperbarui"; // Menyimpan pesan sukses ke session
                    header("Location: index.php"); // Mengarahkan ke halaman utama
                    exit(); // Menghentikan eksekusi script
                } else {
                    // Menampilkan pesan error jika terjadi kegagalan
                    echo "<div class='alert alert-danger'>Gagal memperbarui data: " . $stmt->error . "</div>";
                }
            }

            $stmt->close(); // Menutup statement
        } else {
            // Menampilkan pesan error jika ID tidak ditemukan
            echo "<div class='alert alert-danger'>ID tidak ditemukan dalam URL.</div>";
        }

        $conn->close(); // Menutup koneksi database
        ?>

        <?php if (isset($data)): ?>
            <!-- Form untuk memperbarui data peserta -->
            <form action="" method="post">
                <div class="form-group">
                    <label>Nama:</label>
                    <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin:</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="Laki-laki" <?php echo $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Umur:</label>
                    <input type="number" name="umur" class="form-control" value="<?php echo htmlspecialchars($data['umur']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
