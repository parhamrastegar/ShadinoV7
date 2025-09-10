<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_ads();
        break;
    case 'POST':
        handle_create_ad();
        break;
    case 'PUT':
        handle_update_ad();
        break;
    case 'DELETE':
        handle_delete_ad();
        break;
    default:
        send_error('متد درخواست نامعتبر است', 405);
}

function handle_get_ads() {
    global $conn;
    
    // Get single ad by ID
    if (isset($_GET['id'])) {
        $ad_id = intval($_GET['id']);
        
        // Get ad details with user info
        $stmt = mysqli_prepare($conn, "
            SELECT a.*, u.name as user_name, u.mobile as user_mobile, u.avatar as user_avatar,
                   (SELECT COUNT(*) FROM proposals WHERE ad_id = a.id) as proposal_count
            FROM ads a 
            JOIN users u ON a.user_id = u.id 
            WHERE a.id = ? AND a.status = 'active'
        ");
        mysqli_stmt_bind_param($stmt, "i", $ad_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $ad = mysqli_fetch_assoc($result);
        
        if (!$ad) {
            send_error('آگهی یافت نشد', 404);
        }
        
        // Increment view count
        $stmt = mysqli_prepare($conn, "UPDATE ads SET views = views + 1 WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $ad_id);
        mysqli_stmt_execute($stmt);
        
        // Parse images JSON
        $ad['images'] = json_decode($ad['images'], true) ?: [];
        
        send_success($ad);
    }
    
    // Get ads list with filters
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = isset($_GET['per_page']) ? min(50, max(1, intval($_GET['per_page']))) : 10;
    
    $where_conditions = ["a.status = 'active'"];
    $params = [];
    $param_types = "";
    
    // Filter by category
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $where_conditions[] = "a.category = ?";
        $params[] = sanitize_input($_GET['category']);
        $param_types .= "s";
    }
    
    // Filter by city
    if (isset($_GET['city']) && !empty($_GET['city'])) {
        $where_conditions[] = "a.city = ?";
        $params[] = sanitize_input($_GET['city']);
        $param_types .= "s";
    }
    
    // Filter by budget range
    if (isset($_GET['budget_min']) && is_numeric($_GET['budget_min'])) {
        $where_conditions[] = "a.budget_max >= ?";
        $params[] = intval($_GET['budget_min']);
        $param_types .= "i";
    }
    
    if (isset($_GET['budget_max']) && is_numeric($_GET['budget_max'])) {
        $where_conditions[] = "a.budget_min <= ?";
        $params[] = intval($_GET['budget_max']);
        $param_types .= "i";
    }
    
    // Search in title and description
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = '%' . sanitize_input($_GET['search']) . '%';
        $where_conditions[] = "(a.title LIKE ? OR a.description LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $param_types .= "ss";
    }
    
    $where_clause = implode(' AND ', $where_conditions);
    
    $query = "
        SELECT a.*, u.name as user_name, u.avatar as user_avatar,
               (SELECT COUNT(*) FROM proposals WHERE ad_id = a.id) as proposal_count
        FROM ads a 
        JOIN users u ON a.user_id = u.id 
        WHERE $where_clause 
        ORDER BY a.created_at DESC
    ";
    
    // Prepare and execute query for pagination
    $count_query = "
        SELECT COUNT(*) as total 
        FROM ads a 
        JOIN users u ON a.user_id = u.id 
        WHERE $where_clause
    ";
    
    if (!empty($params)) {
        $stmt = mysqli_prepare($conn, $count_query);
        mysqli_stmt_bind_param($stmt, $param_types, ...$params);
        mysqli_stmt_execute($stmt);
        $count_result = mysqli_stmt_get_result($stmt);
        $total = mysqli_fetch_assoc($count_result)['total'];
    } else {
        $count_result = mysqli_query($conn, $count_query);
        $total = mysqli_fetch_assoc($count_result)['total'];
    }
    
    $offset = ($page - 1) * $per_page;
    $paginated_query = $query . " LIMIT $per_page OFFSET $offset";
    
    if (!empty($params)) {
        $stmt = mysqli_prepare($conn, $paginated_query);
        mysqli_stmt_bind_param($stmt, $param_types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_query($conn, $paginated_query);
    }
    
    $ads = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['images'] = json_decode($row['images'], true) ?: [];
        $ads[] = $row;
    }
    
    send_success([
        'ads' => $ads,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total' => $total,
            'total_pages' => ceil($total / $per_page)
        ]
    ]);
}

function handle_create_ad() {
    global $conn;
    $user_data = require_auth();
    
    if ($user_data['role'] !== 'customer') {
        send_error('فقط مشتریان می‌توانند آگهی ثبت کنند', 403);
    }
    
    // Get form data (multipart/form-data)
    $required_fields = ['title', 'description', 'category', 'budget_min', 'budget_max', 'city', 'event_date', 'deadline'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            send_error("فیلد $field الزامی است");
        }
    }
    
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $category = sanitize_input($_POST['category']);
    $budget_min = intval($_POST['budget_min']);
    $budget_max = intval($_POST['budget_max']);
    $city = sanitize_input($_POST['city']);
    $event_date = sanitize_input($_POST['event_date']);
    $deadline = sanitize_input($_POST['deadline']);
    
    // Validate budget
    if ($budget_min <= 0 || $budget_max <= 0 || $budget_min > $budget_max) {
        send_error('بودجه نامعتبر است');
    }
    
    // Validate dates
    if (strtotime($event_date) <= time()) {
        send_error('تاریخ رویداد باید در آینده باشد');
    }
    
    if (strtotime($deadline) >= strtotime($event_date)) {
        send_error('مهلت پیشنهاد باید قبل از تاریخ رویداد باشد');
    }
    
    // Handle image uploads
    $uploaded_images = [];
    if (isset($_FILES['images'])) {
        $files = $_FILES['images'];
        
        // Handle multiple files
        if (is_array($files['name'])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i]
                    ];
                    
                    $upload_result = upload_file($file);
                    if ($upload_result['success']) {
                        $uploaded_images[] = $upload_result['filename'];
                    }
                }
            }
        } else {
            // Single file
            if ($files['error'] === UPLOAD_ERR_OK) {
                $upload_result = upload_file($files);
                if ($upload_result['success']) {
                    $uploaded_images[] = $upload_result['filename'];
                }
            }
        }
    }
    
    $images_json = json_encode($uploaded_images);
    
    // Insert ad
    $stmt = mysqli_prepare($conn, "
        INSERT INTO ads (user_id, title, description, category, budget_min, budget_max, city, event_date, deadline, images) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "isssiiisss", 
        $user_data['id'], $title, $description, $category, 
        $budget_min, $budget_max, $city, $event_date, $deadline, $images_json
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $ad_id = mysqli_insert_id($conn);
        send_success(['ad_id' => $ad_id], 'آگهی با موفقیت ثبت شد');
    } else {
        send_error('خطا در ثبت آگهی');
    }
}

function handle_update_ad() {
    global $conn;
    $user_data = require_auth();
    
    // Get ad ID from URL
    $ad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if (!$ad_id) {
        send_error('شناسه آگهی نامعتبر است');
    }
    
    // Check if user owns this ad
    $stmt = mysqli_prepare($conn, "SELECT * FROM ads WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $ad_id, $user_data['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ad = mysqli_fetch_assoc($result);
    
    if (!$ad) {
        send_error('آگهی یافت نشد یا شما مجاز به ویرایش آن نیستید', 403);
    }
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Update fields
    $update_fields = [];
    $params = [];
    $param_types = "";
    
    if (isset($input['title'])) {
        $update_fields[] = "title = ?";
        $params[] = sanitize_input($input['title']);
        $param_types .= "s";
    }
    
    if (isset($input['description'])) {
        $update_fields[] = "description = ?";
        $params[] = sanitize_input($input['description']);
        $param_types .= "s";
    }
    
    if (isset($input['budget_min'])) {
        $update_fields[] = "budget_min = ?";
        $params[] = intval($input['budget_min']);
        $param_types .= "i";
    }
    
    if (isset($input['budget_max'])) {
        $update_fields[] = "budget_max = ?";
        $params[] = intval($input['budget_max']);
        $param_types .= "i";
    }
    
    if (isset($input['status'])) {
        $update_fields[] = "status = ?";
        $params[] = sanitize_input($input['status']);
        $param_types .= "s";
    }
    
    if (empty($update_fields)) {
        send_error('هیچ فیلدی برای به‌روزرسانی ارسال نشده است');
    }
    
    $params[] = $ad_id;
    $param_types .= "i";
    
    $query = "UPDATE ads SET " . implode(', ', $update_fields) . " WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    
    if (mysqli_stmt_execute($stmt)) {
        send_success([], 'آگهی با موفقیت به‌روزرسانی شد');
    } else {
        send_error('خطا در به‌روزرسانی آگهی');
    }
}

function handle_delete_ad() {
    global $conn;
    $user_data = require_auth();
    
    // Get ad ID from URL
    $ad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if (!$ad_id) {
        send_error('شناسه آگهی نامعتبر است');
    }
    
    // Check if user owns this ad
    $stmt = mysqli_prepare($conn, "SELECT * FROM ads WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $ad_id, $user_data['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ad = mysqli_fetch_assoc($result);
    
    if (!$ad) {
        send_error('آگهی یافت نشد یا شما مجاز به حذف آن نیستید', 403);
    }
    
    // Delete ad (cascade will handle related records)
    $stmt = mysqli_prepare($conn, "DELETE FROM ads WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $ad_id);
    
    if (mysqli_stmt_execute($stmt)) {
        send_success([], 'آگهی با موفقیت حذف شد');
    } else {
        send_error('خطا در حذف آگهی');
    }
}
?>