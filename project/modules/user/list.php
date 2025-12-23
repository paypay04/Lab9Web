<?php
// list.php
// Asumsi file ini ada di: C:\xampp\htdocs\lab9_php_modular\project\user\list.php

// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "latihan1";

$conn = mysqli_connect($host, $user, $pass, $db);
if ($conn == false) {
    echo "Koneksi ke server gagal.";
    die();
}

$sql = 'SELECT * FROM data_barang';
$result = mysqli_query($conn, $sql);

$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/lab9_php_modular/project/';

// Function untuk menentukan badge stok
function getStockBadge($stok) {
    if ($stok >= 10) {
        return 'stock-high';
    } elseif ($stok >= 5) {
        return 'stock-medium';
    } else {
        return 'stock-low';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - Sistem Inventaris</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS berada di folder assets/css -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Custom styling untuk gambar kecil */
        .image-container {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .table-product-img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            border: 3px solid #f0eaff;
            transition: all 0.3s ease;
            background: #f8f5ff;
        }
        
        .table-product-img:hover {
            transform: scale(1.1);
            border-color: #7b4bff;
            box-shadow: 0 4px 15px rgba(123, 75, 255, 0.3);
        }
        
        .image-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: linear-gradient(135deg, #f0eaff, #fff2ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7b4bff;
            font-size: 20px;
            border: 2px solid #e9e1ff;
        }
        
        .image-error {
            font-size: 8px;
            color: #fa5252;
            position: absolute;
            bottom: -18px;
            white-space: nowrap;
            background: white;
            padding: 2px 5px;
            border-radius: 4px;
            border: 1px solid #ff6b6b;
            z-index: 1;
        }
        
        /* Perbaikan untuk sel tabel */
        .data-table td:first-child {
            width: 80px;
            min-width: 80px;
        }
    </style>
</head>
<body>

    <!-- Main Content -->
    <div class="main-container">

        <!-- Main Card -->
        <div class="card">
            <!-- Card Header -->
            <div class="card-header">
                <h1>
                    <i class="fas fa-boxes"></i>
                    Data Barang
                </h1>
                <a href="../project/index.php?page=user/add" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Tambah Barang Baru
                </a>
            </div>

            <!-- Tools Bar -->
            <div class="tools-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari nama barang...">
                </div>
                <div class="filter-group">
                    <select class="filter-select">
                        <option>Semua Kategori</option>
                        <option>Komputer</option>
                        <option>Elektronik</option>
                        <option>Hand Phone</option>
                    </select>
                    <select class="filter-select">
                        <option>Semua Stok</option>
                        <option>Stok Tinggi</option>
                        <option>Stok Sedang</option>
                        <option>Stok Rendah</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php 
                            while($row = mysqli_fetch_array($result)): 
                                // Cek apakah file gambar ada
                                $full_path = $_SERVER['DOCUMENT_ROOT'] . '/lab9_php_modular/project/assets/gambar/' . $row['gambar'];
                                $gambar_exists = ($row['gambar'] && file_exists($full_path));
                            ?>
                            <tr>
                                <td>
                                    <div class="image-container">
                                        <?php if($gambar_exists): ?>
                                        <img src="<?php echo $base_url . 'assets/gambar/' . $row['gambar']; ?>" 
                                            alt="<?= htmlspecialchars($row['nama']);?>" 
                                            class="table-product-img" 
                                            onerror="handleImageError(this)">
                                        <?php else: ?>
                                        <div class="image-placeholder" style="position: relative;">
                                            <i class="fas fa-image"></i>
                                            <?php if($row['gambar']): ?>
                                            <span class="image-error"><?php echo $row['gambar']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($row['nama']);?></td>
                                <td><span class="category-badge"><?= $row['kategori'];?></span></td>
                                <td class="price-display"><?= number_format($row['harga_beli'], 0, ',', '.');?></td>
                                <td class="price-display"><?= number_format($row['harga_jual'], 0, ',', '.');?></td>
                                <td>
                                    <span class="stock-badge <?= getStockBadge($row['stok']); ?>">
                                        <?= $row['stok']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="../project/index.php?page=user/edit&id=<?= $row['id_barang'];?>" 
                                           class="action-btn edit" title="Ubah">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="../project/index.php?page=user/delete&id=<?= $row['id_barang'];?>" 
                                           class="action-btn delete" 
                                           title="Hapus"
                                           onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 50px; color: #b8a8ff;">
                                <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 20px; display: block;"></i>
                                <h3 style="color: #5a2fd4; margin-bottom: 10px;">Belum ada data barang</h3>
                                <p>Mulai dengan menambahkan barang baru</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="main-container">
            <p>&copy; 2025, Informatica, Universitas Pelta Bangsa</p>
        </div>
    </footer>

    <script>
        // Function untuk handle error gambar
        function handleImageError(img) {
            console.log('Gambar gagal dimuat:', img.src);
            const container = img.parentElement;
            container.innerHTML = '<div class="image-placeholder"><i class="fas fa-image"></i></div>';
        }
        
        // Script untuk debugging gambar
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.table-product-img');
            
            // Debug: Tampilkan info gambar di console
            console.log('Total gambar:', images.length);
            images.forEach((img, index) => {
                console.log(`Gambar ${index + 1}:`, img.src);
                
                // Tambahkan event listener untuk error
                img.addEventListener('error', function() {
                    handleImageError(this);
                });
            });
            
            // Tambahkan juga untuk gambar yang sudah ada
            const allImages = document.querySelectorAll('img');
            allImages.forEach(img => {
                if (!img.onerror) {
                    img.addEventListener('error', function() {
                        if (this.classList.contains('table-product-img')) {
                            handleImageError(this);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>