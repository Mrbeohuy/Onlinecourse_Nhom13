<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Material.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Course.php';

class MaterialsController extends Controller {

    // GET /materials/upload/{lessonId}
    public function upload($lessonId = null) {
        Auth::requireRole([1]);
        if ($lessonId === null || !is_numeric($lessonId)) die('Lesson ID không hợp lệ');
        $lessonId = (int)$lessonId;

        $lessonModel = new Lesson();
        $lesson = $lessonModel->getById($lessonId);
        if (!$lesson) die('Không tìm thấy bài học');

        $courseModel = new Course();
        $course = $courseModel->getById((int)$lesson['course_id']);
        if (!$course || (int)$course['instructor_id'] !== (int)Auth::user()['id']) die('Không có quyền');

        $matModel = new Material();
        $materials = $matModel->listByLesson($lessonId);

        $this->view('instructor/materials_upload', [
            'course' => $course,
            'lesson' => $lesson,
            'materials' => $materials,
            'error' => null,
        ]);
    }

    // POST /materials/doUpload/{lessonId}
    public function doUpload($lessonId = null) {
        Auth::requireRole([1]);
        if ($lessonId === null || !is_numeric($lessonId)) die('Lesson ID không hợp lệ');
        $lessonId = (int)$lessonId;

        $lessonModel = new Lesson();
        $lesson = $lessonModel->getById($lessonId);
        if (!$lesson) die('Không tìm thấy bài học');

        $courseModel = new Course();
        $course = $courseModel->getById((int)$lesson['course_id']);
        if (!$course || (int)$course['instructor_id'] !== (int)Auth::user()['id']) die('Không có quyền');

        // Validate file upload
        if (!isset($_FILES['material']) || $_FILES['material']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Vui lòng chọn file hợp lệ.';
            $this->redirect('/materials/upload/' . $lessonId);
        }

        $file = $_FILES['material'];
        $allowed = ['pdf','doc','docx','ppt','pptx','xls','xlsx','zip','rar','7z','txt','md'];
        $maxSize = 25 * 1024 * 1024; // 25MB

        if ($file['size'] <= 0 || $file['size'] > $maxSize) {
            $_SESSION['flash_error'] = 'Kích thước file không hợp lệ (tối đa 25MB).';
            $this->redirect('/materials/upload/' . $lessonId);
        }

        $origName = $file['name'];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            $_SESSION['flash_error'] = 'Định dạng file không được hỗ trợ.';
            $this->redirect('/materials/upload/' . $lessonId);
        }

        // Tạo thư mục lưu trữ
        $relDir = 'assets/uploads/materials/' . (int)$lesson['course_id'] . '/' . $lessonId . '/';
        $absDir = __DIR__ . '/../' . $relDir;
        if (!is_dir($absDir)) {
            mkdir($absDir, 0777, true);
        }

        $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($origName, PATHINFO_FILENAME));
        $finalName = $safeBase . '_' . time() . '.' . $ext;

        $absPath = $absDir . $finalName;
        if (!move_uploaded_file($file['tmp_name'], $absPath)) {
            $_SESSION['flash_error'] = 'Không thể lưu file, vui lòng thử lại.';
            $this->redirect('/materials/upload/' . $lessonId);
        }

        $matModel = new Material();
        $matModel->create([
            'lesson_id' => $lessonId,
            'filename' => $origName,
            'file_path' => $relDir . $finalName,
            'file_type' => $ext,
        ]);

        $_SESSION['flash_success'] = 'Tải tài liệu thành công.';
        $this->redirect('/materials/upload/' . $lessonId);
    }

    // POST /materials/delete/{id}
    public function delete($id = null) {
        Auth::requireRole([1]);
        if ($id === null || !is_numeric($id)) die('ID không hợp lệ');
        $id = (int)$id;

        $matModel = new Material();
        $mat = $matModel->getById($id);
        if (!$mat) die('Không tìm thấy tài liệu');

        $lessonModel = new Lesson();
        $lesson = $lessonModel->getById((int)$mat['lesson_id']);
        if (!$lesson) die('Không tìm thấy bài học');

        $courseModel = new Course();
        $course = $courseModel->getById((int)$lesson['course_id']);
        if (!$course || (int)$course['instructor_id'] !== (int)Auth::user()['id']) die('Không có quyền');

        try {
            $matModel->delete($id);
            $_SESSION['flash_success'] = 'Đã xóa tài liệu.';
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Xóa thất bại: ' . $e->getMessage();
        }

        $this->redirect('/materials/upload/' . (int)$lesson['id']);
    }
}
