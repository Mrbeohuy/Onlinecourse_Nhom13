<?php
require_once __DIR__ . '/../config/Database.php';

class StudentEnrollmentController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Đăng ký khóa học
    public function enroll()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo "Method not allowed"; return; }

        if (!isset($_SESSION['user']) || (int)($_SESSION['user']['role'] ?? -1) !== 0) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $sid = (int)($_SESSION['user']['id'] ?? 0);
        $cid = (int)($_POST['course_id'] ?? 0);
        if ($sid<=0 || $cid<=0) { echo "Dữ liệu không hợp lệ"; return; }

        // insert ignore kiểu thủ công
        $st = $this->db->prepare("SELECT id FROM enrollments WHERE course_id=:cid AND student_id=:sid LIMIT 1");
        $st->execute(['cid'=>$cid,'sid'=>$sid]);
        if (!$st->fetch()) {
            $ins = $this->db->prepare(
                "INSERT INTO enrollments(course_id, student_id, enrolled_date, status, progress)
                 VALUES(:cid,:sid,NOW(),'active',0)"
            );
            $ins->execute(['cid'=>$cid,'sid'=>$sid]);
        }

        header("Location: index.php?controller=catalog&action=detail&id=".$cid);
        exit;
    }

    // Xem khóa học đã đăng ký
    public function myCourses()
    {
        if (!isset($_SESSION['user']) || (int)($_SESSION['user']['role'] ?? -1) !== 0) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $sid = (int)$_SESSION['user']['id'];

        $sql = "SELECT e.*, c.title, c.image, c.description
                FROM enrollments e
                JOIN courses c ON c.id = e.course_id
                WHERE e.student_id = :sid
                ORDER BY e.enrolled_date DESC";

        $st = $this->db->prepare($sql);
        $st->execute(['sid'=>$sid]);
        $myCourses = $st->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/enroll/my_courses.php';
    }
}
