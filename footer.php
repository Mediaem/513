<?php
// Student Name: Mikey
?>
    <footer style="background-color: #2c3e50; color: white; padding: 40px 0; margin-top: 50px;">
        <div class="container" style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            
            <div style="flex: 1; min-width: 200px;">
                <h3 style="font-family: 'Cinzel', serif; margin-bottom: 15px;">CraftCanvas.</h3>
                <p style="font-size: 0.9rem; color: #bdc3c7;">Connecting visionaries with digital artists.</p>
            </div>

            <div style="flex: 1; min-width: 200px;">
                <h4 style="margin-bottom: 15px;">Quick Links</h4>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="index.php" style="color: #bdc3c7; text-decoration: none;">Home</a></li>
                    <li><a href="products.php" style="color: #bdc3c7; text-decoration: none;">Shop</a></li>
                    <li><a href="about.php" style="color: #bdc3c7; text-decoration: none;">About Us</a></li>
                </ul>
            </div>

            <div style="flex: 1; min-width: 200px;">
                <h4 style="margin-bottom: 15px;">Admin Area</h4>
                <p style="font-size: 0.8rem; color: #7f8c8d;">Staff access only.</p>
                <?php if(isset($_SESSION['admin_logged_in'])): ?>
                    <a href="admin_products.php" style="color: #e67e22; font-weight: bold; text-decoration: none;">Enter Control Panel</a>
                    <br>
                    <a href="logout.php" style="color: #e74c3c; font-size: 0.8rem;">Logout</a>
                <?php else: ?>
                    <a href="admin_login.php" style="color: #bdc3c7; text-decoration: underline;">Staff Login</a>
                <?php endif; ?>
            </div>

        </div>
        <div style="text-align: center; margin-top: 30px; border-top: 1px solid #34495e; padding-top: 20px; font-size: 0.8rem; color: #7f8c8d;">
            &copy; <?php echo date("Y"); ?> CraftCanvas Studios. Student Name: Mikey.
        </div>
    </footer>
</body>
</html>