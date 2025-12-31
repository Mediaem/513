<?php
// Student Name: Mikey
require_once 'config.php';
include 'header.php';

$msg = '';

// Security Key configuration
$SECRET_KEY = 'BossMikey'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_pass = trim($_POST['confirm_password']);
    $input_key = trim($_POST['secret_key']);

    if ($input_key !== $SECRET_KEY) {
        $msg = "<div style='background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:20px;'>Security Key is incorrect!</div>";
    } elseif ($password !== $confirm_pass) {
        $msg = "<div style='background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:20px;'>Passwords do not match!</div>";
    } else {
        $conn = getEcomConnection();
        
        // Check duplicate username
        $check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $msg = "<div style='background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:20px;'>Username already exists.</div>";
        } else {
            // Insert new admin (using plain text to match login logic)
            $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);
            
            if ($stmt->execute()) {
                $msg = "<div style='background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:20px;'>Admin registered successfully! <a href='admin_login.php' style='font-weight:bold; color:#155724;'>Login Here</a></div>";
            } else {
                $msg = "<div style='background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:20px;'>Error: " . $conn->error . "</div>";
            }
        }
    }
}
?>

<div class="container" style="max-width: 900px; margin: 60px auto; display: flex; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; min-height: 550px;">
    
    <div style="flex: 1; position: relative;">
        <img src="images/32.jpg" style="width: 100%; height: 100%; object-fit: cover; display: block;">
        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 30px; color: white;">
            <h3 style="margin: 0;">Staff Portal</h3>
            <p style="margin: 5px 0 0; font-size: 0.9rem; opacity: 0.9;">Authorized personnel only.</p>
        </div>
    </div>
    
    <div style="flex: 1; background: white; padding: 40px; display: flex; flex-direction: column; justify-content: center;">
        <h2 style="font-family: 'Cinzel', serif; color: #2c3e50; margin-bottom: 10px;">Create Admin ID</h2>
        <p style="color: #7f8c8d; margin-bottom: 25px;">Enter security credentials to proceed.</p>
        
        <?php echo $msg; ?>

        <form method="post">
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; color: #555; margin-bottom: 5px;">Security Key</label>
                <input type="password" name="secret_key" required placeholder="Required" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; color: #555; margin-bottom: 5px;">New Username</label>
                <input type="text" name="username" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; color: #555; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; color: #555; margin-bottom: 5px;">Confirm Password</label>
                <input type="password" name="confirm_password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <button type="submit" class="btn" style="width: 100%; padding: 12px; background: #e67e22; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                Register Account
            </button>
        </form>
        
        <p style="margin-top: 20px; font-size: 0.9rem; text-align: center;">
            <a href="admin_login.php" style="color: #2c3e50; font-weight: bold;">Back to Login</a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>