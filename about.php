<?php
include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درباره ما - شادینو</title>
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
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100px);
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
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }
        
        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
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
        
        .sparkle {
            animation: sparkle 1.5s ease-in-out infinite;
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
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        
        .hero-section {
            background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 50%, #FFF0F5 100%);
            min-height: 80vh;
            display: flex;
            align-items: center;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .feature-card {
            background: white;
            border-radius: 25px;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: 3px solid transparent;
            background-clip: padding-box;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FF69B4, #FFB6C1, #FFC0CB);
        }
        
        .feature-card:hover::before {
            height: 100%;
            opacity: 0.05;
            transition: all 0.3s ease;
        }
        
        .team-member {
            background: white;
            border-radius: 25px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }
        
        .team-member:hover {
            transform: translateY(-10px);
            border-color: #FFB6C1;
            box-shadow: 0 25px 50px rgba(255,182,193,0.3);
        }
        
        .team-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, #FF69B4, #FFB6C1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            font-weight: bold;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #FFF0F5, #FFE4E1);
            border-radius: 25px;
            padding: 2rem;
            text-align: center;
            border: 3px solid #FFB6C1;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .contact-form {
            background: white;
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 3px solid #FFB6C1;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 1.5rem;
            border: 2px solid #FFB6C1;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #FFF0F5;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #FF69B4;
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 105, 180, 0.1);
            transform: scale(1.02);
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #FF69B4, #FFB6C1);
            color: white;
            padding: 1rem 3rem;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
                width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .submit-btn:hover::before {
            left: 100%;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 35px rgba(255, 105, 180, 0.4);
        }
        
        @media (max-width: 768px) {
            .hero-section {
                min-height: 60vh;
                text-align: center;
            }
            
            .feature-card, .team-member, .stats-card {
                margin-bottom: 2rem;
            }
            
            .contact-form {
                padding: 2rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
<?php include 'header.php';?>
    

    <!-- Hero Section -->
    <section class="hero-section relative">
        <div class="container mx-auto px-4 py-20 relative z-10">
            <div class="text-center text-white">
                <h1 class="text-6xl md:text-7xl font-bold mb-6 fade-in-up">
                    <span class="bounce">🎉</span>
                    درباره شادینو
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto leading-relaxed fade-in-up">
                    پلتفرم آنلاین برای برگزاری بهترین جشن‌ها و مراسم‌های شما
                </p>
                <div class="flex flex-wrap justify-center gap-4 fade-in-up">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-full px-6 py-3">
                        <span class="text-lg font-semibold">🏆 برترین پلتفرم</span>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-full px-6 py-3">
                        <span class="text-lg font-semibold">💎 کیفیت تضمینی</span>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-full px-6 py-3">
                        <span class="text-lg font-semibold">🚀 سرعت بالا</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="slide-in-left">
                    <h2 class="text-4xl font-bold mb-6 gradient-text">
                        <span class="sparkle">🌟</span>
                        داستان ما
                    </h2>
                    <p class="text-lg text-gray-700 leading-relaxed mb-6">
                        شادینو در سال 1404 با هدف ایجاد پلتفرمی جامع برای برگزاری مراسم‌ها و جشن‌ها تأسیس شد. 
                        ما معتقدیم که هر مراسمی باید منحصر به فرد و به یادماندنی باشد.
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed mb-6">
                        با استفاده از تکنولوژی‌های مدرن و تیم متخصص، ما تجربه‌ای بی‌نظیر در زمینه 
                        برنامه‌ریزی و اجرای مراسم‌ها ارائه می‌دهیم.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-pink-100 rounded-full px-4 py-2">
                            <span class="text-pink-600 font-semibold">🎯 هدف‌مند</span>
                </div>
                        <div class="bg-pink-100 rounded-full px-4 py-2">
                            <span class="text-pink-600 font-semibold">💡 خلاقانه</span>
                        </div>
                        <div class="bg-pink-100 rounded-full px-4 py-2">
                            <span class="text-pink-600 font-semibold">🤝 قابل اعتماد</span>
                        </div>
                    </div>
                </div>
                <div class="slide-in-right">
                    <div class="relative">
                        <div class="bg-gradient-to-br from-pink-200 to-purple-200 rounded-3xl p-8 transform rotate-3">
                            <div class="bg-white rounded-2xl p-6 shadow-lg">
                                <div class="text-center">
                                    <div class="text-6xl mb-4">🎂</div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2">جشن تولد</h3>
                                    <p class="text-gray-600">طراحی و اجرای جشن‌های تولد منحصر به فرد</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -top-4 -right-4 bg-gradient-to-br from-yellow-200 to-orange-200 rounded-2xl p-6 transform -rotate-6">
                            <div class="text-center">
                                <div class="text-4xl mb-2">💒</div>
                                <h4 class="font-bold text-gray-800">عروسی</h4>
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-gradient-to-br from-blue-200 to-green-200 rounded-2xl p-6 transform rotate-6">
                            <div class="text-center">
                                <div class="text-4xl mb-2">🎊</div>
                                <h4 class="font-bold text-gray-800">مراسم</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 gradient-text">
                    <span class="pulse">✨</span>
                    ویژگی‌های منحصر به فرد
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    شادینو با ارائه خدمات متنوع و کیفیت بالا، تجربه‌ای بی‌نظیر برای شما فراهم می‌کند
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="feature-card card-hover">
                    <div class="text-5xl mb-4 text-center">🎨</div>
                    <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">طراحی سفارشی</h3>
                    <p class="text-gray-600 text-center leading-relaxed">
                        هر مراسم با طراحی منحصر به فرد و مطابق با سلیقه شما طراحی می‌شود
                    </p>
                </div>
                
                <div class="feature-card card-hover">
                    <div class="text-5xl mb-4 text-center">🚀</div>
                    <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">اجرای سریع</h3>
                    <p class="text-gray-600 text-center leading-relaxed">
                        با تیم متخصص و تجهیزات مدرن، مراسم شما در کوتاه‌ترین زمان اجرا می‌شود
                    </p>
                </div>
                
                <div class="feature-card card-hover">
                    <div class="text-5xl mb-4 text-center">💎</div>
                    <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">کیفیت تضمینی</h3>
                    <p class="text-gray-600 text-center leading-relaxed">
                        کیفیت تمام خدمات ما تضمین شده و رضایت شما اولویت اصلی ماست
                    </p>
                </div>
                
                <div class="feature-card card-hover">
                    <div class="text-5xl mb-4 text-center">📱</div>
                    <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">پشتیبانی ۲۴/۷</h3>
                    <p class="text-gray-600 text-center leading-relaxed">
                        تیم پشتیبانی ما در تمام ساعات شبانه‌روز آماده خدمت‌رسانی به شماست
                    </p>
                </div>
                
                <div class="feature-card card-hover">
                    <div class="text-5xl mb-4 text-center">🎭</div>
                    <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">تنوع خدمات</h3>
                    <p class="text-gray-600 text-center leading-relaxed">
                        از جشن تولد تا مراسم عروسی، تمام نیازهای شما را پوشش می‌دهیم
                    </p>
                </div>
                
                <div class="feature-card card-hover">
                    <div class="text-5xl mb-4 text-center">🌟</div>
                    <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">تجربه بی‌نظیر</h3>
                    <p class="text-gray-600 text-center leading-relaxed">
                        خلق لحظات به یادماندنی و تجربه‌ای فراموش نشدنی برای شما
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-pink-50 to-purple-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="stats-card text-center">
                    <div class="text-5xl mb-4 bounce">🎉</div>
                    <div class="text-4xl font-bold text-gray-800 mb-2">۵۰۰+</div>
                    <div class="text-lg text-gray-600">مراسم موفق</div>
            </div>
            
                <div class="stats-card text-center">
                    <div class="text-5xl mb-4 pulse">👥</div>
                    <div class="text-4xl font-bold text-gray-800 mb-2">۱۰۰۰+</div>
                    <div class="text-lg text-gray-600">مشتری راضی</div>
                </div>
                
                <div class="stats-card text-center">
                    <div class="text-5xl mb-4 float">⭐</div>
                    <div class="text-4xl font-bold text-gray-800 mb-2">۴.۹</div>
                    <div class="text-lg text-gray-600">امتیاز از ۵</div>
                </div>
                
                <div class="stats-card text-center">
                    <div class="text-5xl mb-4 sparkle">🏆</div>
                    <div class="text-4xl font-bold text-gray-800 mb-2">۵۰+</div>
                    <div class="text-lg text-gray-600">جایزه کسب شده</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 gradient-text">
                    <span class="bounce">👥</span>
                    تیم متخصص ما
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    با تیمی از متخصصان مجرب و خلاق، بهترین خدمات را برای شما فراهم می‌کنیم
                    </p>
                </div>
                
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="team-member">
                    <div class="team-avatar">پ</div>
                    <h3 class="text-2xl font-bold mb-2 text-gray-800">پانیذ رستگار</h3>
                    <p class="text-pink-600 font-semibold mb-3">مدیرعامل و مؤسس</p>
                    <p class="text-gray-600 leading-relaxed">
                        
                    </p>
                </div>
                
                <div class="team-member">
                    <div class="team-avatar">س</div>
                    <h3 class="text-2xl font-bold mb-2 text-gray-800">سحر دلبری</h3>
                    <p class="text-pink-600 font-semibold mb-3"> بازار یاب و مشاور</p>
                    <p class="text-gray-600 leading-relaxed">

                    </p>
                </div>
                
                <div class="team-member">
                    <div class="team-avatar">م</div>
                    <h3 class="text-2xl font-bold mb-2 text-gray-800"> مهدیه کیوانلو</h3>
                    <p class="text-pink-600 font-semibold mb-3">مدیر فنی</p>
                    <p class="text-gray-600 leading-relaxed">

                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20 bg-gradient-to-r from-pink-50 to-purple-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 gradient-text">
                    <span class="pulse">📞</span>
                    با ما در تماس باشید
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    برای مشاوره رایگان و دریافت اطلاعات بیشتر، فرم زیر را پر کنید
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="contact-form">
                    <form id="contactForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">نام و نام خانوادگی</label>
                                <input type="text" class="form-input" placeholder="نام خود را وارد کنید" required>
                    </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">شماره تماس</label>
                                <input type="tel" class="form-input" placeholder="۰۹۱۲۳۴۵۶۷۸۹" required>
                            </div>
                </div>
                
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">ایمیل</label>
                            <input type="email" class="form-input" placeholder="example@email.com">
                    </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">موضوع</label>
                            <select class="form-input">
                                <option>انتخاب کنید</option>
                                <option>مشاوره مراسم</option>
                                <option>درخواست قیمت</option>
                                <option>همکاری</option>
                                <option>سایر</option>
                            </select>
                </div>
                
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">پیام</label>
                            <textarea class="form-input" rows="5" placeholder="پیام خود را بنویسید..." required></textarea>
                </div>
                
                        <div class="text-center">
                            <button type="submit" class="submit-btn">
                                <span class="ml-2">📤</span>
                                ارسال پیام
                            </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4 text-pink-400 pulse">🎉 شادینو</h3>
                    <p class="text-gray-300 mb-4">پلتفرم آنلاین برای برگزاری بهترین جشن‌ها و مراسم‌های شما</p>
                    <div class="flex space-x-4 space-x-reverse">
                        <a href="#" class="text-gray-400 hover:text-pink-400 transition-colors text-2xl">📱</a>
                        <a href="#" class="text-gray-400 hover:text-pink-400 transition-colors text-2xl">💬</a>
                        <a href="#" class="text-gray-400 hover:text-pink-400 transition-colors text-2xl">📧</a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4 text-lg">لینک‌های سریع</h4>
                    <ul class="space-y-3">
                        <li><a href="/about.php" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">درباره ما</a></li>
                        <li><a href="/services.php" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">خدمات</a></li>
                        <li><a href="/allAds.php" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">آگهی‌ها</a></li>
                        <li><a href="/makeAds.php" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">ثبت آگهی</a></li>
                    </ul>
            </div>
                
                <div>
                    <h4 class="font-bold mb-4 text-lg">خدمات ما</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">جشن تولد</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">مراسم عروسی</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">مراسم شرکتی</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition-colors duration-300">جشن‌های خانوادگی</a></li>
                    </ul>
        </div>
                
                <div>
                    <h4 class="font-bold mb-4 text-lg">تماس با ما</h4>
                    <div class="space-y-3 text-gray-300">
                        <p class="flex items-center">
                            <span class="ml-2">📞</span>
                            ۰۲۱-۱۲۳۴۵۶۷۸
                        </p>
                        <p class="flex items-center">
                            <span class="ml-2">📧</span>
                            info@shadino.ir
                        </p>
                        <p class="flex items-center">
                            <span class="ml-2">📍</span>
                            تهران، خیابان ولیعصر
                        </p>
                </div>
            </div>
        </div>
            
            <div class="border-t border-gray-700 mt-12 pt-8 text-center">
                <p class="text-gray-300">© ۱۴۰۴ شادینو. تمامی حقوق محفوظ است.</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Contact form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'پیام ارسال شد!',
                text: 'پیام شما با موفقیت ارسال شد. در اسرع وقت با شما تماس خواهیم گرفت.',
                confirmButtonText: 'باشه',
                confirmButtonColor: '#FF69B4'
            });
            
            // Reset form
            this.reset();
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('.feature-card, .team-member, .stats-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>
