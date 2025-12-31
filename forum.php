<?php
// Student Name: Mikey
require_once 'config.php';
include 'header.php';

// =======================================================
// CRITICAL FIX: Use getEcomConnection() instead of getWPConnection()
// This connects to the database where 'forum_topics' actually exists.
// =======================================================
$conn = getEcomConnection();

// Check if a specific topic is selected via URL (e.g., forum.php?topic_id=5)
$view_topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : null;

// --- Handle Form Submissions (POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Logic to Post a New Topic
    if (isset($_POST['new_topic'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        
        // Get author name from Session (if logged in), otherwise 'Anonymous'
        $author = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Anonymous';
        
        $stmt = $conn->prepare("INSERT INTO forum_topics (title, content, author_name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $author);
        $stmt->execute();
        
        // Refresh page to show the new topic
        header("Location: forum.php");
        exit();
    }
    
    // 2. Logic to Post a New Comment
    if (isset($_POST['new_comment'])) {
        $topic_id = $_POST['topic_id'];
        $comment = trim($_POST['comment']);
        $author = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Guest';
        
        $stmt = $conn->prepare("INSERT INTO forum_comments (topic_id, comment_text, author_name) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $topic_id, $comment, $author);
        $stmt->execute();
        
        // Refresh back to the specific topic page
        header("Location: forum.php?topic_id=" . $topic_id);
        exit();
    }
}
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    
    <?php if ($view_topic_id): 
        // === VIEW SINGLE TOPIC MODE ===
        
        // Sanitize ID for safety
        $safe_id = intval($view_topic_id);
        
        // Fetch the Topic
        $topic_sql = $conn->query("SELECT * FROM forum_topics WHERE id = $safe_id");
        
        if($topic_sql && $topic_sql->num_rows > 0):
            $topic = $topic_sql->fetch_assoc();
            
            // Fetch Comments for this Topic
            $comments_sql = $conn->query("SELECT * FROM forum_comments WHERE topic_id = $safe_id ORDER BY created_at ASC");
    ?>
            <a href="forum.php" class="btn-secondary" style="margin-bottom: 20px; display:inline-block; text-decoration:none; padding: 8px 15px; background: #95a5a6; color: white; border-radius: 4px;">&larr; Back to Topics</a>
            
            <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px;">
                <h1 style="margin-top:0; color: #2c3e50; font-family: 'Cinzel', serif;"><?php echo htmlspecialchars($topic['title']); ?></h1>
                <p style="color: #7f8c8d; font-size: 0.9rem;">
                    Posted by <strong style="color: #e67e22;"><?php echo htmlspecialchars($topic['author_name']); ?></strong> 
                    on <?php echo date('F j, Y, g:i a', strtotime($topic['created_at'])); ?>
                </p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <div style="line-height: 1.8; font-size: 1.1rem; color: #444;">
                    <?php echo nl2br(htmlspecialchars($topic['content'])); ?>
                </div>
            </div>

            <h3 style="color: #2c3e50;">Comments</h3>
            
            <?php if ($comments_sql->num_rows > 0): ?>
                <?php while($c = $comments_sql->fetch_assoc()): ?>
                    <div style="background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #e67e22; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                        <p style="margin: 0 0 8px 0; font-size: 0.9rem; color: #e67e22;">
                            <strong><?php echo htmlspecialchars($c['author_name']); ?></strong> says:
                        </p>
                        <p style="margin: 0; color: #555; line-height: 1.6;">
                            <?php echo nl2br(htmlspecialchars($c['comment_text'])); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #999; font-style: italic;">No comments yet. Be the first to share your thoughts!</p>
            <?php endif; ?>

            <div style="margin-top: 40px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h4 style="margin-top: 0; color: #2c3e50;">Leave a Reply</h4>
                <form method="post">
                    <input type="hidden" name="new_comment" value="1">
                    <input type="hidden" name="topic_id" value="<?php echo $safe_id; ?>">
                    
                    <textarea name="comment" required placeholder="Write your comment here..." style="width: 100%; height: 100px; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;"></textarea>
                    
                    <button type="submit" class="btn" style="background: #2c3e50; color: white; padding: 12px 25px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">Post Comment</button>
                </form>
            </div>
            
        <?php else: ?>
            <div style="text-align: center; padding: 50px;">
                <h2>Topic not found.</h2>
                <a href="forum.php" class="btn">Return to Forum</a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        
        <div style="text-align: center; margin-bottom: 50px;">
            <h1 style="font-family: 'Cinzel', serif; color: #2c3e50;">Community Forum</h1>
            <p style="color: #7f8c8d;">Join the discussion with other creators and clients.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 40px;">
            
            <div>
                <?php 
                // Fetch all topics, newest first
                $result = $conn->query("SELECT * FROM forum_topics ORDER BY created_at DESC");
                
                if ($result && $result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
                ?>
                    <div style="background: white; padding: 25px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform 0.2s; border: 1px solid #eee;">
                        <h3 style="margin: 0 0 10px 0;">
                            <a href="forum.php?topic_id=<?php echo $row['id']; ?>" style="text-decoration: none; color: #2c3e50; font-weight: 600;">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </a>
                        </h3>
                        <div style="display: flex; justify-content: space-between; color: #999; font-size: 0.85rem;">
                            <span>By <strong style="color: #e67e22;"><?php echo htmlspecialchars($row['author_name']); ?></strong></span>
                            <span><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                        </div>
                    </div>
                <?php endwhile; else: ?>
                    <div style="text-align: center; padding: 40px; background: white; border-radius: 8px; border: 1px dashed #ccc;">
                        <p style="color: #7f8c8d;">No topics found. Why not start the first one?</p>
                    </div>
                <?php endif; ?>
            </div>

            <div style="background: #2c3e50; color: white; padding: 30px; border-radius: 8px; height: fit-content; position: sticky; top: 20px;">
                <h3 style="color: white; margin-top: 0; margin-bottom: 20px;">Start a New Topic</h3>
                
                <form method="post">
                    <input type="hidden" name="new_topic" value="1">
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-weight: bold; font-size: 0.9rem; display: block; margin-bottom: 5px;">Topic Title</label>
                        <input type="text" name="title" required placeholder="e.g., Question about commissions..." style="width: 100%; padding: 10px; border-radius: 4px; border: none;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="font-weight: bold; font-size: 0.9rem; display: block; margin-bottom: 5px;">Content</label>
                        <textarea name="content" required rows="5" placeholder="What's on your mind?" style="width: 100%; padding: 10px; border-radius: 4px; border: none; font-family: sans-serif;"></textarea>
                    </div>
                    
                    <?php if(!isset($_SESSION['customer_id'])): ?>
                        <div style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                            <p style="font-size: 0.85rem; color: #ecf0f1; margin: 0;">
                                Note: You are currently <strong>Anonymous</strong>. 
                                <br><a href="customer_login.php" style="color: #e67e22; font-weight: bold; text-decoration: underline;">Login here</a> to post with your name.
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn" style="width: 100%; background: #e67e22; color: white; border: none; padding: 12px; font-weight: bold; cursor: pointer; border-radius: 4px; transition: background 0.3s;">Create Thread</button>
                </form>
            </div>
            
        </div>
    <?php endif; ?>
    
</div>

<?php include 'footer.php'; ?>