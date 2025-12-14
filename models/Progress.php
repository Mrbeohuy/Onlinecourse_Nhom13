<?php
require_once __DIR__ . '/../core/Model.php';

class Progress extends Model {
  public function countByLessonId(int $lessonId): int {
    $stmt = $this->conn->prepare("SELECT COUNT(*) FROM lesson_progress WHERE lesson_id = ?");
    $stmt->execute([$lessonId]);
    return (int)$stmt->fetchColumn();
}



    public function isCompleted(int $studentId, int $lessonId): bool {
        $sql = "SELECT id FROM lesson_progress WHERE student_id=:sid AND lesson_id=:lid LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid'=>$studentId, 'lid'=>$lessonId]);
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markCompleted(int $studentId, int $courseId, int $lessonId): void {
        $sql = "INSERT IGNORE INTO lesson_progress(student_id, course_id, lesson_id)
                VALUES (:sid, :cid, :lid)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid'=>$studentId, 'cid'=>$courseId, 'lid'=>$lessonId]);
    }

    public function countCompletedInCourse(int $studentId, int $courseId): int {
        $sql = "SELECT COUNT(*) FROM lesson_progress WHERE student_id=:sid AND course_id=:cid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid'=>$studentId, 'cid'=>$courseId]);
        return (int)$stmt->fetchColumn();
    }

    public function updateEnrollmentProgress(int $studentId, int $courseId, int $percent): void {
        $sql = "UPDATE enrollments SET progress = :p
                WHERE student_id = :sid AND course_id = :cid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['p'=>$percent, 'sid'=>$studentId, 'cid'=>$courseId]);
    }
}
