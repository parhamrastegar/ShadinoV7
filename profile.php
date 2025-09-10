<?php
include 'includes/config.php';

// Check if user is logged in BEFORE including header.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'header.php';

// Get user ID from URL parameter or use current user
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : $_SESSION['user_id'];

// Get user data
    $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

if (!$user_data) {
    echo '<div class="alert alert-danger text-center">کاربر یافت نشد</div>';
    exit();
}

// Check if rating system is installed
$rating_system_installed = false;
$stats_table_check = mysqli_query($conn, "SHOW TABLES LIKE 'user_stats'");
$ratings_table_check = mysqli_query($conn, "SHOW TABLES LIKE 'user_ratings'");
if (mysqli_num_rows($stats_table_check) > 0 && mysqli_num_rows($ratings_table_check) > 0) {
    $rating_system_installed = true;
}

// Determine if viewing own profile
$is_own_profile = ($user_id == $_SESSION['user_id']);

// Get user stats (check if table exists first)
$user_stats = [
    'total_ratings' => 0,
    'average_rating' => 0.00,
    'total_comments' => 0,
    'last_rating_date' => null
];

// Check if user_stats table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'user_stats'");
if (mysqli_num_rows($table_check) > 0) {
    $stats_stmt = $conn->prepare('SELECT * FROM user_stats WHERE user_id = ?');
    $stats_stmt->bind_param('i', $user_id);
    $stats_stmt->execute();
    $stats_result = $stats_stmt->get_result();
    $user_stats = $stats_result->fetch_assoc();

    if (!$user_stats) {
        // Create stats record if doesn't exist
        $create_stats_stmt = $conn->prepare('INSERT INTO user_stats (user_id) VALUES (?)');
        $create_stats_stmt->bind_param('i', $user_id);
        $create_stats_stmt->execute();
        
        $user_stats = [
            'total_ratings' => 0,
            'average_rating' => 0.00,
            'total_comments' => 0,
            'last_rating_date' => null
        ];
    }
}

// Get recent ratings (check if table exists first)
$recent_ratings = [];
$existing_rating = null;

// Check if user_ratings table exists
$ratings_table_check = mysqli_query($conn, "SHOW TABLES LIKE 'user_ratings'");
if (mysqli_num_rows($ratings_table_check) > 0) {
    $ratings_stmt = $conn->prepare('
        SELECT ur.*, u.name as rater_name, u.avatar as rater_avatar
        FROM user_ratings ur
        JOIN users u ON ur.rater_id = u.id
        WHERE ur.user_id = ?
        ORDER BY ur.created_at DESC
        LIMIT 5
    ');
    $ratings_stmt->bind_param('i', $user_id);
    $ratings_stmt->execute();
    $ratings_result = $ratings_stmt->get_result();
    $recent_ratings = $ratings_result->fetch_all(MYSQLI_ASSOC);

    // Check if current user has already rated this user
    if (!$is_own_profile) {
        $existing_rating_stmt = $conn->prepare('SELECT * FROM user_ratings WHERE user_id = ? AND rater_id = ?');
        $existing_rating_stmt->bind_param('ii', $user_id, $_SESSION['user_id']);
        $existing_rating_stmt->execute();
        $existing_rating_result = $existing_rating_stmt->get_result();
        $existing_rating = $existing_rating_result->fetch_assoc();
    }
}
    
    // Load portfolio items if table exists
    $portfolio_items = [];
    $portfolio_table_check = mysqli_query($conn, "SHOW TABLES LIKE 'user_portfolios'");
    if (mysqli_num_rows($portfolio_table_check) > 0) {
        $pf_stmt = $conn->prepare('SELECT id, title, description, image, created_at FROM user_portfolios WHERE user_id = ? ORDER BY created_at DESC');
        $pf_stmt->bind_param('i', $user_id);
        $pf_stmt->execute();
        $pf_result = $pf_stmt->get_result();
        $portfolio_items = $pf_result->fetch_all(MYSQLI_ASSOC);
    }
?>
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل <?php echo htmlspecialchars($user_data['name']); ?> - شادینو</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Vazirmatn', sans-serif;
        }
        
        :root {
            --primary-color: #FF69B4;
            --secondary-color: #FFB6C1;
            --light-pink: #FFC0CB;
            --accent-color: #FF1493;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --error-color: #EF4444;
            --gradient-primary: linear-gradient(135deg, #FF69B4, #FFB6C1);
            --gradient-secondary: linear-gradient(135deg, #FFF0F5, #FFE4E1);
            --shadow-primary: 0 15px 35px rgba(255, 105, 180, 0.3);
            --shadow-secondary: 0 8px 25px rgba(0, 0, 0, 0.1);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background: linear-gradient(135deg, #FFE4E1 0%, #FFF0F5 50%, #F8F9FA 100%);
            min-height: 100vh;
        }

        .main-container {
            padding-top: 100px;
            padding-bottom: 50px;
        }
        
        .profile-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 105, 180, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(255, 105, 180, 0.2);
        }

        .profile-header {
            background: var(--gradient-primary);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: rotate 10s linear infinite;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            margin: 0 auto 1rem;
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }
        
        .profile-name {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }

        .profile-role {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .rating-section {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 105, 180, 0.1);
        }

        .rating-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .rating-stars {
            display: flex;
            gap: 0.2rem;
        }

        .star {
            font-size: 1.5rem;
            color: #FFD700;
            transition: all 0.2s ease;
        }

        .star.empty {
            color: #E5E7EB;
        }

        .rating-number {
                font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .rating-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            text-align: center;
        }

        .stat-item {
            display: flex;
                flex-direction: column;
            align-items: center;
            }
            
            .stat-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            }
            
            .stat-label {
                font-size: 0.9rem;
            color: #6B7280;
            }
            
        .profile-info {
            padding: 1.5rem;
            }
            
        .info-item {
            display: flex;
            align-items: center;
                margin-bottom: 1rem;
            padding: 0.75rem;
            background: rgba(255, 105, 180, 0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: rgba(255, 105, 180, 0.1);
            transform: translateX(-5px);
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-left: 1rem;
            font-size: 1.2rem;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #6B7280;
        }

        .ratings-section {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
                align-items: center;
                gap: 0.5rem;
        }

        .rating-item {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 105, 180, 0.1);
            border-radius: 15px;
                padding: 1rem;
                margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .rating-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.1);
        }

        .rating-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .rater-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .rater-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .rater-name {
            font-weight: 600;
            color: #374151;
        }

        .rating-date {
            font-size: 0.9rem;
            color: #6B7280;
        }

        .rating-comment {
            color: #4B5563;
            line-height: 1.6;
            margin-top: 0.5rem;
        }

        .rating-form {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 105, 180, 0.2);
            border-radius: 20px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
        }

        .rating-input {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .star-input {
            font-size: 2rem;
            color: #E5E7EB;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .star-input:hover,
        .star-input.active {
            color: #FFD700;
            transform: scale(1.1);
        }

        .form-control {
            border: 2px solid rgba(255, 105, 180, 0.2);
            border-radius: 12px;
                padding: 0.75rem;
                font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
            outline: none;
        }

        .btn-rating {
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-rating:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.3);
        }

        .btn-rating:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.3);
            color: white;
        }

        .no-ratings {
            text-align: center;
            color: #6B7280;
            padding: 2rem;
            font-style: italic;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 1rem;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #065F46;
            border-left: 4px solid #10B981;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #991B1B;
            border-left: 4px solid #EF4444;
        }

        @media (max-width: 768px) {
            .main-container {
                padding-top: 80px;
            }
            
            .profile-header {
                padding: 1.5rem;
            }
            
            .profile-name {
                font-size: 1.5rem;
            }
            
            .rating-stats {
                flex-direction: column;
                gap: 1rem;
            }
            
            .rating-display {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Profile Card -->
                    <div class="profile-card">
                        <!-- Profile Header -->
        <div class="profile-header">
                            <div class="profile-avatar">
                                <?php if ($user_data['avatar']): ?>
                                    <img src="<?php echo htmlspecialchars($user_data['avatar']); ?>" alt="آواتار" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                                    <?php echo mb_substr($user_data['name'], 0, 1); ?>
                        <?php endif; ?>
                        </div>
                            <h1 class="profile-name"><?php echo htmlspecialchars($user_data['name']); ?></h1>
                            <p class="profile-role">
                                <?php
                                $role_names = [
                                    'customer' => 'مشتری',
                                    'business' => 'کسب‌وکار',
                                    'delivery' => 'پیک'
                                ];
                                echo $role_names[$user_data['role']] ?? $user_data['role'];
                                ?>
                            </p>
                    </div>

                        <!-- Rating Section -->
                        <div class="rating-section">
                            <?php if (!$rating_system_installed): ?>
                                <div class="alert alert-warning text-center">
                                    <h5>⚠️ سیستم امتیازدهی نصب نشده</h5>
                                    <p>برای استفاده از سیستم امتیازدهی و نظرات، ابتدا جداول دیتابیس را نصب کنید.</p>
                                    <a href="setup_rating_tables.php" class="btn btn-primary">نصب سیستم امتیازدهی</a>
                </div>
                            <?php else: ?>
                                <div class="rating-display">
                                    <div class="rating-stars">
                            <?php 
                                        $avg_rating = floatval($user_stats['average_rating']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            $class = $i <= $avg_rating ? 'star' : 'star empty';
                                            echo '<span class="' . $class . '">★</span>';
                                        }
                                        ?>
                                    </div>
                                    <div class="rating-number"><?php echo number_format($avg_rating, 1); ?></div>
                                </div>
                                <div class="rating-stats">
                                    <div class="stat-item">
                                        <div class="stat-number"><?php echo $user_stats['total_ratings']; ?></div>
                                        <div class="stat-label">امتیاز</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-number"><?php echo $user_stats['total_comments']; ?></div>
                                        <div class="stat-label">نظر</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Profile Info -->
                        <div class="profile-info">
                            <?php if ($is_own_profile): ?>
                            <div class="info-item">
                                <div class="info-icon">📱</div>
                                <div class="info-content">
                                    <div class="info-label">شماره موبایل</div>
                                    <div class="info-value"><?php echo htmlspecialchars($user_data['mobile']); ?></div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="info-item">
                                <div class="info-icon">📱</div>
                                <div class="info-content">
                                    <div class="info-label">شماره موبایل</div>
                                    <div class="info-value">
                                        <span class="text-muted">🔒 محرمانه</span>
                                        <small class="d-block text-muted mt-1">شماره موبایل فقط برای خود کاربر قابل مشاهده است</small>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($user_data['email']): ?>
                            <div class="info-item">
                                <div class="info-icon">📧</div>
                                <div class="info-content">
                                    <div class="info-label">ایمیل</div>
                                    <?php if ($is_own_profile): ?>
                                    <div class="info-value"><?php echo htmlspecialchars($user_data['email']); ?></div>
                            <?php else: ?>
                                    <div class="info-value">
                                        <span class="text-muted">🔒 محرمانه</span>
                                        <small class="d-block text-muted mt-1">ایمیل فقط برای خود کاربر قابل مشاهده است</small>
                                    </div>
                            <?php endif; ?>
                        </div>
                    </div>
                            <?php endif; ?>
                            
                            <?php if ($user_data['city']): ?>
                            <div class="info-item">
                                <div class="info-icon">📍</div>
                                <div class="info-content">
                                    <div class="info-label">شهر</div>
                                    <div class="info-value"><?php echo htmlspecialchars($user_data['city']); ?></div>
                </div>
            </div>
                            <?php endif; ?>
                            
                            <?php if (!$is_own_profile): ?>
                            <div class="info-item">
                                <div class="info-icon">💬</div>
                                <div class="info-content">
                                    <div class="info-label">تماس</div>
                                    <div class="info-value">
                                        <a href="chat.php?user_id=<?php echo $user_id; ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-chat-dots"></i> ارسال پیام
                                        </a>
                                        <small class="d-block text-muted mt-1">برای تماس از سیستم چت استفاده کنید</small>
                </div>
                </div>
            </div>
                            <?php endif; ?>
                            
                            <?php if ($user_data['bio']): ?>
                            <div class="info-item">
                                <div class="info-icon">📝</div>
                                <div class="info-content">
                                    <div class="info-label">درباره من</div>
                                    <div class="info-value"><?php echo nl2br(htmlspecialchars($user_data['bio'])); ?></div>
                </div>
                            </div>
                            <?php endif; ?>
            </div>

                        <!-- Portfolio Section -->
                        <div class="ratings-section">
                            <h3 class="section-title">
                                <i class="bi bi-images"></i>
                                نمونه‌کارها
                            </h3>

                            <?php if (empty($portfolio_items)): ?>
                                <div class="no-ratings">هنوز نمونه‌کاری اضافه نشده است</div>
                            <?php else: ?>
                                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                                    <?php foreach ($portfolio_items as $item): ?>
                                        <div class="col">
                                            <div class="card h-100">
                                                <div style="position:relative;overflow:hidden;height:180px;background:#f3f3f3">
                                                    <img src="<?php echo htmlspecialchars('uploads/' . $item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width:100%;height:100%;object-fit:cover">
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($item['title']); ?></h5>
                                                    <p class="card-text text-muted small mb-2"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted"><?php echo date('Y/m/d', strtotime($item['created_at'])); ?></small>
                                                        <?php if ($is_own_profile): ?>
                                                            <button class="btn btn-sm btn-outline-danger delete-portfolio" data-id="<?php echo $item['id']; ?>">حذف</button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($is_own_profile): ?>
                                <div class="rating-form">
                                    <h5>افزودن نمونه‌کار جدید</h5>
                                    <form id="portfolioForm" enctype="multipart/form-data" method="POST" action="api/portfolio.php">
                                        <div class="form-group">
                                            <label class="form-label">عنوان</label>
                                            <input type="text" name="title" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">توضیحات (اختیاری)</label>
                                            <textarea name="description" class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">عکس (jpg/png/gif)</label>
                                            <input type="file" name="image" id="portfolioImage" accept="image/*" class="form-control" required>
                                            <div id="imagePreview" style="margin-top:8px;display:none">
                                                <img src="" id="imagePreviewImg" style="max-width:160px;max-height:120px;border-radius:8px;object-fit:cover;border:1px solid #eee">
                                            </div>
                                            <div id="uploadProgress" style="display:none;margin-top:8px">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width:0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn-rating" id="submitPortfolio">افزودن</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Ratings Section -->
                        <div class="ratings-section">
                            <h3 class="section-title">
                                <i class="bi bi-star-fill"></i>
                                نظرات و امتیازات
                            </h3>

                            <?php if (!$rating_system_installed): ?>
                                <div class="alert alert-info text-center">
                                    <p>سیستم امتیازدهی نصب نشده است. برای استفاده از این قابلیت، ابتدا جداول دیتابیس را نصب کنید.</p>
            </div>
                            <?php else: ?>
                                <!-- Rating Form (only if not own profile and not already rated) -->
                                <?php if (!$is_own_profile && !$existing_rating): ?>
                            <div class="rating-form">
                                <h5>امتیاز و نظر خود را ثبت کنید</h5>
                                <form id="ratingForm">
                                    <div class="form-group">
                                        <label class="form-label">امتیاز</label>
                                        <div class="rating-input">
                                            <span class="star-input" data-rating="1">★</span>
                                            <span class="star-input" data-rating="2">★</span>
                                            <span class="star-input" data-rating="3">★</span>
                                            <span class="star-input" data-rating="4">★</span>
                                            <span class="star-input" data-rating="5">★</span>
        </div>
                                        <input type="hidden" id="rating" name="rating" value="0">
    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="comment">نظر (اختیاری)</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="نظر خود را بنویسید..."></textarea>
                                    </div>
                                    <button type="submit" class="btn-rating" id="submitRating">
                                        ثبت امتیاز
                                    </button>
                                </form>
                            </div>
                            <?php elseif (!$is_own_profile && $existing_rating): ?>
                            <div class="alert alert-info">
                                شما قبلاً به این کاربر امتیاز داده‌اید: 
                                <strong><?php echo $existing_rating['rating']; ?> ستاره</strong>
                                <?php if ($existing_rating['comment']): ?>
                                    - <?php echo htmlspecialchars($existing_rating['comment']); ?>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <!-- Recent Ratings -->
                            <div id="ratingsList">
                                <?php if (empty($recent_ratings)): ?>
                                    <div class="no-ratings">
                                        هنوز نظری ثبت نشده است
                </div>
                                <?php else: ?>
                                    <?php foreach ($recent_ratings as $rating): ?>
                                    <div class="rating-item">
                                        <div class="rating-header">
                                            <div class="rater-info">
                                                <div class="rater-avatar">
                                                    <?php if ($rating['rater_avatar']): ?>
                                                        <img src="<?php echo htmlspecialchars($rating['rater_avatar']); ?>" alt="آواتار" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                                    <?php else: ?>
                                                        <?php echo mb_substr($rating['rater_name'], 0, 1); ?>
                                                    <?php endif; ?>
                            </div>
                                                <div>
                                                    <div class="rater-name"><?php echo htmlspecialchars($rating['rater_name']); ?></div>
                                                    <div class="rating-stars">
                                                        <?php
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            $class = $i <= $rating['rating'] ? 'star' : 'star empty';
                                                            echo '<span class="' . $class . '">★</span>';
                                                        }
                                                        ?>
                            </div>
                        </div>
                            </div>
                                            <div class="rating-date">
                                                <?php echo date('Y/m/d H:i', strtotime($rating['created_at'])); ?>
                            </div>
                        </div>
                                        <?php if ($rating['comment']): ?>
                                        <div class="rating-comment">
                                            <?php echo nl2br(htmlspecialchars($rating['comment'])); ?>
                        </div>
                                        <?php endif; ?>
                </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                                <!-- Load More Button -->
                                <?php if (count($recent_ratings) >= 5): ?>
                                <div class="text-center mt-3">
                                    <button class="btn btn-outline-primary" id="loadMoreRatings">
                                        مشاهده نظرات بیشتر
                    </button>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <p>در حال بارگذاری...</p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Star rating functionality (only if rating system is installed)
        <?php if ($rating_system_installed): ?>
        document.querySelectorAll('.star-input').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                document.getElementById('rating').value = rating;
                
                // Update star display
                document.querySelectorAll('.star-input').forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
            
            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                document.querySelectorAll('.star-input').forEach((s, index) => {
                    if (index < rating) {
                        s.style.color = '#FFD700';
                    } else {
                        s.style.color = '#E5E7EB';
                    }
                });
            });
        });

        // Reset stars on mouse leave
        const ratingInput = document.querySelector('.rating-input');
        if (ratingInput) {
            ratingInput.addEventListener('mouseleave', function() {
            const currentRating = document.getElementById('rating').value;
            document.querySelectorAll('.star-input').forEach((s, index) => {
                if (index < currentRating) {
                    s.style.color = '#FFD700';
                } else {
                    s.style.color = '#E5E7EB';
                }
            });
        });
        } // Close the if statement for ratingInput

        // Rating form submission
        const ratingForm = document.getElementById('ratingForm');
        if (ratingForm) {
            ratingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const rating = document.getElementById('rating').value;
            const comment = document.getElementById('comment').value;
            
            if (rating == 0) {
                alert('لطفاً امتیاز خود را انتخاب کنید');
                return;
            }
            
            const submitBtn = document.getElementById('submitRating');
            submitBtn.disabled = true;
            submitBtn.textContent = 'در حال ثبت...';
            
            fetch('api/ratings.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: <?php echo $user_id; ?>,
                    rating: parseInt(rating),
                    comment: comment
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.text();
            })
            .then(text => {
                console.log('Response text:', text);
                try {
                    const data = JSON.parse(text);
                if (data.success) {
                        location.reload();
                } else {
                        alert('خطا: ' + data.message);
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'ثبت امتیاز';
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    console.error('Response text:', text);
                    alert('خطا در پردازش پاسخ سرور: ' + text);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'ثبت امتیاز';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('خطا در ارسال درخواست: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = 'ثبت امتیاز';
            });
        });
        } // Close the if statement for ratingForm

        // Load more ratings
        let currentPage = 1;
        const loadMoreBtn = document.getElementById('loadMoreRatings');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                currentPage++;
                const btn = this;
                btn.disabled = true;
                btn.textContent = 'در حال بارگذاری...';
            
            fetch(`api/ratings.php?user_id=<?php echo $user_id; ?>&page=${currentPage}`)
            .then(response => {
                console.log('Load more response status:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.text();
            })
            .then(text => {
                console.log('Load more response text:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        const ratingsList = document.getElementById('ratingsList');
                        data.data.ratings.forEach(rating => {
                            const ratingHtml = createRatingHtml(rating);
                            ratingsList.insertAdjacentHTML('beforeend', ratingHtml);
                        });
                        
                        if (data.data.pagination.current_page >= data.data.pagination.total_pages) {
                            btn.style.display = 'none';
                        } else {
                            btn.disabled = false;
                            btn.textContent = 'مشاهده نظرات بیشتر';
                        }
                    } else {
                        alert('خطا در بارگذاری نظرات: ' + data.message);
                        btn.disabled = false;
                        btn.textContent = 'مشاهده نظرات بیشتر';
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    console.error('Response text:', text);
                    alert('خطا در پردازش پاسخ سرور: ' + text);
                    btn.disabled = false;
                    btn.textContent = 'مشاهده نظرات بیشتر';
                    }
                })
                .catch(error => {
                console.error('Load more error:', error);
                alert('خطا در بارگذاری نظرات: ' + error.message);
                btn.disabled = false;
                btn.textContent = 'مشاهده نظرات بیشتر';
            });
        });
        } // Close the if statement for loadMoreBtn

        function createRatingHtml(rating) {
            const stars = Array.from({length: 5}, (_, i) => 
                `<span class="star ${i < rating.rating ? '' : 'empty'}">★</span>`
            ).join('');
            
            const avatar = rating.rater_avatar ? 
                `<img src="${rating.rater_avatar}" alt="آواتار" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">` :
                rating.rater_name.charAt(0);
            
            return `
                <div class="rating-item">
                    <div class="rating-header">
                        <div class="rater-info">
                            <div class="rater-avatar">${avatar}</div>
                            <div>
                                <div class="rater-name">${rating.rater_name}</div>
                                <div class="rating-stars">${stars}</div>
                    </div>
                        </div>
                        <div class="rating-date">${new Date(rating.created_at).toLocaleString('fa-IR')}</div>
                    </div>
                    ${rating.comment ? `<div class="rating-comment">${rating.comment.replace(/\n/g, '<br>')}</div>` : ''}
                </div>
            `;
        }
        <?php endif; ?>
    </script>
    <script>
    // Portfolio: preview image and handle upload with progress
    const portfolioForm = document.getElementById('portfolioForm');
    const imageInput = document.getElementById('portfolioImage');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewImg = document.getElementById('imagePreviewImg');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = uploadProgress?.querySelector('.progress-bar');

    imageInput?.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) {
            imagePreview.style.display = 'none';
            return;
        }
        const url = URL.createObjectURL(file);
        imagePreviewImg.src = url;
        imagePreview.style.display = 'block';
    });

    portfolioForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.currentTarget;
        const btn = document.getElementById('submitPortfolio');
        btn.disabled = true;
        btn.textContent = 'در حال افزودن...';

        const formData = new FormData(form);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);

        xhr.upload.addEventListener('progress', function(ev) {
            if (ev.lengthComputable) {
                const percent = Math.round((ev.loaded / ev.total) * 100);
                uploadProgress.style.display = 'block';
                progressBar.style.width = percent + '%';
                progressBar.textContent = percent + '%';
            }
        });

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (xhr.status >= 200 && xhr.status < 300 && data.success) {
                        location.reload();
                    } else {
                        alert('خطا: ' + (data.message || xhr.responseText));
                        btn.disabled = false;
                        btn.textContent = 'افزودن';
                        uploadProgress.style.display = 'none';
                        progressBar.style.width = '0%';
                        progressBar.textContent = '';
                    }
                } catch (e) {
                    alert('پاسخ نامعتبر از سرور');
                    btn.disabled = false;
                    btn.textContent = 'افزودن';
                    uploadProgress.style.display = 'none';
                    progressBar.style.width = '0%';
                    progressBar.textContent = '';
                }
            }
        };

        xhr.onerror = function() {
            alert('خطا در ارسال');
            btn.disabled = false;
            btn.textContent = 'افزودن';
            uploadProgress.style.display = 'none';
            progressBar.style.width = '0%';
            progressBar.textContent = '';
        };

        xhr.send(formData);
    });

    // Delete portfolio
    document.querySelectorAll('.delete-portfolio').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('آیا از حذف نمونه‌کار مطمئن هستید؟')) return;
            const id = this.dataset.id;
            fetch('api/portfolio.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(r => r.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.success) location.reload();
                    else alert('خطا: ' + data.message);
                } catch (e) { alert('پاسخ نامعتبر'); }
            })
            .catch(err => alert('خطا: ' + err.message));
        });
    });

    // Start Chat button
    document.querySelectorAll('.btn-start-chat').forEach(btn => {
        btn.addEventListener('click', function() {
            const user1_id = this.dataset.user1;
            const user2_id = this.dataset.user2;
            
            fetch('api/start_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user1_id: user1_id,
                    user2_id: user2_id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to the chat page
                    window.location.href = 'chat.php?user_id=' + user2_id;
                } else {
                    alert('خطا: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('خطا در برقراری ارتباط با سرور');
            });
        });
    });
    </script>
</body>
</html>
