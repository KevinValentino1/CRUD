<?php
// Pengaturan koneksi database
$server = "localhost"; // Nama server database
$username = "root"; // Username untuk mengakses database
$password = ""; // Password untuk mengakses database
$database = "tabledb"; // Nama database yang digunakan

// Membuat koneksi ke database
$conn = new mysqli($server, $username, $password, $database);

// Mengecek apakah koneksi berhasil
if ($conn->connect_error) {
    // Jika koneksi gagal, tampilkan pesan error dan hentikan script
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
