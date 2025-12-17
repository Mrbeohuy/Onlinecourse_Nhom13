<?php
class Course {
    private $conn;
    private $table = 'courses';

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- CODE CŨ (Giữ nguyên) ---
    public function getByInstructor($instructor_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE instructor_id = :id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $instructor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($title, $desc, $instructor_id, $cat_id, $price, $duration, $level, $image) {
        // Mặc định tạo ra là 'draft' hoặc 'pending' tùy logic, ở đây giả sử pending để admin duyệt
        $query = "INSERT INTO " . $this->table . " 
                  (title, description, instructor_id, category_id, price, duration_weeks, level, image, status, created_at) 
                  VALUES (:title, :desc, :uid, :cat, :price, :dur, :level, :img, 'pending', NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':title' => $title, ':desc' => $desc, ':uid' => $instructor_id,
            ':cat' => $cat_id, ':price' => $price, ':dur' => $duration,
            ':level' => $level, ':img' => $image
        ]);
        return $this->conn->lastInsertId();
    }

    public function getStudentsProgress($course_id) {
        $query = "SELECT u.fullname, u.email, e.enrolled_date, e.progress, e.status 
                  FROM enrollments e
                  JOIN users u ON e.student_id = u.id
                  WHERE e.course_id = :cid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cid', $course_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestCourses($limit) {
        // Chỉ lấy khóa học đã published
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'published' ORDER BY created_at DESC LIMIT " . $limit;
        $stmt = $this->conn->prepare($query); // Đã sửa lỗi syntax $this->table
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $desc, $cat_id, $price, $duration, $level, $image) {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, description = :desc, category_id = :cat, 
                      price = :price, duration_weeks = :dur, level = :level, 
                      image = :image, updated_at = NOW()
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':title' => $title, ':desc' => $desc, ':cat' => $cat_id,
            ':price' => $price, ':dur' => $duration, ':level' => $level,
            ':image' => $image, ':id' => $id
        ]);
        return true;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // --- CODE MỚI: Chức năng cho Admin ---

    // Lấy danh sách khóa học đang chờ duyệt (pending)
    public function getPendingCourses() {
        $query = "SELECT c.*, u.fullname as instructor_name 
                  FROM " . $this->table . " c 
                  JOIN users u ON c.instructor_id = u.id 
                  WHERE c.status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Duyệt khóa học
    public function approveCourse($id) {
        $query = "UPDATE " . $this->table . " SET status = 'published' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Đếm tổng khóa học
    public function countCourses() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>