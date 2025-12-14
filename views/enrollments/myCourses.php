<h2>Khóa học của tôi</h2>

<?php if (empty($courses)): ?>
    <p>Bạn chưa đăng ký khóa học nào.</p>
<?php else: ?>
    <ul>
        <?php foreach ($courses as $c): ?>
            <li>
                <b><?= htmlspecialchars($c['title']) ?></b><br>

                Trạng thái: <?= htmlspecialchars($c['status']) ?><br>

                Tiến độ: <b><?= (int)$c['progress'] ?>%</b><br>

                <progress value="<?= (int)$c['progress'] ?>" max="100"></progress>
                <br><br>

                <a href="<?= BASE_URL ?>/course/detail/<?= (int)$c['course_id'] ?>">
                    <button>Tiếp tục học</button>
                </a>

                <?php if ((int)$c['progress'] >= 100): ?>
                    <span style="color:green; font-weight:bold;">✔ Hoàn thành</span>
                <?php endif; ?>
            </li>
            <hr>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= BASE_URL ?>/student/dashboard">← Về dashboard</a>
