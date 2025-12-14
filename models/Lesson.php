<?php
class Lesson {
    private $conn;
    private $table = 'lessons';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByCourseId($course_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE course_id = :cid ORDER BY `order` ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':cid' => $course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($course_id, $title, $content, $video, $order) {
        $query = "INSERT INTO " . $this->table . " (course_id, title, content, video_url, `order`, created_at) 
                  VALUES (:cid, :title, :content, :vid, :ord, NOW())";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':cid' => $course_id, ':title' => $title, 
            ':content' => $content, ':vid' => $video, ':ord' => $order
        ]);
    }

    // 1. Lấy chi tiết 1 bài học
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Cập nhật bài học
    public function update($id, $title, $content, $video, $order) {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, 
                      content = :content, 
                      video_url = :vid, 
                      `order` = :ord 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':title' => $title, 
            ':content' => $content, 
            ':vid' => $video, 
            ':ord' => $order,
            ':id' => $id
        ]);
    }

    // 3. Xóa bài học (Xóa tài liệu trước để tránh lỗi Foreign Key)
    public function delete($id) {
        try {
            $this->conn->beginTransaction();

            // Xóa tài liệu đính kèm trước
            $queryMat = "DELETE FROM materials WHERE lesson_id = :id";
            $stmtMat = $this->conn->prepare($queryMat);
            $stmtMat->execute([':id' => $id]);

            // Xóa bài học
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>