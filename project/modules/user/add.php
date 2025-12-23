<?php
// add.php
// Asumsi file ini ada di: C:\xampp\htdocs\lab9_php_modular\project\user\add.php

// Logika untuk menambah barang
if (isset($_POST['submit'])) {
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

    $sql = "INSERT INTO data_barang (nama, kategori, harga_jual, harga_beli, stok, gambar) 
            VALUES ('{$nama}', '{$kategori}', '{$harga_jual}', '{$harga_beli}', '{$stok}', '{$gambar}')";
    
    $result = mysqli_query($conn, $sql);

    if($result) {
        header('location: ../index.php?page=user/list');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Sistem Inventaris</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS berada di folder assets/css -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Custom styling untuk form tambah */
        .form-note {
            background: rgba(123, 75, 255, 0.05);
            border-left: 4px solid #7b4bff;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-size: 14px;
            color: #5a2fd4;
        }
        
        .form-note i {
            color: #7b4bff;
            margin-right: 8px;
        }
        
        /* Styling khusus untuk input number */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        input[type="number"] {
            -moz-appearance: textfield;
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
                    <i class="fas fa-plus-circle"></i>
                    Tambah Barang Baru
                </h1>
                <a href="../project/index.php?page=user/list" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Daftar
                </a>
            </div>

            <!-- Form Container -->
            <div class="form-container">
                <div class="form-note">
                    <i class="fas fa-info-circle"></i>
                    Isi semua form berikut untuk menambahkan barang baru ke inventaris
                </div>
                
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
                                       placeholder="Masukkan nama barang" required>
                                <small style="color: #b8a8ff; display: block; margin-top: 5px; font-size: 13px;">
                                    <i class="fas fa-exclamation-circle"></i> Minimal 3 karakter, maksimal 100 karakter
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="kategori">
                                    <i class="fas fa-folder"></i>
                                    Kategori
                                </label>
                                <select id="kategori" name="kategori" class="form-control" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="Komputer">Komputer</option>
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="Hand Phone">Hand Phone</option>
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
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #7b4bff; font-weight: 600;">Rp</span>
                                    <input type="number" id="harga_beli" name="harga_beli" class="form-control" 
                                           placeholder="Masukkan harga beli" 
                                           style="padding-left: 45px;"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="harga_jual">
                                    <i class="fas fa-tag"></i>
                                    Harga Jual
                                </label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #7b4bff; font-weight: 600;">Rp</span>
                                    <input type="number" id="harga_jual" name="harga_jual" class="form-control" 
                                           placeholder="Masukkan harga jual" 
                                           style="padding-left: 45px;"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="stok">
                                    <i class="fas fa-boxes"></i>
                                    Stok
                                </label>
                                <input type="number" id="stok" name="stok" class="form-control" 
                                       placeholder="Masukkan jumlah stok" 
                                       value="0" min="0"
                                       required>
                            </div>
                        </div>

                        <!-- Section 3: Gambar -->
                        <div class="form-section" style="grid-column: span 2;">
                            <div class="section-header">
                                <h3>
                                    <div class="section-icon">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    Gambar Barang (Opsional)
                                </h3>
                            </div>

                            <div class="form-group">
                                <label>
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    Upload Gambar
                                </label>
                                <div class="file-upload-area" onclick="document.getElementById('file_gambar').click()">
                                    <div>
                                        <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #7b4bff; margin-bottom: 15px;"></i>
                                        <p style="color: #5a2fd4; font-weight: 500; margin-bottom: 5px;">
                                            Klik untuk upload gambar
                                        </p>
                                        <small style="color: #b8a8ff;">
                                            Format yang didukung: JPG, PNG, GIF (Maks: 2MB)
                                        </small>
                                    </div>
                                    <input type="file" id="file_gambar" name="file_gambar" 
                                           accept="/assets/gambar/*" style="display: none;" 
                                           onchange="previewImage(this)">
                                </div>

                                <div id="image-preview" style="display: none; text-align: center; margin-top: 20px;">
                                    <img id="preview" src="" alt="Preview" style="max-width: 200px; border-radius: 12px; border: 3px solid #e9e1ff;">
                                    <br>
                                    <button type="button" class="btn-danger" onclick="removePreview()" style="margin-top: 15px; padding: 8px 16px;">
                                        <i class="fas fa-trash"></i> Hapus Gambar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="reset" class="btn btn-danger">
                            <i class="fas fa-times"></i>
                            Reset Form
                        </button>
                        <button type="submit" name="submit" class="btn btn-success">
                            <i class="fas fa-save"></i>
                            Simpan Barang
                        </button>
                    </div>
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
                // Validasi ukuran file (max 2MB)
                const fileSize = input.files[0].size / 1024 / 1024; // MB
                if (fileSize > 2) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB');
                    input.value = '';
                    return;
                }
                
                // Validasi tipe file
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(input.files[0].type)) {
                    alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF');
                    input.value = '';
                    return;
                }
                
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
            document.getElementById('preview').src = '';
        }
        
        // Validasi form sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama').value;
            const hargaBeli = document.getElementById('harga_beli').value;
            const hargaJual = document.getElementById('harga_jual').value;
            
            // Validasi nama
            if (nama.length < 3 || nama.length > 100) {
                e.preventDefault();
                alert('Nama barang harus 3-100 karakter!');
                document.getElementById('nama').focus();
                return false;
            }
            
            // Validasi harga
            if (parseInt(hargaJual) <= parseInt(hargaBeli)) {
                e.preventDefault();
                alert('Harga jual harus lebih besar dari harga beli!');
                document.getElementById('harga_jual').focus();
                return false;
            }
            
            return true;
        });
        
        // Format input number dengan titik
        document.addEventListener('DOMContentLoaded', function() {
            const numberInputs = document.querySelectorAll('input[type="number"]');
            numberInputs.forEach(input => {
                input.addEventListener('input', function() {
                    let value = this.value.replace(/[^0-9]/g, '');
                    if (value) {
                        this.value = new Intl.NumberFormat('id-ID').format(value);
                    }
                });
                
                // Untuk submit, hapus format titik
                input.addEventListener('blur', function() {
                    let value = this.value.replace(/\./g, '');
                    this.value = value;
                });
            });
        });
    </script>
</body>
</html>