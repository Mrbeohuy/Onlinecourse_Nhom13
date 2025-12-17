<h3>Danh sách Khóa học chờ duyệt</h3>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>Tên khóa học</th>
            <th>Giảng viên</th>
            <th>Giá</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['courses'] as $course): ?>
        <tr>
            <td><?= htmlspecialchars($course['title']) ?></td>
            <td><?= htmlspecialchars($course['instructor_name']) ?></td>
            <td><?= number_format($course['price']) ?> VNĐ</td>
            <td>
                <form action="<?= BASE_URL ?>/admin/coursesApprove" method="POST">
                    <input type="hidden" name="approve_id" value="<?= $course['id'] ?>">
                    <button type="submit">Duyệt bài</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>