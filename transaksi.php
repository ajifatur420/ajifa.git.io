<?php
$host = 'localhost';
$username = "root";
$password = "";
$dbName = 'rabuceria';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Form Transaksi Seblak Judes</h1>
    </header>
    <main>
        <div class="form-container">
            <!-- Form untuk memproses transaksi -->
            <form action="" method="post">
                <input type="hidden" name="menu_id" value="1">
                <label for="nama_pemesan">Nama Pemesan:</label>
                <input type="text" id="nama_pemesan" name="nama_pemesan" required>
                <label for="tingkat_kepedasan">Tingkat Kepedasan:</label>
                <input type="text" id="tingkat_kepedasan" name="tingkat_kepedasan" required>
                <label for="alamat">Alamat Pengiriman:</label>
                <textarea id="alamat" name="alamat" rows="4" required></textarea>
                <button type="submit" name="submit_order">Pesan Sekarang</button>
            </form>
            <?php
            // Memproses form pemesanan
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_order'])) {
                $menu_id = $_POST['menu_id'];
                $nama_pemesan = $_POST['nama_pemesan'];
                $tingkat_kepedasan = $_POST['tingkat_kepedasan'];
                $alamat = $_POST['alamat'];

                try {
                    $koneksi = new mysqli($host, $username, $password, $dbName);
                    if ($koneksi->connect_error) {
                        throw new Exception("Koneksi database gagal: " . $koneksi->connect_error);
                    }

                    $query_menu = "SELECT * FROM menu_seblak WHERE id = $menu_id";
                    $result_menu = $koneksi->query($query_menu);

                    if ($result_menu->num_rows > 0) {
                        $menu = $result_menu->fetch_assoc();
                        $menu_title = $menu['title'];
                        $menu_price = $menu['price'];

                        $query_transaksi = "INSERT INTO transaksi (menu_id, nama_pemesan, tingkat_kepedasan, alamat, total_harga)
                                            VALUES ($menu_id, '$nama_pemesan', '$tingkat_kepedasan', '$alamat', '$menu_price')";

                        if ($koneksi->query($query_transaksi) === TRUE) {
                            echo "<p>Transaksi berhasil! Terima kasih telah memesan $menu_title.</p>";
                        } else {
                            throw new Exception("Error: " . $query_transaksi . "<br>" . $koneksi->error);
                        }
                    } else {
                        echo "Menu tidak ditemukan.";
                    }
                    $koneksi->close();
                } catch (Exception $e) {
                    echo "Terjadi kesalahan: " . $e->getMessage();
                }
            }

            // Memproses penghapusan transaksi
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
                $delete_id = $_POST['delete_id'];

                try {
                    $koneksi = new mysqli($host, $username, $password, $dbName);
                    if ($koneksi->connect_error) {
                        throw new Exception("Koneksi database gagal: " . $koneksi->connect_error);
                    }

                    $query_delete = "DELETE FROM transaksi WHERE id=$delete_id";
                    if ($koneksi->query($query_delete) === TRUE) {
                        echo "<p>Transaksi berhasil dihapus!</p>";
                    } else {
                        throw new Exception("Error: " . $query_delete . "<br>" . $koneksi->error);
                    }
                    $koneksi->close();
                } catch (Exception $e) {
                    echo "Terjadi kesalahan: " . $e->getMessage();
                }
            }
            // Memproses pengeditan transaksi
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_submit'])) {
                $edit_id = $_POST['edit_id'];
                $edit_nama_pemesan = $_POST['edit_nama_pemesan'];
                $edit_tingkat_kepedasan = $_POST['edit_tingkat_kepedasan'];
                $edit_alamat = $_POST['edit_alamat'];

                try {
                    $koneksi = new mysqli($host, $username, $password, $dbName);
                    if ($koneksi->connect_error) {
                        throw new Exception("Koneksi database gagal: " . $koneksi->connect_error);
                    }

                    $query_update = "UPDATE transaksi SET nama_pemesan='$edit_nama_pemesan', tingkat_kepedasan='$edit_tingkat_kepedasan', alamat='$edit_alamat' WHERE id=$edit_id";
                    if ($koneksi->query($query_update) === TRUE) {
                        echo "<p>Transaksi berhasil diperbarui!</p>";
                    } else {
                        throw new Exception("Error: " . $query_update . "<br>" . $koneksi->error);
                    }
                    $koneksi->close();
                } catch (Exception $e) {
                    echo "Terjadi kesalahan: " . $e->getMessage();
                }
            }

            // Menampilkan transaksi yang ada
            try {
                $koneksi = new mysqli($host, $username, $password, $dbName);
                if ($koneksi->connect_error) {
                    throw new Exception("Koneksi database gagal: " . $koneksi->connect_error);
                }

                $query_transaksi = "SELECT transaksi.id, menu_seblak.title, transaksi.nama_pemesan, transaksi.tingkat_kepedasan, transaksi.alamat, transaksi.total_harga 
                                    FROM transaksi
                                    JOIN menu_seblak ON transaksi.menu_id = menu_seblak.id";
                $result_transaksi = $koneksi->query($query_transaksi);

                if ($result_transaksi->num_rows > 0) {
                    echo "<table border='1'>
                            <tr>
                                <th>ID</th>
                                <th>Menu</th>
                                <th>Nama Pemesan</th>
                                <th>Tingkat Kepedasan</th>
                                <th>Alamat</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>";
                    while($row = $result_transaksi->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row['id']."</td>
                                <td>".$row['title']."</td>
                                <td>".$row['nama_pemesan']."</td>
                                <td>".$row['tingkat_kepedasan']."</td>
                                <td>".$row['alamat']."</td>
                                <td>".$row['total_harga']."</td>
                                <td>
                                    <form action='' method='post' style='display:inline;'>
                                        <input type='hidden' name='delete_id' value='".$row['id']."'>
                                        <button type='submit'>Hapus</button>
                                    </form>
                                    <form action='' method='post' style='display:inline;'>
                                        <input type='hidden' name='edit_id' value='".$row['id']."'>
                                        <input type='hidden' name='edit_nama_pemesan' value='".$row['nama_pemesan']."'>
                                        <input type='hidden' name='edit_tingkat_kepedasan' value='".$row['tingkat_kepedasan']."'>
                                        <input type='hidden' name='edit_alamat' value='".$row['alamat']."'>
                                        <button type='submit' name='edit_show_form'>Edit</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>Tidak ada transaksi yang ditemukan.</p>";
                }
                $koneksi->close();
            } catch (Exception $e) {
                echo "Terjadi kesalahan: " . $e->getMessage();
            }

            // Menampilkan form edit jika tombol edit diklik
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_show_form'])) {
                $edit_id = $_POST['edit_id'];
                $edit_nama_pemesan = $_POST['edit_nama_pemesan'];
                $edit_tingkat_kepedasan = $_POST['edit_tingkat_kepedasan'];
                $edit_alamat = $_POST['edit_alamat'];

                echo "<form action='' method='post'>
                        <input type='hidden' name='edit_id' value='$edit_id'>
                        
                        <label for='edit_nama_pemesan'>Nama Pemesan:</label>
                        <input type='text' id='edit_nama_pemesan' name='edit_nama_pemesan' value='$edit_nama_pemesan' required>
                        
                        <label for='edit_tingkat_kepedasan'>Tingkat Kepedasan:</label>
                        <input type='text' id='edit_tingkat_kepedasan' name='edit_tingkat_kepedasan' value='$edit_tingkat_kepedasan' required>
                        
                        <label for='edit_alamat'>Alamat Pengiriman:</label>
                        <textarea id='edit_alamat' name='edit_alamat' rows='4' required>$edit_alamat</textarea>
                        
                        <button type='submit' name='edit_submit'>Simpan Perubahan</button>
                      </form>";
            }
            ?>
        </div>
    </main>
</body>
</html>
