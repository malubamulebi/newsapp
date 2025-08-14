<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm border-0 rounded-4">
        
        <!-- Card Header -->
        <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
          <h2 class="fw-bold mb-0">Sign In</h2>
        </div>

        <!-- Card Body -->
        <div class="card-body p-4 p-md-5">
          <form method="post" action="<?= site_url('login') ?>">
            
            <!-- Email -->
            <div class="mb-4">
              <label for="email" class="form-label fw-semibold">Email</label>
              <input type="email" id="email" name="email" 
                     class="form-control form-control-lg" 
                     placeholder="Enter your email" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Password</label>
              <input type="password" id="password" name="password" 
                     class="form-control form-control-lg" 
                     placeholder="Enter your password" required>
            </div>

            <!-- Remember & Forgot -->
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
              </div>
              <a href="#" class="text-decoration-none">Forgot Password?</a>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">
              Login
            </button>
          </form>
        </div>
        
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
