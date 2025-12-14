<?php
require_once __DIR__ . '/../core/Controller.php';

class AdminController extends Controller {
    public function dashboard() {
        Auth::requireRole([2]);
        $this->view('admin/dashboard', []);
    }
}
