<?php
require_once __DIR__ . '/../config/Database.php';

class CatalogController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Danh sách + search + filter category
    public function index()
    {
        $keyword = trim($_GET['q'] ?? '');
        $categoryId = (int)($_GET['category_id'] ?? 0);

        // Lấy categories (nếu có bảng categories)
        $categories = [];
        try {
            $stCat = $this->db->query("SELECT id, name FROM categories ORDER BY name ASC");
            $categories = $stCat->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $categories = [];
        }

        // Query courses
        $sql = "SELECT * FROM courses WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (title LIKE :kw OR description LIKE :kw)";
            $params['kw'] = "%$keyword%";
        }

        if ($categoryId > 0) {
            // Nếu DB bạn dùng course.category_id
            $sql .= " AND category_id = :cid";
            $params['cid'] = $categoryId;
        }

        $sql .= " ORDER BY id DESC";

        $st = $this->db->prepare($sql);
        $st->execute($params);
        $courses = $st->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/catalog/index.php';
    }

    // Chi tiết khóa học
    public function detail()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { echo "Khóa học không hợp lệ"; return; }

        $st = $this->db->prepare("SELECT * FROM courses WHERE id = :id LIMIT 1");
        $st->execute(['id' => $id]);
        $course = $st->fetch(PDO::FETCH_ASSOC);
        if (!$course) { echo "Không tìm thấy khóa học"; return; }

        // lessons
        $lessons = [];
        try {
            $st2 = $this->db->prepare("SELECT * FROM lessons WHERE course_id = :id ORDER BY id ASC");
            $st2->execute(['id' => $id]);
            $lessons = $st2->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $lessons = []; }

        // materials
        $materials = [];
        try {
            $st3 = $this->db->prepare("SELECT * FROM materials WHERE course_id = :id ORDER BY id DESC");
            $st3->execute(['id' => $id]);
            $materials = $st3->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $materials = []; }

        // check enrolled
        $isEnrolled = false;
        if (isset($_SESSION['user']) && (int)($_SESSION['user']['role'] ?? -1) === 0) {
            $sid = (int)($_SESSION['user']['id'] ?? 0);
            $stE = $this->db->prepare("SELECT id, progress FROM enrollments WHERE course_id=:cid AND student_id=:sid LIMIT 1");
            $stE->execute(['cid'=>$id,'sid'=>$sid]);
            $en = $stE->fetch(PDO::FETCH_ASSOC);
            $isEnrolled = $en ? true : false;
            $enrollment = $en;
        } else {
            $enrollment = null;
        }

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/catalog/detail.php';
    }
}
