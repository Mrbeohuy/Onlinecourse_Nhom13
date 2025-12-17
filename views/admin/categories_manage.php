<h2>Quản lý Danh mục Khóa học</h2>

<div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 10px;">
    <h3>Thêm danh mục mới</h3>
    <form action="index.php?controller=admin&action=manageCategories" method="POST">
        <input type="text" name="name" placeholder="Tên danh mục" required>
        <input type="text" name="description" placeholder="Mô tả">
        <button type="submit" name="add_category">Thêm</button>
    </form>
</div>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Danh mục</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $cate): ?>
        <tr>
            <td><?= $cate['id'] ?></td>
            <td><?= htmlspecialchars($cate['name']) ?></td>
            <td><?= htmlspecialchars($cate['description']) ?></td>
            <td>
                <a href="index.php?controller=admin&action=manageCategories&delete_id=<?= $cate['id'] ?>" 
                   onclick="return confirm('Xóa danh mục này?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>