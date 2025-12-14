<h2>Tài liệu cho bài học: <?= htmlspecialchars($lesson['title']) ?></h2>
<p>Khóa học: <b><?= htmlspecialchars($course['title']) ?></b></p>

<form method="post" action="<?= BASE_URL ?>/materials/doUpload/<?= (int)$lesson['id'] ?>" enctype="multipart/form-data" style="margin: 12px 0;">
  <input type="file" name="material" required />
  <button type="submit">Tải lên</button>
</form>

<hr>
<h3>Danh sách tài liệu</h3>
<?php if (empty($materials)): ?>
  <p>Chưa có tài liệu.</p>
<?php else: ?>
  <ul>
  <?php foreach ($materials as $m): ?>
    <li>
      <a href="<?= BASE_URL . '/' . ltrim($m['file_path'],'/') ?>" target="_blank">
        <?= htmlspecialchars($m['filename']) ?>
      </a>
      (<?= htmlspecialchars($m['file_type']) ?>)
      <form method="post" action="<?= BASE_URL ?>/materials/delete/<?= (int)$m['id'] ?>" style="display:inline">
        <button type="submit" onclick="return confirm('Xóa tài liệu này?')">Xóa</button>
      </form>
    </li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>

<p>
  <a href="<?= BASE_URL ?>/instructor/lessonIndex/<?= (int)$lesson['course_id'] ?>">← Quay lại danh sách bài học</a>
</p>
