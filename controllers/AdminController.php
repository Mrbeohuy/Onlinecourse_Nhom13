<?php
require_once __DIR__ . '/../core/Controller.php';
// Import các models cần thiết nếu chưa autoload
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../config/Database.php'; // Đảm bảo đường dẫn đúng

class AdminController extends Controller {
    private $db;
    private $userModel;
    private $courseModel;
    private $categoryModel;

    public function __construct() {
        // Kiểm tra quyền Admin (role = 2)
        Auth::requireRole([2]);
        
        // Khởi tạo kết nối DB và Models
        $database = new Database(); // Hoặc Database::getInstance()->getConnection() tùy config của bạn
        $this->db = $database->getConnection(); // Giả sử class Database có hàm getConnection
        
        $this->userModel = new User($this->db);
        $this->courseModel = new Course($this->db);
        $this->categoryModel = new Category($this->db);
    }

    // 1. Dashboard thống kê
    public function dashboard() {
        $totalStudents = $this->userModel->countStudents();
        $totalCourses = $this->courseModel->countCourses();
        
        $this->view('admin/dashboard', [
            'totalStudents' => $totalStudents,
            'totalCourses' => $totalCourses
        ]);
    }

    // 2. Quản lý Users
    public function users() {
        $users = $this->userModel->getAllUsers();
        $this->view('admin/users/index', ['users' => $users]);
    }

    public function toggleUserStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $currentStatus = $_POST['current_status'];
            $newStatus = ($currentStatus == 1) ? 0 : 1;
            
            $this->userModel->updateStatus($id, $newStatus);
        }
        // Redirect lại trang danh sách (Giả sử bạn có hàm redirect hoặc dùng header)
        header("Location: " . BASE_URL . "/admin/users");
        exit;
    }

    // 3. Quản lý Danh mục
    public function categories() {
        // Xử lý thêm mới
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
            $this->categoryModel->create($_POST['name'], $_POST['description']);
            header("Location: " . BASE_URL . "/admin/categories");
            exit;
        }

        // Xử lý xóa
        if (isset($_GET['delete_id'])) {
            $this->categoryModel->delete($_GET['delete_id']);
            header("Location: " . BASE_URL . "/admin/categories");
            exit;
        }

        $categories = $this->categoryModel->getAll();
        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    // 4. Duyệt khóa học
    public function coursesApprove() {
        // Xử lý duyệt
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_id'])) {
            $this->courseModel->approveCourse($_POST['approve_id']);
            header("Location: " . BASE_URL . "/admin/coursesApprove");
            exit;
        }

        $pendingCourses = $this->courseModel->getPendingCourses();
        $this->view('admin/courses/pending', ['courses' => $pendingCourses]);
    }
}
?>