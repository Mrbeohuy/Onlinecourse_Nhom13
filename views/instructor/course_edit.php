<h2>Sửa khóa học</h2>

<form method="post" action="<?= BASE_URL ?>/instructor/courseUpdate/<?= (int)$course['id'] ?>">
    <p>Tiêu đề: <input name="title" value="<?= htmlspecialchars($course['title']) ?>" required></p>

    <p>Danh mục:
        <select name="category_id">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= (int)$cat['id'] ?>" <?= ((int)$course['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>Giá: <input type="number" name="price" value="<?= (int)$course['price'] ?>"></p>
    <p>Level: <input name="level" value="<?= htmlspecialchars($course['level']) ?>"></p>
    <p>Thời lượng (tuần): <input type="number" name="duration_weeks" value="<?= (int)$course['duration_weeks'] ?>"></p>

    <p>Mô tả:<br>
        <textarea name="description" rows="5" cols="60"><?= htmlspecialchars($course['description']) ?></textarea>
    </p>

    <button type="submit">Cập nhật</button>
</form>

<a href="<?= BASE_URL ?>/instructor/dashboard">← Quay lại</a>
