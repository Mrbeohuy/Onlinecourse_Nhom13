<?php
require_once __DIR__ . '/../core/Model.php';

class Enrollment extends Model {
  public function countByCourseId(int $courseId): int {
    $stmt = $this->conn->prepare("SELECT COUNT(*) FROM enrollments WHERE course_id = ?");
    $stmt->execute([$courseId]);
    return (int)$stmt->fetchColumn();
}



    public function isEnrolled(int $courseId, int $studentId): bool {
        $sql = "SELECT id FROM enrollments WHERE course_id = :c AND student_id = :s LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['c' => $courseId, 's' => $studentId]);
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function enroll(int $courseId, int $studentId): bool {
        $sql = "INSERT INTO enrollments (course_id, student_id, status, progress)
                VALUES (:c, :s, 'active', 0)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['c' => $courseId, 's' => $studentId]);
    }

    public function myCourses(int $studentId): array {
        $sql = "
            SELECT e.*, c.title, c.level, c.price
            FROM enrollments e
            JOIN courses c ON e.course_id = c.id
            WHERE e.student_id = :sid
            ORDER BY e.enrolled_date DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
