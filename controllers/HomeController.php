<?php
require_once 'models/Course.php';

class HomeController {
    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $courseModel = new Course($db);
        
        // Lấy danh sách khóa học mới nhất để hiển thị
        $latestCourses = $courseModel->getLatestCourses(6);
        
        require 'views/home/index.php';
    }
}
?>