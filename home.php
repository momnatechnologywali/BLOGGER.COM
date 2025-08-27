<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Homepage</title>
    <style>
        /* Internal CSS - Amazing, real-looking, professional */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to right, #f0f4f8, #d9e2ec); color: #333; }
        header { background: #007bff; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        header h1 { margin: 0; font-size: 2.5em; text-shadow: 1px 1px 2px rgba(0,0,0,0.2); }
        nav { background: #0056b3; padding: 10px; }
        nav ul { list-style: none; padding: 0; margin: 0; display: flex; justify-content: center; }
        nav li { margin: 0 15px; }
        nav a { color: white; text-decoration: none; font-weight: bold; transition: color 0.3s; }
        nav a:hover { color: #ffd700; }
        .search-bar { margin: 20px auto; width: 80%; max-width: 600px; }
        .search-bar form { display: flex; }
        .search-bar input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px 0 0 4px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1); }
        .search-bar button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 0 4px 4px 0; cursor: pointer; transition: background 0.3s; }
        .search-bar button:hover { background: #218838; }
        .posts { max-width: 1200px; margin: 20px auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; padding: 0 20px; }
        .post { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: transform 0.3s, box-shadow 0.3s; }
        .post:hover { transform: translateY(-5px); box-shadow: 0 6px 16px rgba(0,0,0,0.2); }
        .post h2 { margin: 0 0 10px; font-size: 1.8em; color: #007bff; }
        .post p { margin: 0 0 10px; }
        .post .author { font-style: italic; color: #666; }
        .create-btn { display: block; width: 200px; margin: 20px auto; padding: 10px; background: #ffc107; color: #333; text-align: center; text-decoration: none; border-radius: 4px; font-weight: bold; transition: background 0.3s; }
        .create-btn:hover { background: #e0a800; }
        footer { background: #007bff; color: white; text-align: center; padding: 10px; margin-top: 20px; }
        @media (max-width: 768px) { .posts { grid-template-columns: 1fr; } nav ul { flex-direction: column; } nav li { margin: 10px 0; } }
    </style>
</head>
<body>
    <header>
        <h1>My Blog</h1>
    </header>
    <nav>
        <ul>
            <li><a href="home.php?category=Technology">Technology</a></li>
            <li><a href="home.php?category=Lifestyle">Lifestyle</a></li>
            <li><a href="home.php?category=Business">Business</a></li>
            <li><a href="home.php?category=Travel">Travel</a></li>
        </ul>
    </nav>
    <div class="search-bar">
        <form method="GET" action="home.php">
            <input type="text" name="search" placeholder="Search posts..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <a href="create_post.php" class="create-btn">Create New Post</a>
    <div class="posts">
        <?php
        include 'db.php';
 
        $sql = "SELECT p.id, p.title, p.excerpt, u.name AS author, p.publish_date 
                FROM posts p JOIN users u ON p.author_id = u.id";
 
        if (isset($_GET['category'])) {
            $category = htmlspecialchars($_GET['category']);
            $sql .= " JOIN categories c ON p.category_id = c.id WHERE c.name = :category";
        } elseif (isset($_GET['search'])) {
            $search = '%' . htmlspecialchars($_GET['search']) . '%';
            $sql .= " WHERE p.title LIKE :search OR p.excerpt LIKE :search";
        }
 
        $sql .= " ORDER BY p.publish_date DESC LIMIT 10";
 
        $stmt = $pdo->prepare($sql);
 
        if (isset($_GET['category'])) {
            $stmt->bindParam(':category', $category);
        } elseif (isset($_GET['search'])) {
            $stmt->bindParam(':search', $search);
        }
 
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
        if ($posts) {
            foreach ($posts as $post) {
                echo '<div class="post">';
                echo '<h2><a href="view_post.php?id=' . $post['id'] . '">' . htmlspecialchars($post['title']) . '</a></h2>';
                echo '<p>' . htmlspecialchars($post['excerpt']) . '</p>';
                echo '<p class="author">By ' . htmlspecialchars($post['author']) . ' on ' . $post['publish_date'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No posts found.</p>';
        }
        ?>
    </div>
    <footer>
        <p>&copy; 2025 My Blog</p>
    </footer>
</body>
</html>
