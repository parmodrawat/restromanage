<?php
require 'assets/api/session-check.php';
$user = checkUserSession();
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RestroManage — Menu</title>
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/layout.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/menu.css">
  <link rel="stylesheet" href="assets/css/cart.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="app">

  <aside class="sidebar">
    <div class="brand">
      <img src="img/Logoo.png" alt="RestroManage">
    </div>
    <nav class="menu">
      <a href="index.php"><img src="img/dashboard.png" alt=""> Dashboard</a>
      <a href="orders.php"><img src="img/cloche.png" alt=""> Orders</a>
      <a href="kitchen.php"><img src="img/chef-hat.png" alt=""> Kitchen</a>
      <a class="active" href="menu.php"><img src="img/cloche9.png" alt=""> Menu</a>
    </nav>
    <div class="sidebar-footer">
      <button id="logoutBtn" class="logout-btn">
        <img src="img/logout.png" alt=""> Logout
      </button>
    </div>
  </aside>

  <main class="main">

    <header class="header">
      <h2>Menu</h2>
      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search food..." oninput="searchMenu()">
      </div>
      <div class="user">
        <div class="user-img"><img src="img/profile.png" alt=""></div>
        <div class="user-data">
          <h4><?php echo htmlspecialchars(ucfirst($user['role'] ?? 'Staff')); ?></h4>
          <p><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
      </div>
      <!-- Cart button injected by JS -->
    </header>

    <section class="panel menu-charts" style="margin-bottom:22px;">
      <div class="panel-inner">
        <div class="menu-chart-box">
          <div class="sales-header">
            <h3>Category Distribution</h3>
          </div>
          <div class="sales-body">
            <div class="donut-wrap">
              <canvas id="menuCategoryChart"></canvas>
              <div class="donut-center">100%</div>
            </div>
            <div class="legend" id="menuCategoryLegend"></div>
          </div>
        </div>

        <div class="menu-chart-box">
          <div class="week-header">
            <h3>Top Selling Items</h3>
          </div>
          <div class="bar-wrap">
            <canvas id="topItemsChart"></canvas>
          </div>
        </div>
      </div>
    </section>

    <div class="menu-filters">
      <button class="filter-btn active" data-filter="all">All Items</button>
      <button class="filter-btn" data-filter="Veg">🥗 Vegetarian</button>
      <button class="filter-btn" data-filter="Non-Veg">🍗 Non-Veg</button>
      <button class="filter-btn" data-filter="Chinese">🥢 Chinese</button>
      <button class="filter-btn" data-filter="Drinks">🥤 Drinks</button>
    </div>

    <section class="menu-container">
      <div class="food-grid" id="foodGrid">
        <div style="text-align:center;padding:60px;color:var(--text-muted);grid-column:1/-1;font-size:14px;">
          Loading menu items...
        </div>
      </div>
    </section>

    <!-- Item Detail Modal -->
    <div id="itemModal" class="modal-overlay hidden">
      <div class="modal menu-modal">
        <button class="modal-close" onclick="closeItemModal()">×</button>
        <div class="modal-content">
          <img id="modalItemImage" src="" alt="Item" class="modal-item-image">
          <div class="modal-info">
            <h2 id="modalItemName"></h2>
            <p id="modalItemCategory" class="modal-category"></p>
            <p id="modalItemDescription" class="modal-description"></p>
            <div class="modal-price-section">
              <span class="modal-label">Price</span>
              <span id="modalItemPrice" class="modal-price"></span>
            </div>
            <div class="modal-quantity">
              <label>Quantity</label>
              <div class="quantity-control">
                <button onclick="decreaseQty()">−</button>
                <input type="number" id="quantityInput" value="1" min="1" max="10">
                <button onclick="increaseQty()">+</button>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn secondary" onclick="closeItemModal()">Cancel</button>
              <button class="btn primary" onclick="addToCart()">🛒 Add to Cart</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>
</div>

<script>
  window.currentUser = <?php echo json_encode(['id' => $user['user_id'], 'email' => $user['email'], 'role' => $user['role'] ?? 'staff']); ?>;
</script>
<script src="assets/js/menuEnhanced.js"></script>
<script>
  function searchMenu() {
    const term = document.getElementById('searchInput').value.toLowerCase();
    if (typeof menuItems !== 'undefined') {
      const filtered = menuItems.filter(item =>
        item.name.toLowerCase().includes(term) ||
        item.category.toLowerCase().includes(term) ||
        (item.description && item.description.toLowerCase().includes(term))
      );
      renderMenuItems(filtered);
    }
  }

  document.getElementById('logoutBtn').addEventListener('click', () => {
    if (confirm('Are you sure you want to logout?')) {
      fetch('assets/api/logout.php', { method: 'POST' }).then(() => {
        localStorage.removeItem('rms_user');
        localStorage.removeItem('auth_token');
        localStorage.removeItem('restaurant_cart');
        window.location.href = 'auth/login.html';
      });
    }
  });
</script>
</body>
</html>
