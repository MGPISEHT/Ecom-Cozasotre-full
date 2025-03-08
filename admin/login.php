<?php
session_start();
?>
<!doctype html>
<html lang="en">

<?php include 'components/head.php'; ?>
<title>Login Account</title>

<body>
  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">

        <div class="row justify-content-center w-100">

          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">

              <div class="card-body">
                <a class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <h2 class="text-danger">Cozastore</h2>
                </a>
                <p class="text-center">Login your Account</p>
                <form action="functions/authcode.php" method="POST">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <button name="login_btn" type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Login</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'components/js.php' ?>

  <!-- Password Visibility Toggle Script -->
  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.querySelector('i').classList.toggle('fa-eye-slash');
    });
  </script>
</body>

</html>