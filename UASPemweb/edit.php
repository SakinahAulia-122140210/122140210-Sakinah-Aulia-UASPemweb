<?php
// Memulai sesi untuk penggunaan variabel sesssion
session_start();

// Membuat koneksi ke database MySQL menggunakan objek mysqli
$conn = new mysqli('localhost', 'root', '', 'manajemen_barang');

// Mengecek apakah koneksi berhasil atau gagal
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error); // Menampilkan pesan kesalahan jika koneksi gagal
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form dan memperbarui data barang
    $id = $_POST['id'];
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Menyiapkan query SQL untuk mengupdate data barang
    $stmt = $conn->prepare("UPDATE barang SET nama_barang = ?, harga = ?, stok = ? WHERE id = ?");
    $stmt->bind_param("siii", $nama_barang, $harga, $stok, $id);
    $stmt->execute();  // Menjalankan query untuk memperbarui data

    // Setelah eksekusi, mengalihkan pengguna ke halaman index.php
    header("Location: index.php");

    // Menutup prepared statement dan koneksi database
    $stmt->close();
    $conn->close();
} elseif (isset($_GET['id'])) {
    // Jika ada parameter 'id' di URL, ambil data barang sesuai ID
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM barang WHERE id = $id");

    // Mengecek apakah barang ditemukan
    if ($result->num_rows > 0) {
        $barang = $result->fetch_assoc(); // Mengambil data barang
    } else {
        echo "Barang tidak ditemukan!"; // Menampilkan pesan jika barang tidak ditemukan
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Barang</h1>
    <form action="edit.php" method="POST">
        <!-- Input Hidden untuk menyimpan ID Barang -->
        <input type="hidden" name="id" value="<?php echo $barang['id']; ?>">
        
        <!-- Input untuk Nama Barang -->
        <label for="nama_barang">Nama Barang:</label>
        <input type="text" id="nama_barang" name="nama_barang" value="<?php echo $barang['nama_barang']; ?>" required>

        <!-- Input untuk Harga -->
        <label for="harga">Harga:</label>
        <input type="number" id="harga" name="harga" value="<?php echo $barang['harga']; ?>" required>

        <!-- Input untuk Stok -->
        <label for="stok">Stok:</label>
        <input type="number" id="stok" name="stok" value="<?php echo $barang['stok']; ?>" required>

        <!-- Tombol untuk menyimpan perubahan -->
        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
