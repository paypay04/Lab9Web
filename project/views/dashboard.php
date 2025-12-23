<?php
// Query untuk statistik dengan error handling
$sql_total = "SELECT COUNT(*) as total FROM data_barang";
$sql_stock = "SELECT SUM(stok) as total_stok FROM data_barang";
$sql_categories = "SELECT kategori, COUNT(*) as count FROM data_barang GROUP BY kategori";
$sql_low_stock = "SELECT COUNT(*) as low_stock FROM data_barang WHERE stok < 5";

// Eksekusi query
$result_total = mysqli_query($conn, $sql_total);
$result_stock = mysqli_query($conn, $sql_stock);
$result_categories = mysqli_query($conn, $sql_categories);
$result_low_stock = mysqli_query($conn, $sql_low_stock);

// Cek error
if (!$result_total || !$result_stock || !$result_categories || !$result_low_stock) {
    die("Query error: " . mysqli_error($conn));
}

$total_barang = mysqli_fetch_assoc($result_total)['total'];
$total_stok = mysqli_fetch_assoc($result_stock)['total_stok'] ?: 0;
$low_stock = mysqli_fetch_assoc($result_low_stock)['low_stock'];

// Data untuk chart kategori
$categories_data = [];
while ($row = mysqli_fetch_assoc($result_categories)) {
    $categories_data[$row['kategori']] = $row['count'];
}
?>

<div class="dashboard">
    <h2>Dashboard Sistem Inventaris</h2>
    <p class="dashboard-subtitle">
        Selamat datang, <strong><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?></strong>! 
        Berikut ringkasan data barang Anda.
    </p>

    <!-- Statistik Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon" style="background: #7b4bff;">
                📦
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($total_barang); ?></h3>
                <p>Total Barang</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #4CAF50;">
                📊
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($total_stok); ?></h3>
                <p>Total Stok</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #ff9800;">
                ⚠️
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($low_stock); ?></h3>
                <p>Stok Menipis (<5)</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #2196F3;">
                📋
            </div>
            <div class="stat-info">
                <h3><?php echo count($categories_data); ?></h3>
                <p>Kategori</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-content">
        <!-- Quick Actions -->
        <div class="dashboard-section">
            <h3>Quick Actions</h3>
            <div class="quick-actions">
                <a href="index.php?page=user/add" class="action-card">
                    <div class="action-icon">➕</div>
                    <span>Tambah Barang</span>
                </a>
                <a href="index.php?page=user/list" class="action-card">
                    <div class="action-icon">📋</div>
                    <span>Lihat Barang</span>
                </a>
                <a href="#" class="action-card" onclick="alert('Fitur kelola stok akan segera tersedia')">
                    <div class="action-icon">📦</div>
                    <span>Kelola Stok</span>
                </a>
                <a href="#" class="action-card" onclick="alert('Fitur laporan akan segera tersedia')">
                    <div class="action-icon">📊</div>
                    <span>Laporan</span>
                </a>
            </div>
        </div>

        <div class="content-columns">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Distribusi Kategori -->
                <div class="dashboard-section">
                    <h3>Distribusi Kategori</h3>
                    <div class="categories-chart">
                        <?php if (!empty($categories_data)): ?>
                            <?php foreach ($categories_data as $kategori => $count): 
                                $percentage = ($total_barang > 0) ? ($count / $total_barang) * 100 : 0;
                            ?>
                                <div class="category-item">
                                    <div class="category-info">
                                        <span class="category-name"><?php echo htmlspecialchars($kategori); ?></span>
                                        <span class="category-count"><?php echo $count; ?> barang</span>
                                    </div>
                                    <div class="category-bar">
                                        <div class="category-progress" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                    <div class="category-percentage"><?php echo number_format($percentage, 1); ?>%</div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-data">Belum ada data kategori</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Barang Stok Menipis -->
                <div class="dashboard-section">
                    <h3>Barang Stok Menipis <span class="badge"><?php echo $low_stock; ?></span></h3>
                    <?php
                    $sql_low_items = "SELECT nama, stok FROM data_barang WHERE stok < 5 ORDER BY stok ASC LIMIT 5";
                    $result_low_items = mysqli_query($conn, $sql_low_items);
                    
                    if (!$result_low_items) {
                        die("Query error: " . mysqli_error($conn));
                    }
                    ?>
                    
                    <?php if (mysqli_num_rows($result_low_items) > 0): ?>
                        <div class="low-stock-items">
                            <?php while ($item = mysqli_fetch_assoc($result_low_items)): ?>
                                <div class="stock-item">
                                    <span class="item-name"><?php echo htmlspecialchars($item['nama']); ?></span>
                                    <span class="item-stock <?php echo $item['stok'] == 0 ? 'out-of-stock' : 'low-stock'; ?>">
                                        <?php echo $item['stok']; ?> pcs
                                    </span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-data">✅ Semua stok dalam kondisi baik</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Barang Terbaru -->
                <div class="dashboard-section">
                    <h3>Barang Terbaru</h3>
                    <?php
                    // FIXED: Query tanpa created_at
                    $sql_recent = "SELECT nama, kategori, harga_jual FROM data_barang ORDER BY id_barang DESC LIMIT 5";
                    $result_recent = mysqli_query($conn, $sql_recent);
                    
                    if (!$result_recent) {
                        die("Query error: " . mysqli_error($conn));
                    }
                    ?>
                    
                    <?php if (mysqli_num_rows($result_recent) > 0): ?>
                        <div class="recent-items">
                            <?php while ($recent = mysqli_fetch_assoc($result_recent)): ?>
                                <div class="recent-item">
                                    <div class="recent-info">
                                        <strong><?php echo htmlspecialchars($recent['nama']); ?></strong>
                                        <span class="recent-category"><?php echo htmlspecialchars($recent['kategori']); ?></span>
                                    </div>
                                    <div class="recent-price">
                                        Rp <?php echo number_format($recent['harga_jual'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-data">Belum ada data barang</p>
                    <?php endif; ?>
                </div>

                <!-- Ringkasan Harga -->
                <div class="dashboard-section">
                    <h3>Ringkasan Harga</h3>
                    <?php
                    $sql_price_stats = "SELECT 
                        COUNT(*) as total_items,
                        AVG(harga_jual) as avg_price,
                        MAX(harga_jual) as max_price,
                        MIN(harga_jual) as min_price
                        FROM data_barang";
                    
                    $result_price_stats = mysqli_query($conn, $sql_price_stats);
                    $price_stats = mysqli_fetch_assoc($result_price_stats);
                    ?>
                    
                    <div class="price-stats">
                        <div class="price-stat-item">
                            <span class="stat-label">Rata-rata Harga:</span>
                            <span class="stat-value">Rp <?php echo number_format($price_stats['avg_price'] ?? 0, 0, ',', '.'); ?></span>
                        </div>
                        <div class="price-stat-item">
                            <span class="stat-label">Harga Tertinggi:</span>
                            <span class="stat-value">Rp <?php echo number_format($price_stats['max_price'] ?? 0, 0, ',', '.'); ?></span>
                        </div>
                        <div class="price-stat-item">
                            <span class="stat-label">Harga Terendah:</span>
                            <span class="stat-value">Rp <?php echo number_format($price_stats['min_price'] ?? 0, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard {
    padding: 20px;
    background: #f5f7fa;
    min-height: calc(100vh - 100px);
}

.dashboard-subtitle {
    color: #666;
    margin-bottom: 30px;
    font-size: 16px;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border: 1px solid #e5e7eb;
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-right: 20px;
}

.stat-info h3 {
    margin: 0;
    font-size: 28px;
    color: #1f2937;
    font-weight: 700;
}

.stat-info p {
    margin: 5px 0 0;
    color: #6b7280;
    font-size: 14px;
}

.dashboard-content {
    margin-top: 30px;
}

.content-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-top: 24px;
}

@media (max-width: 1024px) {
    .content-columns {
        grid-template-columns: 1fr;
    }
}

.dashboard-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border: 1px solid #e5e7eb;
}

.dashboard-section h3 {
    margin: 0 0 20px 0;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.badge {
    background: #f3f4f6;
    color: #4b5563;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 24px 16px;
    background: #f9fafb;
    border-radius: 10px;
    text-decoration: none;
    color: #374151;
    border: 2px solid #e5e7eb;
    transition: all 0.3s;
}

.action-card:hover {
    background: white;
    border-color: #d1d5db;
    transform: translateY(-3px);
}

.action-icon {
    font-size: 32px;
    margin-bottom: 12px;
}

.categories-chart {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.category-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.category-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-name {
    font-weight: 500;
    color: #1f2937;
}

.category-count {
    font-size: 14px;
    color: #6b7280;
}

.category-bar {
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.category-progress {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    border-radius: 4px;
}

.category-percentage {
    text-align: right;
    font-size: 14px;
    color: #6b7280;
}

.low-stock-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.stock-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: #fef3c7;
    border-radius: 8px;
    border-left: 4px solid #f59e0b;
}

.item-name {
    font-weight: 500;
    color: #1f2937;
}

.item-stock {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}

.item-stock.low-stock {
    background: #fef3c7;
    color: #92400e;
}

.item-stock.out-of-stock {
    background: #fee2e2;
    color: #991b1b;
}

.recent-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.recent-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

.recent-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.recent-info strong {
    color: #1f2937;
    font-weight: 500;
}

.recent-category {
    font-size: 13px;
    color: #6b7280;
}

.recent-price {
    font-weight: 700;
    color: #059669;
    font-size: 16px;
}

.price-stats {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.price-stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
}

.stat-label {
    color: #6b7280;
    font-size: 14px;
}

.stat-value {
    font-weight: 600;
    color: #1f2937;
}

.no-data {
    text-align: center;
    color: #9ca3af;
    padding: 40px 20px;
    font-style: italic;
}
</style>