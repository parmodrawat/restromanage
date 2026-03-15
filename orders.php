<?php
require 'assets/api/session-check.php';
$user = checkUserSession();
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RestroManage — Orders</title>
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/layout.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/orders.css">
</head>
<body>
<div class="app">

  <aside class="sidebar">
    <div class="brand">
      <img src="img/Logoo.png" alt="RestroManage">
    </div>
    <nav class="menu">
      <a href="index.php"><img src="img/dashboard.png" alt=""> Dashboard</a>
      <a class="active" href="orders.php"><img src="img/cloche9.png" alt=""> Orders</a>
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
      <h2>Orders</h2>
      <div class="search-container">
        <input type="text" placeholder="Search orders...">
      </div>
      <div class="user">
        <div class="user-img"><img src="img/profile.png" alt=""></div>
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

    <section class="table">
      <div class="panel orders-panel">
        <div class="panel-header">
          <h3>All Orders</h3>
          <div class="orders-filter">
            <select class="week-filter" id="statusFilter">
              <option value="all">All Status</option>
              <option value="pending">Pending</option>
              <option value="preparing">Preparing</option>
              <option value="done">Done</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        <div class="table-wrap">
          <table class="orders-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Time</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="ordersTableBody">
              <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">Loading orders...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

  </main>
</div>

<script>
  window.currentUser = <?php echo json_encode(['id' => $user['user_id'], 'email' => $user['email'], 'role' => $user['role'] ?? 'staff']); ?>;
</script>
<script src="assets/js/ordersEnhanced.js"></script>
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
