<?php
session_start();

// Redirect to login if no active session
if (!isset($_SESSION['email'])) {
    header("Location: login-page.php");
    exit();
}

require_once __DIR__ . '/api/connect.php';

$email = $_SESSION['email'];

// Fetch customer record
$stmt = $connect->prepare("SELECT * FROM customer WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    $customer_id = $customer['customer_id'];
    $is_verified = (int)$customer['verified']; // 0 = not verified, 1 = verified
} else {
    // fallback if user not found (shouldn’t happen)
    $customer = [
        'first_name' => 'User',
        'last_name'  => '',
        'email'      => $email,
        'verified'   => 0
    ];
    $customer_id = null;
    $is_verified = 0;
}

// Fetch address record (if any)
$address = [];
if ($customer_id) {
    $stmt = $connect->prepare("SELECT * FROM address WHERE customer_id = ? LIMIT 1");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $address_result = $stmt->get_result();

    if ($address_result && $address_result->num_rows > 0) {
        $address = $address_result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pampamilyang PC | Profile Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="assets/PPClogo.png">
    <link rel="stylesheet" href="css/dashboard.css?v=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
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

    <div class="Dashboard-Section">
            <div class="container">
                <div class="profile-section">
                    <div class="profile-sidebar">
                        <div class="profile-info">
                            <div class="profile-avatar">
                                <i class='bx bxs-user'></i>
                            </div>
                            <h3 class="profile-name">
                                <?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?>
                            </h3>
                            <p class="profile-email"><?= htmlspecialchars($customer['email']) ?></p>
                        </div>
                        <ul class="profile-menu">
                            <li><a href="#" class="active"><i class='bx bxs-user-detail'></i>Personal Information</a></li>
                            <li><a href="#" class="cart-link"><i class='bx bxs-cart'></i></i>Cart</a></li>
                            <li><a href="#" class="order-link"><i class='bx bxs-package'></i></i></i>Orders</a></li>
                            <li><a href="#" class="settings-link"><i class='bx bxs-cog'></i>Settings</a></li>
                            <li><a href="../src/config/logout.php"><i class='bx bx-log-out'></i>Logout</a></li>
                        </ul>
                    </div>
    
                    <div class="profile-content">
                        <form action="api/update.php" method="POST" class="personal-info-form">
                            <div class="content-header">
                                <h2>Personal Information</h2>
                            </div>
    
                                <div class="form-group">
                                    <label for="FirstName">First Name</label>
                                    <input type="text" id="FirstName" name="FirstName" value="<?= htmlspecialchars($customer['first_name'])?>">
                                </div>
                                <div class="form-group">
                                    <label for="LastName">Last Name</label>
                                    <input type="text" id="LastName" name="LastName" value="<?= htmlspecialchars($customer['last_name']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                        <input type="email" id="email" name="email"
                                        value="<?= htmlspecialchars($customer['email']) . ($is_verified === 1 ? ' | (Your account is Verified)' : ' | (It seems that your account is Not yet Verified. Verify it now to get good deals and checkout.)') ?>"
                                        readonly>

                                        <?php if ($is_verified === 0): ?>
                                            <!-- Only show button if NOT verified -->
                                            <button type="button" class="verify-btn" onclick="window.location.href='verify_acc.php'">Verify Email</button>
                                        <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($customer['contact_number']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="address" placeholder="Seems like you don't have address yet. Click edit to add one."
                                        value="<?= htmlspecialchars(
                                            trim(($address['street'] ?? '') . ' ' .
                                                 ($address['city'] ?? '') . ' ' .
                                                 ($address['region'] ?? '') . ' ' .
                                                 ($address['barangay'] ?? '') . ' ' .
                                                 ($address['postal_code'] ?? ''))
                                        ) ?>" readonly>
                                    <!-- address (hidden by default- when the user clicked the edit button this will pop up and in the address it will show the region, city, barangay, street, postal code together in the address) -->
                                    <div class="hidden-address-fields">
                                        <div class="form-group">
                                                <label for="region">Region</label>
                                            <input type="text" id="region" name="region" placeholder="Add your region" value="<?= htmlspecialchars($address['region']?? '') ?>">
                                        </div>
                                        <div class="form-group">
                                                <label for="city">City</label>
                                            <input type="text" id="city" name="city" placeholder="Add your city" value="<?= htmlspecialchars($address['city']?? '') ?>">
                                        </div>
                                        <div class="form-group">
                                                <label for="barangay">Barangay</label>
                                            <input type="text" id="barangay" name="barangay" placeholder="Add your barangay" value="<?= htmlspecialchars($address['barangay']?? '') ?>">
                                        </div>
                                        <div class="form-group">
                                                <label for="street">Street</label>
                                            <input type="text" id="street" name="street" placeholder="Add your street" value="<?= htmlspecialchars($address['street']?? '') ?>">
                                        </div>
                                        <div class="form-group">
                                                <label for="postal_code">Postal Code</label>
                                            <input type="text" id="postal_code" name="postal_code" placeholder="Add your postal code" value="<?= htmlspecialchars($address['postal_code']?? '') ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-edit">Edit</button>
                                <button type="submit" class="btn-cancel">Cancel</button>
                                <button type="submit" class="btn-save">Save Changes</button>
                        </form>

<!-- Cart (hidden by default) -->
    
<?php
// Load customer's cart items
$cart_items = [];
$cart_sql = "SELECT ci.*, p.product_name, p.price, p.image_url, p.description, p.stock_quantity
             FROM cart_item ci
             JOIN cart c ON ci.cart_id = c.cart_id
             JOIN products p ON ci.product_id = p.product_id
             WHERE c.customer_id = ?";
$stmt = $connect->prepare($cart_sql);
$stmt->bind_param("i", $customer['customer_id']);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $cart_items[] = $row;
}
?>
<div class="cart-section">
    <h2>My Cart</h2>
    <?php if(empty($cart_items)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <ul class="cart-items">
            <?php foreach($cart_items as $item): ?>
                <li class="cart-item" data-price="<?= $item['price'] ?>" data-id="<?= $item['cart_item_id'] ?>">

                    <input type="checkbox" class="select-item">
                    <!-- <span class="checkmark"></span> -->
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                    <div class="cart-item-info">


                        <div class="text-cart-left-content">
                           <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                           <p><?= htmlspecialchars($item['description']) ?></p>
                           <span>₱<?= number_format($item['price'], 2) ?></span>
                           <p class="stock-info">Available Stock: <?= (int)$item['stock_quantity'] ?></p>                            
                        </div>


                        <div class="right-rmv-qnty-counter">
                           <div class="quantity-controls">
                               <button class="decrement">-</button>
                               <input type="number" class="quantity" value="<?= $item['quantity'] ?>" min="1">
                               <button class="increment">+</button>
                           </div>
                           <button class="remove-item">
                                <svg class="trash-icon"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M136.7 5.9C141.1-7.2 153.3-16 167.1-16l113.9 0c13.8 0 26 8.8 30.4 21.9L320 32 416 32c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 8.7-26.1zM32 144l384 0 0 304c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-304zm88 64c-13.3 0-24 10.7-24 24l0 192c0 13.3 10.7 24 24 24s24-10.7 24-24l0-192c0-13.3-10.7-24-24-24zm104 0c-13.3 0-24 10.7-24 24l0 192c0 13.3 10.7 24 24 24s24-10.7 24-24l0-192c0-13.3-10.7-24-24-24zm104 0c-13.3 0-24 10.7-24 24l0 192c0 13.3 10.7 24 24 24s24-10.7 24-24l0-192c0-13.3-10.7-24-24-24z"/>
                               </svg>
                            </button>                            
                        </div>

                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="cart-footer">
            <p>Subtotal: ₱<span id="subtotal">0.00</span></p>
            <button id="checkout" disabled>Checkout</button>
        </div>
    <?php endif; ?>
</div>
    
<!-- Order Plan (hidden by default) -->
<div class="order-section" style="display: none;">
    <h2 style="margin-bottom: 20px;">My Orders</h2>
    <?php
    $orders_sql = "
        SELECT o.order_id, o.order_date, o.total_amount, o.payment_method
        FROM `order` o
        WHERE o.customer_id = ?
        ORDER BY o.order_date DESC
    ";

    $stmt = $connect->prepare($orders_sql);
    $stmt->bind_param("i", $customer['customer_id']);
    $stmt->execute();
    $orders_result = $stmt->get_result();
    ?>

    <?php if ($orders_result->num_rows === 0): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <ul class="order-list">
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <?php
                // Load items for each order, now include product_id
                $item_sql = "
                    SELECT oi.product_id, oi.quantity, oi.price_each, p.product_name, p.image_url
                    FROM order_item oi
                    JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_id = ?
                ";
                $stmt_items = $connect->prepare($item_sql);
                $stmt_items->bind_param("i", $order['order_id']);
                $stmt_items->execute();
                $items_res = $stmt_items->get_result();
                ?>

                <li class="order-card">
                    <small class="order-date">Ordered at: <?= htmlspecialchars($order['order_date']) ?></small>

                    <div class="order-method">
                        Payment Method: <?= htmlspecialchars($order['payment_method']) ?>
                    </div>

                    <ul class="order-items">
                        <?php while ($item = $items_res->fetch_assoc()): ?>
                                            <?php
                // Fetch existing review for this product by this customer
                $review_sql = "SELECT review_id, rating, comment FROM review WHERE product_id = ? AND customer_id = ?";
                $stmt_review = $connect->prepare($review_sql);
                $stmt_review->bind_param('ii', $item['product_id'], $customer['customer_id']);
                $stmt_review->execute();
                $review_res = $stmt_review->get_result();
                $review = $review_res->fetch_assoc();
                ?>
                            <li class="order-item">

                                <img src="<?= htmlspecialchars($item['image_url'] ?? 'assets/default-product.png') ?>" class="order-item-img">

                                <div class="order-item-flex-content">
                                    <div class="order-item-info">
                                        <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                                        <span>₱<?= number_format((float)$item['price_each'], 2) ?></span>
                                        <p>Qty: <?= (int)$item['quantity'] ?></p>
                                    </div>
                            
                                    <button class="review-btn"
                                        data-product-id="<?= $item['product_id'] ?>"
                                        data-product-name="<?= htmlspecialchars($item['product_name']) ?>"
                                        data-image-url="<?= htmlspecialchars($item['image_url'] ?? 'assets/default-product.png') ?>"
                                        <?= $review ? 'data-rating="'.$review['rating'].'" data-comment="'.htmlspecialchars($review['comment']).'"' : '' ?>
                                    >
                                    <?= $review ? 'Edit Review' : 'Add Review' ?>
                                    </button>                                       
                                </div>
   

                            </li>
                        <?php endwhile; ?>
                    </ul>

                    <div class="order-total">
                        Total: ₱<?= number_format((float)$order['total_amount'], 2) ?>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>

<div id="reviewModal" class="review-modal">
    <div class="review-modal-content">
        <span id="closeModal" class="review-close">&times;</span>

        <div class="review-product-info">
            <img id="reviewProductImage" src="" alt="" class="review-modal-product-img">
            <h3 id="reviewProductName" class="review-product-name"></h3>
        </div>

        <form id="reviewForm" class="review-form">
            <input type="hidden" id="product_id" name="product_id">
            <input type="hidden" id="order_id" name="order_id">
            
            <label class="review-label">Rating:</label>
            <div class="review-star-rating" id="starRating">
                <i class="review-star" data-value="1">&#9733;</i>
                <i class="review-star" data-value="2">&#9733;</i>
                <i class="review-star" data-value="3">&#9733;</i>
                <i class="review-star" data-value="4">&#9733;</i>
                <i class="review-star" data-value="5">&#9733;</i>
            </div>
            <input type="hidden" name="rating" id="rating" required>

            <label class="review-label">Comment:</label>
            <textarea name="comment" id="comment" rows="4" placeholder="Write your review..." class="review-comment-box" required></textarea>

            <button type="submit" class="review-submit-btn">Submit Review</button>
        </form>
    </div>
</div>

                                <!-- Settings (hidden by default) -->
    
                                <div class="settings-section" style="display: none;">
                                    <h2 style="margin-bottom: 20px;">Settings</h2>
                                        <form action="api/update.php" method="POST" style="margin: 0;">
                                            <div class="form-group">
                                                <label for="current_password">Current Password</label>
                                                <input type="password" id="current_password" name="current_password" placeholder="Enter Current Password" required>
                                            </div>
    
                                            <div class="form-group">
                                                <label for="new_password">New Password</label>
                                                <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" required>
                                            </div>
    
                                            <div class="form-group">
                                                <label for="confirm_password">Confirm New Password</label>
                                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
                                            </div>
    
                                            <button type="submit" name="change_password" class="btn-save-settings">Save Password</button>
                                        </form>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<script>

//FUNC TO UPDATE THE DATACOUNR ON NAV (num of prods in cart)
document.addEventListener('DOMContentLoaded', () => {
    const cartSection = document.querySelector('.cart-section');
    if (!cartSection) return; // Exit if no cart section on this page

    const checkoutBtn = cartSection.querySelector('#checkout');
    const cartBtn = document.getElementById('cart-btn');

    // Update subtotal
    function updateSubtotal() {
        let subtotal = 0;
        cartSection.querySelectorAll('.cart-item').forEach(item => {
            const checkbox = item.querySelector('.select-item');
            const quantity = parseInt(item.querySelector('.quantity').value);
            const price = parseFloat(item.dataset.price);
            if (checkbox.checked) subtotal += price * quantity;
        });
        const subtotalElem = cartSection.querySelector('#subtotal');
        if (subtotalElem) subtotalElem.innerText = subtotal.toFixed(2);
        if (checkoutBtn) checkoutBtn.disabled = subtotal === 0;
    }

    // Update cart badge in navbar
    // function updateCartBadge() {
    //     if (!cartBtn) return;
    //     const count = cartSection.querySelectorAll('.cart-item').length;
    //     cartBtn.dataset.count = count;
    //     cartBtn.classList.toggle('has-items', count > 0);
    // }

    // Update quantity via AJAX
    function updateCartQuantity(cart_item_id, quantity) {
        fetch('api/cart_action.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: `action=update_quantity&cart_item_id=${cart_item_id}&quantity=${quantity}`
        }).then(res => res.json()).then(() => {
            updateSubtotal();
            updateCartBadge();
        });
    }

    // Remove item via AJAX
    function removeCartItem(cart_item_id, itemElement) {
        fetch('api/cart_action.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: `action=remove_item&cart_item_id=${cart_item_id}`
        }).then(res => res.json()).then(data => {
            if (data.success) {
                itemElement.remove();
                updateSubtotal();
                updateCartBadge();
            }
        });
    }

    // Attach events to each cart item
    cartSection.querySelectorAll('.cart-item').forEach(item => {
        const quantityInput = item.querySelector('.quantity');
        const incrementBtn = item.querySelector('.increment');
        const decrementBtn = item.querySelector('.decrement');
        const removeBtn = item.querySelector('.remove-item');
        const checkbox = item.querySelector('.select-item');

        if (incrementBtn) {
            incrementBtn.addEventListener('click', () => {
                quantityInput.value = parseInt(quantityInput.value) + 1;
                updateCartQuantity(item.dataset.id, quantityInput.value);
            });
        }

        if (decrementBtn) {
            decrementBtn.addEventListener('click', () => {
                let current = parseInt(quantityInput.value) - 1;
                if (current <= 0) {
                    removeCartItem(item.dataset.id, item);
                } else {
                    quantityInput.value = current;
                    updateCartQuantity(item.dataset.id, current);
                }
            });
        }

        if (removeBtn) removeBtn.addEventListener('click', () => removeCartItem(item.dataset.id, item));
        if (checkbox) checkbox.addEventListener('change', updateSubtotal);
    });

    updateSubtotal();
    updateCartBadge();

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            const selectedItems = [];
            cartSection.querySelectorAll('.cart-item').forEach(item => {
                if (item.querySelector('.select-item').checked) {
                    selectedItems.push({
                        cart_item_id: item.dataset.id,
                        quantity: item.querySelector('.quantity').value
                    });
                }
            });
            console.log('Checkout items:', selectedItems);
            // send to server if needed
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);

    if (params.get('section') === 'cart') {
        setTimeout(() => {
            const cartLink = document.querySelector('.cart-link');
            if (cartLink) cartLink.click();
        }, 150); // Allows the fade/show script to fully load
    }
    if (params.get('section') === 'orders') {
    setTimeout(() => {
        const orderLink = document.querySelector('.order-link');
        if (orderLink) orderLink.click();
    }, 150);
    }   
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('show_cart') === '1') {
        // trigger the click on cart menu link
        document.querySelector('.cart-link').click();
    }
});

// Profile menu navigation with fade transitions
document.addEventListener('DOMContentLoaded', function() {
    const menuLinks = document.querySelectorAll('.profile-menu a');
    const OrderSection = document.querySelector('.order-section');
    const CartSection = document.querySelector('.cart-section');
    const SettingsSection = document.querySelector('.settings-section');
    const personalInfoForm = document.querySelector('.personal-info-form');

    // collect sections in array for easy handling
    const sections = [OrderSection, CartSection, SettingsSection, personalInfoForm];

    // make sure fade/show classes exist on sections 
    sections.forEach(section => {
        if (!section) return;
        section.classList.add('fade');
    });

    // show only personalInfoForm by default
    sections.forEach(s => {
        if (!s) return;
        s.style.display = (s === personalInfoForm) ? 'block' : 'none';
        if (s === personalInfoForm) {
            // small timeout so CSS transition can run if necessary
            setTimeout(() => s.classList.add('show'), 10);
        } else {
            s.classList.remove('show');
        }
    });

    const TRANSITION_MS = 400; // match the CSS transition duration (0.4s)
    let isAnimating = false;

    function hideAllSections(callback) {
        // immediately remove "show" from all to start fade-out
        sections.forEach(s => {
            if (!s) return;
            s.classList.remove('show');
        });

        // after transition duration, set display: none for all hidden sections
        setTimeout(() => {
            sections.forEach(s => {
                if (!s) return;
                // only hide those that are not supposed to be visible 
                if (!s.classList.contains('show')) {
                    s.style.display = 'none';
                }
            });
            if (typeof callback === 'function') callback();
        }, TRANSITION_MS);
    }

    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // allow logout link to act normally
            if (this.textContent.trim().includes('Logout')) return;

            e.preventDefault();

            // If an animation is running, ignore clicks
            if (isAnimating) return;

            // mark animating so extra clicks are ignored until transition finishes
            isAnimating = true;

            // update active classes on menu
            menuLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            // decide target section
            let target = null;
            if (this.classList.contains('order-link')) {
                target = OrderSection;
            } else if (this.classList.contains('cart-link')) {
                target = CartSection;
            }
            else if (this.classList.contains('settings-link')) {
                target = SettingsSection;

            } else {
                // fallback / personal info
                target = personalInfoForm;
            }

            // If target is already visible and has .show, do nothing
            if (target && target.classList.contains('show')) {
                isAnimating = false;
                return;
            }

            // Start hide all, then show target
            hideAllSections(() => {
                if (!target) {
                    isAnimating = false;
                    return;
                }

                // Make target visible then trigger fade-in
                target.style.display = 'block';

                // Ensure browser registers the display change before adding .show
                requestAnimationFrame(() => {
                    // tiny delay to ensure transition runs
                    setTimeout(() => {
                        target.classList.add('show');

                        // after transition completes allow clicks again
                        setTimeout(() => {
                            isAnimating = false;
                        }, TRANSITION_MS);
                    }, 10);
                });
            });
        });
    });
});

   // Edit / Cancel / Save button logic
document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.querySelector('.btn-edit');
    const cancelBtn = document.querySelector('.btn-cancel');
    const saveBtn = document.querySelector('.btn-save');
    const inputs = document.querySelectorAll('.personal-info-form input');
    const hiddenFields = document.querySelector('.hidden-address-fields');
    const addressInput = document.getElementById('address');
    const personalInfoForm = document.querySelector('.personal-info-form');

    // Hide cancel/save buttons and hidden address fields initially
    cancelBtn.style.display = 'none';
    saveBtn.style.display = 'none';
    if (hiddenFields) hiddenFields.style.display = 'none';

    // Store original values
    let originalValues = {};
    function saveOriginalValues() {
        inputs.forEach(input => {
            originalValues[input.id] = input.value;
        });
    }
    saveOriginalValues();

// Edit button
editBtn.addEventListener('click', function(e) {
    e.preventDefault();
    editBtn.style.display = 'none';
    cancelBtn.style.display = 'inline-block';
    saveBtn.style.display = 'inline-block';
    saveOriginalValues();
    const lockedFields = ['email', 'address'];
    inputs.forEach(input => {
        if (!lockedFields.includes(input.id)) {
            input.removeAttribute('readonly');
        }
    });
    // Show hidden fields if any
    if (hiddenFields) hiddenFields.style.display = 'block';
});
    // Cancel button
    cancelBtn.addEventListener('click', function(e) {
        e.preventDefault();
        inputs.forEach(input => {
            if (originalValues[input.id] !== undefined) {
                input.value = originalValues[input.id];
            }
            input.setAttribute('readonly', true);
        });
        editBtn.style.display = 'inline-block';
        cancelBtn.style.display = 'none';
        saveBtn.style.display = 'none';
        if (hiddenFields) hiddenFields.style.display = 'none';
    });

    // Save button
    saveBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const street = document.getElementById('street').value.trim();
        const barangay = document.getElementById('barangay').value.trim();
        const city = document.getElementById('city').value.trim();
        const region = document.getElementById('region').value.trim();
        const postal = document.getElementById('postal_code').value.trim();

        // Combine into one string
        const fullAddress = `${street} ${barangay} ${city} ${region} ${postal}`;

        // Put combined string into the visible address field
        addressInput.value = fullAddress;

        // Lock inputs again
        inputs.forEach(input => input.setAttribute('readonly', true));
        editBtn.style.display = 'inline-block';
        cancelBtn.style.display = 'none';
        saveBtn.style.display = 'none';
        if (hiddenFields) hiddenFields.style.display = 'none';

        // Submit form
        personalInfoForm.submit();
    });
});
        // Change Pass


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


document.addEventListener('DOMContentLoaded', () => {
    if(window.location.hash === '#cart'){
        document.querySelector('.cart-link').click(); // opens cart section
    }
});

</script>

<div id="checkout-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Checkout</h2>
        <div id="checkout-items"></div>

        <div class="checkout-footer">
            <div class="checklist-checkout">
                <p>Total: ₱<span id="checkout-total">0.00</span></p>
                <input type="checkbox" id="cod-option"> Cash-on-Delivery
            </div>
            <button id="confirm-payment" data-verified="<?= $is_verified === 1 ? 'true' : 'false' ?>">Confirm Payment</button>
        </div>
    </div>
</div>

<script>

// Checkout modal logic
document.addEventListener('DOMContentLoaded', () => {
    const checkoutBtn = document.getElementById('checkout');
    const checkoutModal = document.getElementById('checkout-modal');
    const closeModal = checkoutModal.querySelector('.close');
    const confirmPaymentBtn = document.getElementById('confirm-payment');
    const codCheckbox = document.getElementById('cod-option');
    const checkoutItemsContainer = document.getElementById('checkout-items');
    const checkoutTotal = document.getElementById('checkout-total');

    // Show modal
    checkoutBtn.addEventListener('click', () => {
        const selectedItems = document.querySelectorAll('.cart-item .select-item:checked');
        checkoutItemsContainer.innerHTML = '';
        let total = 0;

        selectedItems.forEach(item => {
            const li = item.closest('.cart-item');
            const name = li.querySelector('h4').innerText;
            const price = parseFloat(li.dataset.price);
            const quantity = parseInt(li.querySelector('.quantity').value);
            const subtotal = price * quantity;
            total += subtotal;
            checkoutItemsContainer.innerHTML += `<p>${name} x ${quantity} = ₱${subtotal.toFixed(2)}</p>`;
        });

        checkoutTotal.innerText = total.toFixed(2);
        checkoutModal.style.display = 'block';
    });

    // Close modal
    closeModal.addEventListener('click', () => checkoutModal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === checkoutModal) checkoutModal.style.display = 'none'; });

    // Confirm payment (COD only) + verification check
    confirmPaymentBtn.addEventListener('click', () => {
        const isVerified = confirmPaymentBtn.dataset.verified === 'true';

        if (!isVerified) {
            alert('You must verify your account before making a payment.');
            return;
        }

        if (!codCheckbox.checked) {
            alert('Please select COD to proceed.');
            return;
        }

        const selected = Array.from(document.querySelectorAll('.cart-item .select-item:checked'));
        if (selected.length === 0) {
            alert('Please select at least one item to checkout.');
            return;
        }

        const cart_item_ids = [];
        const quantities = [];
        selected.forEach(el => {
            const li = el.closest('.cart-item');
            cart_item_ids.push(li.dataset.id);
            quantities.push(li.querySelector('.quantity').value);
        });

        confirmPaymentBtn.disabled = true;
        confirmPaymentBtn.innerText = 'Placing order...';

        const formData = new URLSearchParams();
        cart_item_ids.forEach(id => formData.append('cart_item_ids[]', id));
        quantities.forEach(q => formData.append('quantities[]', q));

        fetch('api/place_order.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(data => {
            confirmPaymentBtn.disabled = false;
            confirmPaymentBtn.innerText = 'Confirm Payment';
            if (!data.success) {
                alert('Order failed: ' + (data.message || 'Unknown error'));
                return;
            }

            location.reload();
            checkoutModal.style.display = 'none';
            alert('Order placed! Thank you for shopping with us.');
            window.location.href = 'dashboard.php?section=orders';

            fetch('api/load_orders.php')
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const ordersSection = document.querySelector('.order-section');
                    if (!ordersSection) return;

                    const html = `
                        <ul class="order-list">
                            ${data.orders.map(o => `
                                <li class="order-card">
                                    <small class="order-date">${o.order_date}</small>
                                    <ul class="order-items">
                                        ${o.items.map(it => `
                                            <li class="order-item">
                                                <img src="${it.image_url}" class="order-item-img">
                                                <div class="order-item-info">
                                                    <h4>${it.product_name}</h4>
                                                    <span>₱${Number(it.price_each).toFixed(2)}</span>
                                                    <p>Qty: ${it.quantity}</p>
                                                </div>
                                            </li>
                                        `).join('')}
                                    </ul>
                                    <p>Method: ${o.payment_method}</p>
                                    <div class="order-total">Total: ₱${Number(o.total_amount).toFixed(2)}</div>
                                </li>
                            `).join('')}
                        </ul>
                    `;
                    ordersSection.innerHTML = html;
                });
        })
        .catch(err => {
            confirmPaymentBtn.disabled = false;
            confirmPaymentBtn.innerText = 'Confirm Payment';
            console.error(err);
            alert('Error placing order. See console for details.');
        });
    });
});

// Review Modal Logic

document.querySelectorAll('.review-btn').forEach(button => {
    button.addEventListener('click', function () {
        const productName = this.dataset.productName;
        const productImage = this.dataset.imageUrl || '/api/assets/default-product.png';
        const productId = this.dataset.productId;
        const rating = this.dataset.rating || '';
        const comment = this.dataset.comment || '';

        document.getElementById('reviewProductName').textContent = productName;
        document.getElementById('reviewProductImage').src = productImage;
        document.getElementById('product_id').value = productId;

        // Pre-fill rating
        const stars = document.querySelectorAll('.review-star');
        stars.forEach(s => s.classList.remove('selected'));
        if (rating) {
            for (let i = 0; i < rating; i++) stars[i].classList.add('selected');
            document.getElementById('rating').value = rating;
        }

        // Pre-fill comment
        document.getElementById('comment').value = comment;

        document.getElementById('reviewModal').style.display = 'block';
    });
});


document.getElementById('closeModal').onclick = function () {
    document.getElementById('reviewModal').style.display = 'none';
};

window.onclick = function (event) {
    if (event.target == document.getElementById('reviewModal')) {
        document.getElementById('reviewModal').style.display = 'none';
    }
};

document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();

    fetch('api/add_review.php', {
        method: 'POST',
        body: new FormData(this)
    })

    .then(response => response.text()) // get raw text
    .then(text => {
        console.log('Response text:', text);
        try {
            const data = JSON.parse(text);
            alert(data.message);
            document.getElementById('reviewModal').style.display = 'none';
        } catch (err) {
            console.error('Invalid JSON:', err);
        }
    })
    .catch(error => console.error('Error:', error));
});

const stars = document.querySelectorAll('.review-star');
const ratingInput = document.getElementById('rating');

stars.forEach(star => {
    star.addEventListener('mouseover', function () {
        stars.forEach(s => s.style.color = '#ccc');
        for (let i = 0; i < this.dataset.value; i++) {
            stars[i].style.color = 'gold';
        }
    });

    star.addEventListener('click', function () {
        ratingInput.value = this.dataset.value;
        stars.forEach(s => s.classList.remove('selected'));
        for (let i = 0; i < this.dataset.value; i++) {
            stars[i].classList.add('selected');
        }
    });
});

document.querySelector('.review-star-rating').addEventListener('mouseleave', function () {
    stars.forEach(s => s.style.color = s.classList.contains('selected') ? 'gold' : '#ccc');
});


</script>

</body>
</html> 