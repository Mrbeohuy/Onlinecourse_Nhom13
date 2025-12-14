<?php
require_once __DIR__ . '/../core/Model.php';

class Material extends Model {

    public function listByLesson(int $lessonId): array {
        $sql = "SELECT * FROM materials WHERE lesson_id = :lid ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['lid' => $lessonId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM materials WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO materials (lesson_id, filename, file_path, file_type, uploaded_at)
                VALUES (:lesson_id, :filename, :file_path, :file_type, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'lesson_id' => (int)$data['lesson_id'],
            'filename'  => $data['filename'],
            'file_path' => $data['file_path'],
            'file_type' => $data['file_type'],
        ]);
    }

    public function delete(int $id): bool {
        // Lấy thông tin để xóa file vật lý
        $mat = $this->getById($id);
        if (!$mat) return false;

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare("DELETE FROM materials WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }

        // Xóa file vật lý sau khi commit để tránh khóa transaction lâu
        $absPath = __DIR__ . '/../' . ltrim($mat['file_path'], '/\\');
        if (is_file($absPath)) {
            @unlink($absPath);
        }
        return true;
    }
}
