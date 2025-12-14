<?php
require_once 'models/Lesson.php';
require_once 'models/Material.php';

class LessonController {
    private $db;
    private $lessonModel;

    public function __construct() {
        // Check Auth...
        $database = new Database();
        $this->db = $database->getConnection();
        $this->lessonModel = new Lesson($this->db);
    }

    // Quản lý bài học của 1 khóa
    public function manage() {
        $course_id = $_GET['course_id'];
        $lessons = $this->lessonModel->getByCourseId($course_id);
        require 'views/instructor/lessons/manage.php';
    }

    // Lưu bài học mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->lessonModel->create(
                $_POST['course_id'], $_POST['title'], 
                $_POST['content'], $_POST['video_url'], $_POST['order']
            );
            
            // Nếu có upload tài liệu (Material)
            if (!empty($_FILES['material']['name'])) {
                $lesson_id = $this->db->lastInsertId(); // Lấy ID bài học vừa tạo
                $filename = time() . "_" . $_FILES['material']['name'];
                move_uploaded_file($_FILES['material']['tmp_name'], "assets/uploads/materials/" . $filename);
                
                // Insert vào bảng materials (giả sử có Model Material)
                $sql = "INSERT INTO materials (lesson_id, filename, file_path, file_type, uploaded_at) VALUES (?, ?, ?, ?, NOW())";
                $stmt = $this->db->prepare($sql);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $stmt->execute([$lesson_id, $_FILES['material']['name'], $filename, $ext]);
            }

            header("Location: index.php?controller=lesson&action=manage&course_id=" . $_POST['course_id']);
        }
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: index.php"); 
            exit;
        }
        
        $lesson_id = $_GET['id'];
        $lesson = $this->lessonModel->getById($lesson_id);

        if (!$lesson) {
            echo "Bài học không tồn tại"; exit;
        }

        require 'views/instructor/lessons/edit.php';
    }

    // --- CHỨC NĂNG CẬP NHẬT ---
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $course_id = $_POST['course_id']; // Để redirect về đúng chỗ

            $this->lessonModel->update(
                $id,
                $_POST['title'],
                $_POST['content'],
                $_POST['video_url'],
                $_POST['order']
            );

            // (Có thể thêm logic upload lại file material ở đây nếu cần)

            header("Location: index.php?controller=lesson&action=manage&course_id=" . $course_id);
        }
    }

    // --- CHỨC NĂNG XÓA ---
    public function delete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // Lấy thông tin bài học để biết nó thuộc khóa nào (để redirect về)
            $lesson = $this->lessonModel->getById($id);
            if ($lesson) {
                $course_id = $lesson['course_id'];
                $this->lessonModel->delete($id);
                header("Location: index.php?controller=lesson&action=manage&course_id=" . $course_id);
            } else {
                header("Location: index.php?controller=course&action=index");
            }
        }
    }
}
?>