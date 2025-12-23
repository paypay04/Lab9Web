<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

error_log("SESSION DATA: " . print_r($_SESSION, true));

// Include konfigurasi database
include("config/database.php");

// Routing sederhana
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Debug log
error_log("Requested page: $page");

// Daftar halaman yang tidak memerlukan login (PUBLIC PAGES)
$public_pages = ['auth/login', 'auth/logout', 'auth/register', 'auth/forgot-password'];

// Cek apakah user sudah login
$logged_in = isset($_SESSION['user_id']) || (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true);

// Debug log status login
error_log("User logged in: " . ($logged_in ? 'YES' : 'NO'));
error_log("Session data: " . print_r($_SESSION, true));

// Redirect ke login jika belum login dan mengakses halaman terproteksi
if (!$logged_in && !in_array($page, $public_pages)) {
    error_log("Redirecting to login (user not logged in)");
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: index.php?page=auth/login');
    exit();
}

// Redirect ke dashboard jika sudah login dan mengakses halaman login/register
if ($logged_in && ($page === 'auth/login' || $page === 'auth/register')) {
    error_log("Redirecting to dashboard (user already logged in)");
    header('Location: index.php?page=dashboard');
    exit();
}

// MAPPING HALAMAN YANG BENAR dengan struktur folder yang ada
$page_mapping = [
    // Dashboard
    'dashboard' => 'views/dashboard.php',
    
    // User Management
    'user/list' => 'modules/user/list.php',
    'user/add' => 'modules/user/add.php', 
    'user/edit' => 'modules/user/edit.php',
    'user/delete' => 'modules/user/delete.php',
    
    // Auth
    'auth/login' => 'modules/auth/login.php',
    'auth/logout' => 'modules/auth/logout.php',
    'auth/register' => 'modules/auth/register.php',
    'auth/forgot-password' => 'modules/auth/forgot-password.php',
    
    // Barang Management
    'barang/list' => 'modules/barang/list.php',
    'barang/add' => 'modules/barang/add.php',
    'barang/edit' => 'modules/barang/edit.php',
    'barang/delete' => 'modules/barang/delete.php',
    'barang/view' => 'modules/barang/view.php',
    
    // Kategori Management
    'kategori/list' => 'modules/kategori/list.php',
    'kategori/add' => 'modules/kategori/add.php',
    'kategori/edit' => 'modules/kategori/edit.php',
    'kategori/delete' => 'modules/kategori/delete.php',
    
    // Laporan
    'laporan' => 'modules/laporan/index.php',
    'laporan/stok' => 'modules/laporan/stok.php',
    'laporan/transaksi' => 'modules/laporan/transaksi.php',
    
    // Settings
    'settings' => 'modules/settings/index.php',
    'settings/profile' => 'modules/settings/profile.php',
    'settings/password' => 'modules/settings/password.php',
];

// Cek apakah halaman ada di mapping
if (isset($page_mapping[$page])) {
    $page_path = $page_mapping[$page];
    
    // Debug log path
    error_log("Mapped path: $page_path");
    
    // Cek apakah file benar-benar ada
    if (file_exists($page_path)) {
        // Untuk halaman auth (login, register), tidak perlu header/footer
        if (strpos($page, 'auth/') === 0) {
            include($page_path);
            exit();
        }
        
        // Untuk halaman logout, langsung include tanpa header/footer
        if ($page === 'auth/logout') {
            include($page_path);
            exit();
        }
        
        // Untuk halaman lainnya, include header + content + footer
        include("views/header.php");
        include($page_path);
        include("views/footer.php");
    } else {
        // File tidak ditemukan, tampilkan error 404
        show_404($page, $page_path);
    }
} else {
    // Halaman tidak valid, tampilkan error 404
    show_404($page);
}

// Function untuk menampilkan halaman 404
function show_404($requested_page, $expected_path = null) {
    // Untuk halaman 404, kita tetap butuh header/footer
    include("views/header.php");
    ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card shadow">
                    <div class="card-body py-5">
                        <h1 class="display-1 text-muted">404</h1>
                        <h2 class="mb-4">Halaman Tidak Ditemukan</h2>
                        
                        <div class="alert alert-warning mb-4">
                            <h5 class="alert-heading">Informasi Error:</h5>
                            <p class="mb-0">
                                Halaman <strong>'<?php echo htmlspecialchars($requested_page); ?>'</strong> tidak ditemukan.
                            </p>
                            <?php if ($expected_path): ?>
                            <hr>
                            <p class="mb-0">
                                <small>File yang dicari: <code><?php echo htmlspecialchars($expected_path); ?></code></small>
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4">
                            <p>Kemungkinan penyebab:</p>
                            <ul class="list-unstyled">
                                <li>• File tidak ada di server</li>
                                <li>• URL yang dimasukkan salah</li>
                                <li>• Halaman telah dihapus atau dipindahkan</li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <a href="index.php?page=dashboard" class="btn btn-primary btn-lg">
                                <i class="fas fa-home"></i> Kembali ke Dashboard
                            </a>
                            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("views/footer.php");
    exit();
}
?>