CREATE TABLE barang (
         id INT AUTO_INCREMENT PRIMARY KEY,
         nama_barang VARCHAR(255) NOT NULL,
         harga INT NOT NULL,
         stok INT NOT NULL
     );

CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         username VARCHAR(255) NOT NULL UNIQUE,
         password VARCHAR(255) NOT NULL
     );