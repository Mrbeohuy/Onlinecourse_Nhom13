<h2>Đăng ký</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="<?= BASE_URL ?>/auth/doRegister">
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Họ tên</label><br>
    <input type="text" name="fullname" required><br><br>

    <label>Mật khẩu</label><br>
    <input type="password" name="password" required><br><br>

    <label>Vai trò</label><br>
    <select name="role">
        <option value="0">Học viên</option>
        <option value="1">Giảng viên</option>
        <option value="2">Admin</option>
    </select><br><br>

    <button type="submit">Tạo tài khoản</button>
</form>

<p>Đã có tài khoản? <a href="<?= BASE_URL ?>/auth/login">Đăng nhập</a></p>
