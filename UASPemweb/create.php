<?php
// Memulai sesi untuk penggunaan variabel session
session_start();

// Mengecek apakah metode request yang digunakan adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Menghubungkan database MySQL menggunakan objek mysqli
    $conn = new mysqli('localhost', 'root', '', 'manajemen_barang');

    // Mengecek apakah koneksi berhasil atau gagal
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error); // Menampilkan pesan kesalahan jika koneksi gagal
    }

    // Mengambil data yang dikirimkan melalui form POST
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Menyiapkan query SQL dengan parameter yang akan di-bind
    $stmt = $conn->prepare("INSERT INTO barang (nama_barang, harga, stok) VALUES (?, ?, ?)");

    $stmt->bind_param("sii", $nama_barang, $harga, $stok);

    // Menjalankan query untuk memasukkan data ke tabel barang
    $stmt->execute();

    // Setelah eksekusi query selesai, mengalihkan pengguna ke halaman index.php
    header("Location: index.php");

    // Menutup prepared statement dan koneksi database
    $stmt->close();
    $conn->close();
}
?>

