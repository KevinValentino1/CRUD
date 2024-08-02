<!DOCTYPE html>
<html>
<head>
    <title>Daftar Peserta - DPR RI</title>
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
        <span class="navbar-dprfont mb-5 h1">DPR RI</span>
    </nav>
    <div class="container mt-5">
        <h2 class="mb-4">Daftar Peserta</h2>

        <?php
        session_start(); // Memulai sesi untuk menampilkan pesan
        include "database.php"; // Menghubungkan ke database

        // Tampilkan pesan jika ada
        if (isset($_SESSION['message'])) {
            // Menampilkan pesan sukses
            echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']); // Menghapus pesan setelah ditampilkan
        }

        if (isset($_SESSION['error'])) {
            // Menampilkan pesan error
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']); // Menghapus pesan setelah ditampilkan
        }

        // Hapus data jika ID diberikan
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']); // Mengambil ID dari URL
            $sql = "DELETE FROM peserta WHERE id=?"; // Query untuk menghapus data berdasarkan ID
            $stmt = $conn->prepare($sql); // Mempersiapkan statement
            $stmt->bind_param("i", $id); // Mengikat parameter ID
            if ($stmt->execute()) {
                $_SESSION['message'] = "Data berhasil dihapus"; // Menyimpan pesan sukses ke session
                header("Location: index.php"); // Mengarahkan ke halaman utama
                exit(); // Menghentikan eksekusi script
            } else {
                $_SESSION['error'] = "Gagal menghapus data: " . $stmt->error; // Menyimpan pesan error ke session
                header("Location: index.php"); // Mengarahkan kembali ke halaman utama
                exit(); // Menghentikan eksekusi script
            }
        }

        // Query untuk mengambil semua data peserta
        $sql = "SELECT * FROM peserta";
        $result = $conn->query($sql); // Mengeksekusi query

        // Menampilkan tabel data peserta
        if ($result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Umur</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                            <td><?php echo htmlspecialchars($row['umur']); ?></td>
                            <td>
                                <a href="update.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada data peserta.</p>
        <?php endif;

        $conn->close(); // Menutup koneksi database
        ?>
        <a href="create.php" class="btn btn-primary">Tambah Data</a>
    </div>
</body>
</html>
