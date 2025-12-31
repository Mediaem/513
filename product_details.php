<?php
// Student Name: Mikey
// File: product_details.php
require_once 'config.php';
include 'header.php';

// 1. 获取产品 ID (Get Product ID from URL)
// 检查 URL 是否包含 ?id=数字
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // 转换为整数，防止 SQL 注入
    
    // 2. 连接数据库并查询
    $conn = getEcomConnection();
    
    // 使用预处理语句 (Prepared Statement) 安全查询
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // 检查产品是否存在
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // ID 存在但找不到产品
        echo "<div class='container' style='margin-top:100px; text-align:center;'><h2>Product not found.</h2><a href='products.php' class='btn'>Back to Shop</a></div>";
        include 'footer.php';
        exit();
    }
} else {
    // URL 里没有 ID
    echo "<div class='container' style='margin-top:100px; text-align:center;'><h2>Invalid Product ID.</h2><a href='products.php' class='btn'>Back to Shop</a></div>";
    include 'footer.php';
    exit();
}
?>

<div class="container" style="margin-top: 50px; margin-bottom: 80px;">
    
    <div style="margin-bottom: 20px; color: #7f8c8d;">
        <a href="index.php" style="color: #7f8c8d; text-decoration: none;">Home</a> &gt; 
        <a href="products.php" style="color: #7f8c8d; text-decoration: none;">Shop</a> &gt; 
        <span style="color: #2c3e50; font-weight: bold;"><?php echo htmlspecialchars($product['name']); ?></span>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 40px;">
        
        <div style="flex: 1; min-width: 300px;">
            <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; padding: 10px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     style="width: 100%; height: auto; display: block; border-radius: 4px;">
            </div>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <h1 style="font-family: 'Cinzel', serif; color: #2c3e50; margin-top: 0;"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <p style="font-size: 2rem; color: #e67e22; font-weight: bold; margin: 15px 0;">
                $<?php echo htmlspecialchars($product['price']); ?>
            </p>

            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border-left: 4px solid #2c3e50; margin-bottom: 25px;">
                <p style="color: #555; line-height: 1.6; margin: 0;">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>
            </div>

            <form action="add_to_cart.php" method="post" style="margin-bottom: 30px;">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="number" name="quantity" value="1" min="1" max="10" style="padding: 12px; width: 60px; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                    <button type="submit" class="btn" style="background: #e67e22; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; flex-grow: 1;">
                        Add to Cart
                    </button>
                </div>
            </form>

            <div style="border-top: 1px solid #eee; padding-top: 20px;">
                <h3 style="color: #2c3e50; margin-bottom: 15px;">What's Included?</h3>
                <ul style="list-style: none; padding: 0; color: #555; line-height: 2;">
                    <li>✅ <strong>High Resolution:</strong> 300 DPI files ready for print.</li>
                    <li>✅ <strong>Commercial Use:</strong> Full rights for your projects.</li>
                    <li>✅ <strong>File Formats:</strong> JPG, PNG, and Source File (PSD/AI).</li>
                    <li>✅ <strong>Revisions:</strong> Up to 3 rounds of changes included.</li>
                    <li>⚡ <strong>Delivery:</strong> Digital download within 3-5 days.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-bottom: 80px; border-top: 1px solid #eee; padding-top: 40px;">
    <h3 style="text-align: center; margin-bottom: 30px; color: #2c3e50;">Secure Commission Process</h3>
    <div style="display: flex; justify-content: space-around; text-align: center; flex-wrap: wrap; gap: 20px;">
        <div style="flex: 1; min-width: 200px;">
            <h4 style="color: #e67e22;">1. Order</h4>
            <p style="font-size: 0.9rem; color: #7f8c8d;">Select your style and provide details.</p>
        </div>
        <div style="flex: 1; min-width: 200px;">
            <h4 style="color: #e67e22;">2. Create</h4>
            <p style="font-size: 0.9rem; color: #7f8c8d;">Artist works on your draft.</p>
        </div>
        <div style="flex: 1; min-width: 200px;">
            <h4 style="color: #e67e22;">3. Download</h4>
            <p style="font-size: 0.9rem; color: #7f8c8d;">Receive high-quality files instantly.</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>