<?php
// Student Name: Mikey
require_once 'config.php';

// 1. Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Connect to Database
$conn = getEcomConnection();

// Init Variables
$edit_mode = false;
$edit_data = null;
$message = '';

// --- ACTION: DELETE ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: admin_products.php?msg=deleted");
        exit;
    }
}

// --- ACTION: EDIT LOAD ---
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    }
}

// --- ACTION: SAVE (ADD / UPDATE) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $image = $_POST['image'];
    $pid = $_POST['product_id'];

    if (!empty($pid)) {
        // Update Existing
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, image=? WHERE id=?");
        $stmt->bind_param("sdssi", $name, $price, $desc, $image, $pid);
        $stmt->execute();
        $msg_type = "updated";
    } else {
        // Create New
        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $desc, $image);
        $stmt->execute();
        $msg_type = "created";
    }
    header("Location: admin_products.php?msg=" . $msg_type);
    exit;
}

include 'header.php';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px;">
        <div>
            <h1 style="margin: 0; font-family: 'Cinzel', serif; color: #2c3e50;">Product Manager</h1>
            <p style="margin: 5px 0 0 0; color: #7f8c8d;">CRUD Operations Panel</p>
        </div>
        <div>
            <a href="admin_dashboard.php" class="btn-secondary" style="margin-right: 10px;">&larr; Back to Dashboard</a>
            <a href="logout.php" class="btn" style="background: #c0392b;">Logout</a>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div style="padding: 15px; margin-bottom: 30px; border-radius: 4px; text-align: center; font-weight: bold;
            <?php echo ($_GET['msg'] == 'deleted') ? 'background: #f8d7da; color: #721c24;' : 'background: #d4edda; color: #155724;'; ?>">
            <?php 
                if($_GET['msg'] == 'created') echo "✅ Product Created Successfully";
                elseif($_GET['msg'] == 'updated') echo "✅ Product Updated Successfully";
                elseif($_GET['msg'] == 'deleted') echo "🗑️ Product Deleted";
            ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px;">
        
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); height: fit-content;">
            <h3 style="margin-top: 0; color: #2c3e50; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
                <?php echo $edit_mode ? 'Edit Product' : 'Add New Product'; ?>
            </h3>
            
            <form method="post">
                <input type="hidden" name="product_id" value="<?php echo $edit_mode ? $edit_data['id'] : ''; ?>">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-weight: bold;">Product Name</label>
                    <input type="text" name="name" required value="<?php echo $edit_mode ? $edit_data['name'] : ''; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-weight: bold;">Price ($)</label>
                    <input type="number" step="0.01" name="price" required value="<?php echo $edit_mode ? $edit_data['price'] : ''; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-weight: bold;">Image Path</label>
                    <input type="text" name="image" required value="<?php echo $edit_mode ? $edit_data['image'] : ''; ?>" placeholder="images/7.jpg" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="font-weight: bold;">Description</label>
                    <textarea name="description" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo $edit_mode ? $edit_data['description'] : ''; ?></textarea>
                </div>

                <button type="submit" class="btn" style="width: 100%; padding: 12px; background: #2c3e50; color: white; border: none; font-weight: bold; border-radius: 4px; cursor: pointer;">
                    <?php echo $edit_mode ? 'Save Changes' : 'Add Product'; ?>
                </button>
                
                <?php if($edit_mode): ?>
                    <a href="admin_products.php" style="display: block; text-align: center; margin-top: 10px; color: #7f8c8d; text-decoration: none;">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <div>
            <h3 style="margin-top: 0; color: #2c3e50; margin-bottom: 20px;">Current Inventory</h3>
            
            <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa; color: #2c3e50;">
                        <tr>
                            <th style="padding: 15px; text-align: left;">Image</th>
                            <th style="padding: 15px; text-align: left;">Details</th>
                            <th style="padding: 15px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Read from Database
                        $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
                        if($result->num_rows > 0): 
                            while($p = $result->fetch_assoc()):
                        ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px; width: 80px;">
                                    <img src="<?php echo $p['image']; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td style="padding: 15px;">
                                    <div style="font-weight: bold; color: #2c3e50; font-size: 1.1rem;"><?php echo htmlspecialchars($p['name']); ?></div>
                                    <div style="color: #e67e22; font-weight: bold;">$<?php echo number_format($p['price'], 2); ?></div>
                                    <div style="font-size: 0.85rem; color: #999; margin-top: 5px;"><?php echo htmlspecialchars(substr($p['description'], 0, 50)) . '...'; ?></div>
                                </td>
                                <td style="padding: 15px; text-align: right;">
                                    <a href="admin_products.php?edit=<?php echo $p['id']; ?>" style="display: inline-block; padding: 6px 12px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; font-size: 0.8rem; margin-right: 5px;">Edit</a>
                                    
                                    <a href="admin_products.php?delete=<?php echo $p['id']; ?>" onclick="return confirm('Are you sure?');" style="display: inline-block; padding: 6px 12px; background: #c0392b; color: white; text-decoration: none; border-radius: 4px; font-size: 0.8rem;">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" style="padding: 30px; text-align: center;">No products in database.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>