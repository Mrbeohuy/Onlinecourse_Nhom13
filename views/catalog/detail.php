<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <img src="assets/uploads/courses/<?php echo $course['image'] ?? 'default.jpg'; ?>" class="card-img-top">
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($course['description'] ?? '')); ?></p>

                    <?php if(!isset($_SESSION['user'])): ?>
                        <a class="btn btn-primary w-100" href="index.php?controller=auth&action=login">Đăng nhập để đăng ký</a>
                    <?php elseif((int)($_SESSION['user']['role'] ?? -1) !== 0): ?>
                        <div class="alert alert-warning mb-0">Tài khoản hiện tại không phải học viên.</div>
                    <?php else: ?>
                        <?php if(!$isEnrolled): ?>
                            <form method="post" action="index.php?controller=enroll&action=enroll">
                                <input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>">
                                <button class="btn btn-success w-100" type="submit">Đăng ký khóa học</button>
                            </form>
                        <?php else: ?>
                            <a class="btn btn-success w-100"
                               href="index.php?controller=learning&action=course&course_id=<?php echo (int)$course['id']; ?>">
                               Vào học (<?php echo (int)($enrollment['progress'] ?? 0); ?>%)
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h4>Nội dung (Bài học)</h4>
                    <?php if(!empty($lessons)): ?>
                        <ul class="list-group">
                            <?php foreach($lessons as $i => $l): ?>
                                <li class="list-group-item">
                                    Bài <?php echo $i+1; ?>: <?php echo htmlspecialchars($l['title'] ?? ''); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">Chưa có bài học.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Tài liệu</h4>
                    <?php if(!empty($materials)): ?>
                        <ul class="list-group">
                            <?php foreach($materials as $m): ?>
                                <li class="list-group-item">
                                    <?php echo htmlspecialchars($m['title'] ?? 'Tài liệu'); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">Chưa có tài liệu.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
