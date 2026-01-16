<?php
$host = "localhost";
$user = "root";
$password = "admin";
$db = "pampamilyang_pc";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$query = $_GET['query'] ?? '';
$category = $_GET['category'] ?? '';

$query = trim($query);
$category = trim($category);

// Prepare SQL with optional category filter
$sql = "SELECT 
            p.product_id AS id,
            c.category_name AS category,
            p.product_name AS name,
            p.brand,
            p.price,
            p.stock_quantity AS stock,
            p.image_url AS image,
            COALESCE((SELECT AVG(rating) FROM Review WHERE product_id = p.product_id), 0) AS rating
        FROM Products p
        JOIN Category c ON p.category_id = c.category_id
        WHERE p.product_name LIKE ?";

$params = ["%$query%"];

if ($category !== '') {
    $sql .= " AND c.category_name = ?";
    $params[] = $category;
}

// Limit for AJAX dropdown
$limit = isset($_GET['ajax']) ? 5 : 100;
$sql .= " ORDER BY p.product_name ASC LIMIT $limit";

$stmt = $conn->prepare($sql);

if ($category !== '') {
    $stmt->bind_param("ss", ...$params);
} else {
    $stmt->bind_param("s", $params[0]);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $row["price"] = (float)$row["price"];
    $row["rating"] = (float)$row["rating"];
    $row["stock"] = (int)$row["stock"];
    $products[] = $row;
}

// AJAX request returns only JSON
if (isset($_GET['ajax'])) {
    header("Content-Type: application/json");
    echo json_encode($products);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Products</title>
<link rel="stylesheet" href="css/homepage.css">
<link rel="icon" type="image/png" href="assets/PPClogo.png">
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #fff;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 20px;
}

.search-container {
    max-width: 700px;
    width: 100%;
    text-align: center;
    position: relative;
}
.search-results-dropdown {
    position: absolute;
    top: 100%; /* immediately below input */
    left: 0;
    width: 100%;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
    pointer-events: auto;
}
.search-bar {
    display: flex;
    border: 2px solid #ff69b4;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
}

select, input {
    border: none;
    padding: 12px;
    font-size: 1rem;
    outline: none;
}

select {
    background-color: #f9f9f9;
    border-right: 1px solid #ccc;
}

input {
    flex: 1;
}

.search-button {
    background-color: #ff69b4;
    color: white;
    padding: 0 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-button:hover {
    background-color: #ff85c1;
}

.search-button::after {
    content: '🔍';
    margin-left: 8px;
}

.popular-search {
    margin-bottom: 30px;
}

.popular-search button {
    background-color: #eee;
    border: none;
    padding: 10px 16px;
    margin: 5px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 0.95rem;
}

.popular-search button:hover {
    background-color: #ddd;
}

.search-results-dropdown {
    position: absolute;
    background: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    width: 100%;
    max-width: 700px;
    z-index: 100;
    margin-top: -10px;
}

.search-result-item {
    display: flex;
    align-items: center;
    padding: 10px;
    gap: 10px;
    cursor: pointer;
}

.search-result-item:hover {
    background-color: #f0f0f0;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
    margin-top: 40px;
    width: 100%;
    max-width: 900px;
}

.product-card {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 10px;
    text-align: center;
}

.product-card img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}
</style>
</head>
<body>

<div class="search-container">
    <div class="search-bar">
        <select id="category-select">
            <option value="">All Categories</option>
            <option value="CPU" <?php if ($category === 'CPU') echo 'selected'; ?>>CPU</option>
            <option value="GPU" <?php if ($category === 'GPU') echo 'selected'; ?>>GPU</option>
            <option value="Motherboard" <?php if ($category === 'Motherboard') echo 'selected'; ?>>Motherboard</option>
            <option value="RAM" <?php if ($category === 'RAM') echo 'selected'; ?>>RAM</option>
            <option value="Storage" <?php if ($category === 'Storage') echo 'selected'; ?>>Storage</option>
            <option value="PSU" <?php if ($category === 'PSU') echo 'selected'; ?>>PSU</option>
            <option value="Cooler" <?php if ($category === 'Cooler') echo 'selected'; ?>>Cooler</option>
            <option value="Case" <?php if ($category === 'Case') echo 'selected'; ?>>Case</option>
            
        </select>
        <input type="text" id="search-input" placeholder="WHAT ARE YOU LOOKING FOR?" value="<?php echo htmlspecialchars($query); ?>">
        <div class="search-button" id="search-button"></div>
    </div>

    <div class="popular-search">
        <button onclick="quickSearch('nvidia')">NVIDIA</button>
        <button onclick="quickSearch('AMD')">AMD</button>
        <button onclick="quickSearch('ram')">16GB</button>
        <button onclick="quickSearch('Asus')">Asus</button>

    </div>

    <div id="search-results" class="search-results-dropdown"></div>
</div>

<div class="products-grid" id="products-grid">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                <h2><?php echo $product['name']; ?></h2>
                <p>₱<?php echo number_format($product['price'], 2); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found matching your search.</p>
    <?php endif; ?>
</div>


<script>

const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');
const searchButton = document.getElementById('search-button');
const categorySelect = document.getElementById('category-select');
const productsGrid = document.getElementById('products-grid');

productsGrid.style.display = 'none';


// Function to redirect to product.php with query and category
function redirectToProductPage(searchTerm) {
    const category = categorySelect.value;
    window.location.href = `product?query=${encodeURIComponent(searchTerm)}&category=${encodeURIComponent(category)}`;
}

searchInput.addEventListener('input', async () => {
    const query = searchInput.value.trim();
    const category = categorySelect.value;

    searchResults.innerHTML = '';

    if (!query) return;

    try {
        const res = await fetch(
            `search.php?query=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}&ajax=1`
        );
        const data = await res.json();

        if (data.length === 0) {
            const noItem = document.createElement('div');
            noItem.className = 'search-result-item';
            noItem.textContent = 'No products found';
            searchResults.appendChild(noItem);
            return;
        }

        data.forEach(product => {
            const item = document.createElement('div');
            item.className = 'search-result-item';
            item.innerHTML = `
                <img src="${product.image}" alt="${product.name}" width="50">
                <span>${product.name}</span>
            `;
            item.addEventListener('click', () => redirectToProductPage(product.name));
            searchResults.appendChild(item);
        });

    } catch (err) {
        console.error('Error fetching search results:', err);
    }
});


// Search button click
searchButton.addEventListener('click', () => {
    const query = searchInput.value.trim();
    if (query) {
        redirectToProductPage(query);
    }
});

searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query) {
            redirectToProductPage(query);
        }
    }
});

// Quick search buttons
function quickSearch(term) {
    searchInput.value = term;
    redirectToProductPage(term);
}


</script>


</body>
</html>
