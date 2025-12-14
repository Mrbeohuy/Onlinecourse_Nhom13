<h2>Danh sách khóa học</h2>

<form method="get" action="<?= BASE_URL ?>/course/index">
    <input type="text" name="keyword"
           placeholder="Tìm theo tên khóa học"
           value="<?= htmlspecialchars($keyword) ?>">

    <select name="category_id">
        <option value="">-- Tất cả danh mục --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                <?= ($categoryId == $cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Tìm kiếm</button>
</form>

<hr>

<?php if (empty($courses)): ?>
    <p>Không có khóa học nào.</p>
<?php else: ?>
    <ul>
        <?php foreach ($courses as $c): ?>
            <li>
                <strong>
    <a href="<?= BASE_URL ?>/course/detail/<?= $c['id'] ?>">
        <?= htmlspecialchars($c['title']) ?>
    </a>
</strong><br>

                Danh mục: <?= htmlspecialchars($c['category_name']) ?><br>
                Giảng viên: <?= htmlspecialchars($c['instructor_name']) ?><br>
                Giá: <?= number_format($c['price']) ?> VNĐ<br>
                Level: <?= htmlspecialchars($c['level']) ?>
            </li>
            <hr>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
