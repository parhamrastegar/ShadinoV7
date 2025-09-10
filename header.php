<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شادینو - هدر ارتقا یافته</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        .site-header {
            background: linear-gradient(135deg, #FF69B4, #FFB6C1, #9B59B6);
            color: #fff;
            padding: 1rem 0;
            box-shadow: 0 8px 32px rgba(255, 105, 180, 0.3);
            font-family: Vazirmatn, Tahoma, sans-serif;
            direction: rtl;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .site-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="sparkles" x="0" y="0" width="25" height="25" patternUnits="userSpaceOnUse"><text x="12.5" y="18" text-anchor="middle" font-size="12" fill="rgba(255,255,255,0.1)">✨</text></pattern></defs><rect width="100" height="100" fill="url(%23sparkles)"/></svg>');
            opacity: 0.3;
            animation: sparkleMove 20s linear infinite;
        }

        .site-header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 10;
        }

        .site-header .logo {
            color: #fff;
            font-size: 2rem;
            text-decoration: none;
            font-weight: 900;
            letter-spacing: 2px;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .site-header .logo::before {
            content: '🌈';
            font-size: 1.8rem;
            animation: logoFloat 3s ease-in-out infinite;
        }

        .site-header .logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.5));
        }

        .main-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 50px;
            padding: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .main-nav li {
            position: relative;
        }

        .main-nav a {
            color: #fff;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: block;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .main-nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .main-nav a:hover::before {
            left: 100%;
        }

        .main-nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        .main-nav a:active {
            transform: translateY(0);
        }

        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 0.75rem;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .mobile-menu-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes sparkleMove {
            0% { transform: translateX(-50px); }
            100% { transform: translateX(50px); }
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-5px) rotate(10deg); }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .site-header .container {
                padding: 0 1rem;
            }
            
            .site-header .logo {
                font-size: 1.8rem;
            }
            
            .main-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, rgba(255, 105, 180, 0.95), rgba(155, 89, 182, 0.95));
                backdrop-filter: blur(20px);
                border-top: 1px solid rgba(255, 255, 255, 0.2);
                animation: slideDown 0.3s ease-out;
            }
            
            .main-nav.active {
                display: block;
            }
            
            .main-nav ul {
                flex-direction: column;
                gap: 0;
                background: transparent;
                border-radius: 0;
                padding: 1rem;
                border: none;
            }
            
            .main-nav a {
                padding: 1rem 1.5rem;
                border-radius: 12px;
                margin-bottom: 0.5rem;
                border: 1px solid rgba(255, 255, 255, 0.1);
                background: rgba(255, 255, 255, 0.05);
            }
            
            .main-nav a:hover {
                background: rgba(255, 255, 255, 0.15);
                transform: translateX(-5px);
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }

        @media (max-width: 480px) {
            .site-header {
                padding: 0.75rem 0;
            }
            
            .site-header .container {
                padding: 0 1rem;
            }
            
            .site-header .logo {
                font-size: 1.5rem;
            }
            
            .site-header .logo::before {
                font-size: 1.3rem;
            }
        }

        /* Additional hover effects */
        .main-nav li:hover {
            transform: scale(1.02);
        }

        /* Focus states for accessibility */
        .main-nav a:focus {
            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 2px;
        }

        .mobile-menu-btn:focus {
            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="index.php" class="logo">شادینو</a>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="shop.php">فروشگاه</a></li>
                    <li><a href="about.php">درباره ما</a></li>
                    <li><a href="login.php">ورود</a></li>
                    <li><a href="allads.php">همه آگهی ها</a></li>
                    <li><a href="profile.php">پروفایل</a></li>
                    <li><a href="chat.php">چت</a></li>
                    <li><a href="makeads.php">ساخت آگهی</a></li>
                    
                </ul>
            </nav>
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <span class="menu-icon">☰</span>
            </button>
        </div>
    </header>

    <script>
        function toggleMobileMenu() {
            const nav = document.getElementById('mainNav');
            const btn = document.querySelector('.mobile-menu-btn');
            
            nav.classList.toggle('active');
            
            // Change icon
            const icon = btn.querySelector('.menu-icon');
            if (nav.classList.contains('active')) {
                icon.textContent = '✕';
            } else {
                icon.textContent = '☰';
            }
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('mainNav');
            const btn = document.querySelector('.mobile-menu-btn');
            
            if (!nav.contains(event.target) && !btn.contains(event.target)) {
                nav.classList.remove('active');
                btn.querySelector('.menu-icon').textContent = '☰';
            }
        });

        // Close mobile menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const nav = document.getElementById('mainNav');
                const btn = document.querySelector('.mobile-menu-btn');
                nav.classList.remove('active');
                btn.querySelector('.menu-icon').textContent = '☰';
            }
        });

        // Add smooth scroll effect
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9727abc77716dcc0',t:'MTc1NTc1MzUxOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
