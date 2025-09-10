<?php
include 'includes/config.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) session_start();

// Initialize filters and pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$items_per_page = 12;
$offset = ($page - 1) * $items_per_page;

// Prepare base query with proper indexing and joins
$base_query = "
    SELECT a.*, u.name as user_name, u.avatar as user_avatar, u.city as user_city,
           COUNT(DISTINCT p.id) as proposal_count
    FROM ads a 
    JOIN users u ON a.user_id = u.id 
    LEFT JOIN proposals p ON a.id = p.ad_id
    WHERE a.status = 'active' 
    AND a.deadline >= CURDATE()
    AND a.event_date >= CURDATE()
";

// Apply filters if set
$filters = [];
$params = [];
$types = '';

if (!empty($_GET['category'])) {
    $filters[] = "a.category = ?";
    $params[] = $_GET['category'];
    $types .= 's';
}

if (!empty($_GET['city'])) {
    $filters[] = "u.city = ?";
    $params[] = $_GET['city'];
    $types .= 's';
}

if (!empty($_GET['budget_min'])) {
    $filters[] = "a.budget_min >= ?";
    $params[] = intval($_GET['budget_min']);
    $types .= 'i';
}

if (!empty($_GET['budget_max'])) {
    $filters[] = "a.budget_max <= ?";
    $params[] = intval($_GET['budget_max']);
    $types .= 'i';
}

if (!empty($_GET['search'])) {
    $search_term = '%' . $_GET['search'] . '%';
    $filters[] = "(a.title LIKE ? OR a.description LIKE ?)";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= 'ss';
}

// Combine filters
if (!empty($filters)) {
    $base_query .= " AND " . implode(" AND ", $filters);
}

// Add grouping and sorting
$base_query .= " GROUP BY a.id";

// Define sort options
$sort_options = [
    'newest' => 'ORDER BY a.created_at DESC',
    'budget-high' => 'ORDER BY a.budget_max DESC',
    'budget-low' => 'ORDER BY a.budget_min ASC',
    'deadline' => 'ORDER BY a.deadline ASC',
    'proposals' => 'ORDER BY proposal_count ASC'
];

$sort = isset($_GET['sort']) && array_key_exists($_GET['sort'], $sort_options) 
    ? $_GET['sort'] 
    : 'newest';

$base_query .= " " . $sort_options[$sort];

// Get total count for pagination
$count_query = "SELECT COUNT(DISTINCT a.id) as total FROM ads a JOIN users u ON a.user_id = u.id WHERE a.status = 'active' AND a.deadline >= CURDATE()";
if (!empty($filters)) {
    $count_query .= " AND " . implode(" AND ", $filters);
}

$stmt = $conn->prepare($count_query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Get paginated results
$query = $base_query . " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);

// Add pagination parameters
$params[] = $items_per_page;
$params[] = $offset;
$types .= 'ii';

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$ads = [];
while ($row = $result->fetch_assoc()) {
    $ads[] = $row;
}

// Define categories
$categories = [
    'cake' => 'کیک و شیرینی',
    'decoration' => 'تزیینات',
    'photography' => 'عکاسی',
    'music' => 'موسیقی',
    'ceremony' => 'مراسم',
    'catering' => 'پذیرایی',
    'other' => 'سایر'
];

// Define cities
$cities = ['تهران', 'مشهد', 'اصفهان', 'شیراز', 'تبریز', 'کرج', 'اهواز', 'قم'];
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>همه نیازمندی‌ها - شادینو</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #FF69B4;
            --secondary-color: #FFB6C1;
            --accent-color: #FF1493;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --error-color: #EF4444;
            --gradient-primary: linear-gradient(135deg, #FF69B4, #FFB6C1);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        * {
            font-family: 'Vazirmatn', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #FFE4E1 0%, #FFF0F5 50%, #F8F9FA 100%);
            min-height: 100vh;
        }
        
        /* Search header section */
        .search-header {
            background: var(--gradient-primary);
            padding: 2rem 0;
            margin-bottom: 2rem;
            color: white;
        }
        
        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--card-shadow);
        }
        
        .ad-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        
        .ad-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
        }
        
        .category-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-right: 0.5rem;
            color: white;
            background: var(--gradient-primary);
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
            color: white;
        }
        
        .status-active {
            background: var(--success-color);
        }
        
        .status-urgent {
            background: var(--warning-color);
            animation: pulse 2s infinite;
        }
        
        .budget-badge {
            background: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-weight: 500;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 105, 180, 0.25);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.3);
        }
        
        .pagination {
            margin-top: 2rem;
        }
        
        .page-link {
            color: var(--primary-color);
            border-radius: 8px;
            margin: 0 0.25rem;
        }
        
        .page-link:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .page-item.active .page-link {
            background: var(--gradient-primary);
            border-color: var(--primary-color);
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        @media (max-width: 768px) {
            .filter-card {
                margin-bottom: 1rem;
            }
            
            .ad-card {
                margin-bottom: 1rem;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .col-md-3 {
                margin-bottom: 2rem;
            }
            
            .col-md-9 {
                order: -1;
            }
            
            .row {
                flex-direction: column;
            }
            
            .g-3 > * {
                margin-bottom: 1rem;
            }
            
            .g-2 > * {
                margin-bottom: 0.5rem;
            }
            
            .mb-4 {
                margin-bottom: 1rem;
            }
            
            .mb-3 {
                margin-bottom: 0.75rem;
            }
            
            .mb-2 {
                margin-bottom: 0.5rem;
            }
            
            .py-5 {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }
            
            .mt-3 {
                margin-top: 1rem;
            }
            
            .gap-2 {
                gap: 0.5rem;
            }
            
            .gap-3 {
                gap: 0.75rem;
            }
            
            .text-end {
                text-align: center;
                margin-top: 1rem;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }
            
            .d-flex.align-items-center.gap-3 {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem;
            }
            
            .ad-card {
                padding: 1rem;
            }
            
            .filter-card {
                padding: 1rem;
            }
            
            .form-control, .form-select {
                font-size: 16px;
            }
            
            .btn {
                padding: 0.75rem 1rem;
                font-size: 1rem;
            }
            
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem;
            }
            
            .page-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .col-md-6, .col-md-4, .col-md-2 {
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .col-6 {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .ad-card {
                padding: 0.75rem;
            }
            
            .filter-card {
                padding: 0.75rem;
            }
            
            .category-badge, .status-badge, .budget-badge {
                display: block;
                margin-bottom: 0.5rem;
                text-align: center;
            }
            
            .d-flex.flex-wrap.gap-2 {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .text-muted.small {
                display: block;
                margin-bottom: 0.25rem;
                text-align: center;
            }
            
            .pagination {
                gap: 0.25rem;
            }
            
            .page-link {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }
        }

        /* Text wrapping and overflow fixes */
        .text-wrap {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }
        
        .ad-card {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .ad-card h5 {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .ad-card p {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .category-badge, .status-badge, .budget-badge {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }
        
        .form-label {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .form-check-label {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Ensure all text containers handle overflow properly */
        h1, h2, h3, h4, h5, h6, p, span, div, label, button {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Fix for long words and URLs */
        * {
            word-break: break-word;
            overflow-wrap: break-word;
        }
        
        /* Specific fixes for flexbox items */
        .d-flex > * {
            min-width: 0;
        }
        
        .row > * {
            min-width: 0;
        }
        
        /* Button text wrapping */
        .btn {
            white-space: normal;
            text-align: center;
        }
        
        /* Card content overflow */
        .ad-card, .filter-card {
            overflow: hidden;
        }
        
        .ad-card > *, .filter-card > * {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>
</head>
<body>
<?php include 'header.php';?>
    

    <div class="search-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h1 class="mb-4">جستجو در نیازمندی‌ها</h1>
                    <form action="" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="جستجو در نیازمندی‌ها..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <select name="category" class="form-select">
                                    <option value="">همه دسته‌بندی‌ها</option>
                                    <?php foreach ($categories as $key => $value): ?>
                                        <option value="<?php echo $key; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] === $key) ? 'selected' : ''; ?>>
                                            <?php echo $value; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">جستجو</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-white-50">
                        <?php echo $total_items; ?> نیازمندی فعال یافت شد
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-md-3">
                <form action="" method="GET" id="filterForm">
                    <!-- Preserve search params -->
                    <?php if (!empty($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                    <?php endif; ?>
                    
                    <div class="filter-card">
                        <h5 class="mb-3">فیلترها</h5>
                        
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <label class="form-label">دسته‌بندی</label>
                            <?php foreach ($categories as $key => $value): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]" 
                                           value="<?php echo $key; ?>" id="cat-<?php echo $key; ?>"
                                           <?php echo (isset($_GET['categories']) && in_array($key, $_GET['categories'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="cat-<?php echo $key; ?>">
                                        <?php echo $value; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- City Filter -->
                        <div class="mb-4">
                            <label class="form-label">شهر</label>
                            <select name="city" class="form-select">
                                <option value="">همه شهرها</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo $city; ?>" <?php echo (isset($_GET['city']) && $_GET['city'] === $city) ? 'selected' : ''; ?>>
                                        <?php echo $city; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Budget Filter -->
                        <div class="mb-4">
                            <label class="form-label">محدوده قیمت (تومان)</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="budget_min" class="form-control" placeholder="از"
                                           value="<?php echo htmlspecialchars($_GET['budget_min'] ?? ''); ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="budget_max" class="form-control" placeholder="تا"
                                           value="<?php echo htmlspecialchars($_GET['budget_max'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort -->
                        <div class="mb-4">
                            <label class="form-label">مرتب‌سازی</label>
                            <select name="sort" class="form-select">
                                <option value="newest" <?php echo ($sort === 'newest') ? 'selected' : ''; ?>>جدیدترین</option>
                                <option value="budget-high" <?php echo ($sort === 'budget-high') ? 'selected' : ''; ?>>بیشترین بودجه</option>
                                <option value="budget-low" <?php echo ($sort === 'budget-low') ? 'selected' : ''; ?>>کمترین بودجه</option>
                                <option value="deadline" <?php echo ($sort === 'deadline') ? 'selected' : ''; ?>>نزدیک‌ترین مهلت</option>
                                <option value="proposals" <?php echo ($sort === 'proposals') ? 'selected' : ''; ?>>کمترین پیشنهاد</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">اعمال فیلترها</button>
                    </div>
                </form>
            </div>
            
            <!-- Ads List -->
            <div class="col-md-9">
                <?php if (empty($ads)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-search" style="font-size: 3rem; color: var(--primary-color);"></i>
                        <h3 class="mt-3">نیازمندی‌ای یافت نشد</h3>
                        <p class="text-muted">لطفاً فیلترهای جستجو را تغییر دهید.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($ads as $ad): ?>
                        <div class="ad-card" onclick="window.location.href='openedads.php?id=<?php echo $ad['id']; ?>'">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-2"><?php echo htmlspecialchars($ad['title']); ?></h5>
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <span class="category-badge">
                                            <?php echo $categories[$ad['category']] ?? 'سایر'; ?>
                                        </span>
                                        <span class="status-badge status-active">فعال</span>
                                        <?php if (strtotime($ad['deadline']) - time() < 3 * 24 * 60 * 60): ?>
                                            <span class="status-badge status-urgent">فوری</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="budget-badge mb-2">
                                        <?php
                                        if (!empty($ad['budget_min']) && !empty($ad['budget_max'])) {
                                            echo number_format($ad['budget_min']) . ' - ' . number_format($ad['budget_max']);
                                        } elseif (!empty($ad['budget_min'])) {
                                            echo 'از ' . number_format($ad['budget_min']);
                                        } elseif (!empty($ad['budget_max'])) {
                                            echo 'تا ' . number_format($ad['budget_max']);
                                        }
                                        ?> تومان
                                    </div>
                                    <div class="text-muted small">
                                        <?php 
                                        $created = new DateTime($ad['created_at']);
                                        $now = new DateTime();
                                        $diff = $created->diff($now);
                                        if ($diff->days == 0) {
                                            echo $diff->h > 0 ? $diff->h . ' ساعت پیش' : 'لحظاتی پیش';
                                        } elseif ($diff->days == 1) {
                                            echo 'دیروز';
                                        } else {
                                            echo $diff->days . ' روز پیش';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-muted mb-3">
                                <?php 
                                $desc = htmlspecialchars($ad['description']);
                                echo (strlen($desc) > 200) ? substr($desc, 0, 200) . '...' : $desc;
                                ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-muted small">
                                        <i class="bi bi-person"></i>
                                        <?php echo htmlspecialchars($ad['user_name']); ?>
                                    </span>
                                    <span class="text-muted small">
                                        <i class="bi bi-geo-alt"></i>
                                        <?php echo htmlspecialchars($ad['user_city']); ?>
                                    </span>
                                    <span class="text-muted small">
                                        <i class="bi bi-clock"></i>
                                        مهلت: 
                                        <?php
                                        $deadline = new DateTime($ad['deadline']);
                                        $diff = $now->diff($deadline);
                                        echo $diff->days . ' روز';
                                        ?>
                                    </span>
                                </div>
                                <span class="text-primary">
                                    <i class="bi bi-chat-dots"></i>
                                    <?php echo $ad['proposal_count']; ?> پیشنهاد
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?php
                                            $_GET['page'] = $page - 1;
                                            echo http_build_query($_GET);
                                        ?>">قبلی</a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                    <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?<?php
                                            $_GET['page'] = $i;
                                            echo http_build_query($_GET);
                                        ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?php
                                            $_GET['page'] = $page + 1;
                                            echo http_build_query($_GET);
                                        ?>">بعدی</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Clear filters
        function clearFilters() {
            const form = document.getElementById('filterForm');
            const inputs = form.querySelectorAll('input:not([type="hidden"]), select');
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            form.submit();
        }
        
        // Handle mobile filters toggle
        function toggleFilters() {
            const filters = document.querySelector('.filters-sidebar');
            filters.classList.toggle('show');
        }
    </script>
</body>
</html>
