<?php include 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow border-0">
                <div class="card-header text-center bg-primary text-white py-3">
                    <h3 class="mb-0 fw-bold"><i class="fas fa-user-lock"></i> Đăng Nhập</h3>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div><?php echo $error; ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <form action="index.php?controller=auth&action=handleLogin" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" required placeholder="Nhap email..." value="teacher@gmail.com">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Mật khẩu:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" required placeholder="Nhap mat khau..." value="123456">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            ĐĂNG NHẬP
                        </button>
                    </form>
                </div>
                <div class="card-footer text-center bg-white py-3">
                    <small>Chưa có tài khoản? 
                        <a href="index.php?controller=auth&action=register" class="text-primary text-decoration-none fw-bold">Đăng ký ngay</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>