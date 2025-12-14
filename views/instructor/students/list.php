<?php include 'views/layouts/header.php'; ?>
<div class="container mt-4">
    <h3>Danh sách học viên đăng ký</h3>
    <a href="index.php?controller=course&action=index" class="btn btn-secondary mb-3">Quay lại</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Ngày đăng ký</th>
                <th>Trạng thái</th>
                <th>Tiến độ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($students as $s): ?>
            <tr>
                <td><?php echo $s['fullname']; ?></td>
                <td><?php echo $s['email']; ?></td>
                <td><?php echo $s['enrolled_date']; ?></td>
                <td>
                    <span class="badge bg-<?php echo ($s['status'] == 'active') ? 'success' : 'secondary'; ?>">
                        <?php echo $s['status']; ?>
                    </span>
                </td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $s['progress']; ?>%;" aria-valuenow="<?php echo $s['progress']; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $s['progress']; ?>%</div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'views/layouts/footer.php'; ?>