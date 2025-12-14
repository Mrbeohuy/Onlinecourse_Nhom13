<?php include 'views/layouts/header.php'; ?>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h3>Tạo khóa học mới</h3>
        </div>
        <div class="card-body">
            <form action="index.php?controller=course&action=store" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label class="form-label">Tiêu đề khóa học (*)</label>
                    <input type="text" name="title" class="form-control" required placeholder="Ví dụ: Lập trình PHP...">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh mục (*)</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php if(isset($categories)): foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Học phí (VNĐ) (*)</label>
                        <input type="number" name="price" class="form-control" required min="0" value="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thời lượng (tuần)</label>
                        <input type="number" name="duration_weeks" class="form-control" min="1" value="4">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cấp độ</label>
                        <select name="level" class="form-select">
                            <option value="Beginner">Beginner (Cơ bản)</option>
                            <option value="Intermediate">Intermediate (Trung bình)</option>
                            <option value="Advanced">Advanced (Nâng cao)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả chi tiết</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh đại diện</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu khóa học
                </button>
            </form>
        </div>
    </div>
</div>
<?php include 'views/layouts/footer.php'; ?>