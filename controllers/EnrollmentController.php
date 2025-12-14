<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Enrollment.php';

class EnrollmentController extends Controller {

    // POST /enrollment/enroll/{courseId}
    public function enroll($courseId = null) {
        Auth::requireRole([0]); // chỉ học viên

        if ($courseId === null || !is_numeric($courseId)) {
            die("Course ID không hợp lệ");
        }

        $studentId = (int)Auth::user()['id'];
        $courseId = (int)$courseId;

        $enrollmentModel = new Enrollment();

        // chặn đăng ký trùng
        if ($enrollmentModel->isEnrolled($courseId, $studentId)) {
            $this->redirect("/course/detail/$courseId");
        }

        // insert
        try {
            $enrollmentModel->enroll($courseId, $studentId);
        } catch (Exception $e) {
            // nếu DB có UNIQUE uq_enroll thì insert trùng sẽ bị lỗi -> vẫn quay lại detail
        }

        $this->redirect("/course/detail/$courseId");
    }

    
    // GET /enrollment/myCourses
public function myCourses() {
    Auth::requireRole([0]); // chỉ học viên

    $studentId = (int)Auth::user()['id'];

    $enrollmentModel = new Enrollment();
    $courses = $enrollmentModel->myCourses($studentId);

    // view hiển thị danh sách khóa học đã đăng ký
    $this->view('student/my_courses', [
        'courses' => $courses
    ]);
}

}
