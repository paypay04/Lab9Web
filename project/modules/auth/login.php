<?php

// Cek jika sudah login
if (isset($_SESSION['user_id']) || (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true)) {
    header("Location: index.php?page=dashboard");
    exit();
}

// Include CSS khusus untuk login
$css_file = 'assets/css/auth.css';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris</title>
    
    <!-- HAPUS yang ini karena sudah ada di $css_file -->
    <!-- <link rel="stylesheet" href="<?php echo $css_file; ?>"> -->
    
    <!-- CSS utama untuk semua halaman -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- CSS khusus untuk login -->
    <link rel="stylesheet" href="<?php echo $css_file; ?>">
    
    <!-- Font dari Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1><i class="fas fa-boxes"></i> Sistem Inventaris</h1>
                <p class="auth-subtitle">Silakan login untuk mengakses sistem</p>
            </div>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="auth-message error">
                    <?php 
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="process_login.php" class="auth-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <input type="text" id="username" name="username" class="form-input" 
                               placeholder="Masukkan username" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="password" name="password" class="form-input" 
                               placeholder="Masukkan password" required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="forgot-password-container">
                        <a href="index.php?page=auth/forgot-password" class="forgot-password">
                            Lupa password?
                        </a>
                    </div>
                </div>
                
                <button type="submit" class="auth-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="auth-divider">
                <span>ATAU</span>
            </div>

            <div class="demo-credentials">
                <h4><i class="fas fa-info-circle"></i> Login Demo</h4>
                <div class="credential-item">
                    <span class="credential-label">Username:</span>
                    <span class="credential-value">admin</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value">admin123</span>
                </div>
            </div>
            
            <div class="auth-footer">
                <p>Belum punya akun? <a href="index.php?page=auth/register" class="auth-link">Daftar disini</a></p>
            </div>
        </div>
    </div>
    
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.password-toggle i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    </script>
</body>
</html>