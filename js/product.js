let allProducts = [];   
let filteredProducts = [];
let currentView = 'grid';



async function loadProducts() {
    try {
        const response = await fetch('api/get-products.php');
        allProducts = await response.json();

        allProducts = allProducts.map(p => ({
            ...p,
            categoryLower: p.category.toLowerCase(),
            brandLower: p.brand.toLowerCase()
        }));

        const url = new URL(window.location.href);
        const query = url.searchParams.get('query')?.toLowerCase() || '';
        const category = url.searchParams.get('category')?.toLowerCase() || '';

        if (query || category) {

            filteredProducts = allProducts.filter(p => {
                const matchesQuery = query
                    ? p.name.toLowerCase().includes(query)
                    : true;

                const matchesCategory = category
                    ? p.categoryLower === category
                    : true;

                return matchesQuery && matchesCategory;
            });
        } else {

            filteredProducts = [...allProducts];
        }

        updateProductCounts();
        renderProducts();
        updateResultsInfo();

    } catch (error) {
        console.error('Error loading products:', error);
    }
}



function updateProductCounts(){

    const categoryCounts = {};
        const brandCounts = {};
    
    allProducts.forEach(product => {
        categoryCounts[product.category] = (categoryCounts[product.category] || 0) + 1;
            brandCounts[product.brand] = (brandCounts[product.brand] || 0) + 1;
    });
    
    document.querySelectorAll('input[name="category"]').forEach(input => {
        const count = categoryCounts[input.value] || 0;
        const countSpan = input.parentElement.querySelector('.count');
            if (countSpan) countSpan.textContent = `(${count})`;
    });
    
    document.querySelectorAll('input[name="brand"]').forEach(input => {
        const count = brandCounts[input.value] || 0;
        const countSpan = input.parentElement.querySelector('.count');
        if (countSpan)     countSpan.textContent = `(${count})`;
    });
}


function renderProducts() {
    const grid = document.getElementById('products-grid');
    grid.innerHTML = '';

    if (filteredProducts.length === 0) {
        grid.innerHTML = ` 
            <div class="no-products-message">
                No products found.
            </div>
        `;
        return;
    }

    filteredProducts.forEach(product => {
        const card = createProductCard(product);
        grid.appendChild(card);
    });
}


function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';

    const categoryClass = 
        `category-${product.categoryLower.replace(/[^a-z0-9]+/g, '-')}`;

    const stars = Array(5).fill(0).map((_, i) => 
        `<span class="star ${i < product.rating ? 'filled' : ''}">★</span>`
    ).join('');

    const originalPriceHtml = product.originalPrice 
        ? `<span class="original-price">₱${product.originalPrice.toFixed(2)}</span>`
            : '';
    
    card.innerHTML = `
        <a href="product_info?product_id=${product.id}" class="product-link">
           <img src="${product.image}" alt="${product.name}" class="product-image">                
        </a>

        <div class="product-info">
            <span class="product-category ${categoryClass}">${product.category}</span>

            <span class="product-condition ${product.product_condition?.toLowerCase() === 'used' ? 'used' : 'new'}">
                ${product.product_condition?.toUpperCase() || 'N/A'}
            </span>


             <a href="product_info?product_id=${product.id}" class="product-link" style="text-decoration:none; color:inherit;">
                <div style="text-decoration:none; color:inherit;" class="product-name">${product.name}</div>  
                <div class="product-stock">Stock: ${product.stock}</div>             
            </a>

            <div class="product-rating">
                ${stars} <span class="review-count">(${product.total_reviews})</span>

            </div>

            <div class="product-price">

                 <span class="current-price">₱${product.price.toFixed(2)}</span>
                 ${originalPriceHtml}
     
                 <button class="add-to-cart cart-link" onclick="window.location.href='../public/api/addtocartfunc.php?product_id=${product.id}'">

                    <svg class="carticon"
                       xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                       <path d="M0 8C0-5.3 10.7-16 24-16l45.3 0c27.1 0 50.3 19.4 55.1 46l.4 2 412.7 0c20 0 35.1 18.2 31.4 37.9L537.8 235.8c-5.7 30.3-32.1 52.2-62.9 52.2l-303.6 0 5.1 28.3c2.1 11.4 12 19.7 23.6 19.7L456 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-255.9 0c-34.8 0-64.6-24.9-70.8-59.1L77.2 38.6c-.7-3.8-4-6.6-7.9-6.6L24 32C10.7 32 0 21.3 0 8zM160 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm224 0a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM336 78.4c-13.3 0-24 10.7-24 24l0 33.6-33.6 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l33.6 0 0 33.6c0 13.3 10.7 24 24 24s24-10.7 24-24l0-33.6 33.6 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-33.6 0 0-33.6c0-13.3-10.7-24-24-24z"/>
                    </svg>

                 </button>

            </div>
        </div>
    `;


    return card;
}


async function fetchProductCondition(productId) {
    try {
        const res = await fetch(`api/condition-product.php?ids=${productId}`);
        const data = await res.json();

        // Find the product card's condition span
        const conditionSpan = document.querySelector(
            `.product-card[data-product-id="${productId}"] .product-condition`
        );

        if (conditionSpan) {
            const condition = data[productId] || 'N/A';

            // This is where you put your snippet
            conditionSpan.classList.remove('used', 'new');
            conditionSpan.classList.add(condition.toLowerCase() === 'used' ? 'used' : 'new');
            conditionSpan.textContent = condition.toUpperCase() || 'N/A';
        }
    } catch (err) {
        console.error('Error fetching product condition:', err);
    }
}




function filterProducts() {
    const selectedCategories = Array.from(
        document.querySelectorAll('input[name="category"]:checked')
    ).map(input => input.value.toLowerCase());

    const selectedBrands = Array.from(
        document.querySelectorAll('input[name="brand"]:checked')
    ).map(input => input.value.toLowerCase());

    const minPrice = parseFloat(document.getElementById('min-price').value) || 0;
    const maxPrice = parseFloat(document.getElementById('max-price').value) || 0;

    filteredProducts = allProducts.filter(product => {
        const categoryMatch =
            selectedCategories.length === 0 || selectedCategories.includes(product.categoryLower);
        const brandMatch =
            selectedBrands.length === 0 || selectedBrands.includes(product.brandLower);
        const priceMatch = product.price >= minPrice && product.price <= maxPrice;

        return categoryMatch && brandMatch && priceMatch;
    });

    applyCurrentSort();
    renderProducts();
    updateResultsInfo();
}



function applyCurrentSort() {
    const sortValue = document.getElementById('sort-select').value;
    
    switch(sortValue) {
        case 'price-asc':
            filteredProducts.sort((a, b) => a.price - b.price);
            break;
        case 'price-desc':
            filteredProducts.sort((a, b) => b.price - a.price);
            break;
        case 'rating':
            filteredProducts.sort((a, b) => b.rating - a.rating);
            break;
        case 'name':
            filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
            break;


    }
}

function sortProducts() {
    applyCurrentSort();
    renderProducts();
}

function updateResultsInfo() {
    document.getElementById('shown-count').textContent = filteredProducts.length;
    document.getElementById('total-count').textContent = allProducts.length;
}

function updatePriceSlider() {
    const slider = document.getElementById('price-range');
    const maxPriceInput = document.getElementById('max-price');

    // keep max-price input synced
    maxPriceInput.value = slider.value;

    // slider background gradient
    const percent = (slider.value / slider.max) * 100;
    slider.style.background =
        `linear-gradient(to right, var(--accent-pink) 0%, var(--accent-pink) ${percent}%, var(--border-color) ${percent}%)`;
}

function toggleSection(header) {
    const content = header.nextElementSibling;
    header.classList.toggle('collapsed');
    content.classList.toggle('collapsed');
}


function changeView(view) {
    currentView = view;
    const grid = document.getElementById('products-grid');
    grid.className = 'products-grid';
    
    if (view === 'list') {
         grid.classList.add('list-view');
    } else if (view === 'compact') {
        grid.classList.add('compact-view');
        }
    
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.view === view) {
            btn.classList.add('active');
        }
    });
}


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
    loadProducts();

    document.querySelectorAll('.filter-section-header').forEach(header => {
        header.addEventListener('click', () => toggleSection(header));
    });

    document.querySelectorAll('input[name="category"], input[name="brand"]').forEach(input => {
        input.addEventListener('change', filterProducts);
    });

    const priceSlider = document.getElementById('price-range');
    const maxPriceInput = document.getElementById('max-price');

    if (priceSlider && maxPriceInput) {
        // Slider input updates
        priceSlider.addEventListener('input', () => {
            updatePriceSlider();
            filterProducts();
        });

        // Max price input manually changed
        maxPriceInput.addEventListener('change', () => {
            priceSlider.value = maxPriceInput.value;
            updatePriceSlider();
            filterProducts();
        });

        // Initialize slider background
        updatePriceSlider();
    }

    document.querySelector('.filter-btn').addEventListener('click', filterProducts);

        document.getElementById('sort-select').addEventListener('change', sortProducts);

     document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => changeView(btn.dataset.view));
    });

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

