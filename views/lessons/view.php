<h2><?= htmlspecialchars($lesson['title']) ?></h2>

<?php if (!empty($lesson['video_url'])): ?>
    <p>
        <a href="<?= htmlspecialchars($lesson['video_url']) ?>" target="_blank">
            ▶ Xem video bài học
        </a>
    </p>
<?php endif; ?>

<p><?= nl2br(htmlspecialchars($lesson['content'])) ?></p>

<hr>

<?php if (!empty($isCompleted)): ?>
    <p style="color:green;"><b>✔ Bạn đã hoàn thành bài học này.</b></p>
<?php else: ?>
    <form method="post" action="<?= BASE_URL ?>/lesson/complete/<?= (int)$lesson['id'] ?>">
        <button type="submit">Hoàn thành bài học</button>
    </form>
<?php endif; ?>

<br>
<a href="<?= BASE_URL ?>/course/detail/<?= (int)$lesson['course_id'] ?>">← Quay lại khóa học</a>
