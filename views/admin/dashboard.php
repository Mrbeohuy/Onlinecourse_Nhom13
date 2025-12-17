<h2>Admin Dashboard</h2>
<div class="stats-container" style="display:flex; gap:20px; margin-bottom:20px;">
    <div class="card" style="border:1px solid #ccc; padding:20px;">
        <h3>Học viên</h3>
        <p style="font-size:24px; color:blue"><?= $data['totalStudents'] ?></p>
    </div>
    <div class="card" style="border:1px solid #ccc; padding:20px;">
        <h3>Khóa học</h3>
        <p style="font-size:24px; color:green"><?= $data['totalCourses'] ?></p>
    </div>
</div>

<ul>
    <li><a href="<?= BASE_URL ?>/admin/users">Quản lý User</a></li>
    <li><a href="<?= BASE_URL ?>/admin/categories">Quản lý Danh mục</a></li>
    <li><a href="<?= BASE_URL ?>/admin/coursesApprove">Duyệt Khóa học</a></li>
</ul>