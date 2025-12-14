<?php include 'views/layouts/header.php'; ?>
<div class="container mt-4">
    <h3>Quản lý bài học cho khóa: [ID: <?php echo $_GET['course_id']; ?>]</h3>
    <a href="index.php?controller=course&action=index" class="btn btn-outline-secondary mb-3">&laquo; Quay lại danh sách khóa học</a>

    <div class="card mb-4 p-3 bg-light shadow-sm">
        <h5><i class="fas fa-plus"></i> Thêm bài học mới</h5>
        <form action="index.php?controller=lesson&action=store" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="course_id" value="<?php echo $_GET['course_id']; ?>">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="title" class="form-control mb-2" placeholder="Tên bài học" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="order" class="form-control mb-2" placeholder="Thứ tự" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="video_url" class="form-control mb-2" placeholder="Link Video">
                </div>
            </div>
            <div class="mb-2">
                <textarea name="content" class="form-control" placeholder="Nội dung bài học"></textarea>
            </div>
            <div class="mb-2">
                <label>Tài liệu đính kèm:</label>
                <input type="file" name="material" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Thêm bài học</button>
        </form>
    </div>

    <div class="list-group">
        <?php if(empty($lessons)): ?>
            <div class="alert alert-info">Chưa có bài học nào.</div>
        <?php else: ?>
            <?php foreach($lessons as $lesson): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Bài <?php echo $lesson['order']; ?>: <?php echo htmlspecialchars($lesson['title']); ?></h5>
                    <small class="text-muted"><i class="fas fa-video"></i> <?php echo $lesson['video_url']; ?></small>
                </div>
                <div>
                    <a href="index.php?controller=lesson&action=edit&id=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit"></i> Sửa
                    </a>

                    <a href="index.php?controller=lesson&action=delete&id=<?php echo $lesson['id']; ?>" 
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa bài học này không? Tài liệu đi kèm sẽ bị xóa!');">
                        <i class="fas fa-trash"></i> Xóa
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'views/layouts/footer.php'; ?>