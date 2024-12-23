<?php
// Memulai sesi untuk penggunaan variabel session
session_start();

// Mengecek apakah parameter 'id' tersedia dalam URL (query string)
if (isset($_GET['id'])) {
    // Membuat koneksi ke database MySQL menggunakan objek mysqli
    $conn = new mysqli('localhost', 'root', '', 'manajemen_barang');
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error); // Menampilkan pesan kesalahan jika koneksi gagal
    }

    $id = $_GET['id'];   // Mengambil nilai 'id' dari query string URL

    // Menyiapkan query SQL untuk menghapus data dari tabel barang berdasarkan 'id'
    $stmt = $conn->prepare("DELETE FROM barang WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Mengalihkan pengguna ke halaman index.php
    header("Location: index.php");

    // Menutup prepared statement dan koneksi database
    $stmt->close();
    $conn->close();
}
?>

