<?php
require_once 'models/User.php';

class AuthController {
    // Hiển thị form đăng nhập
    public function login() {
        if (isset($_SESSION['user'])) {
            header("Location: index.php");
            exit;
        }
        require 'views/auth/login.php';
    }

    // Xử lý logic đăng nhập (POST)
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $database = new Database();
            $db = $database->getConnection();
            $userModel = new User($db);

            $user = $userModel->login($email, $password);

            if ($user) {
                // Lưu user vào session
                $_SESSION['user'] = $user;
                
                // Điều hướng dựa trên Role
                if ($user['role'] == 1) {
                    header("Location: index.php?controller=course&action=index"); // Vào trang giảng viên
                } else {
                    header("Location: index.php"); // Vào trang chủ
                }
            } else {
                $error = "Email hoặc mật khẩu không chính xác!";
                require 'views/auth/login.php';
            }
        }
    }

    // Đăng xuất
    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
    }
}
?>