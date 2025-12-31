<?php
// Student Name: Mikey
require_once 'config.php';
include 'header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // --- Hardcoded Admin Credentials ---
    $admin_user = 'Mikey';
    $admin_pass = 'xjn016027';

    if ($username === $admin_user && $password === $admin_pass) {
        // [FIX] Use the standard session key
        $_SESSION['admin_logged_in'] = true; 
        $_SESSION['admin_name'] = $admin_user;
        
        // Redirect to the new Dashboard to see all info
        echo "<script>window.location.href='admin_dashboard.php';</script>";
        exit();
    } else {
        $error = "Invalid Username or Password.";
    }
}
?>

<div class="container" style="max-width: 900px; margin: 60px auto; display: flex; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; min-height: 500px;">
    <div style="flex: 1; position: relative;">
        <img src="images/30.jpg" style="width: 100%; height: 100%; object-fit: cover; display: block;">
        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 30px; color: white;">
            <h3 style="margin: 0;">Welcome Back</h3>
            <p style="margin: 5px 0 0; font-size: 0.9rem; opacity: 0.9;">Access your dashboard.</p>
        </div>
    </div>
    
    <div style="flex: 1; background: white; padding: 50px; display: flex; flex-direction: column; justify-content: center;">
        <h2 style="font-family: 'Cinzel', serif; color: #2c3e50; margin-bottom: 10px;">Admin Login</h2>
        <p style="color: #7f8c8d; margin-bottom: 30px;">Please sign in to continue.</p>
        
        <?php if($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; color: #555; margin-bottom: 8px;">Username</label>
                <input type="text" name="username" required placeholder="Enter username" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 30px;">
                <label style="display: block; font-weight: bold; color: #555; margin-bottom: 8px;">Password</label>
                <input type="password" name="password" required placeholder="Enter password" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <button type="submit" class="btn" style="width: 100%; padding: 14px; background: #2c3e50; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                Secure Login
            </button>
        </form>
        
        <p style="margin-top: 20px; font-size: 0.9rem; text-align: center;">
            <a href="index.php" style="color: #7f8c8d; text-decoration: none;">&larr; Back to Website</a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>