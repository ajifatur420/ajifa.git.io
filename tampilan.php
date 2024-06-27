<?php
$host = 'localhost';
$username = "root";
$password = "";
$dbName = 'rabuceria';
// Membuat koneksi ke database
$koneksi = new mysqli($host, $username, $password, $dbName);
// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
// Query untuk mengambil data menu seblak dari tabel
$query = "SELECT id, title, price FROM menu_seblak";
$result = $koneksi->query($query);
if (!$result) {
    die("Query error: " . $koneksi->error);
}
if ($result->num_rows > 0) {
    // Output data dari setiap baris
    while($row = $result->fetch_assoc()) {
        "ID: " . $row["id"]. " - Title: " . $row["title"]. " - Price: Rp. " . number_format($row["price"], 0, ',', '.') . "<br>";
    }
} else {
    echo "Tidak ada data menu seblak.";
}
// Menutup koneksi
$koneksi->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seblak Judes</title>
    <link rel="stylesheet" href="1.css">
</head>
<body>
    <header>
        <h1>Seblak Judes</h1>
        <p>Menu Pilihan yang Lezat</p>
    </header>
    <main>
        <div class="card-container">
            <?php
            $menus = array(
                array(
                    'image' => 'assets/seblak1.jpg',
                    'title' => 'Seblak Biasa',
                    'price' => 'Rp. 20,000'
                ),
                array(
                    'image' => 'assets/seblak2.jpg',
                    'title' => 'Seblak Komplit',
                    'price' => 'Rp. 25,000'
                ),
                array(
                    'image' => 'assets/seblak3.jpg',
                    'title' => 'Seblak Super Komplit',
                    'price' => 'Rp. 30,000'
                )
            );

            foreach ($menus as $menu) {
                echo '<div class="card">';
                echo '<div class="card-img">';
                echo '<img src="' . $menu['image'] . '" alt="' . $menu['title'] . '">';
                echo '</div>';
                echo '<div class="card-text">';
                echo '<h2 class="card-title">' . $menu['title'] . '</h2>';
                echo '<div class="card-price">' . $menu['price'] . '</div>';
                echo '</div>';
                echo '<a href="transaksi.php" class="transaksi">Pesan Sekarang</a>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
</body>
</html>
