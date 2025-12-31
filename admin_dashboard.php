<?php
// Student Name: Mikey
require_once 'config.php';
include 'header.php';

// Check Admin Session
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$conn = getEcomConnection();

// 1. Get Feedback / Contact Info
$feedback_sql = "SELECT * FROM feedback ORDER BY created_at DESC";
$feedback_result = $conn->query($feedback_sql);

// 2. Get Orders (Assuming you have an orders table based on your screenshot)
$orders_sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 10";
$orders_result = $conn->query($orders_sql);

// 3. Count Totals
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
?>

<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h1 style="font-family: 'Cinzel', serif; color: #2c3e50; margin: 0;">Admin Dashboard</h1>
        <div>
            <a href="admin_products.php" class="btn" style="background: #e67e22; margin-right: 10px;">Manage Products</a>
            <a href="logout.php" class="btn" style="background: #c0392b;">Logout</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div style="background: #2c3e50; color: white; padding: 20px; border-radius: 8px;">
            <h3><?php echo $total_products; ?></h3>
            <p>Active Products</p>
        </div>
        <div style="background: #27ae60; color: white; padding: 20px; border-radius: 8px;">
            <h3><?php echo $total_orders; ?></h3>
            <p>Total Orders</p>
        </div>
    </div>

    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 40px;">
        <h3 style="color: #2c3e50; border-bottom: 1px solid #eee; padding-bottom: 15px;">Recent Orders & Customers</h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background: #f9f9f9; text-align: left;">
                    <th style="padding: 10px;">ID</th>
                    <th style="padding: 10px;">Customer Name</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders_result && $orders_result->num_rows > 0): ?>
                    <?php while($row = $orders_result->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">#<?php echo $row['id']; ?></td>
                        <td style="padding: 10px; font-weight: bold;"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td style="padding: 10px;"><?php echo $row['order_date']; ?></td>
                        <td style="padding: 10px; color: #e67e22;">$<?php echo $row['total_amount']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="padding: 20px; text-align: center;">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h3 style="color: #2c3e50; border-bottom: 1px solid #eee; padding-bottom: 15px;">Contact Messages (Feedback)</h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background: #f9f9f9; text-align: left;">
                    <th style="padding: 10px;">Name</th>
                    <th style="padding: 10px;">Email</th>
                    <th style="padding: 10px;">Message</th>
                    <th style="padding: 10px;">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($feedback_result && $feedback_result->num_rows > 0): ?>
                    <?php while($row = $feedback_result->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px; font-weight: bold;"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($row['message']); ?></td>
                        <td style="padding: 10px; font-size: 0.9rem; color: #999;"><?php echo $row['created_at']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="padding: 20px; text-align: center;">No messages yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'footer.php'; ?>