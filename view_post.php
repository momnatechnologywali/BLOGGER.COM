<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <style>
        /* Internal CSS - Amazing, professional */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to right, #f0f4f8, #d9e2ec); color: #333; }
        header { background: #007bff; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .post-content { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .post-content h1 { color: #007bff; }
        .author-info { font-style: italic; color: #666; margin-bottom: 20px; }
        .comments { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .comment { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .comment-form { margin-top: 20px; }
        .comment-form label { display: block; margin: 10px 0 5px; }
        .comment-form input, .comment-form textarea { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .comment-form button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background 0.3s; }
        .comment-form button:hover { background: #218838; }
        .related-posts { max-width: 800px; margin: 20px auto; }
        .related-posts h3 { color: #007bff; }
        .related-post { margin-bottom: 10px; }
        .edit-btn { display: inline-block; margin: 20px 0; padding: 10px 20px; background: #ffc107; color: #333; text-decoration: none; border-radius: 4px; transition: background 0.3s; }
        .edit-btn:hover { background: #e0a800; }
        @media (max-width: 768px) { .post-content, .comments { padding: 10px; } }
    </style>
</head>
<body>
    <header>
        <h1>Blog Post</h1>
    </header>
    <?php
    include 'db.php';
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT p.*, u.name AS author, c.name AS category FROM posts p JOIN users u ON p.author_id = u.id JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="post-content">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="author-info">By <?php echo htmlspecialchars($post['author']); ?> | Published on <?php echo $post['publish_date']; ?> | Category: <?php echo htmlspecialchars($post['category']); ?></p>
        <?php echo $post['content']; ?>
        <a href="edit_post.php?id=<?php echo $id; ?>" class="edit-btn">Edit Post</a>
    </div>
 
    <div class="related-posts">
        <h3>Related Posts</h3>
        <?php
        $related_stmt = $pdo->prepare("SELECT id, title FROM posts WHERE category_id = ? AND id != ? ORDER BY publish_date DESC LIMIT 5");
        $related_stmt->execute([$post['category_id'], $id]);
        $related = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($related as $rel) {
            echo '<div class="related-post"><a href="view_post.php?id=' . $rel['id'] . '">' . htmlspecialchars($rel['title']) . '</a></div>';
        }
        ?>
    </div>
 
    <div class="comments">
        <h2>Comments</h2>
        <?php
        $comm_stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY comment_date DESC");
        $comm_stmt->execute([$id]);
        $comments = $comm_stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($comments as $comm) {
            echo '<div class="comment">';
            echo '<p><strong>' . htmlspecialchars($comm['author_name']) . '</strong> on ' . $comm['comment_date'] . '</p>';
            echo '<p>' . htmlspecialchars($comm['content']) . '</p>';
            echo '</div>';
        }
        ?>
 
        <div class="comment-form">
            <form method="POST" action="">
                <label for="author_name">Your Name:</label>
                <input type="text" id="author_name" name="author_name" required>
 
                <label for="content">Comment:</label>
                <textarea id="content" name="content" required></textarea>
 
                <input type="hidden" name="post_id" value="<?php echo $id; ?>">
 
                <button type="submit" name="submit_comment">Submit Comment</button>
            </form>
        </div>
    </div>
 
    <?php
    if (isset($_POST['submit_comment'])) {
        $author_name = htmlspecialchars($_POST['author_name']);
        $content = htmlspecialchars($_POST['content']);
        $post_id = $_POST['post_id'];
 
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, author_name, content) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $author_name, $content]);
 
        echo '<script>window.location.href = "view_post.php?id=' . $id . '";</script>';
    }
    ?>
</body>
</html>
