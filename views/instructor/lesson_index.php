
<h2>Quản lý bài học: <?= htmlspecialchars($course['title']) ?></h2>

<p>
    <a href="<?= BASE_URL ?>/instructor/lessonCreate/<?= (int)$course['id'] ?>">
        <button>Thêm bài học</button>
    </a>
</p>

<hr>

<?php if (empty($lessons)): ?>
    <p>Chưa có bài học nào.</p>
<?php else: ?>
    <ol>
        <?php foreach ($lessons as $l): ?>
            <li>
                <b><?= htmlspecialchars($l['title']) ?></b>
                (order: <?= (int)$l['order'] ?>)
                <br><br>

                <a href="<?= BASE_URL ?>/instructor/lessonEdit/<?= (int)$l['id'] ?>"><button>Sửa</button></a>
                <a href="<?= BASE_URL ?>/materials/upload/<?= (int)$l['id'] ?>"><button>Tài liệu</button></a>

                <form method="post" action="<?= BASE_URL ?>/instructor/lessonDelete/<?= (int)$l['id'] ?>" style="display:inline;">
                    <button type="submit" onclick="return confirm('Xóa bài học này?')">Xóa</button>
                </form>
            </li>
            <hr>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>

<a href="<?= BASE_URL ?>/instructor/dashboard">← Quay lại dashboard</a>
