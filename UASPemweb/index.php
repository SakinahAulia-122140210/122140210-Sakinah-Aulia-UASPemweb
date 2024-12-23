<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Barang</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    // Memulai sesi untuk penggunaan variabel sesssion
    session_start();

    // Cek apakah pengguna sudah login, jika tidak arahkan ke halaman login
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit;
    }
    ?>

    <p>Selamat datang di website penyimpanan barang swalayan, <?php echo $_SESSION['username']; ?>!</p>
    <a href="logout.php">Logout</a>

    <!-- Bagian CRUD Data Barang -->
    <div id="crud">
        <h2>Data Barang</h2>
        
        <!-- Form untuk Menambah Barang -->
        <form action="create.php" method="POST">
            <label for="nama_barang">Nama Barang:</label>
            <input type="text" id="nama_barang" name="nama_barang" required>

            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" required>

            <label for="stok">Stok:</label>
            <input type="number" id="stok" name="stok" required>

            <button type="submit">Tambah Barang</button>
        </form>

        <h3>Daftar Barang</h3>
        <!-- Tabel Daftar Barang -->
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Membuat koneksi ke database
                $conn = new mysqli('localhost', 'root', '', 'manajemen_barang');

                // Mengecek koneksi
                if ($conn->connect_error) {
                    die("Koneksi gagal: " . $conn->connect_error);
                }

                // Mengambil data barang dari database
                $result = $conn->query("SELECT * FROM barang");

                // Menampilkan data barang
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['nama_barang']}</td>";
                        echo "<td>{$row['harga']}</td>";
                        echo "<td>{$row['stok']}</td>";
                        echo "<td>
                                <a href='edit.php?id={$row['id']}'>Edit</a> |
                                <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Yakin ingin menghapus barang ini?\")'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    // Menampilkan pesan jika tidak ada data
                    echo "<tr><td colspan='5'>Tidak ada data barang</td></tr>";
                }

                // Menutup koneksi database
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
