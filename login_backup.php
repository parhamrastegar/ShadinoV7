<?php
include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود/ثبت‌نام - شادینو</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Vazirmatn', sans-serif;
        }
        
        .loading-spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #FFC1CC;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
        
        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                max-height: 500px;
                transform: translateY(0);
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 1;
                max-height: 500px;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                max-height: 0;
                transform: translateY(-20px);
            }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
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
        
        @keyframes heartbeat {
            0% {
                transform: scale(1);
            }
            14% {
                transform: scale(1.3);
            }
            28% {
                transform: scale(1);
            }
            42% {
                transform: scale(1.3);
            }
            70% {
                transform: scale(1);
            }
        }
        
        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateX(-10px);
            }
            20%, 40%, 60%, 80% {
                transform: translateX(10px);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .slide-down {
            animation: slideDown 0.5s ease-out forwards;
        }
        
        .slide-up {
            animation: slideUp 0.3s ease-out forwards;
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
        
        .wiggle {
            animation: wiggle 2s ease-in-out infinite;
        }
        
        .sparkle {
            animation: sparkle 1.5s ease-in-out infinite;
        }
        
        .heartbeat {
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
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
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(255, 105, 180, 0.3);
        }
        
        .button-loading {
            position: relative;
            color: transparent;
        }
        
        .button-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }
        
        .error-input {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }
        
        .success-input {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
        }
        
        .hidden-field {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.5s ease;
        }
        
        .visible-field {
            max-height: 200px;
            opacity: 1;
            transition: all 0.5s ease;
        }
        
        .tab-active {
            background: linear-gradient(135deg, #FF69B4, #FFB6C1);
            color: white;
            transform: scale(1.05);
        }
        
        .verification-digit {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .account-type-option:hover {
            border-color: var(--primary-color);
            background: var(--light-pink);
        }

        /* Responsive Design Improvements */
        @media (max-width: 768px) {
            .min-h-screen {
                min-height: 100vh;
                padding: 1rem;
            }
            
            .py-12 {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }
            
            .px-4 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .max-w-md {
                max-width: 100%;
            }
            
            .text-8xl {
                font-size: 4rem;
            }
            
            .text-4xl {
                font-size: 2rem;
            }
            
            .text-2xl {
                font-size: 1.5rem;
            }
            
            .text-xl {
                font-size: 1.25rem;
            }
            
            .text-lg {
                font-size: 1rem;
            }
            
            .mb-8 {
                margin-bottom: 2rem;
            }
            
            .mb-6 {
                margin-bottom: 1.5rem;
            }
            
            .mb-4 {
                margin-bottom: 1rem;
            }
            
            .mb-2 {
                margin-bottom: 0.5rem;
            }
            
            .p-8 {
                padding: 2rem;
            }
            
            .p-4 {
                padding: 1rem;
            }
            
            .p-1 {
                padding: 0.25rem;
            }
            
            .py-3 {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
            
            .py-4 {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            
            .px-4 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .space-y-6 > * + * {
                margin-top: 1.5rem;
            }
            
            .space-y-4 > * + * {
                margin-top: 1rem;
            }
            
            .space-y-3 > * + * {
                margin-top: 0.75rem;
            }
            
            .gap-3 {
                gap: 0.75rem;
            }
            
            .rounded-3xl {
                border-radius: 1.5rem;
            }
            
            .rounded-2xl {
                border-radius: 1rem;
            }
            
            .rounded-xl {
                border-radius: 0.75rem;
            }
            
            .border-4 {
                border-width: 2px;
            }
            
            .border-2 {
                border-width: 1px;
            }
            
            .w-full {
                width: 100%;
            }
            
            .flex-1 {
                flex: 1 1 100%;
            }
            
            .flex {
                flex-direction: column;
            }
            
            .grid-cols-1 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }

        @media (max-width: 576px) {
            .min-h-screen {
                padding: 0.5rem;
            }
            
            .py-12 {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            
            .px-4 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            .text-8xl {
                font-size: 3rem;
            }
            
            .text-4xl {
                font-size: 1.75rem;
            }
            
            .text-2xl {
                font-size: 1.25rem;
            }
            
            .text-xl {
                font-size: 1.1rem;
            }
            
            .text-lg {
                font-size: 0.9rem;
            }
            
            .mb-8 {
                margin-bottom: 1.5rem;
            }
            
            .mb-6 {
                margin-bottom: 1rem;
            }
            
            .mb-4 {
                margin-bottom: 0.75rem;
            }
            
            .mb-2 {
                margin-bottom: 0.4rem;
            }
            
            .p-8 {
                padding: 1.5rem;
            }
            
            .p-4 {
                padding: 0.75rem;
            }
            
            .p-1 {
                padding: 0.2rem;
            }
            
            .py-3 {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
            
            .py-4 {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
            
            .px-4 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .space-y-6 > * + * {
                margin-top: 1rem;
            }
            
            .space-y-4 > * + * {
                margin-top: 0.75rem;
            }
            
            .space-y-3 > * + * {
                margin-top: 0.5rem;
            }
            
            .gap-3 {
                gap: 0.5rem;
            }
            
            .rounded-3xl {
                border-radius: 1rem;
            }
            
            .rounded-2xl {
                border-radius: 0.75rem;
            }
            
            .rounded-xl {
                border-radius: 0.5rem;
            }
            
            .border-4 {
                border-width: 1px;
            }
            
            .border-2 {
                border-width: 1px;
            }
            
            .flex {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .flex-1 {
                flex: 1 1 100%;
            }
            
            .grid-cols-1 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }

        /* Text wrapping and overflow fixes */
        .text-wrap {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }
        
        .auth-card {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .account-type-option {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .account-type-option > div > div {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .form-label {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .input-placeholder {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Ensure all text containers handle overflow properly */
        h1, h2, h3, h4, h5, h6, p, span, div, label, button, input, textarea, select {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Radio button styles */
        input[type="radio"] {
            width: 20px;
            height: 20px;
            margin-left: 10px;
            cursor: pointer;
            accent-color: #FF69B4;
        }
        
        input[type="radio"]:checked {
            accent-color: #FF1493;
        }
        
        /* Ensure radio buttons are visible and clickable */
        .account-type-option input[type="radio"] {
            position: relative;
            z-index: 10;
            opacity: 1;
            pointer-events: auto;
        }
        
        /* Fix for long words and URLs */
        * {
            word-break: break-word;
            overflow-wrap: break-word;
        }
        
        /* Specific fixes for flexbox items */
        .flex > * {
            min-width: 0;
        }
        
        .grid > * {
            min-width: 0;
        }
        
        /* Button text wrapping */
        button {
            white-space: normal;
            text-align: center;
        }
        
        /* Input text wrapping */
        input, textarea, select {
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-size: 16px;
        }
        
        /* Card content overflow */
        .auth-card {
            overflow: hidden;
        }
        
        .auth-card > * {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Account type option overflow */
        .account-type-option {
            overflow: hidden;
        }
        
        .account-type-option > * {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    

    <!-- Main Content -->
    <div class="min-h-screen gradient-bg flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <!-- Welcome Section -->
            <div class="text-center mb-8 fade-in-up">
                <div class="text-8xl mb-4 wiggle">👋</div>
                <h1 class="text-4xl font-bold text-white mb-2">خوش آمدید!</h1>
                <p class="text-white/80 text-lg">به خانواده شادینو بپیوندید</p>
            </div>

            <!-- Auth Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 card-hover border-4 border-pink-100">
                <!-- Tab Buttons -->
                <div class="flex mb-8 bg-gray-100 rounded-2xl p-1">
                    <button onclick="switchTab('login')" id="login-tab" class="flex-1 py-3 px-4 rounded-xl font-bold transition-all duration-300 tab-active">
                        <span class="text-lg ml-2">🔑</span>
                        ورود
                    </button>
                    <button onclick="switchTab('signup')" id="signup-tab" class="flex-1 py-3 px-4 rounded-xl font-bold transition-all duration-300 text-gray-600 hover:text-pink-400">
                        <span class="text-lg ml-2">✨</span>
                        ثبت‌نام
                    </button>
                </div>

                <!-- Login Form -->
                <div id="login-form" class="space-y-6">
                    <div class="text-center mb-6">
                        <div class="text-4xl mb-2 bounce">🔑</div>
                        <h2 class="text-2xl font-bold text-gray-800">ورود به حساب</h2>
                        <p class="text-gray-600">اطلاعات ورود خود را وارد کنید</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">شماره موبایل</label>
                            <input type="tel" id="login-mobile" placeholder="09123456789" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent input-focus text-lg"
                                   maxlength="11" oninput="validateMobile(this, 'login')">
                            <div id="login-mobile-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">رمز عبور</label>
                            <input type="password" id="login-password" placeholder="رمز عبور خود را وارد کنید" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent input-focus text-lg">
                            <div id="login-password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <button onclick="loginUser()" id="login-btn" 
                                class="w-full bg-gradient-to-r from-pink-400 to-pink-500 text-white py-4 rounded-xl font-bold text-lg hover:from-pink-500 hover:to-pink-600 transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="text-xl ml-2 sparkle">🚀</span>
                            ورود
                        </button>
                    </div>
                </div>

                <!-- Signup Form -->
                <div id="signup-form" class="space-y-6 hidden">
                    <div class="text-center mb-6">
                        <div class="text-4xl mb-2 heartbeat">🌟</div>
                        <h2 class="text-2xl font-bold text-gray-800">عضویت در شادینو</h2>
                        <p class="text-gray-600">اطلاعات خود را وارد کنید</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">نام و نام خانوادگی</label>
                            <input type="text" id="signup-name" placeholder="نام کامل خود را وارد کنید" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent input-focus text-lg"
                                   oninput="validateName(this)">
                            <div id="signup-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">شماره موبایل</label>
                            <input type="tel" id="signup-mobile" placeholder="09123456789" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent input-focus text-lg"
                                   maxlength="11" oninput="validateMobile(this, 'signup')">
                            <div id="signup-mobile-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">رمز عبور</label>
                            <input type="password" id="signup-password" placeholder="حداقل ۶ کاراکتر" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent input-focus text-lg"
                                   minlength="6">
                            <div id="signup-password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">تکرار رمز عبور</label>
                            <input type="password" id="signup-password-confirm" placeholder="رمز عبور را تکرار کنید" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent input-focus text-lg"
                                   minlength="6">
                            <div id="signup-password-confirm-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">شهر محل سکونت</label>
                            <select id="signup-city" class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent input-focus text-lg">
                                <option value="">انتخاب کنید</option>
                                <option value="tehran">تهران</option>
                                <option value="isfahan">اصفهان</option>
                                <option value="shiraz">شیراز</option>
                                <option value="mashhad">مشهد</option>
                                <option value="karaj">کرج</option>
                                <option value="tabriz">تبریز</option>
                                <option value="yazd">یزد</option>
                                <option value="kerman">کرمان</option>
                                <option value="ahvaz">اهواز</option>
                                <option value="qom">قم</option>
                                <option value="kermanshah">کرمانشاه</option>
                                <option value="urmia">ارومیه</option>
                                <option value="rasht">رشت</option>
                                <option value="bandar-abbas">بندرعباس</option>
                                <option value="other">سایر شهرها</option>
                            </select>
                            <div id="signup-city-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">نوع حساب کاربری</label>
                            <div class="grid grid-cols-1 gap-3">
                                <label class="flex items-center p-4 border-2 border-pink-200 rounded-xl cursor-pointer hover:bg-pink-50 transition-all duration-300 account-type-option">
                                    <input type="radio" name="account-type" value="customer" id="account-customer" class="mr-3" onchange="handleAccountTypeChange(this)" checked>
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3 bounce">🛍️</span>
                                        <div>
                                            <div class="font-bold text-gray-800">مشتری</div>
                                            <div class="text-gray-600 text-sm">جستجو و سفارش خدمات</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-pink-200 rounded-xl cursor-pointer hover:bg-pink-50 transition-all duration-300 account-type-option">
                                    <input type="radio" name="account-type" value="business" id="account-business" class="mr-3" onchange="handleAccountTypeChange(this)">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3 pulse">🏪</span>
                                        <div>
                                            <div class="font-bold text-gray-800">کسب‌وکار</div>
                                            <div class="text-gray-600 text-sm">ارائه خدمات و فروش</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-pink-200 rounded-xl cursor-pointer hover:bg-pink-50 transition-all duration-300 account-type-option">
                                    <input type="radio" name="account-type" value="delivery" id="account-delivery" class="mr-3" onchange="handleAccountTypeChange(this)">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3 wiggle">🏍️</span>
                                        <div>
                                            <div class="font-bold text-gray-800">پیک</div>
                                            <div class="text-gray-600 text-sm">حمل و نقل سفارشات</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Business Additional Fields -->
                        <div id="business-fields" class="hidden-field">
                            <div class="space-y-4 p-4 bg-pink-50 rounded-xl border-2 border-pink-200">
                                <div class="text-center">
                                    <span class="text-2xl float">🏪</span>
                                    <h3 class="font-bold text-gray-800 mt-2">اطلاعات کسب‌وکار</h3>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">نام فروشگاه/کسب‌وکار</label>
                                    <input type="text" id="business-name" placeholder="نام کسب‌وکار خود را وارد کنید" 
                                           class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent input-focus">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">دسته‌بندی خدمات</label>
                                    <select id="business-category" class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                                        <option value="">انتخاب کنید</option>
                                        <option value="cake">🎂 کیک و شیرینی</option>
                                        <option value="decoration">💐 گل و دکوراسیون</option>
                                        <option value="photography">📸 عکاسی و فیلمبرداری</option>
                                        <option value="music">🎵 موسیقی و DJ</option>
                                        <option value="ceremony">🎪 تشریفات کامل</option>
                                        <option value="catering">🍰 کترینگ و پذیرایی</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Additional Fields -->
                        <div id="delivery-fields" class="hidden-field">
                            <div class="space-y-4 p-4 bg-blue-50 rounded-xl border-2 border-blue-200">
                                <div class="text-center">
                                    <span class="text-2xl wiggle">🏍️</span>
                                    <h3 class="font-bold text-gray-800 mt-2">اطلاعات پیک</h3>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">نوع وسیله نقلیه</label>
                                    <select id="vehicle-type" class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                                        <option value="">انتخاب کنید</option>
                                        <option value="motorcycle">🏍️ موتورسیکلت</option>
                                        <option value="bicycle">🚲 دوچرخه</option>
                                        <option value="car">🚗 خودرو</option>
                                        <option value="van">🚐 ون</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">شهر فعالیت</label>
                                    <select id="delivery-city" class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                                        <option value="">انتخاب کنید</option>
                                        <option value="tehran">تهران</option>
                                        <option value="isfahan">اصفهان</option>
                                        <option value="shiraz">شیراز</option>
                                        <option value="mashhad">مشهد</option>
                                        <option value="karaj">کرج</option>
                                        <option value="tabriz">تبریز</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button onclick="registerUser()" id="signup-btn" 
                                class="w-full bg-gradient-to-r from-pink-400 to-pink-500 text-white py-4 rounded-xl font-bold text-lg hover:from-pink-500 hover:to-pink-600 transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="text-xl ml-2 sparkle">✨</span>
                            ثبت‌نام
                        </button>
                    </div>
                </div>



                <!-- Success Message -->
                <div id="success-message" class="text-center space-y-6 hidden">
                    <div class="text-8xl mb-4 bounce">🎉</div>
                    <h2 class="text-3xl font-bold text-gray-800">خوش آمدید!</h2>
                    <p class="text-gray-600 text-lg">حساب شما با موفقیت ایجاد شد</p>
                    <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4">
                        <p class="text-green-700 font-bold">در حال انتقال به داشبورد...</p>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-8 text-white/80">
                <p class="mb-2">با عضویت در شادینو، <a href="#" class="text-white font-bold hover:underline">قوانین و مقررات</a> را می‌پذیرید</p>
                <p>نیاز به کمک دارید؟ <a href="#" class="text-white font-bold hover:underline">تماس با پشتیبانی</a></p>
            </div>
        </div>
    </div>

    <script>
        let currentForm = 'login';

        // Switch between login and signup tabs
        function switchTab(tab) {
            currentForm = tab;
            
            // Update tab buttons
            document.getElementById('login-tab').classList.remove('tab-active');
            document.getElementById('signup-tab').classList.remove('tab-active');
            document.getElementById('login-tab').classList.add('text-gray-600', 'hover:text-pink-400');
            document.getElementById('signup-tab').classList.add('text-gray-600', 'hover:text-pink-400');
            
            if (tab === 'login') {
                document.getElementById('login-tab').classList.add('tab-active');
                document.getElementById('login-tab').classList.remove('text-gray-600', 'hover:text-pink-400');
                document.getElementById('login-form').classList.remove('hidden');
                document.getElementById('signup-form').classList.add('hidden');
            } else {
                document.getElementById('signup-tab').classList.add('tab-active');
                document.getElementById('signup-tab').classList.remove('text-gray-600', 'hover:text-pink-400');
                document.getElementById('signup-form').classList.remove('hidden');
                document.getElementById('login-form').classList.add('hidden');
            }
            
            // Reset forms
            resetForms();
        }

        // Validate mobile number
        function validateMobile(input, formType) {
            const mobile = input.value;
            const errorElement = document.getElementById(formType + '-mobile-error');
            
            // Remove non-digits
            input.value = mobile.replace(/\D/g, '');
            
            // Validation
            if (input.value.length === 0) {
                hideError(input, errorElement);
                return false;
            }
            
            if (input.value.length < 11) {
                showError(input, errorElement, 'شماره موبایل باید ۱۱ رقم باشد');
                return false;
            }
            
            if (!input.value.startsWith('09')) {
                showError(input, errorElement, 'شماره موبایل باید با ۰۹ شروع شود');
                return false;
            }
            
            if (input.value.length === 11) {
                showSuccess(input, errorElement);
                return true;
            }
            
            return false;
        }

        // Validate name
        function validateName(input) {
            const name = input.value.trim();
            const errorElement = document.getElementById('signup-name-error');
            
            if (name.length === 0) {
                hideError(input, errorElement);
                return false;
            }
            
            if (name.length < 2) {
                showError(input, errorElement, 'نام باید حداقل ۲ کاراکتر باشد');
                return false;
            }
            
            if (name.length >= 2) {
                showSuccess(input, errorElement);
                return true;
            }
            
            return false;
        }

        // Show error
        function showError(input, errorElement, message) {
            input.classList.add('error-input', 'shake');
            input.classList.remove('success-input');
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            
            setTimeout(() => {
                input.classList.remove('shake');
            }, 500);
        }

        // Show success
        function showSuccess(input, errorElement) {
            input.classList.add('success-input');
            input.classList.remove('error-input');
            errorElement.classList.add('hidden');
        }

        // Hide error
        function hideError(input, errorElement) {
            input.classList.remove('error-input', 'success-input');
            errorElement.classList.add('hidden');
        }


        // Handle account type change
        function handleAccountTypeChange(radio) {
            const businessFields = document.getElementById('business-fields');
            const deliveryFields = document.getElementById('delivery-fields');
            
            // Debug log
            console.log('Account type changed to:', radio.value);
            
            // Reset all fields
            businessFields.classList.remove('visible-field');
            businessFields.classList.add('hidden-field');
            deliveryFields.classList.remove('visible-field');
            deliveryFields.classList.add('hidden-field');
            
            // Update option styles
            document.querySelectorAll('.account-type-option').forEach(option => {
                option.classList.remove('border-pink-400', 'bg-pink-50');
                option.classList.add('border-pink-200');
            });
            
            // Highlight selected option
            radio.closest('.account-type-option').classList.add('border-pink-400', 'bg-pink-50');
            radio.closest('.account-type-option').classList.remove('border-pink-200');
            
            // Show relevant fields
            setTimeout(() => {
                if (radio.value === 'business') {
                    businessFields.classList.remove('hidden-field');
                    businessFields.classList.add('visible-field');
                } else if (radio.value === 'delivery') {
                    deliveryFields.classList.remove('hidden-field');
                    deliveryFields.classList.add('visible-field');
                }
            }, 100);
        }

        // Login user
        function loginUser() {
            const mobileInput = document.getElementById('login-mobile');
            const passwordInput = document.getElementById('login-password');
            const button = document.getElementById('login-btn');
            
            // Validate inputs
            if (!validateMobile(mobileInput, 'login')) {
                mobileInput.focus();
                return;
            }
            
            if (!passwordInput.value.trim()) {
                showError(passwordInput, document.getElementById('login-password-error'), 'رمز عبور الزامی است');
                passwordInput.focus();
                return;
            }
            
            // Show loading
            button.classList.add('button-loading');
            button.disabled = true;
            
            // API call
            fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    mobile: mobileInput.value.trim(),
                    password: passwordInput.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage('ورود با موفقیت انجام شد');
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                showMessage('خطا در ارتباط با سرور', 'error');
            })
            .finally(() => {
                button.classList.remove('button-loading');
                button.disabled = false;
            });
        }

        // Register user
        function registerUser() {
            const nameInput = document.getElementById('signup-name');
            const mobileInput = document.getElementById('signup-mobile');
            const passwordInput = document.getElementById('signup-password');
            const passwordConfirmInput = document.getElementById('signup-password-confirm');
            const cityInput = document.getElementById('signup-city');
            const button = document.getElementById('signup-btn');
            
            // Validate inputs
            if (!validateName(nameInput)) {
                nameInput.focus();
                return;
            }
            
            if (!validateMobile(mobileInput, 'signup')) {
                mobileInput.focus();
                return;
            }
            
            if (!passwordInput.value.trim()) {
                showError(passwordInput, document.getElementById('signup-password-error'), 'رمز عبور الزامی است');
                passwordInput.focus();
                return;
            }
            
            if (passwordInput.value.length < 6) {
                showError(passwordInput, document.getElementById('signup-password-error'), 'رمز عبور باید حداقل ۶ کاراکتر باشد');
                passwordInput.focus();
                return;
            }
            
            if (passwordInput.value !== passwordConfirmInput.value) {
                showError(passwordConfirmInput, document.getElementById('signup-password-confirm-error'), 'رمز عبور و تکرار آن یکسان نیستند');
                passwordConfirmInput.focus();
                return;
            }

            if (cityInput.value === "") {
                showError(cityInput, document.getElementById('signup-city-error'), 'شهر محل سکونت الزامی است');
                cityInput.focus();
                return;
            }
            
            // Get selected role - Debug and fix
            const selectedRoleElement = document.querySelector('input[name="account-type"]:checked');
            if (!selectedRoleElement) {
                showMessage('لطفاً نوع حساب کاربری را انتخاب کنید', 'error');
                return;
            }
            
            const selectedRole = selectedRoleElement.value;
            console.log('Selected role:', selectedRole); // Debug log
            
            // Show loading
            button.classList.add('button-loading');
            button.disabled = true;
            
            // Prepare registration data
            const registrationData = {
                name: nameInput.value.trim(),
                mobile: mobileInput.value.trim(),
                password: passwordInput.value,
                role: selectedRole,
                city: cityInput.value
            };
            
            // Debug log
            console.log('Registration data:', registrationData);
            
            // API call
            fetch('api/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(registrationData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage('ثبت‌نام با موفقیت انجام شد');
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                showMessage('خطا در ارتباط با سرور', 'error');
            })
            .finally(() => {
                button.classList.remove('button-loading');
                button.disabled = false;
            });
        }

        // Show success message
        function showSuccessMessage(message) {
            document.getElementById('success-message').classList.remove('hidden');
            document.getElementById('success-message').querySelector('p').textContent = message;
        }

        // Show message helper
        function showMessage(message, type) {
            // You can implement a toast notification system here
            alert(message);
        }

        // Reset forms
        function resetForms() {
            // Clear inputs
            document.querySelectorAll('input').forEach(input => {
                input.value = '';
                input.classList.remove('error-input', 'success-input');
            });
            
            // Clear select elements
            document.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });
            
            // Clear errors
            document.querySelectorAll('[id$="-error"]').forEach(error => {
                error.classList.add('hidden');
            });
            
            // Reset account type
            const customerRadio = document.getElementById('account-customer');
            if (customerRadio) {
                customerRadio.checked = true;
                handleAccountTypeChange(customerRadio);
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Debug: Log all radio buttons
            console.log('All radio buttons:', document.querySelectorAll('input[name="account-type"]'));
            
            // Add click event listeners to all radio buttons for debugging
            document.querySelectorAll('input[name="account-type"]').forEach(radio => {
                radio.addEventListener('click', function() {
                    console.log('Radio clicked:', this.value);
                });
                
                radio.addEventListener('change', function() {
                    console.log('Radio changed:', this.value);
                });
            });
            
            // Set up Enter key submission for login
            document.getElementById('login-mobile').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('login-password').focus();
                }
            });
            
            document.getElementById('login-password').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    loginUser();
                }
            });
            
            // Set up Enter key submission for signup
            document.getElementById('signup-name').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('signup-mobile').focus();
                }
            });
            
            document.getElementById('signup-mobile').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('signup-password').focus();
                }
            });
            
            document.getElementById('signup-password').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('signup-password-confirm').focus();
                }
            });
            
            document.getElementById('signup-password-confirm').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('signup-city').focus();
                }
            });

            document.getElementById('signup-city').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    registerUser();
                }
            });
        });
    </script>
</body>
</html>
