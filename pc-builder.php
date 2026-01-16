<?php
session_start();

if (isset($_GET['query'])) {
  $query = htmlspecialchars($_GET['query']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pampamilyang PC | PC Builder</title>
<link rel="stylesheet" href="./css/pc-builder.css?v=1.2">
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="logo">PAMPAMILYANG PC</div>
            <nav class="nav">
                <a href="index" class="nav-link">Home</a>
                <a href="#" class="nav-link">PC Builder</a>
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

<main class="main-content">
<div class="container">
<h1>PC Builder</h1>

<table class="pc-builder-table">
  <thead>
    <tr>
      <th>Component</th>
      <th>Product</th>
      <th>Details</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $categories = ["CPU","GPU","Motherboard","RAM","Storage","PSU","Case","Cooler"];
    foreach($categories as $cat):
    $qtyDisabled = ($cat=="RAM"||$cat=="Storage") ? '' : 'disabled';
    $note = "";
    if($cat=="CPU") $note = "Start by choosing your preferred CPU before adding other components.";
    if($cat=="Motherboard") $note = "You need to choose a CPU before choosing your Motherboard.";
    ?>
    <tr data-category="<?= $cat ?>">
  <td><?= $cat ?></td>
  <td class="product-name">—</td>
  <td class="product-details"><?= $note ? "<span class='note'>$note</span>" : "" ?></td>
  <td class="product-price">₱0.00</td>
  <td class="product-qty"><input type="number" min="1" value="1" <?= $qtyDisabled ?>></td>
  <td>
    <!-- Add enabled except Motherboard -->
    <button class="add-btn" data-category="<?= $cat ?>" <?= $cat=="Motherboard" ? "disabled" : "" ?>>Add</button>
    <!-- Edit/Delete always disabled at start -->
    <button class="edit-btn" data-category="<?= $cat ?>" disabled>Edit</button>
    <button class="remove-btn" data-category="<?= $cat ?>" disabled>Remove</button>
  </td>
</tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="pc-builder-subtotal">
  <strong>SUBTOTAL: </strong><span id="pc-subtotal">₱0.00</span>
  <button id="add-to-cart-btn" disabled>
    <!-- Cart Icon -->
    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" style="margin-right:6px;">
      <path d="M1 1h3l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L22 6H6" 
            stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </svg>
    Add to Cart
  </button>
</div>

<!-- Modal -->
<div id="pc-modal" class="pc-modal" style="display:none;">
  <div class="pc-modal-content">
    <span class="pc-modal-close">&times;</span>
    <h3>Select Product</h3>
    <div id="pc-modal-products" class="modal-products-grid"></div>
  </div>
</div>

</div>
</main>

<script src="./pc-builder.js"></script>

</body>
</html>