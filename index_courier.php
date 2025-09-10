 <?php
session_start();
include 'includes/config.php';
include 'header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'delivery') {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'business') {
        header('Location: index_business.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}
?>

<div class="container" style="max-width:900px;margin:2rem auto;background:#fff;border-radius:8px;box-shadow:0 2px 8px #0001;padding:2rem;">
    <h1>خوش آمدید <?php echo isset($_SESSION['delivery_name']) ? htmlspecialchars($_SESSION['delivery_name']) : 'پیک عزیز'; ?>!</h1>
    <p>در اینجا می‌توانید آگهی‌هایی که نیاز به پیک دارند را مشاهده کنید.</p>
    <div class="ads-list">
        <div class="ad-item">
            <div class="ad-title">ارسال کیک تولد</div>
            <div class="ad-desc">نیاز به پیک برای ارسال کیک به آدرس خیابان آزادی، ساعت ۵ عصر.</div>
            <div class="ad-delivery">نیاز به پیک دارد</div>
            <div class="ad-date" style="font-size:12px;color:#aaa;">ثبت شده در: 1404/05/30</div>
        </div>
        <div class="ad-item">
            <div class="ad-title">تحویل بسته گل</div>
            <div class="ad-desc">یک بسته گل باید به آدرس خیابان ولیعصر ارسال شود.</div>
            <div class="ad-delivery">نیاز به پیک دارد</div>
            <div class="ad-date" style="font-size:12px;color:#aaa;">ثبت شده در: 1404/05/29</div>
        </div>
        <div class="ad-item">
            <div class="ad-title">ارسال دعوتنامه مراسم</div>
            <div class="ad-desc">توزیع دعوتنامه‌ها به چند آدرس مختلف در سطح شهر.</div>
            <div class="ad-delivery">نیاز به پیک دارد</div>
            <div class="ad-date" style="font-size:12px;color:#aaa;">ثبت شده در: 1404/05/28</div>
        </div>
    </div>
</div>
</body>
</html>
