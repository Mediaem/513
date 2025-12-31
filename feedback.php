<?php
// Student Name: Mikey
// File: feedback.php (Support Page)
require_once 'config.php';
include 'header.php';

$msg = "";
$msg_class = "";

// 1. 处理表单提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 获取并清理输入数据
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // 简单验证
    if (!empty($name) && !empty($email) && !empty($message)) {
        
        $conn = getEcomConnection();
        
        // 2. 准备 SQL 插入语句
        $sql = "INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $name, $email, $message);
            
            // 3. 执行插入
            if ($stmt->execute()) {
                $msg = "✅ Message sent successfully! We will contact you soon.";
                $msg_class = "alert-success";
            } else {
                $msg = "❌ Error sending message. Please try again.";
                $msg_class = "alert-danger";
            }
            $stmt->close();
        } else {
            $msg = "❌ Database error.";
            $msg_class = "alert-danger";
        }
    } else {
        $msg = "⚠️ Please fill in all fields.";
        $msg_class = "alert-warning";
    }
}
?>

<style>
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .alert-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .form-control { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
</style>

<div class="container" style="max-width: 800px; margin: 50px auto; padding: 0 20px;">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-family: 'Cinzel', serif; color: #2c3e50;">Customer Support</h1>
        <p style="color: #7f8c8d;">Have a question? We'd love to hear from you.</p>
    </div>

    <?php if ($msg != ""): ?>
        <div style="padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;" class="<?php echo $msg_class; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
        
        <form method="post" action="feedback.php">
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; color: #555;">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Your Name">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; color: #555;">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="name@example.com">
            </div>

            <div style="margin-bottom: 30px;">
                <label style="font-weight: bold; color: #555;">Message</label>
                <textarea name="message" rows="6" class="form-control" required placeholder="How can we help you?"></textarea>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 15px; background: #2c3e50; color: white; border: none; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 1rem;">
                Send Message
            </button>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>