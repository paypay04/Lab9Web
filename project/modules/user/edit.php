<?php
// edit.php
// Asumsi file ini ada di: C:\xampp\htdocs\lab9_php_modular\project\user\edit.php

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga_jual = $_POST['harga_jual'];
    $harga_beli = $_POST['harga_beli'];
    $stok = $_POST['stok'];
    $file_gambar = $_FILES['file_gambar'];
    $gambar = null;

    if ($file_gambar['error'] == 0) {
        $filename = str_replace(' ', '_', $file_gambar['name']);
        $destination = $_SERVER['DOCUMENT_ROOT'] . '/lab9_php_modular/project/assets/gambar/' . $filename;
        if (move_uploaded_file($file_gambar['tmp_name'], $destination)) {
            $gambar = $filename;
        }
    }

    $sql = "UPDATE data_barang SET ";
    $sql .= "nama = '{$nama}', kategori = '{$kategori}', ";
    $sql .= "harga_jual = '{$harga_jual}', harga_beli = '{$harga_beli}', stok = '{$stok}'";
    if (!empty($gambar)) {
        $sql .= ", gambar = '{$gambar}'";
    }
    $sql .= " WHERE id_barang = '{$id}'";
    
    $result = mysqli_query($conn, $sql);

    if($result) {
        header('location: index.php?page=user/list');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$id = $_GET['id'];
$sql = "SELECT * FROM data_barang WHERE id_barang = '{$id}'";
$result = mysqli_query($conn, $sql);
if (!$result) die('Error: Data tidak tersedia');
$data = mysqli_fetch_array($result);

function is_select($var, $val) {
    if ($var == $val) return 'selected="selected"';
    return false;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Barang - Sistem Inventaris</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS berada di folder assets/css -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Main Content -->
    <div class="main-container">

        <!-- Main Card -->
        <div class="card">
            <!-- Card Header -->
            <div class="card-header">
                <h1>
                    <i class="fas fa-edit"></i>
                    Ubah Barang
                </h1>
                <a href="../project/index.php?page=user/list" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Daftar
                </a>
            </div>

            <!-- Form Container -->
            <div class="form-container">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-grid">
                        <!-- Section 1: Informasi Dasar -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>
                                    <div class="section-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    Informasi Dasar
                                </h3>
                            </div>
                            
                            <div class="form-group">
                                <label for="nama">
                                    <i class="fas fa-tag"></i>
                                    Nama Barang
                                </label>
                                <input type="text" id="nama" name="nama" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['nama']);?>" 
                                       placeholder="Masukkan nama barang" required>
                            </div>

                            <div class="form-group">
                                <label for="kategori">
                                    <i class="fas fa-folder"></i>
                                    Kategori
                                </label>
                                <select id="kategori" name="kategori" class="form-control" required>
                                    <option value="Komputer" <?php echo is_select('Komputer', $data['kategori']);?>>Komputer</option>
                                    <option value="Elektronik" <?php echo is_select('Elektronik', $data['kategori']);?>>Elektronik</option>
                                    <option value="Hand Phone" <?php echo is_select('Hand Phone', $data['kategori']);?>>Hand Phone</option>
                                </select>
                            </div>
                        </div>

                        <!-- Section 2: Harga & Stok -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>
                                    <div class="section-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    Harga & Stok
                                </h3>
                            </div>

                            <div class="form-group">
                                <label for="harga_beli">
                                    <i class="fas fa-shopping-cart"></i>
                                    Harga Beli
                                </label>
                                <input type="number" id="harga_beli" name="harga_beli" class="form-control" 
                                       value="<?php echo $data['harga_beli'];?>" 
                                       placeholder="Masukkan harga beli" required>
                            </div>

                            <div class="form-group">
                                <label for="harga_jual">
                                    <i class="fas fa-tag"></i>
                                    Harga Jual
                                </label>
                                <input type="number" id="harga_jual" name="harga_jual" class="form-control" 
                                       value="<?php echo $data['harga_jual'];?>" 
                                       placeholder="Masukkan harga jual" required>
                            </div>

                            <div class="form-group">
                                <label for="stok">
                                    <i class="fas fa-boxes"></i>
                                    Stok
                                </label>
                                <input type="number" id="stok" name="stok" class="form-control" 
                                       value="<?php echo $data['stok'];?>" 
                                       placeholder="Masukkan jumlah stok" required>
                            </div>
                        </div>

                        <!-- Section 3: Gambar -->
                        <div class="form-section" style="grid-column: span 2;">
                            <div class="section-header">
                                <h3>
                                    <div class="section-icon">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    Gambar Barang
                                </h3>
                            </div>

                            <?php if($data['gambar']): ?>
                            <div class="current-image">
                                <p>
                                    <i class="fas fa-image"></i> Gambar Saat Ini:
                                </p>
                                <img src="../assets/gambar/<?php echo $data['gambar'];?>" 
                                     alt="<?php echo htmlspecialchars($data['nama']);?>">
                                <p style="color: #7b4bff; margin-top: 10px; font-size: 14px;">
                                    <?php echo $data['gambar']; ?>
                                </p>
                            </div>
                            <?php endif; ?>

                            <div class="file-upload-area" onclick="document.getElementById('file_gambar').click()">
                                <div>
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #7b4bff; margin-bottom: 15px;"></i>
                                    <p>Klik untuk upload gambar baru</p>
                                    <small style="color: #b8a8ff;">
                                        Format yang didukung: JPG, PNG, GIF (Maks: 2MB)
                                    </small>
                                </div>
                                <input type="file" id="file_gambar" name="file_gambar" 
                                       accept="image/*" style="display: none;" 
                                       onchange="previewImage(this)">
                            </div>

                            <div id="image-preview" style="display: none; text-align: center; margin-top: 20px;">
                                <img id="preview" src="" alt="Preview" style="max-width: 200px; border-radius: 12px; border: 3px solid #e9e1ff;">
                                <br>
                                <button type="button" class="btn-danger" onclick="removePreview()" style="margin-top: 15px; padding: 8px 16px;">
                                    <i class="fas fa-trash"></i> Hapus Preview
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="../index.php?page=user/list" class="btn btn-danger">
                            <i class="fas fa-times"></i>
                            Batal
                        </a>
                        <button type="submit" name="submit" class="btn btn-success">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                    
                    <input type="hidden" name="id" value="<?php echo $data['id_barang'];?>" />
                </form>
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
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('image-preview').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePreview() {
            document.getElementById('file_gambar').value = '';
            document.getElementById('image-preview').style.display = 'none';
        }
    </script>
</body>
</html>