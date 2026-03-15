<?php
require 'assets/api/session-check.php';
$user = checkUserSession();
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RestroManage — Kitchen</title>
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/layout.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/kitchen.css">
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
      <a class="active" href="kitchen.php"><img src="img/chef-hat9.png" alt=""> Kitchen</a>
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
      <h2>Kitchen</h2>
      <div class="search-container search-wide">
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
        <div class="card-text"><h3>Pending</h3><p id="countPending">—</p></div>
      </div>
      <div class="card card-2">
        <img src="img/fork.png" alt="">
        <div class="card-text"><h3>Preparing</h3><p id="countPreparing">—</p></div>
      </div>
      <div class="card card-3">
        <img src="img/review.png" alt="">
        <div class="card-text"><h3>Done</h3><p id="countDone">—</p></div>
      </div>
      <div class="card card-4">
        <img src="img/salary.png" alt="">
        <div class="card-text"><h3>Total Today</h3><p id="countTotal">—</p></div>
      </div>
    </section>

    <div class="split-container">

      <section class="panel">
        <div class="kitchen-summary">🍳 Live Kitchen Orders</div>
        <div class="table-wrap">
          <table class="orders-table" id="kitchen-table-2">
            <thead>
              <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">Loading orders...</td></tr>
            </tbody>
          </table>
        </div>
      </section>

      <section class="staff-panel">
        <h3>👨‍🍳 Staff on Duty</h3>
        <div id="sidebar-staff-list" class="staff-grid"></div>
      </section>

    </div>

  </main>
</div>

<!-- Staff Status Modal -->
<div id="staffModal" class="modal-overlay hidden">
  <div class="modal staff-modal">
    <button class="modal-close" onclick="closeStaffPopup()">×</button>
    <div class="staff-modal-header">
      <img id="modalImage" class="modal-staff-image" src="" alt="Staff">
      <div>
        <h2 id="modalName"></h2>
        <p id="modalPosition" class="modal-position"></p>
      </div>
    </div>
    <div class="modal-body">
      <label>Update Status</label>
      <select id="modalStatusSelect" class="status-select">
        <option value="present">🟢 Present</option>
        <option value="break">🟡 On Break</option>
        <option value="leave">🔵 On Leave</option>
        <option value="absent">🔴 Absent</option>
      </select>
      <div class="status-info" id="statusInfo"></div>
    </div>
    <div class="modal-footer">
      <button class="btn secondary" onclick="closeStaffPopup()">Cancel</button>
      <button class="btn primary" onclick="saveStaffStatus()">Save Status</button>
    </div>
  </div>
</div>

<script>
  window.currentUser = <?php echo json_encode(['id' => $user['user_id'], 'email' => $user['email'], 'role' => $user['role'] ?? 'staff']); ?>;
</script>
<script src="assets/js/kitchenEnhanced.js"></script>
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
