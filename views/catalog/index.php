<div class="container my-5">
    <h2 class="mb-3">Danh sách khóa học</h2>

    <form class="row g-2 mb-4" method="get" action="index.php">
        <input type="hidden" name="controller" value="catalog">
        <input type="hidden" name="action" value="index">

        <div class="col-md-6">
            <input class="form-control" name="q" value="<?php echo htmlspecialchars($keyword ?? ''); ?>" placeholder="Tìm theo tên/mô tả...">
        </div>

        <div class="col-md-4">
            <select class="form-select" name="category_id">
                <option value="0">-- Tất cả danh mục --</option>
                <?php foreach (($categories ?? []) as $c): ?>
                    <option value="<?php echo (int)$c['id']; ?>" <?php echo ((int)($categoryId ?? 0) === (int)$c['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Lọc</button>
        </div>
    </form>

    <div class="row">
        <?php if(!empty($courses)): ?>
            <?php foreach($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="assets/uploads/courses/<?php echo $course['image'] ?? 'default.jpg'; ?>" class="card-img-top">
                        <div class="card-body">
                            <h5><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="text-truncate"><?php echo htmlspecialchars($course['description']); ?></p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a class="btn btn-outline-primary w-100"
                               href="index.php?controller=catalog&action=detail&id=<?php echo (int)$course['id']; ?>">
                               Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có khóa học phù hợp.</p>
        <?php endif; ?>
    </div>
</div>
