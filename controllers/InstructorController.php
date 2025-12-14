<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Progress.php';



class InstructorController extends Controller {
    // GET /instructor/lessonCreate/{courseId}
public function lessonCreate($courseId = null) {
    Auth::requireRole([1]);
    if ($courseId === null || !is_numeric($courseId)) die("Course ID không hợp lệ");

    $courseModel = new Course();
    $course = $courseModel->getById((int)$courseId);
    if (!$course) die("Không tìm thấy khóa học");
    if ((int)$course['instructor_id'] !== (int)Auth::user()['id']) die("Không có quyền");

    $this->view('instructor/lesson_create', [
        'course' => $course,
        'error' => null
    ]);
}

// POST /instructor/lessonStore/{courseId}
public function lessonStore($courseId = null) {
    Auth::requireRole([1]);
    if ($courseId === null || !is_numeric($courseId)) die("Course ID không hợp lệ");

    $courseModel = new Course();
    $course = $courseModel->getById((int)$courseId);
    if (!$course) die("Không tìm thấy khóa học");
    if ((int)$course['instructor_id'] !== (int)Auth::user()['id']) die("Không có quyền");

    $data = [
        'course_id' => (int)$courseId,
        'title' => trim($_POST['title'] ?? ''),
        'content' => trim($_POST['content'] ?? ''),
        'video_url' => trim($_POST['video_url'] ?? ''),
        'order' => (int)($_POST['order'] ?? 1),
    ];

    if ($data['title'] === '') {
        $this->view('instructor/lesson_create', [
            'course' => $course,
            'error' => 'Vui lòng nhập tiêu đề bài học.'
        ]);
        return;
    }

    $lessonModel = new Lesson();
    $lessonModel->create($data);

    $this->redirect('/instructor/lessonIndex/' . (int)$courseId);
}

// GET /instructor/lessonEdit/{lessonId}
public function lessonEdit($lessonId = null) {
    Auth::requireRole([1]);
    if ($lessonId === null || !is_numeric($lessonId)) die("Lesson ID không hợp lệ");

    $lessonModel = new Lesson();
    $lesson = $lessonModel->getById((int)$lessonId);
    if (!$lesson) die("Không tìm thấy bài học");

    $courseModel = new Course();
    $course = $courseModel->getById((int)$lesson['course_id']);
    if (!$course) die("Không tìm thấy khóa học");
    if ((int)$course['instructor_id'] !== (int)Auth::user()['id']) die("Không có quyền");

    $this->view('instructor/lesson_edit', [
        'course' => $course,
        'lesson' => $lesson,
        'error' => null
    ]);
}

// POST /instructor/lessonUpdate/{lessonId}
public function lessonUpdate($lessonId = null) {
    Auth::requireRole([1]);
    if ($lessonId === null || !is_numeric($lessonId)) die("Lesson ID không hợp lệ");

    $lessonModel = new Lesson();
    $lesson = $lessonModel->getById((int)$lessonId);
    if (!$lesson) die("Không tìm thấy bài học");

    $courseModel = new Course();
    $course = $courseModel->getById((int)$lesson['course_id']);
    if (!$course) die("Không tìm thấy khóa học");
    if ((int)$course['instructor_id'] !== (int)Auth::user()['id']) die("Không có quyền");

    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'content' => trim($_POST['content'] ?? ''),
        'video_url' => trim($_POST['video_url'] ?? ''),
        'order' => (int)($_POST['order'] ?? 1),
    ];

    if ($data['title'] === '') {
        $this->view('instructor/lesson_edit', [
            'course' => $course,
            'lesson' => $lesson,
            'error' => 'Vui lòng nhập tiêu đề bài học.'
        ]);
        return;
    }

    $lessonModel->update((int)$lessonId, $data);

    $this->redirect('/instructor/lessonIndex/' . (int)$lesson['course_id']);
}

// POST /instructor/lessonDelete/{lessonId}
// POST /instructor/lessonDelete/{lessonId}
public function lessonDelete($lessonId = null) {
    Auth::requireRole([1]);

    if ($lessonId === null || !is_numeric($lessonId)) {
        $_SESSION['flash_error'] = "Lesson ID không hợp lệ";
        $this->redirect('/instructor/dashboard');
    }

    $lessonId = (int)$lessonId;

    $lessonModel = new Lesson();
    $lesson = $lessonModel->getById($lessonId);

    if (!$lesson) {
        $_SESSION['flash_error'] = "Không tìm thấy bài học";
        $this->redirect('/instructor/dashboard');
    }

    $courseModel = new Course();
    $course = $courseModel->getById((int)$lesson['course_id']);

    if (!$course || (int)$course['instructor_id'] !== (int)Auth::user()['id']) {
        $_SESSION['flash_error'] = "Không có quyền";
        $this->redirect('/instructor/dashboard');
    }

    // ✅ chỉ chặn khi có học viên học/hoàn thành (lesson_progress có dòng)
    $progressModel = new Progress();
    $cnt = $progressModel->countByLessonId($lessonId);

    if ($cnt > 0) {
        $_SESSION['flash_error'] = "Không thể xóa bài học vì đã có học viên học/hoàn thành.";
        $this->redirect('/instructor/lessonIndex/' . (int)$lesson['course_id']);
    }

    try {
        $lessonModel->delete($lessonId);
        $_SESSION['flash_success'] = "Đã xóa bài học.";
    } catch (Exception $e) {
        $_SESSION['flash_error'] = "Xóa bài học thất bại: " . $e->getMessage();
    }

    $this->redirect('/instructor/lessonIndex/' . (int)$lesson['course_id']);
}




    // GET /instructor/dashboard
    public function dashboard() {
        Auth::requireRole([1]);

        $courseModel = new Course();
        $courses = $courseModel->getByInstructorId((int)Auth::user()['id']);

        $this->view('instructor/dashboard', [
            'courses' => $courses
        ]);
    }

    // GET /instructor/courseCreate
    public function courseCreate() {
        Auth::requireRole([1]);

        $catModel = new Category();
        $categories = $catModel->getAll();

        $this->view('instructor/course_create', [
            'categories' => $categories,
            'error' => null
        ]);
    }

    // POST /instructor/courseStore
    public function courseStore() {
        Auth::requireRole([1]);

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (int)($_POST['price'] ?? 0),
            'level' => trim($_POST['level'] ?? ''),
            'duration_weeks' => (int)($_POST['duration_weeks'] ?? 0),
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'instructor_id' => (int)Auth::user()['id'],
        ];

        if ($data['title'] === '' || $data['category_id'] <= 0) {
            $catModel = new Category();
            $categories = $catModel->getAll();
            $this->view('instructor/course_create', [
                'categories' => $categories,
                'error' => 'Vui lòng nhập tiêu đề và chọn danh mục.'
            ]);
            return;
        }

        $courseModel = new Course();
        $courseModel->create($data);

        $this->redirect('/instructor/dashboard');
    }
    // GET /instructor/lessonIndex/{courseId}
public function lessonIndex($courseId = null) {
    Auth::requireRole([1]);
    if ($courseId === null || !is_numeric($courseId)) die("Course ID không hợp lệ");

    $courseModel = new Course();
    $course = $courseModel->getById((int)$courseId);
    if (!$course) die("Không tìm thấy khóa học");
    if ((int)$course['instructor_id'] !== (int)Auth::user()['id']) die("Không có quyền");

    $lessonModel = new Lesson();
    $lessons = $lessonModel->getByCourseId((int)$courseId);

    $this->view('instructor/lesson_index', [
        'course' => $course,
        'lessons' => $lessons
    ]);
}

// GET /instructor/courseEdit/{id}
public function courseEdit($id = null) {
    Auth::requireRole([1]);
    if ($id === null || !is_numeric($id)) die("ID không hợp lệ");

    $courseModel = new Course();
    $course = $courseModel->getById((int)$id);
    if (!$course) die("Không tìm thấy khóa học");
    if ((int)$course['instructor_id'] !== (int)Auth::user()['id']) die("Không có quyền");

    $catModel = new Category();
    $categories = $catModel->getAll();

    $this->view('instructor/course_edit', [
        'course' => $course,
        'categories' => $categories,
        'error' => null
    ]);
}

// POST /instructor/courseUpdate/{id}
public function courseUpdate($id = null) {
    Auth::requireRole([1]);
    if ($id === null || !is_numeric($id)) die("ID không hợp lệ");

    $courseModel = new Course();
    $course = $courseModel->getById((int)$id);
    if (!$course) die("Không tìm thấy khóa học");
    if ((int)$course['instructor_id'] !== (int)Auth::user()['id']) die("Không có quyền");

    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'price' => (int)($_POST['price'] ?? 0),
        'level' => trim($_POST['level'] ?? ''),
        'duration_weeks' => (int)($_POST['duration_weeks'] ?? 0),
        'category_id' => (int)($_POST['category_id'] ?? 0),
    ];

    if ($data['title'] === '' || $data['category_id'] <= 0) {
        $catModel = new Category();
        $categories = $catModel->getAll();
        $this->view('instructor/course_edit', [
            'course' => $course,
            'categories' => $categories,
            'error' => 'Vui lòng nhập tiêu đề và chọn danh mục.'
        ]);
        return;
    }

    $courseModel->update((int)$id, $data);
    $this->redirect('/instructor/dashboard');
}

// POST /instructor/courseDelete/{id}
public function courseDelete($id = null) {
    Auth::requireRole([1]);

    if ($id === null || !is_numeric($id)) {
        $_SESSION['flash_error'] = "ID không hợp lệ";
        $this->redirect('/instructor/dashboard');
    }

    $courseId = (int)$id;

    $courseModel = new Course();
    $course = $courseModel->getById($courseId);

    if (!$course) {
        $_SESSION['flash_error'] = "Không tìm thấy khóa học";
        $this->redirect('/instructor/dashboard');
    }

    if ((int)$course['instructor_id'] !== (int)Auth::user()['id']) {
        $_SESSION['flash_error'] = "Bạn không có quyền xóa khóa học này";
        $this->redirect('/instructor/dashboard');
    }

    // ✅ CHỈ chặn khi có học viên đăng ký
    $enrollmentModel = new Enrollment();
    $cntEnroll = $enrollmentModel->countByCourseId($courseId);

    if ($cntEnroll > 0) {
        $_SESSION['flash_error'] = "Không thể xóa khóa học vì đã có học viên đăng ký.";
        $this->redirect('/instructor/dashboard');
    }

    // ✅ chưa ai đăng ký => cho xóa (xóa lessons trước để không bị FK)
    try {
        $courseModel->deleteCourseAndLessons($courseId);
        $_SESSION['flash_success'] = "Đã xóa khóa học thành công.";
    } catch (Exception $e) {
        $_SESSION['flash_error'] = "Xóa khóa học thất bại: " . $e->getMessage();
    }

    $this->redirect('/instructor/dashboard');
}




}
