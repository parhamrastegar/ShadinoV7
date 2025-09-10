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
        
        .gradient-bg {
            background: linear-gradient(135deg, #FFB6C1 0%, #FFC0CB 50%, #FFCCCB 100%);
        }
        
        .role-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .role-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .role-option.selected {
            border-color: #FF69B4 !important;
            background-color: #FFE4E1 !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 105, 180, 0.3);
        }
        
        .role-option input[type="radio"] {
            width: 20px;
            height: 20px;
            accent-color: #FF69B4;
        }
        
        .hidden-field {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.5s ease;
        }
        
        .visible-field {
            max-height: 500px;
            opacity: 1;
            transition: all 0.5s ease;
        }
        
        .loading-spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #FF69B4;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error-input {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }
        
        .success-input {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Main Content -->
    <div class="min-h-screen gradient-bg flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <!-- Welcome Section -->
            <div class="text-center mb-8">
                <div class="text-6xl mb-4">👋</div>
                <h1 class="text-4xl font-bold text-white mb-2">خوش آمدید!</h1>
                <p class="text-white/80 text-lg">به خانواده شادینو بپیوندید</p>
            </div>

            <!-- Auth Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 border-4 border-pink-100">
                <!-- Tab Buttons -->
                <div class="flex mb-8 bg-gray-100 rounded-2xl p-1">
                    <button onclick="switchTab('login')" id="login-tab" class="flex-1 py-3 px-4 rounded-xl font-bold transition-all duration-300 bg-gradient-to-r from-pink-400 to-pink-500 text-white">
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
                        <div class="text-4xl mb-2">🔑</div>
                        <h2 class="text-2xl font-bold text-gray-800">ورود به حساب</h2>
                        <p class="text-gray-600">اطلاعات ورود خود را وارد کنید</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">شماره موبایل</label>
                            <input type="tel" id="login-mobile" placeholder="09123456789" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent text-lg"
                                   maxlength="11">
                            <div id="login-mobile-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">رمز عبور</label>
                            <input type="password" id="login-password" placeholder="رمز عبور خود را وارد کنید" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent text-lg">
                            <div id="login-password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <button onclick="loginUser()" id="login-btn" 
                                class="w-full bg-gradient-to-r from-pink-400 to-pink-500 text-white py-4 rounded-xl font-bold text-lg hover:from-pink-500 hover:to-pink-600 transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="text-xl ml-2">🚀</span>
                            ورود
                        </button>
                    </div>
                </div>

                <!-- Signup Form -->
                <div id="signup-form" class="space-y-6 hidden">
                    <div class="text-center mb-6">
                        <div class="text-4xl mb-2">🌟</div>
                        <h2 class="text-2xl font-bold text-gray-800">عضویت در شادینو</h2>
                        <p class="text-gray-600">اطلاعات خود را وارد کنید</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">نام و نام خانوادگی</label>
                            <input type="text" id="signup-name" placeholder="نام کامل خود را وارد کنید" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent text-lg">
                            <div id="signup-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">شماره موبایل</label>
                            <input type="tel" id="signup-mobile" placeholder="09123456789" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent text-lg"
                                   maxlength="11">
                            <div id="signup-mobile-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">رمز عبور</label>
                            <input type="password" id="signup-password" placeholder="حداقل ۶ کاراکتر" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent text-lg"
                                   minlength="6">
                            <div id="signup-password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">تکرار رمز عبور</label>
                            <input type="password" id="signup-password-confirm" placeholder="رمز عبور را تکرار کنید" 
                                   class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent text-lg"
                                   minlength="6">
                            <div id="signup-password-confirm-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">شهر محل سکونت</label>
                            <select id="signup-city" class="w-full px-4 py-4 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent text-lg">
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

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">نوع حساب کاربری</label>
                            <div class="space-y-3">
                                <div class="role-option flex items-center p-4 border-2 border-pink-200 rounded-xl" onclick="selectRole('customer')">
                                    <input type="radio" name="account-type" value="customer" id="role-customer" class="ml-3" checked>
                                    <div class="flex items-center">
                                        <span class="text-2xl ml-3">🛍️</span>
                                        <div>
                                            <div class="font-bold text-gray-800">مشتری</div>
                                            <div class="text-gray-600 text-sm">جستجو و سفارش خدمات</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="role-option flex items-center p-4 border-2 border-pink-200 rounded-xl" onclick="selectRole('business')">
                                    <input type="radio" name="account-type" value="business" id="role-business" class="ml-3">
                                    <div class="flex items-center">
                                        <span class="text-2xl ml-3">🏪</span>
                                        <div>
                                            <div class="font-bold text-gray-800">کسب‌وکار</div>
                                            <div class="text-gray-600 text-sm">ارائه خدمات و فروش</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="role-option flex items-center p-4 border-2 border-pink-200 rounded-xl" onclick="selectRole('delivery')">
                                    <input type="radio" name="account-type" value="delivery" id="role-delivery" class="ml-3">
                                    <div class="flex items-center">
                                        <span class="text-2xl ml-3">🏍️</span>
                                        <div>
                                            <div class="font-bold text-gray-800">پیک</div>
                                            <div class="text-gray-600 text-sm">حمل و نقل سفارشات</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Additional Fields -->
                        <div id="business-fields" class="hidden-field">
                            <div class="space-y-4 p-4 bg-pink-50 rounded-xl border-2 border-pink-200">
                                <div class="text-center">
                                    <span class="text-2xl">🏪</span>
                                    <h3 class="font-bold text-gray-800 mt-2">اطلاعات کسب‌وکار</h3>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">نام فروشگاه/کسب‌وکار</label>
                                    <input type="text" id="business-name" placeholder="نام کسب‌وکار خود را وارد کنید" 
                                           class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent">
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
                                    <span class="text-2xl">🏍️</span>
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
                            <span class="text-xl ml-2">✨</span>
                            ثبت‌نام
                        </button>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="success-message" class="text-center space-y-6 hidden">
                    <div class="text-6xl mb-4">🎉</div>
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
        let selectedRole = 'customer';

        // Switch between login and signup tabs
        function switchTab(tab) {
            currentForm = tab;
            
            // Update tab buttons
            document.getElementById('login-tab').classList.remove('bg-gradient-to-r', 'from-pink-400', 'to-pink-500', 'text-white');
            document.getElementById('login-tab').classList.add('text-gray-600', 'hover:text-pink-400');
            document.getElementById('signup-tab').classList.remove('bg-gradient-to-r', 'from-pink-400', 'to-pink-500', 'text-white');
            document.getElementById('signup-tab').classList.add('text-gray-600', 'hover:text-pink-400');
            
            if (tab === 'login') {
                document.getElementById('login-tab').classList.add('bg-gradient-to-r', 'from-pink-400', 'to-pink-500', 'text-white');
                document.getElementById('login-tab').classList.remove('text-gray-600', 'hover:text-pink-400');
                document.getElementById('login-form').classList.remove('hidden');
                document.getElementById('signup-form').classList.add('hidden');
            } else {
                document.getElementById('signup-tab').classList.add('bg-gradient-to-r', 'from-pink-400', 'to-pink-500', 'text-white');
                document.getElementById('signup-tab').classList.remove('text-gray-600', 'hover:text-pink-400');
                document.getElementById('signup-form').classList.remove('hidden');
                document.getElementById('login-form').classList.add('hidden');
            }
            
            // Reset forms
            resetForms();
        }

        // Select role function
        function selectRole(role) {
            console.log('Selecting role:', role);
            selectedRole = role;
            
            // Uncheck all radio buttons
            document.querySelectorAll('input[name="account-type"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Uncheck all role options
            document.querySelectorAll('.role-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Check selected radio button
            const selectedRadio = document.getElementById('role-' + role);
            if (selectedRadio) {
                selectedRadio.checked = true;
                selectedRadio.closest('.role-option').classList.add('selected');
            }
            
            // Show/hide additional fields
            const businessFields = document.getElementById('business-fields');
            const deliveryFields = document.getElementById('delivery-fields');
            
            businessFields.classList.remove('visible-field');
            businessFields.classList.add('hidden-field');
            deliveryFields.classList.remove('visible-field');
            deliveryFields.classList.add('hidden-field');
            
            if (role === 'business') {
                setTimeout(() => {
                    businessFields.classList.remove('hidden-field');
                    businessFields.classList.add('visible-field');
                }, 100);
            } else if (role === 'delivery') {
                setTimeout(() => {
                    deliveryFields.classList.remove('hidden-field');
                    deliveryFields.classList.add('visible-field');
                }, 100);
            }
            
            console.log('Role selected successfully:', role);
        }

        // Validate mobile number
        function validateMobile(mobile) {
            if (!mobile || mobile.length === 0) {
                return false;
            }
            
            if (mobile.length < 11) {
                return false;
            }
            
            if (!mobile.startsWith('09')) {
                return false;
            }
            
            return true;
        }

        // Show error
        function showError(inputId, message) {
            const input = document.getElementById(inputId);
            const errorElement = document.getElementById(inputId + '-error');
            
            input.classList.add('error-input');
            input.classList.remove('success-input');
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }

        // Show success
        function showSuccess(inputId) {
            const input = document.getElementById(inputId);
            const errorElement = document.getElementById(inputId + '-error');
            
            input.classList.add('success-input');
            input.classList.remove('error-input');
            errorElement.classList.add('hidden');
        }

        // Hide error
        function hideError(inputId) {
            const input = document.getElementById(inputId);
            const errorElement = document.getElementById(inputId + '-error');
            
            input.classList.remove('error-input', 'success-input');
            errorElement.classList.add('hidden');
        }

        // Login user
        function loginUser() {
            const mobileInput = document.getElementById('login-mobile');
            const passwordInput = document.getElementById('login-password');
            const button = document.getElementById('login-btn');
            
            // Validate inputs
            if (!validateMobile(mobileInput.value.trim())) {
                showError('login-mobile', 'شماره موبایل نامعتبر است');
                mobileInput.focus();
                return;
            }
            
            if (!passwordInput.value.trim()) {
                showError('login-password', 'رمز عبور الزامی است');
                passwordInput.focus();
                return;
            }
            
            // Show loading
            button.innerHTML = '<div class="loading-spinner"></div>';
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
                button.innerHTML = '<span class="text-xl ml-2">🚀</span> ورود';
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
            if (!nameInput.value.trim()) {
                showError('signup-name', 'نام الزامی است');
                nameInput.focus();
                return;
            }
            
            if (!validateMobile(mobileInput.value.trim())) {
                showError('signup-mobile', 'شماره موبایل نامعتبر است');
                mobileInput.focus();
                return;
            }
            
            if (!passwordInput.value.trim()) {
                showError('signup-password', 'رمز عبور الزامی است');
                passwordInput.focus();
                return;
            }
            
            if (passwordInput.value.length < 6) {
                showError('signup-password', 'رمز عبور باید حداقل ۶ کاراکتر باشد');
                passwordInput.focus();
                return;
            }
            
            if (passwordInput.value !== passwordConfirmInput.value) {
                showError('signup-password-confirm', 'رمز عبور و تکرار آن یکسان نیستند');
                passwordConfirmInput.focus();
                return;
            }

            if (!cityInput.value) {
                showError('signup-city', 'شهر محل سکونت الزامی است');
                cityInput.focus();
                return;
            }
            
            console.log('Selected role:', selectedRole);
            
            // Show loading
            button.innerHTML = '<div class="loading-spinner"></div>';
            button.disabled = true;
            
            // Prepare registration data
            const registrationData = {
                name: nameInput.value.trim(),
                mobile: mobileInput.value.trim(),
                password: passwordInput.value,
                role: selectedRole,
                city: cityInput.value
            };
            
            // Add role-specific data
            if (selectedRole === 'business') {
                const businessName = document.getElementById('business-name');
                const businessCategory = document.getElementById('business-category');
                if (businessName && businessName.value.trim()) {
                    registrationData.business_name = businessName.value.trim();
                }
                if (businessCategory && businessCategory.value) {
                    registrationData.business_category = businessCategory.value;
                }
            } else if (selectedRole === 'delivery') {
                const vehicleType = document.getElementById('vehicle-type');
                const deliveryCity = document.getElementById('delivery-city');
                if (vehicleType && vehicleType.value) {
                    registrationData.vehicle_type = vehicleType.value;
                }
                if (deliveryCity && deliveryCity.value) {
                    registrationData.delivery_city = deliveryCity.value;
                }
            }
            
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
                button.innerHTML = '<span class="text-xl ml-2">✨</span> ثبت‌نام';
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
            
            // Reset role selection
            selectRole('customer');
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded');
            
            // Set default role
            selectRole('customer');
            
            // Add event listeners for mobile validation
            document.getElementById('signup-mobile').addEventListener('input', function() {
                const mobile = this.value.replace(/\D/g, '');
                this.value = mobile;
                
                if (mobile.length === 11 && mobile.startsWith('09')) {
                    showSuccess('signup-mobile');
                } else if (mobile.length > 0) {
                    showError('signup-mobile', 'شماره موبایل نامعتبر است');
                } else {
                    hideError('signup-mobile');
                }
            });
            
            document.getElementById('login-mobile').addEventListener('input', function() {
                const mobile = this.value.replace(/\D/g, '');
                this.value = mobile;
            });
        });
    </script>
</body>
</html>
