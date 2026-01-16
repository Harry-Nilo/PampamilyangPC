<?php
$host = "localhost";
$user = "root";      
$pass = "admin";          
$db   = "pampamilyang_pc";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$search = isset($_GET['query']) ? trim($_GET['query']) : "";
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : "az"; 
$sql = "SELECT * FROM products WHERE 1";


if ($search !== "") {
    $keyword = $conn->real_escape_string($search);
    $sql .= " AND (product_name LIKE '%$keyword%' 
              OR brand LIKE '%$keyword%'
              OR model_number LIKE '%$keyword%')";
}


if ($category > 0) {
    $sql .= " AND category_id = $category";
}


switch ($sort) {
    case "za":
        $sql .= " ORDER BY product_name DESC";
        break;
    case "price_low":
        $sql .= " ORDER BY price ASC";
        break;
    case "price_high":
        $sql .= " ORDER BY price DESC";
        break;
    case "newest":
        $sql .= " ORDER BY date_added DESC";
        break;
    default:
        $sql .= " ORDER BY product_name ASC"; 
}

$result = $conn->query($sql);


$categories = $conn->query("SELECT * FROM category ORDER BY category_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pampamilyang PC | Pricelist</title>
    <link rel="stylesheet" href="./css/pricelist.css?v=1.0">

    <style>

    </style>
</head>
<body>

<header class="header">
        <div class="container">
            <div class="logo">PAMPAMILYANG PC</div>
            <nav class="nav">
                <a href="index" class="nav-link">Home</a>
                <a href="pc-builder" class="nav-link">PC Builder</a>
                <a href="product" class="nav-link">Product</a>
                <a href="pricelist" class="nav-link">Pricelist</a>

            </nav>
            <div class="header-icons">
                <button class="icon-btn" aria-label="Search">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" onclick="location.href='search'">
                        <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM18 18l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <button class="icon-btn" aria-label="Account" onclick="location.href='dashboard'">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM3 18a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <button class="icon-btn badge" data-count="0" aria-label="Cart" onclick="location.href='dashboard.php?section=cart'">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M1 1h3l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L22 6H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <button id="theme-toggle" class="icon-btn theme-toggle" aria-label="Toggle theme">
                    <svg class="sun-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <circle cx="10" cy="10" r="4" fill="currentColor"/>
                        <path d="M10 1v2M10 17v2M19 10h-2M3 10H1M16.07 3.93l-1.41 1.41M5.34 14.66l-1.41 1.41M16.07 16.07l-1.41-1.41M5.34 5.34L3.93 3.93" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <svg class="moon-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

<main class="main-content">

    
    <div class="search-bar">
        <form action="pricelist.php" method="GET" style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">

            
            <input type="text" name="query" placeholder="Search products..."
                value="<?php echo htmlspecialchars($search); ?>">

            
            <select name="category">
                <option value="0">All Categories</option>
                <?php 
                if ($categories->num_rows > 0) {
                    while ($cat = $categories->fetch_assoc()) {
                        $selected = ($category == $cat['category_id']) ? "selected" : "";
                        echo "<option value='{$cat['category_id']}' $selected>{$cat['category_name']}</option>";
                    }
                }
                ?>
            </select>

            
            <select name="sort">
                <option value="az" <?php if($sort=="az") echo "selected"; ?>>A → Z</option>
                <option value="za" <?php if($sort=="za") echo "selected"; ?>>Z → A</option>
                <option value="price_low" <?php if($sort=="price_low") echo "selected"; ?>>Price Low → High</option>
                <option value="price_high" <?php if($sort=="price_high") echo "selected"; ?>>Price High → Low</option>
                <option value="newest" <?php if($sort=="newest") echo "selected"; ?>>Newest First</option>
            </select>

            <button type="submit">Apply</button>
        </form>
    </div>

    
    <div class="product-grid">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $image = $row['image_url'];
                if (!file_exists($image)) {
                    $image = "./" . $image;
                }

                echo "
                <div class='product-card'>
                    <img src='{$image}' alt='Product Image'>
                    <div class='product-name'>{$row['product_name']}</div>
                    <div class='product-price'>₱" . number_format($row['price'], 2) . "</div>
                    <div class='product-stock'>Stock: {$row['stock_quantity']}</div>
                </div>";
            }
        } else {
            echo "<p style='text-align:center;'>No products found.</p>";
        }
        ?>
    </div>

</main>

<script>
function toggleTheme() {
    document.body.classList.toggle('light-mode');
    const isLight = document.body.classList.contains('light-mode');
    localStorage.setItem('theme', isLight ? 'light' : 'dark');
}

function loadTheme() {
    const theme = localStorage.getItem('theme');
    if (theme === 'light') document.body.classList.add('light-mode');
}

document.addEventListener('DOMContentLoaded', () => {
    loadTheme();
    document.getElementById('theme-toggle').addEventListener('click', toggleTheme);
});

document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.getElementById('cart-btn');
    if(!cartBtn) return;

    fetch('api/cart_count.php')
        .then(res => res.json())
        .then(data => {
            cartBtn.dataset.count = data.count || 0;
            cartBtn.classList.toggle('has-items', (data.count || 0) > 0);
        });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
