<?php include 'views/layouts/header.php'; ?>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h3>Chỉnh sửa khóa học</h3>
        </div>
        <div class="card-body">
            <form action="index.php?controller=course&action=update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                <input type="hidden" name="current_image" value="<?php echo $course['image']; ?>">

                <div class="mb-3">
                    <label class="form-label">Tiêu đề khóa học</label>
                    <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($course['title']); ?>">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh mục</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $course['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Học phí</label>
                        <input type="number" name="price" class="form-control" required value="<?php echo $course['price']; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thời lượng (tuần)</label>
                        <input type="number" name="duration_weeks" class="form-control" value="<?php echo $course['duration_weeks']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cấp độ</label>
                        <select name="level" class="form-select">
                            <option value="Beginner" <?php echo ($course['level'] == 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
                            <option value="Intermediate" <?php echo ($course['level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                            <option value="Advanced" <?php echo ($course['level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($course['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh đại diện</label>
                    <input type="file" name="image" class="form-control">
                    <div class="mt-2">
                        <small>Ảnh hiện tại:</small><br>
                        <img src="assets/uploads/courses/<?php echo $course['image']; ?>" width="100" class="img-thumbnail">
                    </div>
                </div>

                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="index.php?controller=course&action=index" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
<?php include 'views/layouts/footer.php'; ?>