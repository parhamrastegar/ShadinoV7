<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if rating system is installed
$rating_system_installed = false;
$stats_table_check = mysqli_query($conn, "SHOW TABLES LIKE 'user_stats'");
if (mysqli_num_rows($stats_table_check) > 0) {
    $rating_system_installed = true;
}

include 'header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-5">برترین کسب و کارهای سایت</h1>
            
            <?php if (!$rating_system_installed): ?>
            <div class="alert alert-warning text-center">
                سیستم امتیازدهی هنوز نصب نشده است
            </div>
            <?php else: ?>
            
            <?php
            // Get top rated users with ratings
            $query = "
                SELECT 
                    u.id,
                    u.username,
                    u.profile_image,
                    u.bio,
                    u.user_type,
                    COUNT(r.id) as total_ratings,
                    COALESCE(AVG(r.rating), 0) as average_rating
                FROM users u
                LEFT JOIN user_ratings r ON u.id = r.rated_user_id
                GROUP BY u.id
                HAVING total_ratings > 0
                ORDER BY average_rating DESC, total_ratings DESC
                LIMIT 12
            ";
            
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0):
            ?>
            
            <div class="row">
                <?php while ($business = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card user-card h-100">
                        <div class="card-body text-center">
                            <a href="profile.php?user_id=<?php echo $user['id']; ?>" class="text-decoration-none">
                                <div class="user-avatar mb-3">
                                    <?php if ($user['profile_image']): ?>
                                        <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($user['username']); ?>"
                                             class="rounded-circle">
                                    <?php else: ?>
                                        <img src="assets/images/default-avatar.png" 
                                             alt="Default Avatar"
                                             class="rounded-circle">
                                    <?php endif; ?>
                                </div>
                                
                                <h5 class="card-title mb-2">
                                    <?php echo htmlspecialchars($user['username']); ?>
                                    <?php if ($user['user_type'] == 'business'): ?>
                                        <span class="badge bg-primary">کسب و کار</span>
                                    <?php endif; ?>
                                </h5>
                            </a>
                            
                            <div class="rating-stars mb-3">
                                <?php
                                $rating = round($user['average_rating'] * 2) / 2;
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($rating >= $i) {
                                        echo '<i class="fas fa-star text-warning"></i>';
                                    } elseif ($rating >= $i - 0.5) {
                                        echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                    } else {
                                        echo '<i class="far fa-star text-warning"></i>';
                                    }
                                }
                                ?>
                                <div class="rating-text">
                                    <?php echo number_format($user['average_rating'], 1); ?>
                                    <small class="text-muted">(<?php echo $user['total_ratings']; ?> نظر)</small>
                                </div>
                            </div>
                            
                            <?php if (!empty($user['bio'])): ?>
                            <p class="card-text text-muted small">
                                <?php 
                                echo mb_strlen($user['bio']) > 100 ? 
                                     mb_substr(htmlspecialchars($user['bio']), 0, 100) . '...' : 
                                     htmlspecialchars($user['bio']);
                                ?>
                            </p>
                            <?php endif; ?>
                            
                            <div class="business-actions">
                                <a href="profile.php?user_id=<?php echo $business['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm ml-2">
                                    <i class="fas fa-user"></i> مشاهده پروفایل
                                </a>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="chat.php?user_id=<?php echo $business['id']; ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-comments"></i> گفتگو
                                </a>
                                <?php else: ?>
                                <a href="login.php?redirect=top_businesses.php" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-comments"></i> ورود برای گفتگو
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <?php else: ?>
            <div class="alert alert-info text-center">
                در حال حاضر هیچ کسب و کاری با امتیاز کافی وجود ندارد
            </div>
            <?php endif; ?>
            
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.business-card {
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
    position: relative;
    margin-top: 30px;
}

.business-card:hover {
    transform: translateY(-5px);
}

.business-banner {
    height: 100px;
    background: linear-gradient(45deg, #007bff, #00bcd4);
    overflow: hidden;
}

.business-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.default-banner {
    height: 100%;
    background: linear-gradient(45deg, #007bff, #00bcd4);
}

.business-profile-image {
    position: absolute;
    top: 50px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 80px;
    border: 4px solid white;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}

.business-profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.business-card .card-body {
    padding-top: 45px;
}

.rating-stars {
    color: #ffc107;
}

.business-stats {
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}

.stat-item {
    text-align: center;
}

.stat-item i {
    display: block;
    color: #007bff;
    margin-bottom: 5px;
}

.stat-item span {
    display: block;
    font-weight: bold;
    color: #333;
}

.stat-item small {
    display: block;
    color: #666;
    font-size: 0.8em;
}

.business-actions {
    margin-top: 15px;
}

.rating-text {
    font-size: 0.9em;
    vertical-align: middle;
}
</style>

<?php include 'footer.php'; ?>
