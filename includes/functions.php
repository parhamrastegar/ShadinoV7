<?php
require_once 'config.php';

// Make sure database connection is available
if (!isset($conn) || !$conn) {
    die('Database connection failed');
}


// Basic input functions
function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

function validate_mobile($mobile) {
    return preg_match('/^09[0-9]{9}$/', $mobile);
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Password functions
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// File upload functions
function upload_file($file, $allowed_types = ['jpg', 'jpeg', 'png', 'gif']) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'message' => 'فایل انتخاب نشده است'];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'حجم فایل بیش از حد مجاز است'];
    }

    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Basic extension check
    if (!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'نوع فایل مجاز نیست'];
    }

    // Define common mime map for non-image files
    $non_image_mimes = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    // Allowed image mimes
    $image_mimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif'
    ];

    // If extension is an image, validate with getimagesize
    if (array_key_exists($file_extension, $image_mimes)) {
        $img_info = @getimagesize($file['tmp_name']);
        if ($img_info === false) {
            return ['success' => false, 'message' => 'فایل تصویر نیست'];
        }
        $mime = $img_info['mime'] ?? '';
        if ($mime !== $image_mimes[$file_extension] && !($file_extension === 'jpg' && $mime === 'image/jpeg')) {
            return ['success' => false, 'message' => 'پسوند و نوع فایل همخوانی ندارند'];
        }
    } else {
        // Non-image: use finfo to verify mime if possible
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if (isset($non_image_mimes[$file_extension]) && $mime !== $non_image_mimes[$file_extension]) {
                // Allow some discrepancies for docx which can be detected differently on some systems
                if (!($file_extension === 'docx' && strpos($mime, 'offic') !== false)) {
                    return ['success' => false, 'message' => 'نوع فایل پشتیبانی شده نیست'];
                }
            }
        }
    }

        // create uploads dir if missing
        if (!is_dir(UPLOAD_PATH)) {
            if (!mkdir(UPLOAD_PATH, 0755, true) && !is_dir(UPLOAD_PATH)) {
                return ['success' => false, 'message' => 'خطا در ساخت پوشه آپلود'];
            }
        }

        // generate secure filename
        try {
            $rand = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            $rand = uniqid('', true);
        }
        $filename = $rand . '.' . $file_extension;
        $upload_path = UPLOAD_PATH . $filename;

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            @chmod($upload_path, 0644);
            return ['success' => true, 'filename' => $filename];
        }

        return ['success' => false, 'message' => 'خطا در آپلود فایل'];
}

// Response functions
function send_response($data, $status_code = 200) {
    // If any output was produced before this point (notably warnings, notices or accidental HTML/BOM),
    // capture and log it, then discard so the client receives only valid JSON.
    if (ob_get_level() > 0) {
        $extra = ob_get_contents();
        if (!empty($extra)) {
            error_log("Unexpected output before JSON response: " . $extra);
        }
        // Clean any buffered output
        @ob_end_clean();
    }

    http_response_code($status_code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

function send_error($message, $status_code = 400) {
    send_response(['success' => false, 'message' => $message], $status_code);
}

function send_success($data = [], $message = 'عملیات با موفقیت انجام شد') {
    send_response(['success' => true, 'message' => $message, 'data' => $data]);
}



// Simple session-based authentication
function require_auth() {
    if (!isset($_SESSION['user_id'])) {
        send_error('لطفاً ابتدا وارد شوید', 401);
    }
    
    // Get full user data
    $user_data = get_user_by_id($_SESSION['user_id']);
    if (!$user_data) {
        session_destroy();
        send_error('کاربر یافت نشد', 401);
    }
    
    return $user_data;
}

// Database helper functions
function get_user_by_mobile($mobile) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE mobile = ?");
    mysqli_stmt_bind_param($stmt, "s", $mobile);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function get_user_by_id($user_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Utility to bind params to a mysqli_stmt when param count is dynamic.
function stmt_bind_params($stmt, $types, $params) {
    // mysqli_stmt_bind_param requires references
    $bind_names[] = $types;
    for ($i=0; $i<count($params); $i++) {
        $bind_name = 'bind' . $i;
        $$bind_name = $params[$i];
        $bind_names[] = &$$bind_name;
    }
    return call_user_func_array(array($stmt, 'bind_param'), $bind_names);
}

// Pagination helper
function paginate($query, $page = 1, $per_page = 10) {
    global $conn;
    
    $offset = ($page - 1) * $per_page;
    $count_query = preg_replace('/SELECT .+ FROM/', 'SELECT COUNT(*) as total FROM', $query);
    
    $count_result = mysqli_query($conn, $count_query);
    $total = mysqli_fetch_assoc($count_result)['total'];
    
    $paginated_query = $query . " LIMIT $per_page OFFSET $offset";
    $result = mysqli_query($conn, $paginated_query);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    return [
        'data' => $data,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total' => $total,
            'total_pages' => ceil($total / $per_page)
        ]
    ];
}

// Rating helper functions
function get_user_stats($user_id) {
    global $conn;
    
    $stmt = mysqli_prepare($conn, "SELECT * FROM user_stats WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $stats = mysqli_fetch_assoc($result);
    
    if (!$stats) {
        // Create stats record if doesn't exist
        $stmt = mysqli_prepare($conn, "INSERT INTO user_stats (user_id) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        
        return [
            'user_id' => $user_id,
            'total_ratings' => 0,
            'average_rating' => 0.00,
            'total_comments' => 0,
            'last_rating_date' => null
        ];
    }
    
    return $stats;
}

function update_user_stats($user_id) {
    global $conn;
    
    // Calculate new stats
    $stmt = mysqli_prepare($conn, "
        SELECT 
            COUNT(*) as total_ratings,
            AVG(rating) as average_rating,
            COUNT(CASE WHEN comment IS NOT NULL AND comment != '' THEN 1 END) as total_comments,
            MAX(created_at) as last_rating_date
        FROM user_ratings 
        WHERE user_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $stats = mysqli_fetch_assoc($result);
    
    // Update or insert stats
    $stmt = mysqli_prepare($conn, "
        INSERT INTO user_stats (user_id, total_ratings, average_rating, total_comments, last_rating_date)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        total_ratings = VALUES(total_ratings),
        average_rating = VALUES(average_rating),
        total_comments = VALUES(total_comments),
        last_rating_date = VALUES(last_rating_date),
        updated_at = CURRENT_TIMESTAMP
    ");
    mysqli_stmt_bind_param($stmt, "iidis", 
        $user_id, 
        $stats['total_ratings'], 
        $stats['average_rating'], 
        $stats['total_comments'], 
        $stats['last_rating_date']
    );
    mysqli_stmt_execute($stmt);
}

// Additional rating helper functions
function get_existing_rating($user_id, $rater_id) {
    global $conn;
    
    $stmt = mysqli_prepare($conn, "
        SELECT * FROM user_ratings WHERE user_id = ? AND rater_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $rater_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function get_rating_by_id($rating_id) {
    global $conn;
    
    $stmt = mysqli_prepare($conn, "
        SELECT * FROM user_ratings WHERE id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $rating_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}
?>