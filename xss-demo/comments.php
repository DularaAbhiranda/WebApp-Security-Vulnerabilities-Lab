<?php
// comments.php - Vulnerable implementation
session_start();

// Simple file-based storage for demonstration purposes
$commentsFile = 'comments.txt';

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $username = isset($_POST['username']) ? $_POST['username'] : 'Anonymous';
    $comment = $_POST['comment'];
    
    // Store the comment
    $newComment = $username . ': ' . $comment . "\n";
    file_put_contents($commentsFile, $newComment, FILE_APPEND);
    
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Read existing comments
$comments = file_exists($commentsFile) ? file_get_contents($commentsFile) : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Community Comment Board</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .comment-form { margin-bottom: 20px; }
        .comments { border-top: 1px solid #ccc; padding-top: 20px; }
        .comment { margin-bottom: 10px; padding: 10px; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Community Comment Board</h1>
        
        <div class="comment-form">
            <h2>Leave a Comment</h2>
            <form method="post" action="">
                <div>
                    <label for="username">Your Name:</label>
                    <input type="text" id="username" name="username" placeholder="Anonymous">
                </div>
                <div>
                    <label for="comment">Comment:</label>
                    <textarea id="comment" name="comment" required rows="4" cols="50"></textarea>
                </div>
                <div>
                    <button type="submit">Post Comment</button>
                </div>
            </form>
        </div>
        
        <div class="comments">
            <h2>Recent Comments</h2>
            <?php
            if (!empty($comments)) {
                $commentLines = explode("\n", $comments);
                foreach ($commentLines as $line) {
                    if (!empty($line)) {
                        // VULNERABLE CODE: Directly outputs user input without sanitization
                        echo '<div class="comment">' . $line . '</div>';
                    }
                }
            } else {
                echo '<p>No comments yet. Be the first to comment!</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>