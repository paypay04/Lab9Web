<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Barang</title>
    
    <!-- Include CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Header Styles */
        .header-wrapper {
            background: linear-gradient(135deg, #7b4bff 0%, #9f6bff 100%);
            padding: 0;
            box-shadow: 0 4px 20px rgba(123, 75, 255, 0.3);
            border-bottom: 4px solid #ff9ce6;
        }
        
        .main-container {
            width: 85%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px 0;
        }
        
        /* Top Bar */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Logo & Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .logo i {
            font-size: 24px;
            color: #7b4bff;
        }
        
        .brand-text h1 {
            margin: 0;
            color: white;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .brand-text p {
            margin: 3px 0 0;
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
        }
        
        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-avatar i {
            font-size: 20px;
            color: white;
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            color: white;
            font-weight: 600;
            font-size: 15px;
        }
        
        .user-role {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        
        /* Navigation */
        .main-nav {
            display: flex;
            gap: 5px;
            background: white;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .nav-link {
            flex: 1;
            text-align: center;
            text-decoration: none;
            color: #666;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
        }
        
        .nav-link i {
            font-size: 18px;
        }
        
        .nav-link:hover {
            background: #f8f5ff;
            color: #7b4bff;
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #7b4bff, #9f6bff);
            color: white;
            box-shadow: 0 4px 8px rgba(123, 75, 255, 0.3);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 8px;
            background: #ff9ce6;
            border-radius: 50%;
        }
        
        /* Current Page Indicator */
        .current-page {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .current-page i {
            color: #ff9ce6;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .user-info {
                flex-direction: column;
                gap: 10px;
            }
            
            .main-nav {
                flex-direction: column;
            }
            
            .nav-link {
                justify-content: flex-start;
                padding: 12px 15px;
            }
            
            .brand {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="header-wrapper">
        <div class="main-container">
            <!-- Top Bar -->
            <div class="header-top">
                <!-- Brand & Logo -->
                <div class="brand">
                    <div class="logo">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="brand-text">
                        <h1>Sistem Inventaris Barang</h1>
                        <p>Manajemen Stok & Inventaris Terintegrasi</p>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></span>
                        <span class="user-role"><?php echo isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'Administrator'; ?></span>
                    </div>
                    <a href="index.php?page=auth/logout" class="logout-btn" onclick="return confirm('Yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
            
            <!-- Current Page Indicator -->
            <div class="current-page">
                <i class="fas fa-map-marker-alt"></i>
                <span>Anda berada di: 
                    <?php 
                    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                    $page_names = [
                        'dashboard' => 'Dashboard',
                        'user/list' => 'Data Barang',
                        'user/add' => 'Tambah Barang',
                        'user/edit' => 'Edit Barang',
                        'auth/login' => 'Login',
                        'auth/logout' => 'Logout'
                    ];
                    echo isset($page_names[$page]) ? $page_names[$page] : 'Dashboard';
                    ?>
                </span>
            </div>
            
            <!-- Navigation -->
            <nav class="main-nav">
                <?php
                // Tentukan halaman aktif
                $current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                ?>
                <a href="index.php?page=dashboard" class="nav-link <?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="index.php?page=user/list" class="nav-link <?php echo $current_page == 'user/list' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span>Data Barang</span>
                </a>
                <a href="index.php?page=user/add" class="nav-link <?php echo $current_page == 'user/add' ? 'active' : ''; ?>">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Barang</span>
                </a>
        </div>
    </div>