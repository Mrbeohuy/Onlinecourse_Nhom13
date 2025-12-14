<div class="container my-5">
    <h2 class="mb-2"><?php echo htmlspecialchars($course['title'] ?? ''); ?></h2>
    <div class="text-muted mb-4">Tiến độ: <b><?php echo (int)$percent; ?>%</b></div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Bài học</h4>

                    <?php if(!empty($lessons)): ?>
                        <ul class="list-group">
                            <?php foreach($lessons as $i => $l): ?>
                                <?php $done = !empty($progressMap[(int)$l['id']]); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <b>Bài <?php echo $i+1; ?>:</b> <?php echo htmlspecialchars($l['title'] ?? ''); ?>
                                        <?php if($done): ?>
                                            <span class="badge bg-success ms-2">Đã xong</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(!$done): ?>
                                        <form method="post" action="index.php?controller=learning&action=completeLesson" class="m-0">
                                            <input type="hidden" name="course_id" value="<?php echo (int)($course['id'] ?? 0); ?>">
                                            <input type="hidden" name="lesson_id" value="<?php echo (int)$l['id']; ?>">
                                            <!-- ✅ FIX: nút Hoàn thành phải gửi POST về learning/completeLesson -->
<form method="POST" action="index.php?controller=learning&action=completeLesson" class="m-0">
    <input type="hidden" name="course_id" value="<?php echo (int)($course['id'] ?? 0); ?>">
    <input type="hidden" name="lesson_id" value="<?php echo (int)$l['id']; ?>">
    <button type="submit" class="btn btn-primary">
        Hoàn thành
    </button>
</form>

                                            
                                        </form>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">Chưa có bài học.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Tài liệu</h4>

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

            <div class="mt-3">
            </div>
        </div>
    </div>
</div>
