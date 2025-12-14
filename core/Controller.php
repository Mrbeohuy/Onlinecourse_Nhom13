<?php
class Controller {
    protected function view(string $path, array $data = []) {
        extract($data);
        require __DIR__ . "/../views/layouts/header.php";
        require __DIR__ . "/../views/{$path}.php";
        require __DIR__ . "/../views/layouts/footer.php";
    }

    protected function redirect(string $url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }
}
