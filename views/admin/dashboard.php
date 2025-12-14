<h2>Admin Dashboard</h2>
<p>Xin chào: <?= htmlspecialchars(Auth::user()['fullname']) ?></p>

<ul>
    <li><a href="<?= BASE_URL ?>/admin/users">Quản lý users</a></li>
    <li><a href="<?= BASE_URL ?>/admin/categories">Quản lý danh mục</a></li>
    <li><a href="<?= BASE_URL ?>/admin/coursesApprove">Duyệt khóa học</a></li>
    <li><a href="<?= BASE_URL ?>/auth/logout">Đăng xuất</a></li>
</ul>
