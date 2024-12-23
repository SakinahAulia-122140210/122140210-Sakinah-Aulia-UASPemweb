<?php
session_start(); // Memulai sesi untuk penggunaan variabel sesssion

// Inisialisasi variabel untuk pesan
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'manajemen_barang');

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data dari form
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $terms_accepted = isset($_POST['terms_accepted']) ? $_POST['terms_accepted'] : ''; // Ambil status checkbox

    // Validasi data
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom harus diisi!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } else if (empty($terms_accepted)) {
        $error = "Anda harus menyetujui syarat dan ketentuan!";
    } else {
        // Hash password untuk keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username atau email sudah ada
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        if ($stmt) {
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Username atau email sudah terdaftar. Gunakan yang lain.";
            } else {
                // Insert data ke database
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sss", $username, $email, $hashed_password);

                    if ($stmt->execute()) {
                        // Redirect ke halaman login jika berhasil
                        header("Location: login.php");
                        exit;
                    } else {
                        $error = "Terjadi kesalahan saat menyimpan data.";
                    }
                } else {
                    $error = "Query INSERT gagal: " . $conn->error;
                }
            }

            $stmt->close();
        } else {
            $error = "Query SELECT gagal: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <div class="terms">
                <input type="checkbox" id="terms_accepted" name="terms_accepted" required>
                <label for="terms_accepted">I agree to the <a href="#">Terms and Conditions</a></label>
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Sign In</a></p>
    </div>
</body>
</html>
