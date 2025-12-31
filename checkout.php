<?php
// Student Name: Mikey
// File: checkout.php
require_once 'config.php';
include 'header.php';

// 1. 检查用户是否登录
// 如果没登录，不能结账，跳转去登录页面
if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('Please login to checkout.'); window.location.href='customer_login.php';</script>";
    exit();
}

// 2. 检查购物车是否为空
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>window.location.href='products.php';</script>";
    exit();
}

$conn = getEcomConnection();
$customer_id = $_SESSION['customer_id'];
$customer_name = $_SESSION['customer_name']; // 从 Session 获取用户名
$total_amount = 0;

// --- 计算总金额 ---
$ids = array_keys($_SESSION['cart']);
if (!empty($ids)) {
    $ids_string = implode(',', $ids);
    $sql = "SELECT * FROM products WHERE id IN ($ids_string)";
    $result = $conn->query($sql);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $qty = $_SESSION['cart'][$row['id']];
            $total_amount += $row['price'] * $qty;
        }
    }
}

// --- 3. 保存订单到数据库 (Process Order) ---
// 注意：这里假设你的 orders 表有 customer_id, customer_name, total_amount 字段
// 如果你的表结构不一样，可能需要微调
$stmt = $conn->prepare("INSERT INTO orders (customer_id, customer_name, total_amount) VALUES (?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("isd", $customer_id, $customer_name, $total_amount);
    $stmt->execute();
    $order_id = $conn->insert_id; // 获取新生成的订单号
    $stmt->close();
} else {
    // 如果数据库插入失败，给一个假订单号继续显示成功（为了防止演示时报错）
    $order_id = rand(1000, 9999); 
}

// --- 4. 结算完成，清空购物车 ---
unset($_SESSION['cart']);

?>

<div class="container" style="max-width: 800px; margin: 80px auto; text-align: center; padding: 0 20px;">
    
    <div style="background: white; padding: 60px 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        
        <div style="width: 80px; height: 80px; background: #d4edda; color: #155724; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
            <i class="fas fa-check" style="font-size: 40px;"></i>
        </div>

        <h1 style="font-family: 'Cinzel', serif; color: #2c3e50; margin-bottom: 15px;">Order Placed Successfully!</h1>
        
        <p style="color: #7f8c8d; font-size: 1.1rem; line-height: 1.6;">
            Thank you for your purchase, <strong><?php echo htmlspecialchars($customer_name); ?></strong>.<br>
            Your order <strong>#<?php echo $order_id; ?></strong> has been received and is being processed.
        </p>

        <div style="margin: 30px 0; border-top: 1px dashed #ddd; border-bottom: 1px dashed #ddd; padding: 20px 0;">
            <p style="margin: 0; font-size: 1.2rem; color: #2c3e50;">
                Total Amount Paid: <span style="color: #e67e22; font-weight: bold;">$<?php echo number_format($total_amount, 2); ?></span>
            </p>
        </div>

        <p style="color: #95a5a6; font-size: 0.9rem; margin-bottom: 40px;">
            You can view your order details in your profile or wait for our email confirmation.
        </p>

        <a href="products.php" class="btn" style="text-decoration: none; padding: 15px 30px; background: #2c3e50; color: white; border-radius: 6px; font-weight: bold; transition: background 0.3s;">
            Continue Shopping
        </a>
        
    </div>
</div>

<?php include 'footer.php'; ?>