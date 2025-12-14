<?php include 'views/layouts/header.php'; ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
             <div class="list-group">
                <a href="index.php?controller=course&action=index" class="list-group-item active">
                    <i class="fas fa-book-open"></i> Quản lý khóa học
                </a>
                <a href="#" class="list-group-item">
                    <i class="fas fa-chart-line"></i> Thống kê thu nhập
                </a>
             </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="fas fa-chalkboard-teacher"></i> Các khóa học của tôi</h3>
                <a href="index.php?controller=course&action=create" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tạo khóa học mới
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="10%">Hình ảnh</th>
                            <th width="30%">Tên khóa học</th>
                            <th width="15%">Học phí</th>
                            <th width="15%">Cấp độ</th>
                            <th width="30%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($courses)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted">Bạn chưa tạo khóa học nào.</p>
                                    <a href="index.php?controller=course&action=create" class="btn btn-primary btn-sm">Tạo ngay</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($courses as $c): ?>
                            <tr>
                                <td class="text-center">
                                    <?php 
                                        $imgSrc = !empty($c['image']) ? "assets/uploads/courses/" . $c['image'] : "assets/images/default-course.jpg";
                                    ?>
                                    <img src="<?php echo $imgSrc; ?>" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                </td>
                                
                                <td class="fw-bold text-primary">
                                    <?php echo htmlspecialchars($c['title']); ?>
                                </td>
                                
                                <td class="text-end text-danger fw-bold">
                                    <?php echo number_format($c['price']); ?> đ
                                </td>
                                
                                <td class="text-center">
                                    <?php 
                                        $badgeColor = match($c['level']) {
                                            'Beginner' => 'success',
                                            'Intermediate' => 'warning',
                                            'Advanced' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>
                                    <span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $c['level']; ?></span>
                                </td>
                                
                                <td class="text-center">
                                    <a href="index.php?controller=lesson&action=manage&course_id=<?php echo $c['id']; ?>" 
                                       class="btn btn-sm btn-info text-white" title="Quản lý bài học">
                                        <i class="fas fa-list"></i>
                                    </a>
                                    
                                    <a href="index.php?controller=course&action=students&id=<?php echo $c['id']; ?>" 
                                       class="btn btn-sm btn-secondary" title="Danh sách học viên">
                                        <i class="fas fa-users"></i>
                                    </a>

                                    <a href="index.php?controller=course&action=edit&id=<?php echo $c['id']; ?>" 
                                       class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="index.php?controller=course&action=delete&id=<?php echo $c['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       title="Xóa khóa học"
                                       onclick="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa khóa học này không?\nMọi bài học và dữ liệu liên quan sẽ bị mất!');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'views/layouts/footer.php'; ?>