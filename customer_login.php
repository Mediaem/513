<?php
// Student Name: Mikey
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Sanitize input
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    
    // 2. Connect to the database
    $conn = getWPConnection();
    
    // =======================================================
    // 🟢 关键修改：根据你的截图，表名是 wpid_fc_subscribers
    // =======================================================
    $table_name = "wpid_fc_subscribers"; 
    
    // 3. Prepare SQL Query
    $sql = "SELECT * FROM $table_name WHERE email = ? AND phone = ?";
    $stmt = $conn->prepare($sql);
    
    // Safety Check
    if ($stmt === false) {
        die("Error: SQL Prepare Failed. The table '$table_name' might be missing the 'phone' column or the table name is still wrong. Database Error: " . $conn->error);
    }

    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // 4. Verify User
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // 5. Set Session Variables
        $_SESSION['customer_id'] = $user['id'];
        
        // Handle Name Logic
        $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        if (empty($fullName)) {
            $fullName = explode('@', $user['email'])[0];
        }
        
        $_SESSION['customer_name'] = $fullName;
        $_SESSION['customer_email'] = $user['email'];
        $_SESSION['customer_phone'] = $user['phone'];
        
        // 6. Redirect
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'products.php';
        header("Location: $redirect");
        exit();
    } else {
        $error = "Login failed. No account found with that Email and Phone number.<br>Please ensure you have registered correctly.";
    }
}

include 'header.php';
?>

<div class="container" style="max-width: 500px; margin-top: 60px; margin-bottom: 80px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-family: 'Cinzel', serif; color: #2c3e50;">Client Access</h2>
        <p style="color: #7f8c8d;">Login to manage your commissions.</p>
    </div>
    
    <?php if($error): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <form method="post">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: bold; font-size: 0.9rem; color: #555;">Email Address</label>
                <input type="email" name="email" required placeholder="Ex: mikey@example.com" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 30px;">
                <label style="font-weight: bold; font-size: 0.9rem; color: #555;">Phone Number <small>(Password)</small></label>
                <input type="text" name="phone" required placeholder="Ex: 1234567890" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
            </div>
            
            <button type="submit" class="btn" style="width: 100%; padding: 12px; background: #2c3e50; color: white; border: none; font-weight: bold; border-radius: 4px; cursor: pointer; transition: background 0.3s;">Login</button>
        </form>
    </div>
    
    <p style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
        New client? <a href="https://mikey16.rf.gd/14-2/" style="color: #e67e22; font-weight: bold;">Create an Account</a>
    </p>
</div>

<?php include 'footer.php'; ?>