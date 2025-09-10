<?php include 'header.php';?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پلن اشتراک شادینو - انتخاب دوره</title>
    
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
            --gradient-hero: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 50%, #FFF0F5 100%);
            --shadow-primary: 0 15px 35px rgba(255, 105, 180, 0.3);
            --shadow-secondary: 0 8px 25px rgba(0, 0, 0, 0.1);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --gold-gradient: linear-gradient(135deg, #FFD700, #FFA500);
        }
        
        body {
            background: linear-gradient(135deg, #FFE4E1 0%, #FFF0F5 50%, #F8F9FA 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Floating Elements */
        .floating-hearts {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        
        .floating-heart {
            position: absolute;
            font-size: 2rem;
            color: rgba(255, 105, 180, 0.3);
            animation: floatUp 8s linear infinite;
        }
        
        @keyframes floatUp {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Animations */
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
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-8px);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 105, 180, 0.7);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(255, 105, 180, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 105, 180, 0);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
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
            0%, 100% {
                opacity: 0;
                transform: scale(0) rotate(0deg);
            }
            50% {
                opacity: 1;
                transform: scale(1) rotate(180deg);
            }
        }
        
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(255, 105, 180, 0.5);
            }
            50% {
                box-shadow: 0 0 40px rgba(255, 105, 180, 0.8), 0 0 60px rgba(255, 105, 180, 0.6);
            }
        }
        
        @keyframes rainbow {
            0% { filter: hue-rotate(0deg); }
            100% { filter: hue-rotate(360deg); }
        }
        
        @keyframes typewriter {
            from { width: 0; }
            to { width: 100%; }
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
        
        /* Header */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--light-pink);
            box-shadow: var(--shadow-secondary);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: float 3s ease-in-out infinite;
        }
        
        /* Hero Section */
        .hero-section {
            background: var(--gradient-hero);
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="coins" x="0" y="0" width="25" height="25" patternUnits="userSpaceOnUse"><text x="12.5" y="18" text-anchor="middle" font-size="14" fill="rgba(255,255,255,0.1)">💰</text></pattern></defs><rect width="100" height="100" fill="url(%23coins)"/></svg>');
            animation: float 25s ease-in-out infinite;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease-out;
        }
        
        .typewriter-text {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            line-height: 1.6;
            overflow: hidden;
            white-space: nowrap;
            border-left: 3px solid white;
            animation: typewriter 3s steps(40) 1s both, blink 1s step-end infinite;
            max-width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Live Counter */
        .live-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }
        
        .stat-item {
            text-align: center;
            color: white;
            animation: fadeInUp 1s ease-out;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            display: block;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        /* Billing Toggle */
        .billing-section {
            padding: 80px 0;
            background: white;
            position: relative;
        }
        
        .billing-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: 3rem 0;
        }
        
        .toggle-container {
            position: relative;
            background: var(--gradient-primary);
            border-radius: 50px;
            padding: 0.5rem;
            box-shadow: var(--shadow-primary);
        }
        
        .toggle-option {
            padding: 1rem 2rem;
            border-radius: 25px;
            background: transparent;
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
            cursor: pointer;
        }
        
        .toggle-option:hover {
            transform: translateY(-2px);
        }
        
        .toggle-option.active {
            background: white;
            color: var(--primary-color);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-3px);
        }
        
        .discount-badge {
            background: var(--success-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            animation: bounce 2s ease-in-out infinite;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        }
        
        /* Main Plan Card */
        .plan-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }
        
        .plan-card {
            background: white;
            border-radius: 30px;
            padding: 3rem;
            box-shadow: var(--shadow-primary);
            position: relative;
            overflow: hidden;
            border: 3px solid var(--primary-color);
            animation: glow 3s ease-in-out infinite;
        }
        
        .plan-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 105, 180, 0.1), transparent);
            animation: rotate 10s linear infinite;
            z-index: 1;
        }
        
        .plan-content {
            position: relative;
            z-index: 2;
        }
        
        .plan-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .plan-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--gold-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            margin: 0 auto 2rem;
            animation: float 4s ease-in-out infinite;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .plan-icon:hover {
            animation: wiggle 0.6s ease-in-out;
            transform: scale(1.1);
        }
        
        .plan-name {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .plan-description {
            font-size: 1.2rem;
            color: #6C757D;
            margin-bottom: 2rem;
        }
        
        /* Price Display */
        .price-display {
            text-align: center;
            margin: 3rem 0;
            padding: 2rem;
            background: var(--gradient-secondary);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .price-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .price-amount {
            font-size: 4rem;
            font-weight: 800;
            color: var(--primary-color);
            display: block;
            transition: all 0.5s ease;
            position: relative;
        }
        
        .price-currency {
            font-size: 1.5rem;
            color: #6C757D;
            margin-right: 1rem;
        }
        
        .price-period {
            color: #6C757D;
            font-size: 1.2rem;
            margin-top: 1rem;
            font-weight: 600;
        }
        
        .price-savings {
            background: var(--success-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1rem;
            display: inline-block;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #6C757D;
            font-size: 1.5rem;
            margin-top: 0.5rem;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .original-price.show {
            opacity: 1;
        }
        
        /* Features List */
        .features-container {
            margin: 3rem 0;
        }
        
        .features-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 15px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 105, 180, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .feature-item:hover {
            background: #F8F9FA;
            transform: translateX(10px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .feature-item:hover::before {
            left: 100%;
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }
        
        .feature-icon.available {
            background: var(--success-color);
            color: white;
            animation: checkmark 0.5s ease-out;
        }
        
        .feature-icon.limited {
            background: var(--warning-color);
            color: white;
        }
        
        .feature-icon.unavailable {
            background: #DEE2E6;
            color: #6C757D;
        }
        
        .feature-icon.new {
            background: var(--primary-color);
            color: white;
            animation: sparkle 2s ease-in-out infinite;
        }
        
        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(45deg);
            }
            50% {
                transform: scale(1.2) rotate(45deg);
            }
            100% {
                transform: scale(1) rotate(45deg);
            }
        }
        
        .feature-text {
            flex: 1;
            color: #495057;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }
        
        .feature-text.unavailable {
            color: #6C757D;
            text-decoration: line-through;
        }
        
        .feature-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 1rem;
            animation: bounce 2s ease-in-out infinite;
        }
        
        .feature-badge.new {
            background: var(--success-color);
            animation: glow 2s ease-in-out infinite;
        }
        
        /* Action Button */
        .action-section {
            text-align: center;
            margin-top: 3rem;
        }
        
        .select-plan-btn {
            padding: 1.5rem 3rem;
            border: none;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1.3rem;
            background: var(--gradient-primary);
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            box-shadow: var(--shadow-primary);
        }
        
        .select-plan-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .select-plan-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(255, 105, 180, 0.5);
        }
        
        .select-plan-btn:hover::before {
            left: 100%;
        }
        
        .select-plan-btn:active {
            transform: translateY(-2px);
        }
        
        /* Progress Bar */
        .progress-container {
            margin: 2rem 0;
            text-align: center;
        }
        
        .progress-bar-custom {
            width: 100%;
            height: 10px;
            background: #E9ECEF;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--gradient-primary);
            border-radius: 5px;
            transition: width 1s ease-in-out;
            position: relative;
        }
        
        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s ease-in-out infinite;
        }
        
        /* Live Elements */
        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--success-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin: 1rem 0;
        }
        
        .live-dot {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: blink 1s ease-in-out infinite;
        }
        
        /* Testimonials Slider */
        .testimonials-section {
            padding: 60px 0;
            background: var(--gradient-secondary);
            overflow: hidden;
        }
        
        .testimonial-slider {
            display: flex;
            animation: slide 20s linear infinite;
            gap: 2rem;
        }
        
        @keyframes slide {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .testimonial-card {
            min-width: 300px;
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            text-align: center;
        }
        
        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }
        
        .testimonial-text {
            font-style: italic;
            color: #6C757D;
            margin-bottom: 1rem;
        }
        
        .testimonial-name {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .typewriter-text {
                font-size: 1.1rem;
                white-space: normal;
                border-left: none;
                animation: fadeInUp 1s ease-out;
            }
            
            .live-stats {
                gap: 1.5rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .billing-toggle {
                flex-direction: column;
                gap: 1rem;
            }
            
            .toggle-container {
                width: 100%;
                max-width: 400px;
            }
            
            .plan-card {
                padding: 2rem;
            }
            
            .plan-icon {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
            }
            
            .price-amount {
                font-size: 3rem;
            }
            
            .plan-name {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-section {
                padding: 100px 0 60px;
            }
            
            .billing-section {
                padding: 60px 0;
            }
            
            .live-stats {
                flex-direction: column;
                gap: 1rem;
            }
            
            .testimonial-card {
                min-width: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Hearts Background -->
    <div class="floating-hearts" id="floatingHearts"></div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    پلن <span style="color: #FFD700;">طلایی</span> شادینو
                </h1>
                <p class="typewriter-text">
                    یک پلن، سه دوره مختلف، امکانات متنوع برای هر نیاز
                </p>
                
                <div class="live-stats">
                    <div class="stat-item">
                        <span class="stat-number" id="activeUsers">1,247</span>
                        <span class="stat-label">کاربر فعال</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="completedEvents">3,892</span>
                        <span class="stat-label">مراسم موفق</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="satisfaction">98%</span>
                        <span class="stat-label">رضایت مشتریان</span>
                    </div>
                </div>
                
                <div class="live-indicator">
                    <div class="live-dot"></div>
                    <span>در حال حاضر ۲۳ نفر در حال مشاهده این صفحه هستند</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Billing Section -->
    <section class="billing-section">
        <div class="container">
            <div class="text-center">
                <h2 style="font-size: 2.5rem; font-weight: 700; color: #212529; margin-bottom: 1rem;">
                    دوره اشتراک خود را انتخاب کنید
                </h2>
                <p class="lead" style="color: #6C757D;">
                    هر دوره شامل امکانات و مزایای منحصر به فردی است
                </p>
            </div>
            
            <div class="billing-toggle">
                <span style="color: #212529; font-weight: 600; font-size: 1.1rem;">دوره پرداخت:</span>
                <div class="toggle-container">
                    <button class="toggle-option active" data-period="monthly">۱ ماهه</button>
                    <button class="toggle-option" data-period="sixmonth">۶ ماهه</button>
                    <button class="toggle-option" data-period="yearly">۱ ساله</button>
                </div>
                <div class="discount-badge">
                    تا ۴۰٪ تخفیف + هدایای ویژه
                </div>
            </div>
            
            <div class="plan-container">
                <div class="plan-card">
                    <div class="plan-content">
                        <div class="plan-header">
                            <div class="plan-icon" onclick="addSparkleEffect(this)">👑</div>
                            <h3 class="plan-name">پلن طلایی شادینو</h3>
                            <p class="plan-description" id="planDescription">
                                دسترسی کامل به تمام امکانات پلتفرم برای یک ماه
                            </p>
                        </div>
                        
                        <div class="price-display">
                            <span class="price-amount" id="priceAmount">۴۹۹</span>
                            <span class="price-currency">هزار تومان</span>
                            <div class="price-period" id="pricePeriod">برای ۱ ماه</div>
                            <div class="original-price" id="originalPrice">۵۹۹ هزار تومان</div>
                            <div class="price-savings" id="priceSavings" style="display: none;">
                                شما ۱۰۰ هزار تومان صرفه‌جویی می‌کنید!
                            </div>
                        </div>
                        
                        <div class="progress-container">
                            <p style="color: #6C757D; margin-bottom: 1rem;">میزان محبوبیت این پلن:</p>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" id="popularityProgress" style="width: 0%"></div>
                            </div>
                            <p style="color: var(--primary-color); font-weight: 600; margin-top: 0.5rem;" id="popularityText">
                                ۸۷٪ کاربران این دوره را انتخاب می‌کنند
                            </p>
                        </div>
                        
                        <div class="features-container">
                            <h4 class="features-title">امکانات و مزایای این دوره:</h4>
                            <ul class="features-list" id="featuresList">
                                <!-- Features will be populated by JavaScript -->
                            </ul>
                        </div>
                        
                        <div class="action-section">
                            <button class="select-plan-btn" onclick="selectPlan()">
                                <span id="buttonText">شروع اشتراک ۱ ماهه</span>
                            </button>
                            <p style="color: #6C757D; margin-top: 1rem; font-size: 0.9rem;">
                                ✅ ضمانت ۳۰ روزه بازگشت وجه | 🔒 پرداخت امن | 📞 پشتیبانی ۲۴/۷
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h3 style="text-align: center; font-size: 2rem; font-weight: 700; color: #212529; margin-bottom: 2rem;">
                نظرات کاربران راضی ما
            </h3>
            <div class="testimonial-slider" id="testimonialSlider">
                <!-- Testimonials will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Plan data for different periods
        const planData = {
            monthly: {
                price: '۴۹۹',
                originalPrice: '۵۹۹',
                period: 'برای ۱ ماه',
                description: 'دسترسی کامل به تمام امکانات پلتفرم برای یک ماه',
                savings: 'شما ۱۰۰ هزار تومان صرفه‌جویی می‌کنید!',
                buttonText: 'شروع اشتراک ۱ ماهه',
                popularity: 87,
                popularityText: '۸۷٪ کاربران این دوره را انتخاب می‌کنند',
                features: [
                    { text: 'جستجو در ۱۰۰+ ارائه‌دهنده خدمات', type: 'available' },
                    { text: 'مشاهده اطلاعات تماس', type: 'available' },
                    { text: 'تا ۱۰ درخواست قیمت', type: 'limited' },
                    { text: 'پشتیبانی ایمیلی', type: 'available' },
                    { text: 'دسترسی به چک‌لیست مراسم', type: 'available' },
                    { text: 'مشاور اختصاصی', type: 'unavailable' },
                    { text: 'تخفیف‌های ویژه', type: 'unavailable' },
                    { text: 'مدیریت کامل مراسم', type: 'unavailable' }
                ]
            },
            sixmonth: {
                price: '۲,۱۹۹',
                originalPrice: '۲,۹۹۴',
                period: 'برای ۶ ماه',
                description: 'دسترسی کامل به امکانات پیشرفته و مشاور اختصاصی برای ۶ ماه',
                savings: 'شما ۷۹۵ هزار تومان صرفه‌جویی می‌کنید! (۲۷٪ تخفیف)',
                buttonText: 'شروع اشتراک ۶ ماهه',
                popularity: 92,
                popularityText: '۹۲٪ کاربران این دوره را انتخاب می‌کنند',
                features: [
                    { text: 'جستجو در ۵۰۰+ ارائه‌دهنده خدمات', type: 'available' },
                    { text: 'مشاهده اطلاعات تماس', type: 'available' },
                    { text: 'درخواست قیمت نامحدود', type: 'available' },
                    { text: 'پشتیبانی تلفنی ۲۴/۷', type: 'available' },
                    { text: 'مشاور اختصاصی', type: 'available' },
                    { text: 'تخفیف ۱۵٪ روی خدمات', type: 'new', badge: 'جدید' },
                    { text: 'چک‌لیست و برنامه‌ریزی کامل', type: 'available' },
                    { text: 'گزارش پیشرفت مراسم', type: 'new', badge: 'ویژه' },
                    { text: 'مدیریت کامل مراسم', type: 'unavailable' }
                ]
            },
            yearly: {
                price: '۳,۵۹۹',
                originalPrice: '۵,۹۸۸',
                period: 'برای ۱ سال',
                description: 'دسترسی VIP به تمام امکانات، مدیر مراسم اختصاصی و خدمات ویژه',
                savings: 'شما ۲,۳۸۹ هزار تومان صرفه‌جویی می‌کنید! (۴۰٪ تخفیف)',
                buttonText: 'شروع اشتراک ۱ ساله',
                popularity: 95,
                popularityText: '۹۵٪ کاربران این دوره را انتخاب می‌کنند',
                features: [
                    { text: 'دسترسی به تمام ارائه‌دهندگان (۱۰۰۰+)', type: 'available' },
                    { text: 'اولویت در پاسخ‌گویی', type: 'available' },
                    { text: 'درخواست قیمت نامحدود', type: 'available' },
                    { text: 'پشتیبانی اختصاصی VIP', type: 'available' },
                    { text: 'مشاور و مدیر مراسم اختصاصی', type: 'available' },
                    { text: 'تخفیف ۲۵٪ روی تمام خدمات', type: 'new', badge: 'ویژه' },
                    { text: 'مدیریت کامل مراسم', type: 'available' },
                    { text: 'گزارش تصویری از مراسم', type: 'new', badge: 'منحصر به فرد' },
                    { text: 'بیمه مراسم رایگان', type: 'new', badge: 'هدیه' },
                    { text: 'دسترسی به وبینارهای آموزشی', type: 'new', badge: 'جدید' },
                    { text: 'کنسولتینگ رایگان با متخصصین', type: 'new', badge: 'VIP' }
                ]
            }
        };

        // Testimonials data
        const testimonials = [
            { name: 'سارا احمدی', text: 'با پلن ۶ ماهه شادینو، مراسم عروسی رویایی‌ام را برگزار کردم!', avatar: '👩' },
            { name: 'علی رضایی', text: 'پشتیبانی فوق‌العاده و خدمات بی‌نظیر. پیشنهاد می‌کنم!', avatar: '👨' },
            { name: 'مریم کریمی', text: 'مشاور اختصاصی کمک زیادی به من کرد. عالی بود!', avatar: '👩‍💼' },
            { name: 'حسین محمدی', text: 'قیمت‌ها مناسب و کیفیت خدمات بالا. راضی هستم.', avatar: '👨‍💻' },
            { name: 'فاطمه نوری', text: 'با تخفیف‌های ویژه، هزینه مراسمم خیلی کم شد.', avatar: '👩‍🎓' },
            { name: 'محمد صادقی', text: 'پلن سالانه بهترین انتخاب برای مراسم‌های بزرگ است.', avatar: '👨‍🏫' }
        ];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            initializeFloatingHearts();
            initializeLiveCounters();
            initializeTestimonials();
            updatePlanDisplay('monthly');
            initializeBillingToggle();
            initializeScrollAnimations();
            startLiveUpdates();
        });

        // Floating hearts animation
        function initializeFloatingHearts() {
            const heartsContainer = document.getElementById('floatingHearts');
            const hearts = ['💖', '💕', '💗', '💝', '💘', '💞'];
            
            setInterval(() => {
                const heart = document.createElement('div');
                heart.className = 'floating-heart';
                heart.textContent = hearts[Math.floor(Math.random() * hearts.length)];
                heart.style.left = Math.random() * 100 + '%';
                heart.style.animationDuration = (Math.random() * 3 + 5) + 's';
                heart.style.fontSize = (Math.random() * 1 + 1.5) + 'rem';
                
                heartsContainer.appendChild(heart);
                
                setTimeout(() => {
                    heart.remove();
                }, 8000);
            }, 2000);
        }

        // Live counters animation
        function initializeLiveCounters() {
            const counters = [
                { id: 'activeUsers', start: 1200, end: 1247, suffix: '' },
                { id: 'completedEvents', start: 3800, end: 3892, suffix: '' },
                { id: 'satisfaction', start: 95, end: 98, suffix: '%' }
            ];
            
            counters.forEach(counter => {
                animateCounter(counter.id, counter.start, counter.end, counter.suffix, 2000);
            });
        }

        function animateCounter(id, start, end, suffix, duration) {
            const element = document.getElementById(id);
            const range = end - start;
            const increment = range / (duration / 16);
            let current = start;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= end) {
                    current = end;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current) + suffix;
            }, 16);
        }

        // Initialize testimonials slider
        function initializeTestimonials() {
            const slider = document.getElementById('testimonialSlider');
            
            // Duplicate testimonials for seamless loop
            const allTestimonials = [...testimonials, ...testimonials];
            
            allTestimonials.forEach(testimonial => {
                const card = document.createElement('div');
                card.className = 'testimonial-card';
                card.innerHTML = `
                    <div class="testimonial-avatar">${testimonial.avatar}</div>
                    <p class="testimonial-text">"${testimonial.text}"</p>
                    <div class="testimonial-name">${testimonial.name}</div>
                `;
                slider.appendChild(card);
            });
        }

        // Billing toggle functionality
        function initializeBillingToggle() {
            const toggleButtons = document.querySelectorAll('.toggle-option');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    toggleButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    const period = this.dataset.period;
                    updatePlanDisplay(period);
                    
                    // Add click animation
                    this.style.animation = 'bounce 0.6s ease-in-out';
                    setTimeout(() => {
                        this.style.animation = '';
                    }, 600);
                });
            });
        }

        // Update plan display based on selected period
        function updatePlanDisplay(period) {
            const data = planData[period];
            
            // Update price with animation
            const priceElement = document.getElementById('priceAmount');
            priceElement.style.animation = 'bounce 0.6s ease-in-out';
            setTimeout(() => {
                priceElement.textContent = data.price;
                priceElement.style.animation = '';
            }, 300);
            
            // Update other elements
            document.getElementById('pricePeriod').textContent = data.period;
            document.getElementById('planDescription').textContent = data.description;
            document.getElementById('buttonText').textContent = data.buttonText;
            
            // Update original price and savings
            const originalPriceElement = document.getElementById('originalPrice');
            const savingsElement = document.getElementById('priceSavings');
            
            if (period === 'monthly') {
                originalPriceElement.classList.remove('show');
                savingsElement.style.display = 'none';
            } else {
                originalPriceElement.textContent = data.originalPrice;
                originalPriceElement.classList.add('show');
                savingsElement.textContent = data.savings;
                savingsElement.style.display = 'inline-block';
            }
            
            // Update popularity progress
            const progressElement = document.getElementById('popularityProgress');
            const popularityTextElement = document.getElementById('popularityText');
            
            setTimeout(() => {
                progressElement.style.width = data.popularity + '%';
                popularityTextElement.textContent = data.popularityText;
            }, 300);
            
            // Update features list
            updateFeaturesList(data.features);
        }

        // Update features list
        function updateFeaturesList(features) {
            const featuresList = document.getElementById('featuresList');
            featuresList.innerHTML = '';
            
            features.forEach((feature, index) => {
                const li = document.createElement('li');
                li.className = 'feature-item';
                li.style.animationDelay = (index * 0.1) + 's';
                
                let iconClass = 'feature-icon ';
                let iconContent = '';
                
                switch (feature.type) {
                    case 'available':
                        iconClass += 'available';
                        iconContent = '<i class="bi bi-check"></i>';
                        break;
                    case 'limited':
                        iconClass += 'limited';
                        iconContent = '<i class="bi bi-clock"></i>';
                        break;
                    case 'unavailable':
                        iconClass += 'unavailable';
                        iconContent = '<i class="bi bi-x"></i>';
                        break;
                    case 'new':
                        iconClass += 'new';
                        iconContent = '<i class="bi bi-star-fill"></i>';
                        break;
                }
                
                const textClass = feature.type === 'unavailable' ? 'feature-text unavailable' : 'feature-text';
                const badge = feature.badge ? `<span class="feature-badge ${feature.type === 'new' ? 'new' : ''}">${feature.badge}</span>` : '';
                
                li.innerHTML = `
                    <div class="${iconClass}">${iconContent}</div>
                    <span class="${textClass}">${feature.text}</span>
                    ${badge}
                `;
                
                featuresList.appendChild(li);
                
                // Add entrance animation
                setTimeout(() => {
                    li.style.animation = 'slideInRight 0.5s ease-out';
                }, index * 100);
            });
        }

        // Plan selection function
        function selectPlan() {
            const currentPeriod = document.querySelector('.toggle-option.active').dataset.period;
            const button = document.querySelector('.select-plan-btn');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="bi bi-hourglass-split"></i> در حال پردازش...';
            button.disabled = true;
            button.style.animation = 'pulse 1s ease-in-out infinite';
            
            // Add sparkle effect to plan card
            addSparkleEffect(document.querySelector('.plan-card'));
            
            // Simulate processing
            setTimeout(() => {
                button.style.animation = '';
                button.innerHTML = '<i class="bi bi-check-circle"></i> انتخاب شد!';
                button.style.background = 'var(--success-color)';
                
                showToast(`پلن ${getPeriodName(currentPeriod)} با موفقیت انتخاب شد!`, 'success');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.style.background = '';
                    
                    showToast('در حال انتقال به صفحه پرداخت...', 'info');
                }, 2000);
            }, 3000);
        }

        function getPeriodName(period) {
            const names = {
                'monthly': '۱ ماهه',
                'sixmonth': '۶ ماهه',
                'yearly': '۱ ساله'
            };
            return names[period] || period;
        }

        // Add sparkle effect
        function addSparkleEffect(element) {
            const sparkles = ['✨', '⭐', '💫', '🌟', '💖'];
            
            for (let i = 0; i < 8; i++) {
                const sparkle = document.createElement('div');
                sparkle.textContent = sparkles[Math.floor(Math.random() * sparkles.length)];
                sparkle.style.cssText = `
                    position: absolute;
                    font-size: ${Math.random() * 1 + 1.5}rem;
                    pointer-events: none;
                    animation: sparkle 2s ease-out forwards;
                    top: ${Math.random() * 100}%;
                    left: ${Math.random() * 100}%;
                    z-index: 1000;
                `;
                
                element.style.position = 'relative';
                element.appendChild(sparkle);
                
                setTimeout(() => {
                    sparkle.remove();
                }, 2000);
            }
        }

        // Toast notification system
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed`;
            toast.style.cssText = `
                top: 100px;
                right: 20px;
                z-index: 1060;
                min-width: 350px;
                animation: slideInRight 0.5s ease-out;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
                border-radius: 15px;
                border: none;
                backdrop-filter: blur(10px);
            `;
            
            const icons = {
                success: 'check-circle-fill',
                error: 'exclamation-triangle-fill',
                info: 'info-circle-fill'
            };
            
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-${icons[type]} me-2" style="font-size: 1.2rem;"></i>
                    <span style="font-weight: 600;">${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideInRight 0.5s ease-out reverse';
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 4000);
        }

        // Initialize scroll animations
        function initializeScrollAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animation = 'fadeInUp 0.8s ease-out forwards';
                    }
                });
            }, observerOptions);

            // Observe elements for scroll animations
            document.querySelectorAll('.feature-item, .testimonial-card').forEach(el => {
                observer.observe(el);
            });
        }

        // Start live updates
        function startLiveUpdates() {
            // Update live viewer count
            setInterval(() => {
                const viewerCount = Math.floor(Math.random() * 10) + 18;
                const liveIndicator = document.querySelector('.live-indicator span');
                liveIndicator.textContent = `در حال حاضر ${viewerCount} نفر در حال مشاهده این صفحه هستند`;
            }, 5000);
            
            // Update stats occasionally
            setInterval(() => {
                const activeUsersEl = document.getElementById('activeUsers');
                const currentUsers = parseInt(activeUsersEl.textContent);
                const newUsers = currentUsers + Math.floor(Math.random() * 5) + 1;
                animateCounter('activeUsers', currentUsers, newUsers, '', 1000);
            }, 30000);
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 5px 25px rgba(0, 0, 0, 0.15)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.1)';
            }
        });

        // Add CSS animations dynamically
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(100px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
        `;
        document.head.appendChild(style);

        // Page load animation
        window.addEventListener('load', function() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.8s ease-in-out';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'970b8896e5d1ebae',t:'MTc1NTQ1ODQ3Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
