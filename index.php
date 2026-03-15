<?php
require 'assets/api/session-check.php';
$user = checkUserSession();
$displayName = explode('@', $user['email'])[0];
$displayName = ucfirst($displayName);
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RestroManage — Dashboard</title>
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/layout.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    window.currentUser = <?php echo json_encode([
      'id'    => $user['user_id'],
      'email' => $user['email'],
      'role'  => $user['role'] ?? 'staff'
    ]); ?>;
  </script>
</head>
<body>
<div class="app">

  <aside class="sidebar">
    <div class="brand">
      <img src="img/Logoo.png" alt="RestroManage">
    </div>
    <nav class="menu">
      <a class="active" href="index.php"><img src="img/dashboard 9.png" alt=""> Dashboard</a>
      <a href="orders.php"><img src="img/cloche.png" alt=""> Orders</a>
      <a href="kitchen.php"><img src="img/chef-hat.png" alt=""> Kitchen</a>
      <a href="menu.php"><img src="img/cloche.png" alt=""> Menu</a>
    </nav>
    <div class="sidebar-footer">
      <button id="logoutBtn" class="logout-btn">
        <img src="img/logout.png" alt=""> Logout
      </button>
    </div>
  </aside>

  <main class="main">

    <header class="header">
      <h2>👋 Hello, <?php echo htmlspecialchars($displayName); ?></h2>
      <div class="search-container">
        <input type="text" placeholder="Search anything...">
      </div>
      <div class="user">
        <div class="user-img"><img src="img/profile.png" alt="Profile"></div>
        <div class="user-data">
          <h4><?php echo htmlspecialchars(ucfirst($user['role'] ?? 'Staff')); ?></h4>
          <p><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
      </div>
    </header>

    <section class="cards">
      <div class="card card-1">
        <img src="img/tray.png" alt="">
        <div class="card-text"><h3>Total Orders</h3><p id="statTotal">—</p></div>
      </div>
      <div class="card card-2">
        <img src="img/fork.png" alt="">
        <div class="card-text"><h3>Running Orders</h3><p id="statRunning">—</p></div>
      </div>
      <div class="card card-3">
        <img src="img/review.png" alt="">
        <div class="card-text"><h3>Customers</h3><p id="statCustomers">—</p></div>
      </div>
      <div class="card card-4">
        <img src="img/salary.png" alt="">
        <div class="card-text"><h3>Revenue</h3><p id="statRevenue">—</p></div>
      </div>
    </section>

    <section class="panel" style="margin-bottom:22px;">
      <div class="panel-inner">
        <div class="day-chart sales-details">
          <div class="sales-header">
            <div class="sales-title">
              <h3>Sales by Category</h3>
              <p class="sales-sub">Current period</p>
            </div>
            <select class="filter">
              <option>Monthly</option>
              <option>Weekly</option>
              <option>Daily</option>
            </select>
          </div>
          <div class="sales-body">
            <div class="donut-wrap">
              <canvas id="myChart"></canvas>
              <div class="donut-center" id="donutCenter">—</div>
            </div>
            <div class="legend" id="chartLegend"></div>
          </div>
        </div>

        <div class="week-chart">
          <div class="week-header">
            <h3>Weekly Orders</h3>
            <select class="filter week-filter">
              <option>Last 7 days</option>
              <option>Last 14 days</option>
              <option>Monthly</option>
            </select>
          </div>
          <div class="bar-wrap">
            <canvas id="orderBarChart"></canvas>
          </div>
        </div>
      </div>
    </section>

    <section class="trending-section">
      <div class="trending-header">
        <h3>Trending Items</h3>
        <div class="trending-nav">
          <button class="trend-prev">◀</button>
          <button class="trend-next">▶</button>
        </div>
      </div>
      <div class="trending-list">
        <div class="trend-card">
          <div class="card-media" style="background-image:url('img/chicken-pot.png')"></div>
          <div class="card-body">
            <div class="card-title">Chicken Pot Pie</div>
            <div class="card-price">₹299</div>
          </div>
        </div>
        <div class="trend-card">
          <div class="card-media" style="background-image:url('img/Massed-Salad.png')"></div>
          <div class="card-body">
            <div class="card-title">Massed Salad</div>
            <div class="card-price">₹245</div>
          </div>
        </div>
        <div class="trend-card">
          <div class="card-media" style="background-image:url('img/rice-troppings.png')"></div>
          <div class="card-body">
            <div class="card-title">Rice Toppings</div>
            <div class="card-price">₹225</div>
          </div>
        </div>
      </div>
    </section>

  </main>
</div>

<script src="assets/js/dashboard.js"></script>
<script>
  document.getElementById('logoutBtn').addEventListener('click', () => {
    if (confirm('Are you sure you want to logout?')) {
      fetch('assets/api/logout.php', { method: 'POST' }).then(() => {
        localStorage.removeItem('rms_user');
        localStorage.removeItem('auth_token');
        window.location.href = 'auth/login.html';
      });
    }
  });
</script>
</body>
</html>
