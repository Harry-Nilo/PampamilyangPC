let allProducts = [];
let allCategories = [];

document.addEventListener('DOMContentLoaded', function() {
    fetchDashboardData();
    setupMobileMenu();
    setupFilters();
});

function setupMobileMenu() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });
    }
    
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
}

function setupFilters() {
    const categoryFilter = document.getElementById('category-filter');
    const conditionFilter = document.getElementById('condition-filter');
    const searchFilter = document.getElementById('search-filter');
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterProducts);
    }
    
    if (conditionFilter) {
        conditionFilter.addEventListener('change', filterProducts);
    }
    
    if (searchFilter) {
        searchFilter.addEventListener('input', debounce(filterProducts, 300));
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

async function fetchDashboardData() {
    try {
        const response = await fetch('api/data.php?action=all');
        const text = await response.text(); // get raw response
        console.log('Raw response:', text); // inspect in console

        const data = JSON.parse(text); // try parsing
        if (data.error) {
            console.error('Error:', data.error);
            return;
        }

        updateStats(data.stats);
        createCustomerActivityChart(data.top_customers);
        createSalesRevenueChart(data.monthly_sales);
        createInventoryChart(data.products_by_category);

        allProducts = data.products || [];
        allCategories = data.categories || [];

        populateCategoryFilter();
        renderProducts(allProducts);

    } catch (error) {
        console.error('Failed to fetch dashboard data:', error);
    }
}

function populateCategoryFilter() {
    const categoryFilter = document.getElementById('category-filter');
    if (!categoryFilter) return;
    
    allCategories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.category_id;
        option.textContent = category.category_name;
        categoryFilter.appendChild(option);
    });
}

function filterProducts() {
    const categoryValue = document.getElementById('category-filter')?.value || 'all';
    const conditionValue = document.getElementById('condition-filter')?.value || 'all';
    const searchValue = (document.getElementById('search-filter')?.value || '').toLowerCase().trim();

    const filtered = allProducts.filter(product => {
        // --- CATEGORY MATCH ---
        let categoryMatch = true;
        if (categoryValue !== 'all') {
            if (product.category_id !== undefined) {
                categoryMatch = String(product.category_id) === String(categoryValue);
            } else if (product.category_name !== undefined) {
                // fallback if only category_name exists
                const selectedCategory = allCategories.find(c => String(c.category_id) === String(categoryValue));
                categoryMatch = selectedCategory 
                    ? product.category_name.toLowerCase() === selectedCategory.category_name.toLowerCase()
                    : true;
            }
        }

        // --- CONDITION MATCH ---
        const conditionMatch = conditionValue === 'all' || (product.product_condition || '').toLowerCase() === conditionValue.toLowerCase();

        // --- SEARCH MATCH ---
        const searchMatch = searchValue === '' ||
            (product.product_name || '').toLowerCase().includes(searchValue) ||
            (product.brand || '').toLowerCase().includes(searchValue) ||
            (product.category_name || '').toLowerCase().includes(searchValue);

        return categoryMatch && conditionMatch && searchMatch;
    });

    console.log('Filtered Products:', filtered); // debug
    renderProducts(filtered);
}

function renderProducts(products) {
    const grid = document.getElementById('products-grid');
    if (!grid) return;
    
    if (products.length === 0) {
        grid.innerHTML = '<div class="no-products">No products found matching your criteria.</div>';
        return;
    }
    
    grid.innerHTML = products.map(product => {
        const stockClass = product.stock_quantity === 0 ? 'out-of-stock' : 
                          product.stock_quantity <= 5 ? 'low-stock' : 'in-stock';
        const stockText = product.stock_quantity === 0 ? 'Out of Stock' :
                         product.stock_quantity <= 5 ? `Low Stock (${product.stock_quantity})` :
                         `In Stock (${product.stock_quantity})`;
        
        return `
            <div class="product-card">
                <div class="product-header">
                    <span class="product-category">${escapeHtml(product.category_name)}</span>
                    <span class="product-condition ${(product.product_condition || '').toLowerCase()}">
                        ${product.product_condition || 'N/A'}
                    </span>

                </div>
                <h4 class="product-name">${escapeHtml(product.product_name)}</h4>
                <p class="product-brand">${escapeHtml(product.brand)}</p>
                <div class="product-footer">
                    <span class="product-price">${formatCurrency(product.price)}</span>
                    <span class="product-stock">
                        <span class="stock-indicator ${stockClass}"></span>
                        ${stockText}
                    </span>
                </div>
            </div>
        `;
    }).join('');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function updateStats(stats) {
    document.getElementById('total-revenue').textContent = formatCurrency(stats.total_revenue);
    document.getElementById('total-orders').textContent = stats.total_orders;
    document.getElementById('total-customers').textContent = stats.total_customers;
    document.getElementById('total-products').textContent = stats.total_products;
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

function createCustomerActivityChart(data) {
    const ctx = document.getElementById('customerActivityChart').getContext('2d');
    
    const labels = data.map(item => item.customer_name);
    const values = data.map(item => item.purchases);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Purchases',
                data: values,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return `Purchases: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        },
                        maxRotation: 45,
                        minRotation: 0
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function createSalesRevenueChart(data) {
    const ctx = document.getElementById('salesRevenueChart').getContext('2d');
    
    const labels = data.map(item => item.month);
    const values = data.map(item => item.total);
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(255, 77, 109, 0.4)');
    gradient.addColorStop(1, 'rgba(255, 77, 109, 0.05)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Monthly Revenue',
                data: values,
                fill: true,
                backgroundColor: gradient,
                borderColor: '#ff4d6d',
                borderWidth: 3,
                pointBackgroundColor: '#ff4d6d',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return `Revenue: ${formatCurrency(context.raw)}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            if (value >= 1000000) {
                                return '₱' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return '₱' + (value / 1000).toFixed(0) + 'K';
                            }
                            return '₱' + value;
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function createInventoryChart(data) {
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    
    const labels = data.map(item => item.category_name);
    const newProducts = data.map(item => item.new);
    const usedProducts = data.map(item => item.used);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'New Products',
                    data: newProducts,
                    backgroundColor: 'rgba(67, 233, 123, 0.8)',
                    borderColor: 'rgba(67, 233, 123, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                },
                {
                    label: 'Used Products',
                    data: usedProducts,
                    backgroundColor: 'rgba(255, 159, 64, 0.8)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        },
                        maxRotation: 45,
                        minRotation: 0
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}
