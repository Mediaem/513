<?php
// Student Name: Mikey
require_once 'config.php';
include 'header.php';

// 初始化变量
$cart_items = [];
$total_price = 0;

// 读取购物车数据
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $conn = getEcomConnection();
    
    $ids = array_keys($_SESSION['cart']);
    // 这里的 if 是为了防止空数组报错
    if (!empty($ids)) {
        $ids_string = implode(',', $ids);
        $sql = "SELECT * FROM products WHERE id IN ($ids_string)";
        $result = $conn->query($sql);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $qty = $_SESSION['cart'][$row['id']];
                $subtotal = $row['price'] * $qty;
                $total_price += $subtotal;
                
                $row['qty'] = $qty;
                $row['subtotal'] = $subtotal;
                $cart_items[] = $row;
            }
        }
    }
}
?>

<div class="container" style="max-width: 1000px; margin: 40px auto; min-height: 500px; padding: 0 20px;">
    <h1 style="font-family: 'Cinzel', serif; color: #2c3e50; margin-bottom: 30px;">Your Shopping Cart</h1>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            Cart updated successfully!
        </div>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
        <div style="text-align: center; padding: 50px; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #ddd; margin-bottom: 20px;"></i>
            <h3>Your cart is empty</h3>
            <p style="color: #7f8c8d;">Looks like you haven't added any art yet.</p>
            <a href="products.php" class="btn" style="display: inline-block; margin-top: 20px; background: #2c3e50; color: white; padding: 10px 25px; text-decoration: none; border-radius: 4px;">Start Shopping</a>
        </div>
    <?php else: ?>
        
        <form action="cart_actions.php" method="post">
            <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                        <tr>
                            <th style="padding: 15px; text-align: left;">Product</th>
                            <th style="padding: 15px; text-align: center;">Price</th>
                            <th style="padding: 15px; text-align: center;">Quantity</th>
                            <th style="padding: 15px; text-align: center;">Subtotal</th>
                            <th style="padding: 15px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; display: flex; align-items: center; gap: 15px;">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <span style="font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars($item['name']); ?></span>
                            </td>
                            
                            <td style="padding: 15px; text-align: center;">$<?php echo number_format($item['price'], 2); ?></td>
                            
                            <td style="padding: 15px; text-align: center;">
                                <input type="number" name="qty[<?php echo $item['id']; ?>]" value="<?php echo $item['qty']; ?>" min="1" style="width: 60px; padding: 5px; text-align: center; border: 1px solid #ddd; border-radius: 4px;">
                            </td>
                            
                            <td style="padding: 15px; text-align: center; font-weight: bold; color: #e67e22;">
                                $<?php echo number_format($item['subtotal'], 2); ?>
                            </td>
                            
                            <td style="padding: 15px; text-align: right;">
                                <a href="cart_actions.php?action=remove&id=<?php echo $item['id']; ?>" 
                                   onclick="return confirm('Remove this item?');"
                                   style="color: #e74c3c; text-decoration: none;" title="Remove Item">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                
                <button type="submit" name="update_cart" class="btn-secondary" style="background: white; border: 1px solid #2c3e50; color: #2c3e50; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-sync-alt"></i> Update Quantities
                </button>

                <div style="text-align: right;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: #2c3e50; margin-bottom: 15px;">
                        Grand Total: <span style="color: #e67e22;">$<?php echo number_format($total_price, 2); ?></span>
                    </div>
                    
                    <a href="products.php" class="btn-secondary" style="margin-right: 15px; text-decoration: none; padding: 12px 25px; border: 1px solid #7f8c8d; color: #7f8c8d; border-radius: 4px;">Continue Shopping</a>
                    
                    <a href="checkout.php" class="btn" style="text-decoration: none; padding: 12px 30px; background: #e67e22; color: white; border-radius: 4px; font-weight: bold;">
                        Proceed to Checkout <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </form>

    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>