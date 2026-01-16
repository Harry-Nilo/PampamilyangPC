<?php
session_start();
include 'api/connect.php';

$connect = new mysqli($host, $user, $password, $db);
if ($connect->connect_error) die("Connection failed: " . $connect->connect_error);

// Get product_id from URL
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
if ($product_id <= 0) die("Invalid product ID.");

// Fetch product details
$sql = "SELECT p.*, c.category_name 
        FROM products p
        JOIN category c ON p.category_id = c.category_id
        WHERE p.product_id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Product not found.");
$product = $result->fetch_assoc();

// Fetch product specs
$sql_info = "SELECT * FROM product_info WHERE product_id = ? ORDER BY info_id ASC";
$stmt_info = $connect->prepare($sql_info);
$stmt_info->bind_param("i", $product_id);
$stmt_info->execute();
$info_result = $stmt_info->get_result();
$product_info = [];
while ($row = $info_result->fetch_assoc()) {
    $product_info[] = $row;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $product['product_name']; ?></title>
<link rel="stylesheet" href="css/product_info.css?v=1.0">
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
                <button class="icon-btn" aria-label="Search" onclick="location.href='search'">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM18 18l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <button class="icon-btn" aria-label="Account" onclick="location.href='dashboard'">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM3 18a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <button id="cart-btn" class="icon-btn badge" data-count="0" aria-label="Cart" onclick="location.href='dashboard.php?section=cart'">
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

<div class="product-container">

    <div class="detail-container">
         <div class="product-image">
             <img src="<?= $product['image_url']; ?>" alt="<?= htmlspecialchars($product['product_name']); ?>">
         </div>

         <div class="product-details">
             <h1><?= htmlspecialchars($product['product_name']); ?></h1>
             <p class="price">₱<?= number_format($product['price'], 2); ?></p>

             <h3>Specifications & Features</h3>
             <!-- Short Description -->
             <?php if (!empty($product_info[0]['short_description'])): ?>
                 <p class="short-desc"><?= htmlspecialchars($product_info[0]['short_description']); ?></p>
             <?php endif; ?>

             <!-- Long Description -->
             <?php if (!empty($product_info[0]['long_description'])): ?>
                 <div class="long-desc">
                     <?= nl2br(htmlspecialchars($product_info[0]['long_description'])); ?>
                 </div>
             <?php endif; ?>

             <!-- Key Features -->
             <?php if (!empty($product_info[0]['key_features'])): ?>
                 <div class="features">
                     <h3>Key Features</h3>
                     <ul>
                         <?php 
                         $features = explode("\n", $product_info[0]['key_features']);
                         foreach ($features as $feature) {
                             echo "<li>" . htmlspecialchars(trim($feature)) . "</li>";
                         }
                         ?>
                     </ul>
                 </div>
             <?php endif; ?>
                     
             <button class="buy-btn" onclick="window.location.href='../public/api/addtocartfunc.php?product_id=<?= $product['product_id']; ?>'">
                 Add to Cart
             </button>
         </div>        
    </div>

    <a class="info-back" href="product">Back to Products</a>
</div>


<script>

// document.querySelector('.buy-btn').addEventListener('click', function() {
//     alert('Product added to cart!');
// });





function toggleTheme() {
    document.body.classList.toggle('light-mode');
    const isLightMode = document.body.classList.contains('light-mode');
    localStorage.setItem('theme', isLightMode ? 'light' : 'dark');
}

function loadTheme() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadTheme();


    document.getElementById('theme-toggle').addEventListener('click', toggleTheme);
});
async function searchProducts(query, category = '') {
    try {
        const res = await fetch(`search.php?query=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`);
        const data = await res.json();

        // Update filteredProducts and render
        filteredProducts = data.map(p => ({
            ...p,
            categoryLower: p.category.toLowerCase(),
            brandLower: p.brand.toLowerCase()
        }));

        updateProductCounts();
        renderProducts();
        updateResultsInfo();
    } catch (err) {
        console.error('Error fetching search results:', err);
    }
}

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
