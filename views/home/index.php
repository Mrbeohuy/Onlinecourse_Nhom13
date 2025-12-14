<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống Học Tập Trực Tuyến</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'views/layouts/header.php'; ?>

    <div class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Nâng cao kiến thức của bạn ngay hôm nay</h1>
            <p class="lead">Hàng ngàn khóa học từ các chuyên gia hàng đầu đang chờ đón bạn.</p>
            <a href="index.php?controller=auth&action=register" class="btn btn-light btn-lg">Đăng ký ngay</a>
        </div>
    </div>

    <div class="container my-5">
        <h2 class="text-center mb-4">Khóa học mới nhất</h2>
        <div class="row">
            <?php if(!empty($latestCourses)): ?>
                <?php foreach($latestCourses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="assets/uploads/courses/<?php echo $course['image'] ?? 'default.jpg'; ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text text-truncate"><?php echo htmlspecialchars($course['description']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info"><?php echo $course['level']; ?></span>
                                <span class="fw-bold text-danger"><?php echo number_format($course['price']); ?> VND</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                             <a href="index.php?controller=courses&action=detail&id=<?php echo $course['id']; ?>" class="btn btn-outline-primary w-100">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Chưa có khóa học nào.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'views/layouts/footer.php'; ?>
</body>
</html>