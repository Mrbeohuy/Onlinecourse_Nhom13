<?php
require_once 'models/Course.php';
require_once 'models/Category.php'; // Load thêm model danh mục

class CourseController {
    private $db;
    private $courseModel;

    public function __construct() {
        // 1. Kiểm tra đã đăng nhập chưa?
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        // 2. Kiểm tra có phải Giảng viên (role = 1) không?
        // Role: 0=Học viên, 1=Giảng viên, 2=Admin
        if ($_SESSION['user']['role'] != 1) {
            echo "<script>alert('Bạn không có quyền truy cập trang này!'); window.location.href='index.php';</script>";
            exit;
        }

        // Kết nối DB sau khi đã qua bước kiểm tra bảo mật
        $database = new Database();
        $this->db = $database->getConnection();
        $this->courseModel = new Course($this->db);
    }

    public function index() {
        // Lấy dữ liệu động từ DB thay vì fix cứng
        $instructor_id = $_SESSION['user']['id'];
        $courses = $this->courseModel->getByInstructor($instructor_id);
        
        require 'views/instructor/course/manage.php';
    }

    public function create() {
        // Lấy danh mục động từ DB để hiển thị trong thẻ <select>
        $query = "SELECT * FROM categories";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'views/instructor/course/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // ... (Giữ nguyên logic upload ảnh như cũ)
            $imageName = "default.jpg"; // Placeholder
            
            $this->courseModel->create(
                $_POST['title'], 
                $_POST['description'], 
                $_SESSION['user']['id'], // Lấy ID giảng viên từ Session
                $_POST['category_id'], 
                $_POST['price'], 
                $_POST['duration_weeks'], 
                $_POST['level'], 
                $imageName
            );
            header("Location: index.php?controller=course&action=index");
        }
    }
    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?controller=course&action=index");
            exit;
        }
        
        $course_id = $_GET['id'];
        $course = $this->courseModel->getById($course_id);

        // Bảo mật: Kiểm tra khóa học có tồn tại và có thuộc về giảng viên này không
        if (!$course || $course['instructor_id'] != $_SESSION['user']['id']) {
            echo "<script>alert('Bạn không có quyền sửa khóa học này!'); window.location.href='index.php?controller=course&action=index';</script>";
            exit;
        }

        // Lấy danh mục để đổ vào Select box
        $query = "SELECT * FROM categories";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'views/instructor/course/edit.php';
    }

    // --- CHỨC NĂNG CẬP NHẬT (Xử lý POST) ---
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            
            // Kiểm tra quyền sở hữu lại lần nữa cho chắc chắn
            $course = $this->courseModel->getById($id);
            if (!$course || $course['instructor_id'] != $_SESSION['user']['id']) {
                die("Unauthorized");
            }

            // Lấy dữ liệu từ form
            $title = $_POST['title'];
            $description = $_POST['description'];
            $category_id = $_POST['category_id'];
            $price = $_POST['price'];
            $duration_weeks = $_POST['duration_weeks'];
            $level = $_POST['level'];
            
            // Xử lý ảnh: Nếu người dùng không up ảnh mới -> Dùng ảnh cũ
            $imageName = $_POST['current_image']; 

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                // Upload ảnh mới
                $target_dir = "assets/uploads/courses/";
                $fileName = time() . "_" . $_FILES["image"]["name"];
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $fileName)) {
                    $imageName = $fileName;
                    // (Tùy chọn) Có thể xóa ảnh cũ trên server để tiết kiệm bộ nhớ tại đây
                }
            }

            // Gọi Model update
            $this->courseModel->update($id, $title, $description, $category_id, $price, $duration_weeks, $level, $imageName);
            
            header("Location: index.php?controller=course&action=index");
        }
    }

    // --- CHỨC NĂNG XÓA ---
    public function delete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // Kiểm tra quyền sở hữu
            $course = $this->courseModel->getById($id);
            if ($course && $course['instructor_id'] == $_SESSION['user']['id']) {
                $this->courseModel->delete($id);
            } else {
                echo "<script>alert('Bạn không có quyền xóa khóa học này!');</script>";
            }
        }
        header("Location: index.php?controller=course&action=index");
    }
    
    // ... các function khác (students, edit, delete...)
}
?>