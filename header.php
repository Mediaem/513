<?php
// Student Name: Mikey
// Ensure session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraftCanvas Studios | Digital Art Marketplace</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Navigation specific styles */
        body { font-family: 'Lato', sans-serif; background-color: #fcfcfc; color: #333; margin: 0; }
        h1, h2, h3, .brand { font-family: 'Cinzel', serif; }
        
        header { background: white; border-bottom: 1px solid #eee; padding: 15px 0; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .nav-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        
        /* Updated Brand Style for Logo + Text */
        .brand { 
            font-size: 1.6rem; 
            color: #2c3e50; 
            text-decoration: none; 
            font-weight: 700; 
            letter-spacing: 1px;
            display: flex;       /* Use Flexbox to align image and text */
            align-items: center; /* Vertically center */
            gap: 12px;           /* Space between image and text */
        }
        
        /* Style for the new logo icon */
        .brand-icon {
            height: 45px;        /* Adjust size as needed */
            width: auto;
            border-radius: 50%;  /* Make it circular (optional) */
            object-fit: cover;
        }

        .brand span { color: #e67e22; }
        
        .nav-links { display: flex; align-items: center; gap: 20px; }
        .nav-link { text-decoration: none; color: #555; font-weight: 500; font-size: 0.9rem; text-transform: uppercase; transition: color 0.3s; }
        .nav-link:hover { color: #e67e22; }
        
        /* Cart Badge */
        .cart-link { position: relative; }
        .badge { background: #e67e22; color: white; padding: 2px 6px; border-radius: 50%; font-size: 0.75rem; position: absolute; top: -8px; right: -10px; }
        
        /* Register Button (CTA) */
        .btn-register { padding: 8px 18px; background: #2c3e50; color: white; border-radius: 4px; transition: background 0.3s; }
        .btn-register:hover { background: #34495e; color: white; }
    </style>
</head>
<body>

<header>
    <div class="nav-container">
        
        <a href="index.php" class="brand">
            <img src="images/33.jpg" alt="Logo" class="brand-icon">
            <div>CraftCanvas<span>.</span></div>
        </a>
        
        <nav class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="about.php" class="nav-link">About</a>
            <a href="products.php" class="nav-link">Shop</a>
            <a href="feedback.php" class="nav-link">Support</a>
            
            <a href="careers.php" class="nav-link">Careers</a>
            <a href="forum.php" class="nav-link">Forum</a>
            
            <?php if(isset($_SESSION['admin_logged_in'])): ?>
                <span style="color:#ddd;">|</span>
                <a href="admin_products.php" class="nav-link" style="color: #c0392b;">Admin Panel</a>
                <a href="list_customers.php" class="nav-link" style="color: #c0392b;">Clients</a>
            <?php endif; ?>

            <span style="color:#ddd;">|</span>

            <a href="cart.php" class="nav-link cart-link">
                Cart 
                <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                    <span class="badge"><?php echo count($_SESSION['cart']); ?></span>
                <?php endif; ?>
            </a>
            
            <?php if(isset($_SESSION['customer_id'])): ?>
                <a href="logout.php" class="nav-link">Logout</a>
            <?php else: ?>
                <a href="customer_login.php" class="nav-link">Login</a>
                <a href="https://mikey16.rf.gd/14-2/" target="_blank" class="nav-link btn-register">Join Now</a>
            <?php endif; ?>
        </nav>
    </div>
</header>