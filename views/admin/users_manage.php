<h3>Danh sách người dùng</h3>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['users'] as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['role'] == 2 ? 'Admin' : ($user['role'] == 1 ? 'Giảng viên' : 'Học viên') ?></td>
            <td><?= $user['status'] == 1 ? 'Active' : 'Locked' ?></td>
            <td>
                <?php if ($user['role'] != 2): // Không khóa admin ?>
                <form action="<?= BASE_URL ?>/admin/toggleUserStatus" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="current_status" value="<?= $user['status'] ?>">
                    <button type="submit"><?= $user['status'] == 1 ? 'Khóa' : 'Mở' ?></button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>