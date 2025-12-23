<?php
// process_login.php
session_start();

// Include koneksi database
include('config/database.php');

// Validasi input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $remember = isset($_POST['remember']) ? true : false;
    
    // Validasi input kosong
    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = 'Username dan password harus diisi!';
        header('Location: index.php?page=auth/login');
        exit();
    }
    
    // DEMO LOGIN (untuk testing tanpa database)
    // Gunakan ini dulu untuk testing
    if ($username === 'admin' && $password === 'admin123') {
        // Set session untuk user
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'admin';
        $_SESSION['user_logged_in'] = true;
        $_SESSION['role'] = 'Administrator';
        
        // Jika remember me dicentang, set cookie
        if ($remember) {
            setcookie('remember_user', $username, time() + (86400 * 30), "/"); // 30 hari
        }
        
        // Redirect ke dashboard
        header('Location: index.php?page=dashboard');
        exit();
    }
    
    // JIKA PAKAI DATABASE, gunakan kode ini:
    /*
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password (gunakan password_verify jika password dihash)
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_logged_in'] = true;
            $_SESSION['role'] = $user['role'];
            
            // Jika remember me dicentang
            if ($remember) {
                setcookie('remember_user', $username, time() + (86400 * 30), "/");
            }
            
            // Redirect ke halaman sebelumnya atau dashboard
            $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php?page=dashboard';
            unset($_SESSION['redirect_url']);
            
            header('Location: ' . $redirect_url);
            exit();
        } else {
            $_SESSION['error_message'] = 'Password salah!';
            header('Location: index.php?page=auth/login');
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Username tidak ditemukan!';
        header('Location: index.php?page=auth/login');
        exit();
    }
    */
    
    // Jika tidak cocok dengan demo credentials
    $_SESSION['error_message'] = 'Username atau password salah!';
    header('Location: index.php?page=auth/login');
    exit();
    
} else {
    // Jika bukan POST request, redirect ke login
    header('Location: index.php?page=auth/login');
    exit();
}
?>