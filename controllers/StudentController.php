<?php
require_once __DIR__ . '/../core/Controller.php';

class StudentController extends Controller {
    public function dashboard() {
        Auth::requireRole([0]);
        $this->view('student/dashboard', []);
    }
}
