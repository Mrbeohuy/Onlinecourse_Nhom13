<h2><?= htmlspecialchars($course['title']) ?></h2>

<p><strong>Danh mục:</strong> <?= htmlspecialchars($course['category_name']) ?></p>
<p><strong>Giảng viên:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
<p><strong>Giá:</strong> <?= number_format($course['price']) ?> VNĐ</p>
<p><strong>Level:</strong> <?= htmlspecialchars($course['level']) ?></p>
<p><strong>Thời lượng:</strong> <?= (int)$course['duration_weeks'] ?> tuần</p>

<hr>

<h3>Mô tả khóa học</h3>
<p><?= nl2br(htmlspecialchars($course['description'])) ?></p>

<hr>

<!-- ===== CHẶNG 4: DANH SÁCH BÀI HỌC ===== -->
<h3>Bài học</h3>

<?php if (empty($lessons)): ?>
    <p>Chưa có bài học nào.</p>
<?php else: ?>
    <ol>
        <?php foreach ($lessons as $l): ?>
            <li>
                <a href="<?= BASE_URL ?>/lesson/detail/<?= (int)$l['id'] ?>">

              
                    <?= htmlspecialchars($l['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>

<hr>

<!-- ===== CHẶNG 3: NÚT ĐĂNG KÝ KHÓA HỌC ===== -->
<?php if (!Auth::check()): ?>
    <a href="<?= BASE_URL ?>/auth/login">
        <button>Đăng nhập để đăng ký</button>
    </a>

<?php elseif ((int)Auth::user()['role'] === 0): ?>

    <?php if (!empty($isEnrolled)): ?>
        <p style="color:green;"><b>Bạn đã đăng ký khóa học này.</b></p>
        <a href="<?= BASE_URL ?>/enrollment/myCourses">
            <button>Xem khóa học của tôi</button>
        </a>
    <?php else: ?>
        <form method="post" action="<?= BASE_URL ?>/enrollment/enroll/<?= (int)$course['id'] ?>">
            <button type="submit">Đăng ký khóa học</button>
        </form>
    <?php endif; ?>

<?php else: ?>
    <p><i>Chỉ học viên mới có thể đăng ký khóa học.</i></p>
<?php endif; ?>

<br><br>
<a href="<?= BASE_URL ?>/course/index">← Quay lại danh sách</a>
