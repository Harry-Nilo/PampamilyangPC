<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pampamilyang PC</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <button class="mobile-menu-toggle" aria-label="Toggle menu">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
        </svg>
    </button>
    <div class="sidebar-overlay"></div>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="admin-avatar">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <h3 class="admin-name">Admin</h3>
            </div>
            
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    <span class="nav-text">Dashboard</span>
                </a>

                <a href="#products-grid" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 4H6v-4h6v4z"/>
                    </svg>
                    <span class="nav-text">Products</span>
                </a>

                <a href="#" class="nav-item logout">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    <span class="nav-text">Logout</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="content-header">
                <h1>Dashboard</h1>
                <p class="header-date"><?php echo date('l, F j, Y'); ?></p>
            </header>

            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Total Revenue</h3>
                        <p class="stat-value" id="total-revenue">Loading...</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orders">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Total Orders</h3>
                        <p class="stat-value" id="total-orders">Loading...</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon customers">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Total Customers</h3>
                        <p class="stat-value" id="total-customers">Loading...</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon products">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 4H6v-4h6v4z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Total Products</h3>
                        <p class="stat-value" id="total-products">Loading...</p>
                    </div>
                </div>
            </section>

            <section class="charts-grid">
                <div class="chart-card">
                    <h3>Top 5 Customers by Purchases</h3>
                    <div class="chart-container">
                        <canvas id="customerActivityChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3>Monthly Sales & Revenue</h3>
                    <div class="chart-container">
                        <canvas id="salesRevenueChart"></canvas>
                    </div>
                </div>
                <div class="chart-card full-width">
                    <h3>Products by Category (New vs Used)</h3>
                    <div class="chart-container">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>
            </section>

            <section class="products-section" id="products-section">
                <div class="section-header">
                    <h2>All Products</h2>
                    <div class="filter-controls">
                        <select id="category-filter" class="filter-select">
                            <option value="all">All Categories</option>
                        </select>
                        <select id="condition-filter" class="filter-select">
                            <option value="all">All Conditions</option>
                            <option value="New">New</option>
                            <option value="Used">Used</option>
                        </select>
                        <input type="text" id="search-filter" class="search-input" placeholder="Search products...">
                    </div>
                </div>
                <div class="products-grid" id="products-grid">
                    <div class="loading-spinner">Loading products...</div>
                </div>
            </section>
        </main>
    </div>

    <script src="js/dashboard.js"></script>
</body>
</html>
