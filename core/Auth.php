<?php
class Auth {
    public static function user() {
        return $_SESSION['user'] ?? null;
    }

    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function requireLogin() {
        if (!self::check()) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
    }

    public static function requireRole(array $roles) {
        self::requireLogin();
        $u = self::user();
        if (!in_array((int)$u['role'], $roles, true)) {
            http_response_code(403);
            die("403 Forbidden");
        }
    }
}
