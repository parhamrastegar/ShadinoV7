<?php
include 'includes/config.php';
include 'header.php';
// Check database connection
if (!isset($conn) || $conn->connect_error) {
    die('خطا در اتصال به پایگاه داده');
}

// Check if ad ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: allAds.php');
    exit();
}

$ad_id = intval($_GET['id']);

// Validate ad_id
if ($ad_id <= 0) {
    header('Location: allAds.php');
    exit();
}

// Get ad details from database
$stmt = $conn->prepare("
    SELECT a.*, u.name as user_name, u.avatar as user_avatar, u.city as user_city, u.mobile as user_mobile
    FROM ads a 
    JOIN users u ON a.user_id = u.id 
    WHERE a.id = ? AND a.status = 'active'
");
$stmt->bind_param("i", $ad_id);
if (!$stmt->execute()) {
    die('خطا در اجرای کوئری: ' . $stmt->error);
}
$result = $stmt->get_result();
if (!$result) {
    die('خطا در دریافت نتیجه: ' . $stmt->error);
}
$ad = $result->fetch_assoc();

if (!$ad) {
    header('Location: allAds.php');
    exit();
}

// Get proposals for this ad
$stmt = $conn->prepare("
    SELECT p.*, u.name as business_name, u.avatar as business_avatar, u.city as business_city
    FROM proposals p 
    JOIN users u ON p.business_id = u.id 
    WHERE p.ad_id = ? 
    ORDER BY p.created_at DESC
");
$stmt->bind_param("i", $ad_id);
if (!$stmt->execute()) {
    die('خطا در اجرای کوئری پیشنهادات: ' . $stmt->error);
}
$proposals_result = $stmt->get_result();
if (!$proposals_result) {
    die('خطا در دریافت نتیجه پیشنهادات: ' . $stmt->error);
}
$proposals = [];
while ($row = $proposals_result->fetch_assoc()) {
    $proposals[] = $row;
}

// Increment view count
$stmt = $conn->prepare("UPDATE ads SET views = views + 1 WHERE id = ?");
$stmt->bind_param("i", $ad_id);
if (!$stmt->execute()) {
    // Log error but don't stop execution
    error_log('خطا در افزایش تعداد بازدید: ' . $stmt->error);
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($ad['title']); ?> - شادینو</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Vazirmatn', sans-serif;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        
        @keyframes wiggle {
            0%, 7% {
                transform: rotateZ(0);
            }
            15% {
                transform: rotateZ(-15deg);
            }
            20% {
                transform: rotateZ(10deg);
            }
            25% {
                transform: rotateZ(-10deg);
            }
            30% {
                transform: rotateZ(6deg);
            }
            35% {
                transform: rotateZ(-4deg);
            }
            40%, 100% {
                transform: rotateZ(0);
            }
        }
        
        @keyframes sparkle {
            0% {
                transform: scale(0) rotate(0deg);
                opacity: 0;
            }
            50% {
                transform: scale(1) rotate(180deg);
                opacity: 1;
            }
            100% {
                transform: scale(0) rotate(360deg);
                opacity: 0;
            }
        }
        
        @keyframes heartbeat {
            0% {
                transform: scale(1);
            }
            14% {
                transform: scale(1.3);
            }
            28% {
                transform: scale(1);
            }
            42% {
                transform: scale(1.3);
            }
            70% {
                transform: scale(1);
            }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .bounce {
            animation: bounce 2s infinite;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        .float {
            animation: float 3s ease-in-out infinite;
        }
        
        .wiggle {
            animation: wiggle 2s ease-in-out infinite;
        }
        
        .sparkle {
            animation: sparkle 1.5s ease-in-out infinite;
        }
        
        .heartbeat {
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        
        .slide-in {
            animation: slideIn 0.6s ease-out;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #FFB6C1 0%, #FFC0CB 50%, #FFCCCB 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #FF69B4, #FFB6C1, #FFC0CB);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .proposal-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px solid #FFB6C1;
            transition: all 0.3s ease;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        
        .proposal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-color: #FF69B4;
        }
        
        .proposal-form {
            background: linear-gradient(135deg, #FFF0F5, #FFE4E1);
            border-radius: 25px;
            padding: 30px;
            border: 3px solid #FFB6C1;
            position: sticky;
            top: 100px;
        }
        @media (max-width: 576px) {
            .proposal-card, .proposal-form {
                overflow-wrap: anywhere;
                word-break: break-word;
            }
            .submit-proposal-btn, .contact-customer-btn { white-space: normal; }
        }
        
        .submit-proposal-btn {
            background: linear-gradient(135deg, #FF69B4, #FFB6C1);
            color: white;
            padding: 20px 30px;
            border-radius: 15px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 1.2rem;
        }
        
        .submit-proposal-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .contact-customer-btn {
            background: linear-gradient(135deg, #32CD32, #90EE90);
            color: white;
            padding: 15px 30px;
            border-radius: 15px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .contact-customer-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .status-active {
            background: linear-gradient(135deg, #10B981, #34D399);
            color: white;
            animation: pulse 2s infinite;
        }
        
        .status-urgent {
            background: linear-gradient(135deg, #EF4444, #F87171);
            color: white;
            animation: bounce 1s infinite;
        }
        
        .requirement-item {
            background: white;
            border: 2px solid #FFB6C1;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        
        .requirement-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FF69B4, #FFB6C1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .proposal-stats {
            background: linear-gradient(135deg, #E0E7FF, #C7D2FE);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #4F46E5;
        }
        
        .rating-stars {
            color: #FCD34D;
            font-size: 1.2rem;
        }
        
        .price-range {
            background: linear-gradient(135deg, #FEF3C7, #FDE68A);
            border: 2px solid #F59E0B;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal.show {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 25px;
            padding: 30px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            animation: fadeInUp 0.5s ease-out;
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #FF69B4;
            color: white;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
            font-size: 1.2rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #374151;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #FFB6C1;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #FF69B4;
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Responsive Design Improvements */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .proposal-form {
                position: static;
                margin-top: 2rem;
                padding: 1.5rem;
            }
            
            .proposal-stats {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .requirement-item {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }
            
            .requirement-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }
            
            .status-badge {
                font-size: 0.8rem;
                padding: 6px 12px;
            }
            
            .contact-customer-btn {
                padding: 10px 15px;
                font-size: 1rem;
                white-space: normal;
                text-align: center;
            }
            
            .submit-proposal-btn {
                padding: 15px 20px;
                font-size: 1.1rem;
                white-space: normal;
            }
            
            .form-input, .form-textarea {
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .modal-content {
                padding: 1.5rem;
                margin: 1rem;
                max-height: 90vh;
            }
            
            .close-modal {
                width: 30px;
                height: 30px;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .grid {
                gap: 1rem;
            }
            
            .proposal-card, .proposal-form {
                border-radius: 15px;
                padding: 1rem;
            }
            
            .requirement-item {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            .status-badge {
                display: block;
                margin-bottom: 0.5rem;
                text-align: center;
            }
            
            .flex-wrap {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .text-3xl {
                font-size: 1.5rem;
            }
            
            .text-2xl {
                font-size: 1.25rem;
            }
            
            .text-xl {
                font-size: 1.125rem;
            }
            
            .text-lg {
                font-size: 1rem;
            }
            
            .p-6 {
                padding: 1rem;
            }
            
            .mb-8 {
                margin-bottom: 2rem;
            }
            
            .gap-8 {
                gap: 2rem;
            }
            
            .gap-4 {
                gap: 1rem;
            }
            
            .gap-3 {
                gap: 0.75rem;
            }
        }

        /* Text wrapping and overflow fixes */
        .text-wrap {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }
        
        .prose p {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }
        
        .requirement-item > div:last-child {
            min-width: 0;
            flex: 1;
        }
        
        .requirement-item > div:last-child > div:last-child {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .status-badge {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }
        
        .form-label {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .proposal-stats > div:not(.stat-number) {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Ensure all text containers handle overflow properly */
        h1, h2, h3, h4, h5, h6, p, span, div, label {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Fix for long words and URLs */
        * {
            word-break: break-word;
            overflow-wrap: break-word;
        }
        
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="bg-gray-50">
    

    <!-- Breadcrumb -->
    <div class="bg-pink-50 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center text-sm text-gray-600">
                <a href="/" class="hover:text-pink-400 transition-colors">خانه</a>
                <span class="mx-2">←</span>
                <a href="/ads" class="hover:text-pink-400 transition-colors">نیازمندی‌ها</a>
                <span class="mx-2">←</span>
                <a href="#" class="hover:text-pink-400 transition-colors">کیک و شیرینی</a>
                <span class="mx-2">←</span>
                <span class="text-pink-400 font-medium"><?php echo htmlspecialchars($ad['title']); ?></span>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <?php if (isset($_GET['created']) && $_GET['created'] == '1'): ?>
    <div class="container mx-auto px-4 py-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">🎉 موفقیت!</strong>
            <span class="block sm:inline">آگهی شما با موفقیت ایجاد شد و در حال حاضر فعال است.</span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-wrap">
            <!-- Left Column - Customer Request Details -->
            <div class="lg:col-span-2">
                <!-- Request Header -->
                <div class="bg-white rounded-3xl shadow-lg p-6 mb-8 border-2 border-pink-100 text-wrap">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4 gap-3">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold mb-3 gradient-text">
                                <span class="text-4xl ml-3 bounce">🎂</span>
                                <?php echo htmlspecialchars($ad['title']); ?>
                            </h1>
                            <div class="flex flex-wrap gap-3 mb-4">
                                <div class="status-badge status-active">
                                    <span class="sparkle">🟢</span>
                                    فعال
                                </div>
                                <div class="status-badge status-urgent">
                                    <span class="wiggle">⚡</span>
                                    فوری
                                </div>
                                <div class="status-badge" style="background: linear-gradient(135deg, #8B5CF6, #A78BFA); color: white;">
                                    <span class="float">💰</span>
                                    بودجه: <?php echo isset($ad['budget_min']) ? number_format($ad['budget_min']) : '0'; ?> - <?php echo isset($ad['budget_max']) ? number_format($ad['budget_max']) : '0'; ?> تومان
                                </div>
                            </div>
                        </div>
                        <div class="text-left min-w-0">
                            <div class="text-sm text-gray-500 mb-1">تاریخ انتشار</div>
                                                            <div class="font-bold text-gray-700"><?php echo isset($ad['created_at']) ? date('Y/m/d', strtotime($ad['created_at'])) : 'تاریخ نامشخص'; ?></div>
                            <div class="text-sm text-gray-500 mt-2">مهلت پیشنهاد</div>
                                                        <div class="font-bold text-red-500"><?php 
                            if (isset($ad['deadline']) && $ad['deadline']) {
                                $deadline = new DateTime($ad['deadline']);
                                $now = new DateTime();
                                $diff = $now->diff($deadline);
                                if ($deadline > $now) {
                                    echo $diff->days . ' روز باقی‌مانده';
                                } else {
                                    echo 'مهلت تمام شده';
                                }
                            } else {
                                echo 'مهلت نامشخص';
                            }
                            ?></div>
                        </div>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="bg-pink-50 p-4 rounded-xl border-2 border-pink-200 mb-6 text-wrap">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-16 h-16 bg-gradient-to-r from-pink-300 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                <?php echo isset($ad['user_name']) ? mb_substr($ad['user_name'], 0, 1, 'UTF-8') : '?'; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-lg text-gray-800"><?php echo isset($ad['user_name']) ? htmlspecialchars($ad['user_name']) : 'کاربر نامشخص'; ?></h3>
                                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <span class="ml-1 bounce">📍</span>
                                        <?php echo isset($ad['user_city']) ? htmlspecialchars($ad['user_city']) : 'شهر نامشخص'; ?>
                                    </span>
                                    <span class="flex items-center">
                                        <span class="ml-1 pulse">⭐</span>
                                        امتیاز: ۴.۹ (۲۳ نظر)
                                    </span>
                                    <span class="flex items-center">
                                        <span class="ml-1 wiggle">✅</span>
                                        تایید شده
                                    </span>
                                </div>
                            </div>
                            <button onclick="contactCustomer()" class="contact-customer-btn" style="width: auto; margin: 0; padding: 10px 20px;">
                                <span class="ml-2">💬</span>
                                تماس با مشتری
                            </button>
                        </div>
                    </div>

                    <!-- Request Description -->
                    <div class="prose text-gray-700 leading-relaxed">
                        <p class="text-lg mb-4">
                            <?php echo isset($ad['description']) ? nl2br(htmlspecialchars($ad['description'])) : 'توضیحات موجود نیست'; ?>
                        </p>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="bg-white rounded-3xl shadow-lg p-6 mb-8 border-2 border-pink-100">
                    <h2 class="text-2xl font-bold mb-6 gradient-text">
                        <span class="text-3xl ml-2 sparkle">📋</span>
                        جزئیات درخواست
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="requirement-item">
                            <div class="requirement-icon bounce">👥</div>
                            <div>
                                <div class="font-bold text-gray-800">دسته‌بندی</div>
                                <div class="text-gray-600"><?php echo isset($ad['category']) ? htmlspecialchars($ad['category']) : 'دسته‌بندی نامشخص'; ?></div>
                            </div>
                        </div>
                        
                        <div class="requirement-item">
                            <div class="requirement-icon wiggle">📅</div>
                            <div>
                                <div class="font-bold text-gray-800">تاریخ مراسم</div>
                                <div class="text-gray-600"><?php echo isset($ad['event_date']) ? date('Y/m/d', strtotime($ad['event_date'])) : 'تاریخ نامشخص'; ?></div>
                            </div>
                        </div>
                        
                        <div class="requirement-item">
                            <div class="requirement-icon pulse">🕰️</div>
                            <div>
                                <div class="font-bold text-gray-800">شهر</div>
                                <div class="text-gray-600"><?php echo isset($ad['city']) ? htmlspecialchars($ad['city']) : 'شهر نامشخص'; ?></div>
                            </div>
                        </div>
                        
                        <div class="requirement-item">
                            <div class="requirement-icon float">📍</div>
                            <div>
                                <div class="font-bold text-gray-800">محل تحویل</div>
                                <div class="text-gray-600"><?php echo isset($ad['city']) ? htmlspecialchars($ad['city']) : 'شهر نامشخص'; ?></div>
                            </div>
                        </div>
                        
                        <div class="requirement-item">
                            <div class="requirement-icon sparkle">🎨</div>
                            <div>
                                <div class="font-bold text-gray-800">اولویت</div>
                                <div class="text-gray-600">
                                    <?php 
                                    $priority = isset($ad['priority']) ? $ad['priority'] : 'medium';
                                    switch($priority) {
                                        case 'high':
                                            echo 'بالا ⚡';
                                            break;
                                        case 'medium':
                                            echo 'متوسط ⚖️';
                                            break;
                                        case 'low':
                                            echo 'پایین 📉';
                                            break;
                                        default:
                                            echo 'متوسط ⚖️';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="requirement-item">
                            <div class="requirement-icon heartbeat">📅</div>
                            <div>
                                <div class="font-bold text-gray-800">مهلت پیشنهاد</div>
                                <div class="text-gray-600">
                                    <?php 
                                    if (isset($ad['deadline']) && $ad['deadline']) {
                                        $deadline = new DateTime($ad['deadline']);
                                        $now = new DateTime();
                                        $diff = $now->diff($deadline);
                                        if ($deadline > $now) {
                                            echo $diff->days . ' روز باقی‌مانده';
                                        } else {
                                            echo 'مهلت تمام شده';
                                        }
                                    } else {
                                        echo 'مهلت مشخص نشده';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Budget Range -->
                    <div class="price-range mt-6">
                        <h3 class="font-bold text-xl text-orange-600 mb-2">
                            <span class="text-2xl ml-2 bounce">💰</span>
                            بودجه پیشنهادی
                        </h3>
                        <div class="text-3xl font-bold text-orange-700">
                            <?php 
                            if (isset($ad['budget_min']) && isset($ad['budget_max']) && !empty($ad['budget_min']) && !empty($ad['budget_max'])) {
                                echo number_format($ad['budget_min']) . ' - ' . number_format($ad['budget_max']) . ' تومان';
                            } elseif (isset($ad['budget_min']) && !empty($ad['budget_min'])) {
                                echo 'از ' . number_format($ad['budget_min']) . ' تومان';
                            } elseif (isset($ad['budget_max']) && !empty($ad['budget_max'])) {
                                echo 'تا ' . number_format($ad['budget_max']) . ' تومان';
                            } else {
                                echo 'قابل مذاکره';
                            }
                            ?>
                        </div>
                        <div class="text-sm text-orange-600 mt-2">
                            <?php if ((isset($ad['budget_min']) && !empty($ad['budget_min'])) || (isset($ad['budget_max']) && !empty($ad['budget_max']))): ?>
                                قابل مذاکره بسته به کیفیت و طراحی
                            <?php else: ?>
                                بودجه قابل مذاکره است
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Existing Proposals -->
                <div class="bg-white rounded-3xl shadow-lg p-6 border-2 border-pink-100">
                    <h2 class="text-2xl font-bold mb-6 gradient-text">
                        <span class="text-3xl ml-2 pulse">💼</span>
                        پیشنهادات ثبت شده (<?php echo isset($proposals) ? count($proposals) : 0; ?> پیشنهاد)
                    </h2>
                    
                    <?php if (!isset($proposals) || empty($proposals)): ?>
                        <div class="text-center text-gray-500 py-8">
                            <div class="text-4xl mb-4">📝</div>
                            <p class="text-lg">هنوز پیشنهادی برای این آگهی ثبت نشده است</p>
                            <p class="text-sm text-gray-400 mt-2">اولین کسی باشید که پیشنهاد می‌دهد!</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php if (isset($proposals) && is_array($proposals)): ?>
                            <?php foreach ($proposals as $index => $proposal): ?>
                                <div class="proposal-card <?php echo $index === 0 ? 'border-2 border-green-200 bg-green-50' : ''; ?>">
                                    <?php if ($index === 0): ?>
                                        <div class="text-center mb-3">
                                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">🥇 بهترین پیشنهاد</span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-start gap-4">
                                        <div class="w-16 h-16 bg-gradient-to-r from-purple-300 to-purple-400 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                            <?php echo isset($proposal['business_name']) ? mb_substr($proposal['business_name'], 0, 1, 'UTF-8') : '?'; ?>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="font-bold text-lg text-gray-800"><?php echo isset($proposal['business_name']) ? htmlspecialchars($proposal['business_name']) : 'نام نامشخص'; ?></h3>
                                                <div class="text-2xl font-bold text-green-600"><?php echo isset($proposal['price']) ? number_format($proposal['price']) : '0'; ?> تومان</div>
                                            </div>
                                            
                                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                                                <span class="rating-stars">⭐⭐⭐⭐⭐ ۴.۸</span>
                                                <span>📍 <?php echo isset($proposal['business_city']) ? htmlspecialchars($proposal['business_city']) : 'شهر نامشخص'; ?></span>
                                                <span>🏆 <?php echo rand(2, 10); ?> سال سابقه</span>
                                            </div>
                                            
                                            <p class="text-gray-700 mb-3">
                                                <?php echo isset($proposal['description']) ? htmlspecialchars($proposal['description']) : 'توضیحات موجود نیست'; ?>
                                            </p>
                                            
                                            <div class="flex gap-2 flex-wrap">
                                                <?php if (isset($proposal['address']) && $proposal['address']): ?>
                                                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-bold">
                                                        📍 <?php echo htmlspecialchars($proposal['address']); ?>
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if (isset($proposal['delivery_time']) && $proposal['delivery_time']): ?>
                                                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-bold">
                                                        ⏰ <?php echo htmlspecialchars($proposal['delivery_time']); ?>
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if (isset($proposal['extra_services']) && $proposal['extra_services'] && $proposal['extra_services'] != '[]'): ?>
                                                    <?php 
                                                    $services = json_decode($proposal['extra_services'], true);
                                                    if (is_array($services)):
                                                        foreach ($services as $service):
                                                    ?>
                                                        <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-bold">
                                                            🎁 <?php echo htmlspecialchars($service); ?>
                                                        </span>
                                                    <?php 
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                <?php endif; ?>
                                                
                                                <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-sm font-bold">
                                                    📞 <?php echo isset($proposal['contact']) ? htmlspecialchars($proposal['contact']) : 'تماس نامشخص'; ?>
                                                </span>
                                            </div>
                                            
                                            <div class="mt-4 text-xs text-gray-500">
                                                ثبت شده در: <?php echo isset($proposal['created_at']) ? date('Y/m/d H:i', strtotime($proposal['created_at'])) : 'تاریخ نامشخص'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column - Submit Proposal -->
            <div class="lg:col-span-1">
                <!-- Stats -->
                <div class="proposal-stats slide-in">
                    <div class="stat-number bounce"><?php echo isset($proposals) ? count($proposals) : 0; ?></div>
                    <div class="text-lg font-bold text-gray-700">پیشنهاد ثبت شده</div>
                    <div class="text-sm text-gray-600 mt-2">
                        <?php if (isset($proposals) && !empty($proposals)): ?>
                            <?php 
                            if (isset($proposals) && is_array($proposals)) {
                                $total_price = 0;
                                $valid_prices = 0;
                                foreach ($proposals as $proposal) {
                                    if (isset($proposal['price']) && $proposal['price'] > 0) {
                                        $total_price += $proposal['price'];
                                        $valid_prices++;
                                    }
                                }
                                if ($valid_prices > 0) {
                                    $avg_price = $total_price / $valid_prices;
                                    echo 'میانگین قیمت: ' . number_format($avg_price) . ' تومان';
                                } else {
                                    echo 'قیمت در دسترس نیست';
                                }
                            } else {
                                echo 'قیمت در دسترس نیست';
                            }
                            ?>
                        <?php else: ?>
                            هنوز پیشنهادی ثبت نشده
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Submit Proposal Form -->
                <div class="proposal-form slide-in">
                    <h3 class="text-2xl font-bold mb-6 gradient-text text-center">
                        <span class="text-3xl ml-2 sparkle">💼</span>
                        ثبت پیشنهاد
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ml-2 bounce">🏪</span>
                            نام کسب و کار
                        </label>
                        <input type="text" class="form-input" id="business_name" placeholder="نام فروشگاه یا کسب و کار شما">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ml-2 pulse">💰</span>
                            قیمت پیشنهادی (تومان)
                        </label>
                        <input type="text" class="form-input" placeholder="مثال: ۶۵۰۰۰۰">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ml-2 wiggle">⏰</span>
                            زمان آماده‌سازی
                        </label>
                        <select class="form-input">
                            <option>۲۴ ساعت</option>
                            <option>۴۸ ساعت</option>
                            <option>۷۲ ساعت</option>
                            <option>یک هفته</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ml-2 float">📝</span>
                            توضیحات پیشنهاد
                        </label>
                        <textarea class="form-input form-textarea" 
                                  placeholder="توضیح کاملی از محصول، کیفیت مواد اولیه، نحوه طراحی و سایر مزایای پیشنهاد خود ارائه دهید..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ml-2 sparkle">📞</span>
                            شماره تماس
                        </label>
                        <input type="tel" class="form-input" placeholder="۰۹۱۲۳۴۵۶۷۸۹">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ml-2 heartbeat">📍</span>
                            آدرس کسب و کار
                        </label>
                        <input type="text" class="form-input" placeholder="آدرس کامل فروشگاه">
                    </div>
                    
                    <!-- Additional Services -->
                    <div class="form-group">
                        <label class="form-label">خدمات اضافی</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="ml-2">
                                <span>🚚 ارسال رایگان</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="ml-2">
                                <span>🎨 طراحی سفارشی</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="ml-2">
                                <span>📸 عکس‌برداری از محصول</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="ml-2">
                                <span>🛡️ ضمانت کیفیت</span>
                            </label>
                        </div>
                    </div>
                    
                    <button onclick="submitProposal()" class="submit-proposal-btn">
                        <span class="text-xl ml-2 bounce">📤</span>
                        ارسال پیشنهاد
                    </button>
                    
                    <div class="mt-4 text-center text-sm text-gray-600">
                        <p class="mb-1">💡 پیشنهاد شما برای مشتری ارسال می‌شود</p>
                        <p>📞 در صورت انتخاب، مشتری با شما تماس خواهد گرفت</p>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-blue-50 rounded-3xl p-6 mt-6 border-2 border-blue-200">
                    <h3 class="text-xl font-bold mb-4 text-blue-600">
                        <span class="text-2xl ml-2 wiggle">💡</span>
                        نکات مهم
                    </h3>
                    <ul class="space-y-2 text-sm text-blue-700">
                        <li class="flex items-start">
                            <span class="ml-2 mt-1 bounce">✅</span>
                            قیمت واقعی و رقابتی پیشنهاد دهید
                        </li>
                        <li class="flex items-start">
                            <span class="ml-2 mt-1 pulse">✅</span>
                            نمونه کارهای قبلی خود را ذکر کنید
                        </li>
                        <li class="flex items-start">
                            <span class="ml-2 mt-1 sparkle">✅</span>
                            زمان دقیق تحویل را مشخص کنید
                        </li>
                        <li class="flex items-start">
                            <span class="ml-2 mt-1 float">✅</span>
                            خدمات اضافی خود را معرفی کنید
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Proposal Success Modal -->
    <div id="proposalModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeProposalModal()">×</button>
            <div class="text-center">
                <div class="text-6xl mb-4 bounce">🎉</div>
                <h3 class="text-2xl font-bold mb-4 gradient-text">پیشنهاد شما با موفقیت ثبت شد!</h3>
                <div class="bg-green-50 p-4 rounded-xl border-2 border-green-200 mb-6">
                    <p class="text-green-700 mb-2">
                        <span class="font-bold">کد پیگیری:</span> SP-2024-008
                    </p>
                    <p class="text-green-700">
                        پیشنهاد شما برای مشتری ارسال شد و در صورت علاقه‌مندی با شما تماس خواهد گرفت.
                    </p>
                </div>
                <div class="space-y-3">
                    <button onclick="viewMyProposals()" class="w-full bg-blue-500 text-white py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors">
                        مشاهده پیشنهادات من
                    </button>
                    <button onclick="closeProposalModal()" class="w-full bg-gray-200 text-gray-700 py-3 rounded-xl font-bold hover:bg-gray-300 transition-colors">
                        بازگشت به صفحه
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-16 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4 text-pink-400 pulse">🎉 شادینو</h3>
                    <p class="text-gray-300 mb-4">پلتفرم آنلاین برای برگزاری بهترین جشن‌ها</p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4 text-lg">لینک‌های سریع</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">درباره ما</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">تماس با ما</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">قوانین و مقررات</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4 text-lg">خدمات</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">ثبت نیاز</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">جستجوی خدمات</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">پیک و حمل</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4 text-lg">تماس با ما</h4>
                    <div class="space-y-3 text-gray-300">
                        <p>📞 ۰۲۱-۱۲۳۴۵۶۷۸</p>
                        <p>📧 info@shadino.ir</p>
                        <p>📍 تهران، خیابان ولیعصر</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-12 pt-8 text-center">
                <p class="text-gray-300">© ۱۴۰۳ شادینو. تمامی حقوق محفوظ است.</p>
            </div>
        </div>
    </footer>

    <script>
        // Contact customer
        function contactCustomer() {
            alert('اطلاعات تماس مشتری:\n\n📞 شماره تماس: ۰۹۱۲۳۴۵۶۷۸۹\n💬 پیام مستقیم: در دسترس پس از ثبت پیشنهاد\n📧 ایمیل: sara.ahmadi@email.com\n\n⚠️ توجه: برای حفظ حریم خصوصی، اطلاعات کامل پس از ثبت پیشنهاد در دسترس قرار می‌گیرد.');
        }

        // Submit proposal
        function submitProposal() {
            const button = event.target;
            const originalText = button.innerHTML;

            // Get form values with null checks
            const descriptionElement = document.querySelector('textarea.form-textarea');
            const priceElement = document.querySelector('input[placeholder="مثال: ۶۵۰۰۰۰"]');
            const contactElement = document.querySelector('input[type="tel"]');
            const addressElement = document.querySelector('input[placeholder*="آدرس"]');
            const businessNameElement = document.getElementById('business_name');
            const deliverySelect = document.querySelector('select.form-input');
            
            // Check if elements exist before accessing their values
            if (!descriptionElement || !priceElement || !contactElement || !businessNameElement) {
                alert('خطا: فرم پیدا نشد. لطفاً صفحه را رفرش کنید.');
                return;
            }
            
            const description = descriptionElement.value;
            const price = priceElement.value;
            const contact = contactElement.value;
            const business_name = businessNameElement.value;
            const address = addressElement ? addressElement.value : '';
            const delivery_time = deliverySelect ? deliverySelect.value : '';
            // خدمات اضافی
            const extra_services = Array.from(document.querySelectorAll('.form-group input[type="checkbox"]:checked'))
                .map(cb => cb.nextElementSibling ? cb.nextElementSibling.textContent.trim() : '')
                .filter(text => text.length > 0); // Filter out empty strings

            // Validate form
            if (!description || !price || !contact || !business_name) {
                alert('لطفاً تمام فیلدهای ضروری را پر کنید:\n• نام کسب و کار\n• توضیحات پیشنهاد\n• قیمت پیشنهادی\n• شماره تماس');
                return;
            }

            // Show loading state
            button.innerHTML = '<span class="loading-spinner"></span> در حال ارسال...';
            button.disabled = true;

            // Check if ad_id is valid
            const adId = <?php echo isset($ad_id) ? $ad_id : 'null'; ?>;
            if (!adId) {
                alert('خطا: شناسه آگهی نامعتبر است.');
                return;
            }

            // Prepare the data
            const data = {
                ad_id: adId,
                business_name: business_name,
                description: description,
                price: parseInt(price.replace(/,/g, '')),
                contact: contact,
                address: address,
                delivery_time: delivery_time,
                // send as JSON string to match backend expectation
                extra_services: extra_services && extra_services.length ? JSON.stringify(extra_services) : '[]'
            };

            // Send proposal to server
            console.log('Sending proposal data:', data);
            fetch('./api/proposals.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    // Show success message
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'موفقیت‌آمیز',
                            text: 'پیشنهاد شما با موفقیت ثبت شد',
                            confirmButtonText: 'باشه'
                        }).then(() => {
                            // Reload page to show new proposal
                            window.location.reload();
                        });
                    } else {
                        alert('پیشنهاد شما با موفقیت ثبت شد!');
                        window.location.reload();
                    }
                } else {
                    throw new Error(result.message || 'خطا در ثبت پیشنهاد');
                }
            })
            .catch(error => {
                // Show error message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطا',
                        text: error.message || 'خطا در ثبت پیشنهاد. لطفاً دوباره تلاش کنید.',
                        confirmButtonText: 'باشه'
                    });
                } else {
                    alert('خطا: ' + (error.message || 'خطا در ثبت پیشنهاد. لطفاً دوباره تلاش کنید.'));
                }
            })
            .finally(() => {
                // Reset button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // Open proposal success modal
        function openProposalModal() {
            document.getElementById('proposalModal').classList.add('show');
        }

        // Close proposal modal
        function closeProposalModal() {
            document.getElementById('proposalModal').classList.remove('show');
        }

        // View my proposals
        function viewMyProposals() {
            alert('در حال انتقال به صفحه پیشنهادات من...\n\nدر این صفحه می‌توانید:\n• وضعیت پیشنهادات خود را ببینید\n• پیام‌های مشتریان را مطالعه کنید\n• پیشنهادات جدید ثبت کنید');
        }

        // Close modal when clicking outside
        document.getElementById('proposalModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProposalModal();
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling to internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Auto-format price input
            const priceInput = document.querySelector('input[placeholder="مثال: ۶۵۰۰۰۰"]');
            if (priceInput) {
                priceInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/,/g, '');
                    if (!isNaN(value) && value !== '') {
                        e.target.value = parseInt(value).toLocaleString();
                    }
                });
            }
        });
    </script>
</body>
</html>
