<h2>Tạo khóa học</h2>

<form method="post" action="<?= BASE_URL ?>/instructor/courseStore">
    <p>Tiêu đề: <input name="title" required></p>

    <p>Danh mục:
        <select name="category_id">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= (int)$cat['id'] ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>Giá: <input type="number" name="price" value="0"></p>
    <p>Level: <input name="level" value="Beginner"></p>
    <p>Thời lượng (tuần): <input type="number" name="duration_weeks" value="4"></p>

    <p>Mô tả:<br>
        <textarea name="description" rows="5" cols="60"></textarea>
    </p>

    <button type="submit">Lưu</button>
</form>

<a href="<?= BASE_URL ?>/instructor/dashboard">← Quay lại</a>
