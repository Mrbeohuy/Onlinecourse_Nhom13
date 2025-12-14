<?php
require_once __DIR__ . '/../config/Database.php';

class LearningController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Trang vào học của 1 khóa
    public function course()
    {
        // chỉ học viên mới được vào học
        if (!isset($_SESSION['user']) || (int)($_SESSION['user']['role'] ?? -1) !== 0) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $sid = (int)$_SESSION['user']['id'];
        $cid = (int)($_GET['course_id'] ?? 0);
        if ($cid <= 0) { echo "course_id không hợp lệ"; return; }

        // ====== (1) Kiểm tra học viên đã đăng ký khóa chưa + lấy progress trực tiếp ======
        // [SỬA] Không dùng lesson_progress.is_completed nữa
        $stE = $this->db->prepare("
            SELECT progress 
            FROM enrollments 
            WHERE course_id = :cid AND student_id = :sid AND status = 'active'
            LIMIT 1
        ");
        $stE->execute(['cid' => $cid, 'sid' => $sid]);
        $enroll = $stE->fetch(PDO::FETCH_ASSOC);

        if (!$enroll) {
            echo "Bạn chưa đăng ký khóa học này nên không thể vào học.";
            return;
        }

        $percent = (int)($enroll['progress'] ?? 0); // % tiến độ hiện tại từ enrollments.progress

        // ====== (2) Lấy course ======
        $stC = $this->db->prepare("SELECT * FROM courses WHERE id=:id LIMIT 1");
        $stC->execute(['id'=>$cid]);
        $course = $stC->fetch(PDO::FETCH_ASSOC);

        // ====== (3) Lấy lessons ======
        $stL = $this->db->prepare("SELECT * FROM lessons WHERE course_id=:id ORDER BY id ASC");
        $stL->execute(['id'=>$cid]);
        $lessons = $stL->fetchAll(PDO::FETCH_ASSOC);

        // ====== (4) Lấy materials ======
        $materials = [];
        try {
            $stM = $this->db->prepare("SELECT * FROM materials WHERE course_id=:id ORDER BY id DESC");
            $stM->execute(['id'=>$cid]);
            $materials = $stM->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $materials = [];
        }

        // ====== (5) progressMap (để view có thể đánh dấu bài nào đã hoàn thành) ======
        // [SỬA] Vì DB bạn chưa chắc có lesson_progress/is_completed => tạm để rỗng để không lỗi
        $progressMap = [];  // mặc định chưa đánh dấu bài nào

        // Nếu bạn CÓ bảng lesson_progress chuẩn (có cột status hoặc completed) thì sau này mình nối lại.

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/learning/course.php';
    }

    // Mark complete lesson (đánh dấu hoàn thành 1 bài)
    public function completeLesson()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo "Method not allowed"; return; }
        if (!isset($_SESSION['user']) || (int)($_SESSION['user']['role'] ?? -1) !== 0) { echo "forbidden"; return; }

        $sid = (int)$_SESSION['user']['id'];
        $cid = (int)($_POST['course_id'] ?? 0);
        $lid = (int)($_POST['lesson_id'] ?? 0);
        if($cid<=0 || $lid<=0) { echo "invalid"; return; }

        // ====== (SỬA QUAN TRỌNG) ======
        // Vì DB bạn đang dùng progress ở enrollments, ta tăng progress theo số bài học
        // Cách làm: progress = (số bài đã hoàn thành / tổng bài) * 100
        // Ở phiên bản "chạy chắc" này: mỗi lần complete tăng theo bước.
        // (Không dùng lesson_progress để tránh lỗi cột is_completed)

        // Lấy tổng số bài
        $stTotal = $this->db->prepare("SELECT COUNT(*) AS total FROM lessons WHERE course_id = :cid");
        $stTotal->execute(['cid'=>$cid]);
        $total = (int)($stTotal->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

        if ($total <= 0) {
            // nếu khóa không có bài thì set 100 luôn
            $up = $this->db->prepare("UPDATE enrollments SET progress=100 WHERE course_id=:cid AND student_id=:sid");
            $up->execute(['cid'=>$cid,'sid'=>$sid]);
            header("Location: index.php?controller=learning&action=course&course_id=".$cid);
            exit;
        }

        // Lấy progress hiện tại
        $stCur = $this->db->prepare("SELECT progress FROM enrollments WHERE course_id=:cid AND student_id=:sid LIMIT 1");
        $stCur->execute(['cid'=>$cid,'sid'=>$sid]);
        $cur = (int)(($stCur->fetch(PDO::FETCH_ASSOC)['progress'] ?? 0));

        // tăng theo bậc (mỗi bài ~ 100/total)
        $step = (int)floor(100 / $total);
        $newProgress = $cur + $step;
        if ($newProgress > 100) $newProgress = 100;

        // update enrollments.progress
        $up = $this->db->prepare("UPDATE enrollments SET progress=:p WHERE course_id=:cid AND student_id=:sid");
        $up->execute(['p'=>$newProgress,'cid'=>$cid,'sid'=>$sid]);

        header("Location: index.php?controller=learning&action=course&course_id=".$cid);
        exit;
    }
}
