<?php

if (isset($_GET['query'])) {
    $query = htmlspecialchars($_GET['query']);
}

// Detect refresh
$isRefresh = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

// If user refreshes page AND there is a query string → redirect to clean URL
if ($isRefresh && !empty($_GET)) {
    header("Location: product");
    exit;
}

include 'api/connect.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pampamilyang PC | Products</title>
    <link rel="stylesheet" href="./css/product.css?v=1.0">
    <link rel="icon" type="image/png" href="assets/PPClogo.png">
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

    <main class="main-content">
        <div class="container">
            <aside class="sidebar">
                <div class="filter-header">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M1 4h14M4 8h8M6 12h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>FILTER BY</span>
                </div>

                <div class="filter-section">
                    <div class="filter-section-header" data-section="categories">
                        <h3>Categories</h3>
                        <svg class="chevron" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="filter-section-content" id="categories-content">
                        <label class="filter-checkbox">
                            <input type="checkbox" name="category" value="CPU">
                            <span class="checkmark"></span>
                            <span class="label-text">CPU</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="category" value="GPU">
                            <span class="checkmark"></span>
                            <span class="label-text">GPU</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="category" value="Motherboard">
                            <span class="checkmark"></span>
                            <span class="label-text">Motherboard</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="category" value="RAM">
                            <span class="checkmark"></span>
                            <span class="label-text">RAM</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="category" value="Storage">
                            <span class="checkmark"></span>
                            <span class="label-text">Storage</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="category" value="PSU">
                            <span class="checkmark"></span>
                            <span class="label-text">PSU</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="category" value="Case">
                            <span class="checkmark"></span>
                            <span class="label-text">Case</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="category" value="Cooler">
                            <span class="checkmark"></span>
                            <span class="label-text">Cooler</span>
                            <span class="count">(0)</span>
                        </label>
                    </div>
                </div>

                <div class="filter-section">
                    <div class="filter-section-header" data-section="price">
                        <h3>Price</h3>
                        <svg class="chevron" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="filter-section-content" id="price-content">
                        <div class="price-slider">
                            <input type="range" id="price-range" min="0" max="90000" value="90000" step="5">
                            <div class="price-inputs">
                                <div class="price-input-group">
                                    <label>Range (₱):</label>
                                    <div class="price-range-display">
                                        <input type="number" id="min-price" value="0"> - 
                                        <input type="number" id="max-price" value="90000">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="filter-btn">Filter</button>
                    </div>
                </div>

                <div class="filter-section">
                    <div class="filter-section-header" data-section="brands">
                        <h3>Brands</h3>
                        <svg class="chevron" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="filter-section-content" id="brands-content">
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="AMD">
                            <span class="checkmark"></span>
                            <span class="label-text">AMD</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Intel">
                            <span class="checkmark"></span>
                            <span class="label-text">Intel</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="ASUS">
                            <span class="checkmark"></span>
                            <span class="label-text">ASUS</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="MSI">
                            <span class="checkmark"></span>
                            <span class="label-text">MSI</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="XFX">
                            <span class="checkmark"></span>
                            <span class="label-text">XFX</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Gigabyte">
                            <span class="checkmark"></span>
                            <span class="label-text">Gigabyte</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="ASRock">
                            <span class="checkmark"></span>
                            <span class="label-text">ASRock</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Corsair">
                            <span class="checkmark"></span>
                            <span class="label-text">Corsair</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Kingston">
                            <span class="checkmark"></span>
                            <span class="label-text">Kingston</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Samsung">
                            <span class="checkmark"></span>
                            <span class="label-text">Samsung</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Crucial">
                            <span class="checkmark"></span>
                            <span class="label-text">Crucial</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Seagate">
                            <span class="checkmark"></span>
                            <span class="label-text">Seagate</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Seasonic">
                            <span class="checkmark"></span>
                            <span class="label-text">Seasonic</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Cooler Master">
                            <span class="checkmark"></span>
                            <span class="label-text">Cooler Master</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="NZXT">
                            <span class="checkmark"></span>
                            <span class="label-text">NZXT</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Lian Li">
                            <span class="checkmark"></span>
                            <span class="label-text">Lian Li</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Phanteks">
                            <span class="checkmark"></span>
                            <span class="label-text">Phanteks</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Noctua">
                            <span class="checkmark"></span>
                            <span class="label-text">Noctua</span>
                            <span class="count">(0)</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="brand" value="Arctic">
                            <span class="checkmark"></span>
                            <span class="label-text">Arctic</span>
                            <span class="count">(0)</span>
                        </label>
                    </div>
                </div>
            </aside>

            <section class="products-section">
                <div class="products-header">
                    <div class="view-toggle">
                        <button class="view-btn active" data-view="grid" aria-label="Grid view">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="currentColor">
                                <rect width="7" height="7"/>
                                <rect x="11" width="7" height="7"/>
                                <rect y="11" width="7" height="7"/>
                                <rect x="11" y="11" width="7" height="7"/>
                            </svg>
                        </button>
                        <button class="view-btn" data-view="list" aria-label="List view">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="currentColor">
                                <rect width="18" height="3"/>
                                <rect y="7" width="18" height="3"/>
                                <rect y="14" width="18" height="3"/>
                            </svg>
                        </button>
                        <button class="view-btn" data-view="compact" aria-label="Compact view">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="currentColor">
                                <rect width="5" height="5"/>
                                <rect x="6.5" width="5" height="5"/>
                                <rect x="13" width="5" height="5"/>
                                <rect y="6.5" width="5" height="5"/>
                                <rect x="6.5" y="6.5" width="5" height="5"/>
                                <rect x="13" y="6.5" width="5" height="5"/>
                                <rect y="13" width="5" height="5"/>
                                <rect x="6.5" y="13" width="5" height="5"/>
                                <rect x="13" y="13" width="5" height="5"/>
                            </svg>
                        </button>
                    </div>
                    <div class="results-info">
                        Showing <span id="shown-count">0</span> of <span id="total-count">0</span> results
                    </div>
                    <div class="sort-dropdown">
                        <select id="sort-select">
                            <option value="default">Default sorting</option>
                            <option value="price-asc">Price: Low to High</option>
                            <option value="price-desc">Price: High to Low</option>
                            <option value="rating">Rating: High to Low</option>
                            <option value="name">Name: A to Z</option>
                        </select>
                    </div>
                </div>

                <div id="products-grid" class="products-grid"></div>
            </section>
        </div>
    </main>

    <script src="./js/product.js"></script>
</body>
</html>