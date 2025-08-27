<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <style>
        /* Internal CSS - Amazing, professional */
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
        @media (max-width: 768px) { form { padding: 10px; } }
    </style>
</head>
<body>
    <header>
        <h1>Create New Blog Post</h1>
    </header>
    <form method="POST" action="">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
 
        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="1">Technology</option>
            <option value="2">Lifestyle</option>
            <option value="3">Business</option>
            <option value="4">Travel</option>
        </select>
 
        <label for="excerpt">Excerpt:</label>
        <input type="text" id="excerpt" name="excerpt" required>
 
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
        <div id="editor" contenteditable="true"></div>
        <input type="hidden" id="content" name="content">
 
        <!-- Assuming author_id=1 for simplicity, in real add login -->
        <input type="hidden" name="author_id" value="1">
 
        <button type="submit" name="submit">Publish</button>
    </form>
 
    <?php
    if (isset($_POST['submit'])) {
        include 'db.php';
 
        $title = htmlspecialchars($_POST['title']);
        $excerpt = htmlspecialchars($_POST['excerpt']);
        $content = $_POST['content']; // Raw HTML from editor
        $author_id = $_POST['author_id'];
        $category_id = $_POST['category'];
 
        $stmt = $pdo->prepare("INSERT INTO posts (title, excerpt, content, author_id, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $excerpt, $content, $author_id, $category_id]);
 
        echo '<script>window.location.href = "home.php";</script>';
    }
    ?>
 
    <script>
        // Inline JS for rich text editor (simple contenteditable)
        function formatText(command) {
            document.execCommand(command, false, null);
        }
 
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('content').value = document.getElementById('editor').innerHTML;
        });
    </script>
</body>
</html>
