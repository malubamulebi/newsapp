<?php helper('url'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= esc($title ?? 'NewsApp') ?></title>

  <!-- Bootstrap CSS (single include) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <!-- Your site styles -->
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

  <?= $this->renderSection('styles') ?>
</head>
<body class="<?= esc($bodyClass ?? 'site-bg') ?>">

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?= site_url('/') ?>">NewsApp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navMain" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        
        <li class="nav-item"><a class="nav-link" href="<?= site_url('/') ?>">Home</a></li>
        <!-- If statement for hiding the menu and logout button once logged in -->
        <?php if (session('isLoggedIn')): ?>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin') ?>">Dashboard</a></li>
            <li class="nav-item">
            <!-- <a class="btn btn-sm btn-outline-danger ms-2" href="<?= site_url('logout') ?>">Logout</a> -->
            </li>
        <?php else: ?>
            <!-- <li class="nav-item"><a class="nav-link" href="<?= site_url('login') ?>">Dashboard</a></li> -->
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link" href="<?= site_url('new-post') ?>">Admin</a></li>
        <li class="nav-item">
        <a class="btn btn-sm btn-outline-danger ms-2" href="<?= site_url('logout') ?>">Logout</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

</nav>

<!-- Flash messages -->
<div class="container mt-3">
  <?php if (session()->has('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= session('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
  <?php endif; ?>

  <?php if (session()->has('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= session('error') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
  <?php endif; ?>
</div>

<?= $this->renderSection('content') ?>

<footer class="text-center py-4 text-muted small">



  © <?= date('Y') ?> NewsApp
</footer>

<!-- Bootstrap JS (bundle includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
