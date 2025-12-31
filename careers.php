<?php
// Student Name: Mikey
require_once 'config.php';

$msg = '';

// Handle file upload and form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    
    // File upload logic
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $allowed = ['pdf', 'doc', 'docx'];
        $filename = $_FILES['resume']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Validate file extension
        if (in_array(strtolower($filetype), $allowed)) {
            // Create uploads directory if it does not exist
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            // Generate unique filename to prevent overwriting
            $new_filename = uniqid() . "_" . $filename;
            $destination = "uploads/" . $new_filename;
            
            // Move file from temp storage to destination
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $destination)) {
                // Connect to DB and save application info
                $conn = getWPConnection(); 
                $stmt = $conn->prepare("INSERT INTO job_applications (applicant_name, applicant_email, job_position, resume_path) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $position, $destination);
                
                if ($stmt->execute()) {
                    $msg = "<div class='alert success' style='color: green;'>Application submitted successfully! Good luck.</div>";
                } else {
                    $msg = "<div class='alert error' style='color: red;'>Database error: " . $conn->error . "</div>";
                }
            } else {
                $msg = "<div class='alert error' style='color: red;'>Failed to upload file. Check folder permissions.</div>";
            }
        } else {
            $msg = "<div class='alert error' style='color: red;'>Invalid file type. Only PDF and DOC allowed.</div>";
        }
    } else {
        $msg = "<div class='alert error' style='color: red;'>Please upload a resume.</div>";
    }
}

include 'header.php';
?>

<div class="container">
    <div style="text-align: center; margin: 40px 0;">
        <h2>Join Our Team</h2>
        <p style="color: #7f8c8d;">We are looking for creative minds to join CraftCanvas Studios.</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 50px;">
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: var(--shadow);">
            <h3 style="color: #2c3e50;">Digital Artist (Freelance)</h3>
            <p>Create custom commissions for clients worldwide. Styles needed: Oil, Anime, Minimalist.</p>
            <p><strong>Requirements:</strong> Portfolio link, 2+ years experience.</p>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: var(--shadow);">
            <h3 style="color: #2c3e50;">Frontend Developer</h3>
            <p>Help maintain our e-commerce platform. PHP and MySQL knowledge required.</p>
            <p><strong>Requirements:</strong> Student or Junior level.</p>
        </div>
    </div>

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; border: 1px solid #eee;">
        <h3 style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">Apply Now</h3>
        <?php echo $msg; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Full Name</label>
                <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Position Applying For</label>
                <select name="position" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="Digital Artist">Digital Artist</option>
                    <option value="Frontend Developer">Frontend Developer</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Upload Resume (PDF/DOC)</label>
                <input type="file" name="resume" required style="width: 100%; margin-top: 5px;">
            </div>
            
            <button type="submit" class="btn" style="width: 100%; padding: 12px; cursor: pointer;">Submit Application</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>