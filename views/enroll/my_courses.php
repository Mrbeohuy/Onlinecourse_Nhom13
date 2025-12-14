<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-4">
    <h2 class="mb-3">Khóa học đã đăng ký</h2>

    <?php if (!empty($myCourses)): ?>
        <div class="row">
            <?php foreach ($myCourses as $c): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <img
                            class="card-img-top"
                            src="assets/uploads/courses/<?php echo $c['image'] ?? 'default.jpg'; ?>"
                            alt="course"
                        >
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($c['title'] ?? ''); ?></h5>
                            <div>Tiến độ: <b><?php echo (int)($c['progress'] ?? 0); ?>%</b></div>
                            <div>Trạng thái: <b><?php echo htmlspecialchars($c['status'] ?? ''); ?></b></div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a class="btn btn-success w-100"
                               href="index.php?controller=learning&action=course&course_id=<?php echo (int)$c['id']; ?>">
                                Vào học
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Bạn chưa đăng ký khóa học nào.</div>
    <?php endif; ?>
</div>
