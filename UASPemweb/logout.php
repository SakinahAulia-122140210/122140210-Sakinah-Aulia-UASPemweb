<?php
session_start(); // Memulai sesi untuk penggunaan variabel sesssion

// Hapus semua sesi
session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit;
?>
