<?php
// Student Name: Mikey
// File: list_customers.php
require_once 'config.php';
include 'header.php';

// 1. 安全检查 (使用默认连接检查管理员登录)
if (!isset($_SESSION['admin_logged_in']) && !isset($_SESSION['is_admin'])) {
    echo "<script>window.location.href='admin_login.php';</script>";
    exit;
}

// =========================================================
// 🔴 关键修改：建立第二个连接，专门连向 WordPress 数据库
// =========================================================
// 根据你的截图，数据库名是 'if0_39896485_wp900'
$wp_db_name = 'if0_39896485_wp900'; 

// 我们假设 Host, Username, Password 和 config.php 里的一样
// 如果不一样，请在这里手动修改 DB_USER 和 DB_PASS
$conn_wp = new mysqli(DB_HOST, DB_USER, DB_PASS, $wp_db_name);

if ($conn_wp->connect_error) {
    $wp_error = "无法连接到 WordPress 数据库: " . $conn_wp->connect_error;
}
// =========================================================

// --- 删除功能 (针对 WordPress 数据库) ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // 注意：这里使用的是 wpid_fc_subscribers
    $stmt = $conn_wp->prepare("DELETE FROM wpid_fc_subscribers WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>window.location.href='list_customers.php?msg=deleted';</script>";
        exit;
    }
}
?>

<div class="container" style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    
    <div style="border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between;">
        <div>
            <h1 style="margin: 0; color: #2c3e50;">Registered Clients</h1>
            <p style="margin: 5px 0 0 0; color: #7f8c8d;">Data Source: Database <code>if0_39896485_wp900</code></p>
        </div>
        <a href="admin_dashboard.php" class="btn-secondary">Back to Dashboard</a>
    </div>

    <?php if (isset($wp_error)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
            ⚠️ <strong>Connection Error:</strong> <?php echo $wp_error; ?>
        </div>
    <?php endif; ?>

    <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #2c3e50; color: white;">
                <tr>
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Name</th>
                    <th style="padding: 12px; text-align: left;">Email</th>
                    <th style="padding: 12px; text-align: left;">Phone</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                    <th style="padding: 12px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!isset($wp_error)) {
                    // ✅ 这里的表名必须是 wpid_fc_subscribers (根据你的截图)
                    $sql = "SELECT * FROM wpid_fc_subscribers ORDER BY created_at DESC";
                    $result = $conn_wp->query($sql);

                    if ($result && $result->num_rows > 0): 
                        while($row = $result->fetch_assoc()):
                            // 组合姓名
                            $name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                            $email = htmlspecialchars($row['email']);
                            $phone = htmlspecialchars($row['phone']);
                            $status = htmlspecialchars($row['status']);
                ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px; color: #7f8c8d;">#<?php echo $row['id']; ?></td>
                        <td style="padding: 12px; font-weight: bold; color: #2c3e50;"><?php echo $name; ?></td>
                        <td style="padding: 12px; color: #555;"><?php echo $email; ?></td>
                        <td style="padding: 12px; color: #555;"><?php echo $phone; ?></td>
                        <td style="padding: 12px;">
                            <span style="background: #e8f8f5; color: #27ae60; padding: 2px 8px; border-radius: 4px; font-size: 0.85rem;">
                                <?php echo $status; ?>
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <a href="list_customers.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete user <?php echo $name; ?>?');" style="color: #c0392b; text-decoration: none;">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            No data found in table <code>wpid_fc_subscribers</code>.
                        </td>
                    </tr>
                <?php endif; 
                } // end if no error
                ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; font-size: 0.8rem; color: #ccc;">
        Target Database: <?php echo $wp_db_name; ?> | Target Table: wpid_fc_subscribers
    </div>
</div>

<?php include 'footer.php'; ?>