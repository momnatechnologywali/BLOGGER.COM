<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <style>
        /* Internal CSS - Same as create_post.php for consistency */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to right, #f0f4f8, #d9e2ec); color: #333; }
        header { background: #007bff; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        form { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input[type="text"], select { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1); }
        #editor { border: 1px solid #ccc; padding: 10px; min-height: 300px; border-radius: 4px; background: white; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1); }
        .toolbar { margin-bottom: 10px; }
        .toolbar button { padding: 5px 10px; margin-right: 5px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background 0.3s; }
        .toolbar button:hover { background: #0056b3; }
        button[type="submit"] { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background 0.3s; }
        button[type="submit"]:hover { background: #218838; }
        .delete-btn { background: #dc3545; margin-left: 10px; }
        .delete-btn:hover { background: #c82333; }
        @media (max-width: 768px) { form { padding: 10px; } }
    </style>
</head>
<body>
    <header>
        <h1>Edit Blog Post</h1>
    </header>
    <?php
    include 'db.php';
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <form method="POST" action="">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
 
        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="1" <?php if($post['category_id']==1) echo 'selected'; ?>>Technology</option>
            <option value="2" <?php if($post['category_id']==2) echo 'selected'; ?>>Lifestyle</option>
            <option value="3" <?php if($post['category_id']==3) echo 'selected'; ?>>Business</option>
            <option value="4" <?php if($post['category_id']==4) echo 'selected'; ?>>Travel</option>
        </select>
 
        <label for="excerpt">Excerpt:</label>
        <input type="text" id="excerpt" name="excerpt" value="<?php echo htmlspecialchars($post['excerpt']); ?>" required>
 
        <label for="content">Content:</label>
        <div class="toolbar">
            <button type="button" onclick="formatText('bold')">Bold</button>
            <button type="button" onclick="formatText('italic')">Italic</button>
            <button type="button" onclick="formatText('underline')">Underline</button>
            <button type="button" onclick="formatText('h1')">H1</button>
            <button type="button" onclick="formatText('h2')">H2</button>
            <button type="button" onclick="formatText('ul')">Bullet List</button>
            <button type="button" onclick="formatText('ol')">Numbered List</button>
        </div>
        <div id="editor" contenteditable="true"><?php echo $post['content']; ?></div>
        <input type="hidden" id="content" name="content">
 
        <input type="hidden" name="id" value="<?php echo $id; ?>">
 
        <button type="submit" name="update">Update</button>
        <button type="button" class="delete-btn" onclick="deletePost()">Delete</button>
    </form>
 
    <?php
    if (isset($_POST['update'])) {
        $title = htmlspecialchars($_POST['title']);
        $excerpt = htmlspecialchars($_POST['excerpt']);
        $content = $_POST['content'];
        $category_id = $_POST['category'];
        $id = $_POST['id'];
 
        $stmt = $pdo->prepare("UPDATE posts SET title=?, excerpt=?, content=?, category_id=? WHERE id=?");
        $stmt->execute([$title, $excerpt, $content, $category_id, $id]);
 
        echo '<script>window.location.href = "home.php";</script>';
    }
    ?>
 
    <script>
        function formatText(command) {
            document.execCommand(command, false, null);
        }
 
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('content').value = document.getElementById('editor').innerHTML;
        });
 
        function deletePost() {
            if (confirm('Are you sure you want to delete this post?')) {
                window.location.href = 'edit_post.php?delete_id=<?php echo $id; ?>';
            }
        }
    </script>
 
    <?php
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id=?");
        $stmt->execute([$delete_id]);
        echo '<script>window.location.href = "home.php";</script>';
    }
    ?>
</body>
</html>
