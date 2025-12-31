<?php
// Student Name: Mikey
include 'header.php';
?>

<div class="hero" style="background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), url('images/1.jpg'); background-size: cover; background-position: center; color: white; padding: 100px 20px; text-align: center;">
    <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Bring Your Imagination to Life</h1>
    <p style="font-size: 1.5rem; max-width: 800px; margin: 0 auto 30px;">
        CraftCanvas Studios connects you with elite digital artists for custom commissions. 
        From business branding to personalized gifts, we make art happen.
    </p>
    <a href="products.php" class="btn" style="background: #e67e22; padding: 15px 30px; font-size: 1.2rem;">Explore Commissions</a>
</div>

<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <h2 style="text-align: center; margin-bottom: 40px;">Why Choose CraftCanvas?</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <div style="text-align: center; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
            <h3 style="color: #2c3e50;">Structured Workflow</h3>
            <p>Unlike generic print-on-demand services, we offer a dedicated commissioning system with milestone payments and clear timelines.</p>
        </div>

        <div style="text-align: center; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
            <h3 style="color: #2c3e50;">Real-Time Collaboration</h3>
            <p>Collaborate directly with artists. Provide feedback, request revisions, and ensure the final piece matches your vision perfectly.</p>
        </div>

        <div style="text-align: center; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
            <h3 style="color: #2c3e50;">Secure Delivery</h3>
            <p>Receive your high-resolution files (PNG, JPEG, SVG, PSD) through our secure digital delivery platform.</p>
        </div>
    </div>
</div>

<div class="container" style="background: #fdfbf7; padding: 40px; border-radius: 8px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Trending Styles</h2>
        <a href="products.php" class="btn-secondary">View All Works</a>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
        <img src="images/2.jpg" style="width:100%; height: 300px; object-fit: cover; border-radius:4px;">
        <img src="images/3.jpg" style="width:100%; height: 300px; object-fit: cover; border-radius:4px;">
        <img src="images/4.jpg" style="width:100%; height: 300px; object-fit: cover; border-radius:4px;">
        <img src="images/5.jpg" style="width:100%; height: 300px; object-fit: cover; border-radius:4px;">
    </div>
</div>

<div class="container" style="margin-top: 60px; margin-bottom: 60px; text-align: center;">
    <h2 style="font-family: 'Cinzel', serif; color: #2c3e50; margin-bottom: 30px;">Visit Our Headquarters</h2>
    
    <div style="max-width: 100%; border: 1px solid #ddd; padding: 10px; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <img src="images/31.jpg" alt="Our Location Map" style="width: 100%; height: auto; display: block; border-radius: 4px;">
        
        <div style="margin-top: 15px; color: #7f8c8d; font-size: 0.9rem;">
            <p><strong>Address:</strong> 123 Creative Avenue, Design District, Art City.</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>