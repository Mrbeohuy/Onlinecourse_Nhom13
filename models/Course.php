<?php
class Course {
    private $conn;
    private $table = 'courses';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy khóa học theo giảng viên
    public function getByInstructor($instructor_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE instructor_id = :id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $instructor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tạo khóa học mới
    public function create($title, $desc, $instructor_id, $cat_id, $price, $duration, $level, $image) {
        $query = "INSERT INTO " . $this->table . " 
                  (title, description, instructor_id, category_id, price, duration_weeks, level, image, created_at) 
                  VALUES (:title, :desc, :uid, :cat, :price, :dur, :level, :img, NOW())";
        $stmt = $this->conn->prepare($query);
        // Bind params... (giản lược cho ngắn gọn)
        $stmt->execute([
            ':title' => $title, ':desc' => $desc, ':uid' => $instructor_id,
            ':cat' => $cat_id, ':price' => $price, ':dur' => $duration,
            ':level' => $level, ':img' => $image
        ]);
        return $this->conn->lastInsertId();
    }
    
    // Lấy danh sách học viên của 1 khóa học (kèm tiến độ)
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
    
    // Helper cho Home Page
    public function getLatestCourses($limit) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT " . $limit;
        $stmt = $this->conn->prepare($query);
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

    // 2. Cập nhật khóa học
    public function update($id, $title, $desc, $cat_id, $price, $duration, $level, $image) {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, 
                      description = :desc, 
                      category_id = :cat, 
                      price = :price, 
                      duration_weeks = :dur, 
                      level = :level, 
                      image = :image,
                      updated_at = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':desc', $desc);
        $stmt->bindParam(':cat', $cat_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':dur', $duration);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // 3. Xóa khóa học
    public function delete($id) {
        // Lưu ý: Nếu có khóa ngoại (Lessons, Enrollments), cần xóa các bảng con trước 
        // hoặc cài đặt ON DELETE CASCADE trong MySQL. Ở đây ta xóa bảng courses.
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>