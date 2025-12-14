<h2>Instructor Dashboard</h2>

<p>Xin chào: <b><?= htmlspecialchars(Auth::user()['fullname'] ?? Auth::user()['username']) ?></b></p>

<p>
    <a href="<?= BASE_URL ?>/instructor/courseCreate">
        <button>+ Tạo khóa học mới</button>
    </a>

    <a href="<?= BASE_URL ?>/auth/logout">
        <button>Đăng xuất</button>
    </a>
</p>

<hr>

<?php if (empty($courses)): ?>
    <p>Bạn chưa có khóa học nào.</p>
<?php else: ?>
    <ul>
        <?php foreach ($courses as $c): ?>
            <li>
                <b><?= htmlspecialchars($c['title']) ?></b><br>
                Danh mục: <?= htmlspecialchars($c['category_name'] ?? '') ?><br>
                Giá: <?= number_format((int)$c['price']) ?> VNĐ<br>
                Level: <?= htmlspecialchars($c['level'] ?? '') ?><br>
                Thời lượng: <?= (int)($c['duration_weeks'] ?? 0) ?> tuần
                <br><br>

 <a href="<?= BASE_URL ?>/instructor/courseEdit/<?= (int)$c['id'] ?>"><button>Sửa khóa</button></a>




 <form method="post" action="<?= BASE_URL ?>/instructor/courseDelete/<?= (int)$c['id'] ?>" style="display:inline;">
  <button type="submit" onclick="return confirm('Xóa khóa học này?')">Xóa khóa</button>
</form>



              <a href="<?= BASE_URL ?>/instructor/lessonIndex/<?= (int)$c['id'] ?>">
  <button>Quản lý bài học</button>
</a>

            </li>
            <hr>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
