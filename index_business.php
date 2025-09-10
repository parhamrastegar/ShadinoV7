<?php
session_start();
include 'includes/config.php';
include 'header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'business') {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'delivery') {
        header('Location: index_courier.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}
?>

<div class="container" style="max-width:900px;margin:2rem auto;background:#fff;border-radius:8px;box-shadow:0 2px 8px #0001;padding:2rem;">
    <h1>خوش آمدید <?php echo isset($_SESSION['business_name']) ? htmlspecialchars($_SESSION['business_name']) : 'کسب‌وکار عزیز'; ?>!</h1>
    <p>در اینجا می‌توانید جدیدترین آگهی‌های ثبت‌شده را مشاهده کنید و برای آن‌ها پیشنهاد ارسال نمایید.</p>
    <div class="ads-list">
        <?php
        // اتصال به دیتابیس و دریافت آگهی‌های جدید
        require_once __DIR__ . '/includes/config.php';
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            echo '<p style="color:red">خطا در اتصال به پایگاه داده.</p>';
        } else {
            $sql = "SELECT id, title, description, created_at FROM ads ORDER BY created_at DESC LIMIT 10";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="ad-item">';
                    echo '<div class="ad-title">' . htmlspecialchars($row['title']) . '</div>';
                    echo '<div class="ad-desc">' . htmlspecialchars($row['description']) . '</div>';
                    echo '<div class="ad-date" style="font-size:12px;color:#aaa;">ثبت شده در: ' . htmlspecialchars($row['created_at']) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>هیچ آگهی جدیدی وجود ندارد.</p>';
            }
            $conn->close();
        }
        ?>
    </div>
</div>
</body>
</html>
