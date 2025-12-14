<h2>Student Dashboard</h2>
<p>Xin chào: <?= htmlspecialchars(Auth::user()['fullname']) ?></p>

<ul>
    <li><a href="<?= BASE_URL ?>/course/index">Xem danh sách khóa học</a></li>
    <li><a href="<?= BASE_URL ?>/enrollment/myCourses">Khóa học đã đăng ký</a></li>
    <li><a href="<?= BASE_URL ?>/auth/logout">Đăng xuất</a></li>
</ul>
