<h2>Khóa học của tôi</h2>

<?php if (empty($courses)): ?>
    <p>Bạn chưa đăng ký khóa học nào.</p>
<?php else: ?>
    <ul>
        <?php foreach ($courses as $c): ?>
            <li>
                <b><?= htmlspecialchars($c['title']) ?></b><br>
                Trạng thái: <?= htmlspecialchars($c['status']) ?><br>
                Tiến độ: <?= (int)$c['progress'] ?>%<br>
                <a href="<?= BASE_URL ?>/course/detail/<?= (int)$c['course_id'] ?>">Xem chi tiết</a>
            </li>
            <hr>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= BASE_URL ?>/student/dashboard">← Về dashboard</a>
