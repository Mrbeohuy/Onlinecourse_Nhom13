<h2>Sửa bài học - <?= htmlspecialchars($course['title']) ?></h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><b><?= htmlspecialchars($error) ?></b></p>
<?php endif; ?>

<form method="post" action="<?= BASE_URL ?>/instructor/lessonUpdate/<?= (int)$lesson['id'] ?>">
    <p>Tiêu đề:<br>
        <input type="text" name="title" style="width:450px;" value="<?= htmlspecialchars($lesson['title']) ?>" required>
    </p>

    <p>Video URL (nếu có):<br>
        <input type="text" name="video_url" style="width:450px;" value="<?= htmlspecialchars($lesson['video_url'] ?? '') ?>">
    </p>

    <p>Thứ tự bài (`order`):<br>
        <input type="number" name="order" value="<?= (int)($lesson['order'] ?? 1) ?>" min="1">
    </p>

    <p>Nội dung:<br>
        <textarea name="content" rows="8" style="width:450px;"><?= htmlspecialchars($lesson['content'] ?? '') ?></textarea>
    </p>

    <button type="submit">Cập nhật</button>
</form>

<br>
<a href="<?= BASE_URL ?>/instructor/lessonIndex/<?= (int)$course['id'] ?>">← Quay lại danh sách bài học</a>
