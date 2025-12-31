<?php
// Student Name: Mikey
require_once 'config.php';
include 'header.php';

// 1. Connect to the E-Commerce Database
$conn = getEcomConnection();

// 2. Fetch all products
$sql = "SELECT * FROM products ORDER BY id ASC";
$result = $conn->query($sql);
?>

<div class="container" style="margin-top: 50px; margin-bottom: 80px;">
    
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-family: 'Cinzel', serif; color: #2c3e50;">Explore Commissions</h1>
        <p style="color: #7f8c8d; max-width: 600px; margin: 10px auto;">
            Browse our catalog of services. From 2D character art to 3D models, find the perfect artist for your project.
        </p>
    </div>

    <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
        
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                
                <div class="product-card" style="background: white; border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s; display: flex; flex-direction: column;">
                    
                    <div style="height: 250px; overflow: hidden; background: #f9f9f9;">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" 
                             alt="<?php echo htmlspecialchars($row['name']); ?>" 
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                    </div>
                    
                    <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                        <h3 style="margin: 0 0 10px 0; font-size: 1.2rem; color: #2c3e50; font-weight: 600;">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </h3>
                        
                        <p style="color: #7f8c8d; font-size: 0.9rem; flex-grow: 1; margin-bottom: 20px; line-height: 1.5;">
                            <?php echo htmlspecialchars(substr($row['description'], 0, 80)) . '...'; ?>
                        </p>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid #f0f0f0; padding-top: 15px;">
                            <span style="font-weight: bold; font-size: 1.2rem; color: #e67e22;">
                                $<?php echo htmlspecialchars($row['price']); ?>
                            </span>
                            
                            <a href="product_details.php?id=<?php echo $row['id']; ?>" 
                               class="btn" 
                               style="padding: 8px 18px; font-size: 0.9rem; background: #2c3e50; color: white; text-decoration: none; border-radius: 4px; transition: background 0.3s;">
                               View Details
                            </a>
                        </div>
                    </div>
                    
                </div>
                <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #fff; border-radius: 8px;">
                <h3 style="color: #e74c3c;">No products found.</h3>
                <p>Please make sure you have run the SQL script in phpMyAdmin.</p>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<?php include 'footer.php'; ?>