<?php
session_start();
require_once 'config/Database.php';

// Lấy controller và action từ URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($controller) {
    case 'home':
        require_once 'controllers/HomeController.php';
        $ctrl = new HomeController();
        break;

    // --- QUAN TRỌNG: Phải có case này mới vào được trang đăng nhập ---
    case 'auth':
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();
        break;
    // ----------------------------------------------------------------

    case 'course':
        require_once 'controllers/CourseController.php';
        $ctrl = new CourseController();
        break;

    case 'lesson':
        require_once 'controllers/LessonController.php';
        $ctrl = new LessonController();
        break;
    
    // Nếu controller không khớp cái nào ở trên -> Báo lỗi 404
    default:
        echo "<h1>Lỗi 404: Không tìm thấy trang</h1>";
        echo "<p>Controller '<b>" . htmlspecialchars($controller) . "</b>' không tồn tại trong hệ thống.</p>";
        exit;
}

// Gọi action (hàm) tương ứng trong controller
if (method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    // Nếu action không tồn tại, gọi mặc định index()
    $ctrl->index();
}
?>