<?php include 'views/layouts/header.php'; ?>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h3>Chỉnh sửa bài học</h3>
        </div>
        <div class="card-body">
            <form action="index.php?controller=lesson&action=update" method="POST">
                <input type="hidden" name="id" value="<?php echo $lesson['id']; ?>">
                <input type="hidden" name="course_id" value="<?php echo $lesson['course_id']; ?>">

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Tên bài học</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($lesson['title']); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input type="number" name="order" class="form-control" required value="<?php echo $lesson['order']; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Video URL (Youtube/Vimeo)</label>
                    <input type="text" name="video_url" class="form-control" value="<?php echo htmlspecialchars($lesson['video_url']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nội dung bài học</label>
                    <textarea name="content" class="form-control" rows="6"><?php echo htmlspecialchars($lesson['content']); ?></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="index.php?controller=lesson&action=manage&course_id=<?php echo $lesson['course_id']; ?>" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-warning">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'views/layouts/footer.php'; ?>