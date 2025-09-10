<?php
include 'includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit();
}
include 'header.php';

$user_id = $_SESSION['user_id'];

// Fetch user's portfolio items
$items = [];
$check = mysqli_query($conn, "SHOW TABLES LIKE 'user_portfolios'");
if (mysqli_num_rows($check) > 0) {
    $stmt = $conn->prepare('SELECT id, title, description, image, created_at FROM user_portfolios WHERE user_id = ? ORDER BY created_at DESC');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $items = $res->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>مدیریت نمونه‌کارها</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>مدیریت نمونه‌کارها</h2>
    <p>شما می‌توانید چند فایل را انتخاب کنید و برای هر کدام عنوان و توضیح قرار دهید.</p>

    <div class="card p-3 mb-4">
        <form id="batchForm">
            <div class="mb-3">
                <label class="form-label">انتخاب عکس‌ها (چندتایی)</label>
                <input type="file" id="batchFiles" name="files[]" accept="image/*" multiple class="form-control">
            </div>
            <div id="filesMeta"></div>
            <button type="submit" class="btn btn-primary">آپلود همه</button>
        </form>
    </div>

    <h4>نمونه‌کارهای من</h4>
    <div class="row row-cols-1 row-cols-md-3 g-3">
        <?php foreach ($items as $it): ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?php echo 'uploads/' . htmlspecialchars($it['image']); ?>" class="card-img-top" style="height:160px;object-fit:cover">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($it['title']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($it['description'])); ?></p>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted"><?php echo date('Y/m/d', strtotime($it['created_at'])); ?></small>
                            <button class="btn btn-sm btn-danger delete-item" data-id="<?php echo $it['id']; ?>">حذف</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.getElementById('batchFiles').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const container = document.getElementById('filesMeta');
    container.innerHTML = '';
    files.forEach((file, idx) => {
        const id = 'meta_' + idx;
        const div = document.createElement('div');
        div.className = 'mb-3 p-2 border rounded';
        div.innerHTML = `
            <div class="d-flex gap-3 align-items-start">
                <div style="width:120px;height:80px;overflow:hidden;border-radius:8px"><img src="${URL.createObjectURL(file)}" style="width:100%;height:100%;object-fit:cover"></div>
                <div style="flex:1">
                    <input placeholder="عنوان" class="form-control mb-2 meta-title" data-idx="${idx}">
                    <textarea placeholder="توضیحات" class="form-control meta-desc" data-idx="${idx}"></textarea>
                </div>
            </div>
        `;
        container.appendChild(div);
    });
});

document.getElementById('batchForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const files = Array.from(document.getElementById('batchFiles').files);
    if (!files.length) return alert('فایلی انتخاب نشده');

    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'در حال آپلود...';

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const titleEl = document.querySelector('.meta-title[data-idx="' + i + '"]');
        const descEl = document.querySelector('.meta-desc[data-idx="' + i + '"]');

        const fd = new FormData();
        fd.append('image', file);
        fd.append('title', titleEl ? titleEl.value : '');
        fd.append('description', descEl ? descEl.value : '');

        // create progress UI
        const progressWrap = document.createElement('div');
        progressWrap.className = 'mb-2';
        progressWrap.innerHTML = `<div>${file.name}</div><div class="progress"><div class="progress-bar" role="progressbar" style="width:0%"></div></div>`;
        document.getElementById('filesMeta').appendChild(progressWrap);
        const bar = progressWrap.querySelector('.progress-bar');

        try {
            await new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'api/portfolio.php', true);
                xhr.upload.onprogress = function(ev) {
                    if (ev.lengthComputable) {
                        const p = Math.round((ev.loaded / ev.total) * 100);
                        bar.style.width = p + '%';
                        bar.textContent = p + '%';
                    }
                };
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            if (data.success) resolve(data);
                            else reject(new Error(data.message || 'server error'));
                        } catch (e) { reject(e); }
                    } else reject(new Error('HTTP ' + xhr.status));
                };
                xhr.onerror = function() { reject(new Error('network error')); };
                xhr.send(fd);
            });
        } catch (err) {
            alert('خطا در آپلود ' + file.name + ': ' + err.message);
            submitBtn.disabled = false;
            submitBtn.textContent = 'آپلود همه';
            return;
        }
    }

    location.reload();
});

document.querySelectorAll('.delete-item').forEach(btn => {
    btn.addEventListener('click', function() {
        if (!confirm('آیا حذف شود؟')) return;
        const id = this.dataset.id;
        fetch('api/portfolio.php', { method: 'DELETE', headers: {'Content-Type':'application/x-www-form-urlencoded'}, body: 'id=' + encodeURIComponent(id) })
        .then(r => r.json()).then(data => { if (data.success) location.reload(); else alert(data.message || 'خطا'); }).catch(e => alert('خطا'));
    });
});
</script>
</body>
</html>
