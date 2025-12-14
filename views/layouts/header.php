<?php
// BẮT BUỘC: header phải có session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống Khóa học Trực tuyến</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            🎓 Học Trực Tuyến
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <!-- LINK CHUNG -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Trang chủ</a>
                </li>

                <?php if (isset($_SESSION['user'])): ?>

                    <!-- ================= HỌC VIÊN ================= -->
                    <?php if ((int)($_SESSION['user']['role'] ?? -1) === 0): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=catalog&action=index">
                                📚 Khóa học
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=enroll&action=myCourses">
                                ✅ Khóa học của tôi
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- ================= GIẢNG VIÊN ================= -->
                    <?php if ((int)($_SESSION['user']['role'] ?? -1) === 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=course&action=index">
                                🧑‍🏫 Quản lý khóa học
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=lesson&action=index">
                                📖 Quản lý bài học
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=materials&action=index">
                                📂 Tài liệu học tập
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- USER DROPDOWN -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            👤 <?php echo htmlspecialchars($_SESSION['user']['fullname'] ?? 'User'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="index.php?controller=auth&action=logout">
                                    🚪 Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php else: ?>

                    <!-- CHƯA ĐĂNG NHẬP -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=auth&action=login">
                            Đăng nhập
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=auth&action=register">
                            Đăng ký
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<!-- ✅ [ĐÃ THÊM] Bootstrap Bundle JS để dropdown hoạt động -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
