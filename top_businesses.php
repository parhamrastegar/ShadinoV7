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
            // Get top rated businesses
            $query = "
                SELECT 
                    u.*,
                    us.average_rating,
                    us.total_ratings,
                    us.total_completed_orders,
                    (SELECT COUNT(*) FROM portfolio_items WHERE user_id = u.id) as portfolio_count
                FROM users u
                JOIN user_stats us ON u.id = us.user_id
                WHERE u.user_type = 'business'
                    AND us.total_ratings >= 3 -- حداقل 3 امتیاز برای اعتبار بیشتر
                ORDER BY us.average_rating DESC, us.total_ratings DESC
                LIMIT 12
            ";
            
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0):
            ?>
            
            <div class="row">
                <?php while ($business = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card business-card h-100">
                        <div class="business-banner">
                            <?php if ($business['cover_image']): ?>
                            <img src="<?php echo htmlspecialchars($business['cover_image']); ?>" 
                                 class="card-img-top" alt="تصویر کسب و کار">
                            <?php else: ?>
                            <div class="default-banner"></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="business-profile-image">
                            <img src="<?php echo $business['profile_image'] ?? 'assets/images/default-avatar.png'; ?>" 
                                 alt="<?php echo htmlspecialchars($business['username']); ?>"
                                 class="rounded-circle">
                        </div>
                        
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                <a href="profile.php?user_id=<?php echo $business['id']; ?>" class="text-dark">
                                    <?php echo htmlspecialchars($business['username']); ?>
                                </a>
                            </h5>
                            
                            <div class="rating-stars mb-2">
                                <?php
                                $rating = round($business['average_rating'] * 2) / 2; // Round to nearest 0.5
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
                                <span class="rating-text mr-2">
                                    <?php echo number_format($business['average_rating'], 1); ?>
                                    <small class="text-muted">(<?php echo $business['total_ratings']; ?> نظر)</small>
                                </span>
                            </div>
                            
                            <p class="card-text text-muted mb-3">
                                <?php
                                if (!empty($business['bio'])) {
                                    echo mb_strlen($business['bio']) > 100 ? 
                                         mb_substr(htmlspecialchars($business['bio']), 0, 100) . '...' : 
                                         htmlspecialchars($business['bio']);
                                } else {
                                    echo 'توضیحات موجود نیست';
                                }
                                ?>
                            </p>
                            
                            <div class="business-stats d-flex justify-content-around mb-3">
                                <div class="stat-item">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span><?php echo $business['total_completed_orders']; ?></span>
                                    <small>سفارش</small>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-images"></i>
                                    <span><?php echo $business['portfolio_count']; ?></span>
                                    <small>نمونه کار</small>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-star"></i>
                                    <span><?php echo $business['total_ratings']; ?></span>
                                    <small>نظر</small>
                                </div>
                            </div>
                            
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
