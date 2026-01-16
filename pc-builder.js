let selectedComponents = {};
let productsData = {};

// Load products from API
async function loadProducts() {
  try {
    const res = await fetch('api/get-products.php');
    const data = await res.json();
    productsData = data.reduce((acc, p) => {
      if (!acc[p.category]) acc[p.category] = [];
      acc[p.category].push(p);
      return acc;
    }, {});
  } catch (err) {
    console.error('Failed to load products:', err);
  }
}

// Render star ratings with proper decimal support
function renderStars(rating) {
  const numRating = parseFloat(rating) || 0;
  const fullStars = Math.floor(numRating);
  const hasHalfStar = (numRating % 1) >= 0.5;
  
  let stars = '';
  for (let i = 0; i < 5; i++) {
    if (i < fullStars) {
      stars += '<span class="star filled">★</span>';
    } else if (i === fullStars && hasHalfStar) {
      stars += '<span class="star half">★</span>';
    } else {
      stars += '<span class="star">★</span>';
    }
  }
  return stars;
}

// Open modal for a category
function openModal(category) {
  const modal = document.getElementById('pc-modal');
  const grid = document.getElementById('pc-modal-products');
  grid.innerHTML = '';
  
  if (!productsData[category]) return;

  productsData[category].forEach(p => {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
      <img src="${p.image}" alt="${p.name}" style="width:100%; height:150px; object-fit:contain; margin-bottom:0.5rem; background:#f5f5f5; padding:0.5rem; border-radius:4px;">
      <div class="product-name">${p.name}</div>
      <div style="font-size:0.85rem; color:#666; margin:0.3rem 0;">Brand: ${p.brand}</div>
      <div class="product-price">₱${parseFloat(p.price).toFixed(2)}</div>
      <div class="modal-product-rating" style="margin:0.3rem 0;">${renderStars(p.rating)}</div>
    `;
    card.onclick = () => selectProduct(category, p);
    grid.appendChild(card);
  });
  
  modal.style.display = 'flex';
}

// Close the modal
function closeModal() {
  document.getElementById('pc-modal').style.display = 'none';
}

// Select a product
function selectProduct(category, product) {
  updateCartButtonState();
  selectedComponents[category] = { ...product, qty: 1 };
  updateTableRow(category);
  closeModal();

  // Enable Motherboard if CPU selected
  if (category === "CPU") {
    const mbBtn = document.querySelector('tr[data-category="Motherboard"] .add-btn');
    const mbEditBtn = document.querySelector('tr[data-category="Motherboard"] .edit-btn');
    const mbRemoveBtn = document.querySelector('tr[data-category="Motherboard"] .remove-btn');
    if (mbBtn) mbBtn.disabled = false;
    if (mbEditBtn) mbEditBtn.disabled = false;
    if (mbRemoveBtn) mbRemoveBtn.disabled = false;
    document.querySelector('tr[data-category="Motherboard"] .note')?.remove();
  }

  // Enable quantity input for RAM/Storage
  if (category === "RAM" || category === "Storage") {
    const input = document.querySelector(`tr[data-category="${category}"] input`);
    if (input) {
      input.disabled = false;
      input.value = 1;
      input.oninput = () => updateQty(category, input.value);
    }
  }

  updateSubtotal();
}

function updateTableRow(category) {
  const row = document.querySelector(`tr[data-category="${category}"]`);
  if (!row) return;

  const component = selectedComponents[category];

  // Update product info...
  row.querySelector('.product-name').innerHTML = `
    <div style="display:flex; align-items:center; gap:10px; justify-content:left;">
      <img src="${component.image}" alt="${component.name}" style="width:100px; height:100px; object-fit:contain;">
      <span style="font-size:0.9rem; color:var(--text-primary);">${component.name}</span>
    </div>
  `;
  row.querySelector('.product-details').innerHTML = `Brand: ${component.brand}, Rating: ${renderStars(component.rating)}`;
  row.querySelector('.product-price').textContent = '₱' + parseFloat(component.price).toFixed(2);

  // Toggle buttons
  row.querySelector('.add-btn').disabled = true;
  row.querySelector('.edit-btn').disabled = false;
  row.querySelector('.remove-btn').disabled = false;
}

// Update quantity
function updateQty(category, qty) {
  if (selectedComponents[category]) {
    selectedComponents[category].qty = parseInt(qty);
    updateSubtotal();
  }
}

// Update subtotal
function updateSubtotal() {
  updateCartButtonState();
  let subtotal = 0;
  for (let cat in selectedComponents) {
    let c = selectedComponents[cat];
    let q = c.qty || 1;
    subtotal += parseFloat(c.price) * q;
  }
  document.getElementById('pc-subtotal').textContent = '₱' + subtotal.toFixed(2);
}

function updateCartButtonState() {
  const cartBtn = document.getElementById('add-to-cart-btn');
  if (Object.keys(selectedComponents).length > 0) {
    cartBtn.disabled = false;
  } else {
    cartBtn.disabled = true;
  }
}


function removeComponent(category) {
  updateCartButtonState();
  if (selectedComponents[category]) {
    delete selectedComponents[category];
    const row = document.querySelector(`tr[data-category="${category}"]`);

    if (row) {
      row.querySelector('.product-name').textContent = '—';
      row.querySelector('.product-details').innerHTML = '';
      row.querySelector('.product-price').textContent = '₱0.00';

      // Reset quantity input for RAM/Storage
      if (category === "RAM" || category === "Storage") {
        const input = row.querySelector('input[type="number"]');
        if (input) {
          input.value = 1;
          input.disabled = true;
        }
      }

      // 🔧 Reset buttons for ALL categories
      row.querySelector('.add-btn').disabled = false;
      row.querySelector('.edit-btn').disabled = true;
      row.querySelector('.remove-btn').disabled = true;
    }

if (category === "CPU") {
  // Reset CPU row buttons
  const cpuRow = document.querySelector('tr[data-category="CPU"]');
  cpuRow.querySelector('.add-btn').disabled = false;
  cpuRow.querySelector('.edit-btn').disabled = true;
  cpuRow.querySelector('.remove-btn').disabled = true;

  // Restore CPU guidance note
  cpuRow.querySelector('.product-details').innerHTML =
    '<span class="note">Start by choosing your preferred CPU before adding other components.</span>';

  // Disable Motherboard buttons and reset note
  const mbRow = document.querySelector('tr[data-category="Motherboard"]');
  mbRow.querySelector('.add-btn').disabled = true;
  mbRow.querySelector('.edit-btn').disabled = true;
  mbRow.querySelector('.remove-btn').disabled = true;

  mbRow.querySelector('.product-name').textContent = '—';
  mbRow.querySelector('.product-details').innerHTML =
    '<span class="note">You need to choose a CPU before choosing your Motherboard.</span>';
  mbRow.querySelector('.product-price').textContent = '₱0.00';

  delete selectedComponents["Motherboard"];
}

    updateSubtotal();
  }
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
  loadProducts();

  // Add button listeners
  document.querySelectorAll('.add-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;
      if (cat === "Motherboard" && !selectedComponents.CPU) {
        alert('Please select a CPU first!');
        return;
      }
      openModal(cat);
    });
  });

  // Edit button listeners
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;
      if (cat === "Motherboard" && !selectedComponents.CPU) {
        alert('Please select a CPU first!');
        return;
      }
      openModal(cat);
    });
  });

  // Remove button listeners
  document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;
      removeComponent(cat);
    });
  });

  // Update subtotal on quantity change
  document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('input', () => {
      const row = input.closest('tr');
      const category = row.dataset.category;
      updateQty(category, input.value);
    });
  });

  // Modal close button
  const closeBtn = document.querySelector('.pc-modal-close');
  if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
  }

  // Close modal when clicking outside
  window.addEventListener('click', (e) => {
    if (e.target.id === 'pc-modal') {
      closeModal();
    }
  });
});

// Add this to pc-builder.js
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
  // existing code...
  document.getElementById('theme-toggle').addEventListener('click', toggleTheme);
});

document.getElementById('add-to-cart-btn').addEventListener('click', () => {
  if (Object.keys(selectedComponents).length === 0) {
      alert("No components selected!");
      return;
  }

  // Convert selected components into array of product_id + qty
const items = Object.values(selectedComponents).map(c => {
    return {
        product_id: c.id, 
        qty: c.qty || 1
    };
});

  fetch("api/add_pcbuild_to_cart.php", {
      method: "POST",
      headers: {
          "Content-Type": "application/json"
      },
      body: JSON.stringify({ items })
  })
  .then(res => res.json())
  .then(data => {
      if (data.status === "success") {
          alert("All selected components added to cart!");
          window.location.href = "/PampamilyangPC/public/dashboard?section=cart";
      } else {
          alert("Failed to add items to cart. Please Sign-In first.");
          window.location.href = "/PampamilyangPC/public/login-page";
      }
  })
  .catch(err => {
      console.error("Error:", err);
      alert("Server error while adding items to cart.");
  });
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
