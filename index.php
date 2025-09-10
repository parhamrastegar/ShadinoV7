<?php
session_start();
include 'includes/config.php';
include_once 'includes/functions.php';

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'business') {
        header('Location: index_business.php');
        exit();
    } elseif ($_SESSION['role'] === 'delivery') {
        header('Location: index_courier.php');
        exit();
    }
}

include 'header.php';
?><!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شادینو - شبکه اجتماعی شاد و رنگارنگ</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Vazirmatn', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-color: #FF69B4;
            --secondary-color: #FFB6C1;
            --accent-color: #FF1493;
            --light-pink: #FFC0CB;
            --purple: #9B59B6;
            --blue: #3498DB;
            --green: #2ECC71;
            --orange: #F39C12;
            --gradient-primary: linear-gradient(135deg, #FF69B4, #FFB6C1, #9B59B6);
            --gradient-secondary: linear-gradient(135deg, #3498DB, #2ECC71, #F39C12);
            --gradient-hero: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 25%, #9B59B6 50%, #3498DB 75%, #2ECC71 100%);
            --shadow-primary: 0 20px 60px rgba(255, 105, 180, 0.3);
            --shadow-secondary: 0 15px 40px rgba(0, 0, 0, 0.1);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        body {
            background: linear-gradient(135deg, #FFE4E1 0%, #FFF0F5 25%, #F0F8FF 50%, #F0FFF0 75%, #FFF8DC 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-hero);
            opacity: 0.1;
            animation: gradientShift 15s ease-in-out infinite;
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            animation: float 20s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            background: rgba(255, 105, 180, 0.2);
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            background: rgba(155, 89, 182, 0.2);
            top: 60%;
            right: 15%;
            animation-delay: -5s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            background: rgba(52, 152, 219, 0.2);
            top: 80%;
            left: 20%;
            animation-delay: -10s;
        }
        
        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            background: rgba(46, 204, 113, 0.2);
            top: 30%;
            right: 30%;
            animation-delay: -15s;
        }
        
        .hero-content {
            text-align: center;
            z-index: 10;
            position: relative;
            max-width: 800px;
            padding: 2rem;
        }
        
        .hero-logo {
            font-size: 4rem;
            font-weight: 900;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            animation: logoGlow 3s ease-in-out infinite;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: #2C3E50;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            color: #7F8C8D;
            margin-bottom: 3rem;
            line-height: 1.6;
            animation: fadeInUp 1s ease-out 0.3s both;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.6s both;
        }
        
        .btn-hero {
            padding: 1rem 2.5rem;
            font-size: 1.2rem;
            font-weight: 700;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-hero:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-primary);
        }
        
        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 80px rgba(255, 105, 180, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: var(--primary-color);
            border: 3px solid var(--primary-color);
            box-shadow: var(--shadow-secondary);
        }
        
        .btn-secondary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-5px);
        }
        
        /* Features Section */
        .features-section {
            padding: 6rem 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        
        .section-title {
            text-align: center;
            font-size: 3rem;
            font-weight: 800;
            color: #2C3E50;
            margin-bottom: 1rem;
            position: relative;
        }
        
        .section-subtitle {
            text-align: center;
            font-size: 1.3rem;
            color: #7F8C8D;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .feature-card {
            background: white;
            padding: 2.5rem;
            border-radius: 25px;
            box-shadow: var(--card-shadow);
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin: 0 auto 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .feature-card:nth-child(2) .feature-icon {
            background: var(--gradient-secondary);
        }
        
        .feature-card:nth-child(3) .feature-icon {
            background: linear-gradient(135deg, #F39C12, #E74C3C);
        }
        
        .feature-card:nth-child(4) .feature-icon {
            background: linear-gradient(135deg, #9B59B6, #8E44AD);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 1rem;
        }
        
        .feature-description {
            color: #7F8C8D;
            line-height: 1.6;
            font-size: 1.1rem;
        }
        
        /* Stats Section */
        .stats-section {
            padding: 4rem 0;
            background: var(--gradient-primary);
            color: white;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .stat-item {
            padding: 1.5rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            animation: countUp 2s ease-out;
        }
        
        .stat-label {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 6rem 0;
            background: linear-gradient(135deg, #2C3E50, #34495E);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="stars" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><text x="10" y="15" text-anchor="middle" font-size="10" fill="rgba(255,255,255,0.1)">⭐</text></pattern></defs><rect width="100" height="100" fill="url(%23stars)"/></svg>');
            opacity: 0.3;
        }
        
        .cta-content {
            position: relative;
            z-index: 10;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
        
        .cta-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
        }
        
        /* Footer */
        .footer {
            background: #2C3E50;
            color: white;
            padding: 3rem 0 1rem;
            text-align: center;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-section h4 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: #BDC3C7;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--primary-color);
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 105, 180, 0.4);
        }
        
        .footer-bottom {
            border-top: 1px solid #34495E;
            padding-top: 1rem;
            color: #BDC3C7;
        }
        
        /* Animations */
        @keyframes gradientShift {
            0%, 100% { transform: translateX(-50px); }
            50% { transform: translateX(50px); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(90deg); }
            50% { transform: translateY(-40px) rotate(180deg); }
            75% { transform: translateY(-20px) rotate(270deg); }
        }
        
        @keyframes logoGlow {
            0%, 100% { filter: drop-shadow(0 0 10px rgba(255, 105, 180, 0.5)); }
            50% { filter: drop-shadow(0 0 20px rgba(255, 105, 180, 0.8)); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-logo {
                font-size: 3rem;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-hero {
                width: 100%;
                max-width: 300px;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .cta-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-content {
                padding: 1rem;
            }
            
            .hero-logo {
                font-size: 2.5rem;
            }
            
            .hero-title {
                font-size: 1.5rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .feature-card {
                padding: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
        
        /* Scroll animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background"></div>
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        
        <div class="hero-content">
            <div class="hero-logo">🌈 شادینو</div>
            <h1 class="hero-title">شبکه اجتماعی شاد و رنگارنگ</h1>
            <p class="hero-subtitle">
                جایی که دوستی‌ها شکل می‌گیرند، خاطرات ساخته می‌شوند و لحظات شاد به اشتراک گذاشته می‌شوند
            </p>
            <div class="hero-buttons">
                <a href="#" class="btn-hero btn-primary" onclick="joinNow()">
                    <i class="bi bi-rocket-takeoff"></i>
                    همین حالا شروع کن
                </a>
                <a href="#features" class="btn-hero btn-secondary">
                    <i class="bi bi-play-circle"></i>
                    بیشتر بدانید
                </a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="section-title animate-on-scroll">✨ ویژگی‌های شگفت‌انگیز</h2>
            <p class="section-subtitle animate-on-scroll">
                شادینو با امکانات منحصر به فرد، تجربه‌ای متفاوت از شبکه‌های اجتماعی ارائه می‌دهد
            </p>
            
            <div class="features-grid">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">💬</div>
                    <h3 class="feature-title">چت هوشمند</h3>
                    <p class="feature-description">
                        با سیستم چت پیشرفته و امکانات هوشمند، با دوستانت به راحتی در ارتباط باش
                    </p>
                </div>
                
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">🎨</div>
                    <h3 class="feature-title">محتوای خلاقانه</h3>
                    <p class="feature-description">
                        ابزارهای قدرتمند برای ساخت و به اشتراک‌گذاری محتوای خلاقانه و جذاب
                    </p>
                </div>
                
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">🌟</div>
                    <h3 class="feature-title">تجربه شخصی</h3>
                    <p class="feature-description">
                        الگوریتم هوشمند که محتوا را بر اساس علایق و سلیقه شما شخصی‌سازی می‌کند
                    </p>
                </div>
                
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">امنیت بالا</h3>
                    <p class="feature-description">
                        حریم خصوصی شما برای ما مهم است. با بالاترین استانداردهای امنیتی محافظت می‌شوید
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <h2 class="section-title">🚀 شادینو در اعداد</h2>
            <div class="stats-grid">
                <div class="stat-item animate-on-scroll">
                    <div class="stat-number" data-count="50000">0</div>
                    <div class="stat-label">کاربر فعال</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <div class="stat-number" data-count="1000000">0</div>
                    <div class="stat-label">پیام روزانه</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <div class="stat-number" data-count="25000">0</div>
                    <div class="stat-label">محتوای اشتراکی</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <div class="stat-number" data-count="99">0</div>
                    <div class="stat-label">درصد رضایت</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-background"></div>
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title animate-on-scroll">🎉 آماده برای شروع ماجراجویی؟</h2>
                <p class="cta-subtitle animate-on-scroll">
                    به جمع میلیون‌ها کاربر شاد شادینو بپیوندید و تجربه‌ای فراموش‌نشدنی داشته باشید
                </p>
                <div class="hero-buttons animate-on-scroll">
                    <a href="#" class="btn-hero btn-primary" onclick="signUp()">
                        <i class="bi bi-person-plus"></i>
                        ثبت نام رایگان
                    </a>
                    <a href="#" class="btn-hero btn-secondary" onclick="downloadApp()">
                        <i class="bi bi-download"></i>
                        دانلود اپلیکیشن
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>🌈 شادینو</h4>
                    <p>شبکه اجتماعی که زندگی را شادتر می‌کند</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-telegram"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>لینک‌های مفید</h4>
                    <ul class="footer-links">
                        <li><a href="#">درباره ما</a></li>
                        <li><a href="#">ویژگی‌ها</a></li>
                        <li><a href="#">قیمت‌گذاری</a></li>
                        <li><a href="#">وبلاگ</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>پشتیبانی</h4>
                    <ul class="footer-links">
                        <li><a href="#">مرکز راهنمایی</a></li>
                        <li><a href="#">تماس با ما</a></li>
                        <li><a href="#">گزارش مشکل</a></li>
                        <li><a href="#">سوالات متداول</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>قوانین</h4>
                    <ul class="footer-links">
                        <li><a href="#">شرایط استفاده</a></li>
                        <li><a href="#">حریم خصوصی</a></li>
                        <li><a href="#">قوانین انجمن</a></li>
                        <li><a href="#">کوکی‌ها</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; ۱۴۰۳ شادینو. تمامی حقوق محفوظ است. ساخته شده با ❤️ برای شما</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Scroll animations
        function animateOnScroll() {
            const elements = document.querySelectorAll('.animate-on-scroll');
            
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.classList.add('animated');
                }
            });
        }
        
        // Counter animation
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    
                    if (target >= 1000000) {
                        counter.textContent = (current / 1000000).toFixed(1) + 'M';
                    } else if (target >= 1000) {
                        counter.textContent = (current / 1000).toFixed(0) + 'K';
                    } else {
                        counter.textContent = Math.floor(current) + (target === 99 ? '%' : '');
                    }
                }, 20);
            });
        }
        
        // Smooth scrolling
        function smoothScroll(target) {
            const element = document.querySelector(target);
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
        
        // Button actions
        function joinNow() {
            showToast('خوش آمدید! صفحه ثبت نام باز می‌شود...', 'success');
            setTimeout(() => {
                // Redirect to signup page
                console.log('Redirecting to signup...');
            }, 1500);
        }
        
        function signUp() {
            showToast('فرم ثبت نام آماده می‌شود...', 'info');
            setTimeout(() => {
                // Show signup form
                console.log('Opening signup form...');
            }, 1000);
        }
        
        function downloadApp() {
            showToast('دانلود اپلیکیشن شروع می‌شود...', 'info');
            setTimeout(() => {
                // Start download
                console.log('Starting app download...');
            }, 1000);
        }
        
        // Toast notification system
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed`;
            toast.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 1060;
                min-width: 350px;
                animation: slideInRight 0.5s ease-out;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
                border-radius: 15px;
                border: none;
                backdrop-filter: blur(10px);
                font-weight: 600;
            `;
            
            const icons = {
                success: 'check-circle-fill',
                error: 'exclamation-triangle-fill',
                info: 'info-circle-fill'
            };
            
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-${icons[type]} me-2" style="font-size: 1.2rem;"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideInRight 0.5s ease-out reverse';
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 3000);
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Initial animation check
            animateOnScroll();
            
            // Animate counters when stats section is visible
            const statsSection = document.querySelector('.stats-section');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            if (statsSection) {
                observer.observe(statsSection);
            }
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = this.getAttribute('href');
                    if (target !== '#') {
                        smoothScroll(target);
                    }
                });
            });
        });
        
        // Scroll event listener
        window.addEventListener('scroll', animateOnScroll);
        
        // Add CSS animations
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
        
        // Welcome message
        setTimeout(() => {
            showToast('🌈 به شادینو خوش آمدید! آماده برای تجربه‌ای شاد؟', 'success');
        }, 2000);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9727a537f145dcc0',t:'MTc1NTc1MzI1MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
