<?php
include 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'header.php';

// Check for errors and success messages
$error_message = '';
$success_message = '';
$form_data = [];

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'validation':
            $error_message = 'خطا در اعتبارسنجی فرم. لطفاً تمام فیلدهای الزامی را پر کنید.';
            $form_data = isset($_SESSION['ad_form_data']) ? $_SESSION['ad_form_data'] : [];
            break;
        case 'invalid_category':
            $error_message = 'دسته‌بندی نامعتبر است.';
            break;
        case 'invalid_date':
            $error_message = 'فرمت تاریخ نامعتبر است.';
            break;
        case 'past_event_date':
            $error_message = 'تاریخ مراسم نمی‌تواند در گذشته باشد.';
            break;
        case 'past_deadline':
            $error_message = 'مهلت پیشنهاد نمی‌تواند در گذشته باشد.';
            break;
        case 'deadline_after_event':
            $error_message = 'مهلت پیشنهاد باید قبل از تاریخ مراسم باشد.';
            break;
        case 'database':
            $error_message = 'خطا در ایجاد آگهی. لطفاً دوباره تلاش کنید.';
            break;
        default:
            $error_message = 'خطای نامشخصی رخ داده است.';
    }
}

if (isset($_SESSION['ad_success'])) {
    $success_message = $_SESSION['ad_success'];
    unset($_SESSION['ad_success']);
}

// Clear error data after displaying
if (isset($_SESSION['ad_errors'])) {
    unset($_SESSION['ad_errors']);
}
if (isset($_SESSION['ad_form_data'])) {
    unset($_SESSION['ad_form_data']);
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نیازمندی - شادینو</title>
    
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
            --shadow-primary: 0 15px 35px rgba(255, 105, 180, 0.3);
            --shadow-secondary: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background: linear-gradient(135deg, #FFE4E1 0%, #FFF0F5 50%, #F8F9FA 100%);
            min-height: 100vh;
            overflow-x: hidden;
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
                box-shadow: 0 0 0 10px rgba(255, 105, 180, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 105, 180, 0);
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
        
        @keyframes glow {
            0% {
                box-shadow: 0 0 5px rgba(255, 105, 180, 0.5);
            }
            50% {
                box-shadow: 0 0 20px rgba(255, 105, 180, 0.8), 0 0 30px rgba(255, 105, 180, 0.6);
            }
            100% {
                box-shadow: 0 0 5px rgba(255, 105, 180, 0.5);
            }
        }
        
        @keyframes progressFill {
            from {
                width: 0%;
            }
            to {
                width: var(--progress-width);
            }
        }
        
        /* Animation Classes */
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .glow {
            animation: glow 2s ease-in-out infinite;
        }
        

        
        /* Hero Section */
        .hero-section {
            background: var(--gradient-primary);
            padding: 80px 0;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 1rem;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
        }
        
        .hero-icon {
            font-size: 4rem;
            margin-left: 1rem;
        }
        
        /* Glass Card Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: var(--shadow-primary);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(255, 105, 180, 0.4);
        }
        
        /* Main Form Container */
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 3rem;
            box-shadow: var(--shadow-primary);
            border: 2px solid var(--light-pink);
            position: relative;
            overflow: hidden;
        }
        
        .form-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 182, 193, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
        }
        
        /* Step Indicator */
        .step-indicator {
            position: relative;
            margin-bottom: 3rem;
        }
        
        .step-progress {
            height: 6px;
            background: #E9ECEF;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .step-progress-bar {
            height: 100%;
            background: var(--gradient-primary);
            border-radius: 10px;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            animation: glow 2s ease-in-out infinite;
        }
        
        .step-items {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 3px solid transparent;
        }
        
        .step-circle:hover {
            transform: scale(1.1);
        }
        
        .step-circle.completed {
            background: linear-gradient(135deg, #28A745, #20C997);
            color: white;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }
        
        .step-circle.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-primary);
            transform: scale(1.15);
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        
        .step-circle.pending {
            background: #F8F9FA;
            color: #6C757D;
            border-color: #DEE2E6;
        }
        
        .step-title {
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            color: #495057;
        }
        
        /* Form Steps */
        .form-step {
            display: none;
        }
        
        .form-step.active {
            display: block;
            animation: fadeInUp 0.6s ease-out;
        }
        
        .step-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .step-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }
        
        .step-header .step-icon {
            font-size: 3rem;
            margin-left: 1rem;
        }
        
        /* Category Cards */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .category-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid #E9ECEF;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s ease;
        }
        
        .category-card:hover::before {
            left: 100%;
        }
        
        .category-card:hover {
            border-color: var(--primary-color);
            background: rgba(255, 105, 180, 0.1);
            transform: translateY(-10px) scale(1.03);
            box-shadow: var(--shadow-primary);
            animation: wiggle 0.6s ease-in-out;
        }
        
        .category-card.selected {
            border-color: var(--primary-color);
            background: var(--gradient-secondary);
            transform: translateY(-8px) scale(1.05);
            box-shadow: var(--shadow-primary);
            animation: pulse 2s ease-in-out infinite;
        }
        
        .category-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .category-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .category-desc {
            color: #6C757D;
            font-size: 0.9rem;
        }
        
        /* Form Controls */
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            border: 2px solid #E9ECEF;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 105, 180, 0.25);
            background: white;
            transform: translateY(-2px);
        }
        
        .form-control:hover {
            border-color: var(--secondary-color);
            transform: translateY(-1px);
        }
        
        .form-control.is-invalid {
            border-color: #DC3545;
            background: rgba(220, 53, 69, 0.1);
            animation: shake 0.5s ease-in-out;
        }
        
        .form-control.is-invalid:focus {
            border-color: #DC3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        
        .form-select.is-invalid {
            border-color: #DC3545;
            background: rgba(220, 53, 69, 0.1);
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.75rem;
        }
        
        .form-select {
            border: 2px solid #E9ECEF;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }
        
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 105, 180, 0.25);
        }
        
        /* Budget Options */
        .budget-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .budget-option {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid #E9ECEF;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .budget-option:hover {
            border-color: #FFC107;
            background: rgba(255, 193, 7, 0.1);
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 30px rgba(255, 193, 7, 0.3);
            animation: bounce 0.6s ease-in-out;
        }
        
        .budget-option.selected {
            border-color: #FFC107;
            background: linear-gradient(135deg, #FFC107, #FFD60A);
            color: white;
            transform: translateY(-3px) scale(1.08);
            box-shadow: 0 12px 25px rgba(255, 193, 7, 0.4);
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        .budget-amount {
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        /* Priority Options */
        .priority-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .priority-option {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid #E9ECEF;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .priority-option:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: var(--shadow-secondary);
        }
        
        .priority-option.normal {
            border-color: #28A745;
        }
        
        .priority-option.normal:hover,
        .priority-option.normal.selected {
            background: linear-gradient(135deg, #28A745, #20C997);
            color: white;
            box-shadow: 0 15px 30px rgba(40, 167, 69, 0.3);
        }
        
        .priority-option.urgent {
            border-color: #FD7E14;
        }
        
        .priority-option.urgent:hover,
        .priority-option.urgent.selected {
            background: linear-gradient(135deg, #FD7E14, #FF8C00);
            color: white;
            box-shadow: 0 15px 30px rgba(253, 126, 20, 0.3);
        }
        
        .priority-option.very-urgent {
            border-color: #DC3545;
            animation: pulse 2s infinite;
        }
        
        .priority-option.very-urgent:hover,
        .priority-option.very-urgent.selected {
            background: linear-gradient(135deg, #DC3545, #C82333);
            color: white;
            box-shadow: 0 15px 30px rgba(220, 53, 69, 0.3);
            animation: bounce 0.6s ease-in-out;
        }
        
        .priority-option.selected {
            transform: translateY(-3px) scale(1.12);
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        
        /* File Upload */
        .file-upload-area {
            border: 3px dashed #DEE2E6;
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(5px);
        }
        
        .file-upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(255, 105, 180, 0.1);
            transform: translateY(-5px);
            box-shadow: var(--shadow-secondary);
            animation: pulse 1s ease-in-out;
        }
        
        .file-upload-area.dragover {
            border-color: var(--primary-color);
            background: rgba(255, 105, 180, 0.2);
            transform: scale(1.02);
            animation: bounce 0.6s ease-in-out;
        }
        
        .file-upload-icon {
            font-size: 3rem;
            color: #6C757D;
            margin-bottom: 1rem;
        }
        
        .uploaded-file {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background: rgba(248, 249, 250, 0.8);
            border-radius: 10px;
            margin-bottom: 0.5rem;
            backdrop-filter: blur(5px);
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .remove-file {
            color: #DC3545;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .remove-file:hover {
            color: #C82333;
            transform: scale(1.2);
        }
        
        /* Buttons */
        .btn-primary-custom {
            background: var(--gradient-primary);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-secondary);
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-primary-custom:hover::before {
            left: 100%;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-primary);
            animation: pulse 1s ease-in-out;
        }
        
        .btn-secondary-custom {
            background: rgba(248, 249, 250, 0.9);
            border: 2px solid #DEE2E6;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: #495057;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .btn-secondary-custom:hover {
            background: rgba(233, 236, 239, 0.9);
            border-color: #ADB5BD;
            transform: translateY(-2px);
            box-shadow: var(--shadow-secondary);
        }
        
        /* Tips Sidebar */
        .tips-sidebar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            border: 2px solid var(--light-pink);
            box-shadow: var(--shadow-secondary);
            position: sticky;
            top: 100px;
        }
        
        .tip-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            border: 1px solid rgba(255, 182, 193, 0.3);
            transition: all 0.3s ease;
        }
        
        .tip-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-secondary);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .tip-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .tip-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .tip-text {
            color: #495057;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        /* Success Animation */
        .success-container {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .success-icon {
            font-size: 5rem;
            margin-bottom: 2rem;
            animation: bounce 1s ease-in-out infinite;
        }
        
        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #28A745, #20C997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }
        
        .success-message {
            color: #495057;
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        /* Summary Box */
        .summary-box {
            background: rgba(248, 249, 250, 0.9);
            border-radius: 15px;
            padding: 2rem;
            border: 2px solid #E9ECEF;
            margin-bottom: 2rem;
            backdrop-filter: blur(5px);
        }
        
        .summary-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
        }
        
        .summary-label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
        }
        
        .summary-value {
            color: #212529;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #343A40, #495057);
            color: white;
            padding: 4rem 0 2rem;
            margin-top: 4rem;
        }
        
        .footer h3 {
            color: var(--accent-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .footer a {
            color: #ADB5BD;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--accent-color);
            transform: translateX(-5px);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .form-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .category-grid {
                grid-template-columns: 1fr;
            }
            
            .budget-grid {
                grid-template-columns: 1fr;
            }
            
            .priority-grid {
                grid-template-columns: 1fr;
            }
            
            .step-items {
                flex-wrap: wrap;
                gap: 1rem;
            }
            
            .step-circle {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            
            .tips-sidebar {
                position: static;
                margin-bottom: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .step-header h2 {
                font-size: 2rem;
            }
            
            .form-container {
                padding: 1.5rem 1rem;
            }
            /* Prevent overflow in small screens */
            .form-container, .glass-card, .timeline-content, .value-card {
                overflow-wrap: anywhere;
                word-break: break-word;
            }
            .btn { white-space: normal; }
            .category-grid, .budget-grid, .priority-grid { gap: 0.75rem; }
        }
    </style>
</head>
<body>


   

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">
                    <span class="hero-icon bounce">📝</span>
                    ثبت نیازمندی جدید
                </h1>
                <p class="hero-subtitle">
                    نیاز خود را ثبت کنید و بهترین پیشنهادات را از متخصصان دریافت کنید
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Tips Sidebar -->
            <div class="col-lg-3">
                <div class="tips-sidebar slide-in-right">
                    <h3 class="text-center mb-4">
                        <span class="sparkle">💡</span>
                        نکات مهم
                    </h3>
                    
                    <div class="tip-item">
                        <div class="tip-icon bounce">🎯</div>
                        <div>
                            <div class="tip-title">عنوان واضح</div>
                            <div class="tip-text">عنوان نیازمندی خود را واضح و مفصل بنویسید</div>
                        </div>
                    </div>
                    
                    <div class="tip-item">
                        <div class="tip-icon pulse">💰</div>
                        <div>
                            <div class="tip-title">بودجه واقعی</div>
                            <div class="tip-text">بودجه واقعی خود را مشخص کنید تا پیشنهادات بهتری دریافت کنید</div>
                        </div>
                    </div>
                    
                    <div class="tip-item">
                        <div class="tip-icon wiggle">📅</div>
                        <div>
                            <div class="tip-title">زمان کافی</div>
                            <div class="tip-text">حداقل ۳ روز زمان برای دریافت پیشنهادات در نظر بگیرید</div>
                        </div>
                    </div>
                    
                    <div class="tip-item">
                        <div class="tip-icon float">📸</div>
                        <div>
                            <div class="tip-title">تصاویر مرجع</div>
                            <div class="tip-text">اگر تصویر یا نمونه‌ای دارید، حتماً آپلود کنید</div>
                        </div>
                    </div>
                    
                    <div class="tip-item">
                        <div class="tip-icon heartbeat">📞</div>
                        <div>
                            <div class="tip-title">اطلاعات تماس</div>
                            <div class="tip-text">شماره تماس معتبر وارد کنید تا ارائه‌دهندگان بتوانند با شما تماس بگیرند</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="col-lg-9">
                <div class="form-container fade-in-up">
                    <!-- Error and Success Messages -->
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?php echo htmlspecialchars($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step-progress">
                            <div class="step-progress-bar" id="progressBar" style="width: 20%;"></div>
                        </div>
                        
                        <div class="step-items">
                            <div class="step-item">
                                <div class="step-circle active" id="step1Indicator">1</div>
                                <div class="step-title">دسته‌بندی</div>
                            </div>
                            
                            <div class="step-item">
                                <div class="step-circle pending" id="step2Indicator">2</div>
                                <div class="step-title">جزئیات</div>
                            </div>
                            
                            <div class="step-item">
                                <div class="step-circle pending" id="step3Indicator">3</div>
                                <div class="step-title">بودجه و زمان</div>
                            </div>
                            
                            <div class="step-item">
                                <div class="step-circle pending" id="step4Indicator">4</div>
                                <div class="step-title">تصاویر</div>
                            </div>
                            
                            <div class="step-item">
                                <div class="step-circle pending" id="step5Indicator">5</div>
                                <div class="step-title">تأیید</div>
                            </div>
                        </div>
                    </div>

                    <form id="needForm" method="POST" action="api/create_ad.php" enctype="multipart/form-data">
                        <!-- Step 1: Category Selection -->
                        <div class="form-step active" id="step1">
                            <div class="step-header">
                                <h2>
                                    <span class="step-icon bounce">🎪</span>
                                    دسته‌بندی خدمات مورد نیاز
                                </h2>
                            </div>
                            
                            <div class="category-grid">
                                <div class="category-card" onclick="selectCategory('cake')">
                                    <span class="category-icon bounce">🎂</span>
                                    <div class="category-title">کیک و شیرینی</div>
                                    <div class="category-desc">کیک تولد، شیرینی، دسر</div>
                                </div>
                                
                                <div class="category-card" onclick="selectCategory('decoration')">
                                    <span class="category-icon pulse">🎈</span>
                                    <div class="category-title">تزیینات</div>
                                    <div class="category-desc">بادکنک، گل، تزیین مکان</div>
                                </div>
                                
                                <div class="category-card" onclick="selectCategory('music')">
                                    <span class="category-icon wiggle">🎵</span>
                                    <div class="category-title">موسیقی و DJ</div>
                                    <div class="category-desc">DJ، گروه موسیقی، صدابرداری</div>
                                </div>
                                
                                <div class="category-card" onclick="selectCategory('photo')">
                                    <span class="category-icon float">📸</span>
                                    <div class="category-title">عکاسی و فیلم</div>
                                    <div class="category-desc">عکاس، فیلمبردار، ادیت</div>
                                </div>
                                
                                <div class="category-card" onclick="selectCategory('catering')">
                                    <span class="category-icon sparkle">🍽️</span>
                                    <div class="category-title">پذیرایی</div>
                                    <div class="category-desc">کترینگ، غذا، نوشیدنی</div>
                                </div>
                                
                                <div class="category-card" onclick="selectCategory('venue')">
                                    <span class="category-icon heartbeat">🏛️</span>
                                    <div class="category-title">سالن و مکان</div>
                                    <div class="category-desc">سالن عقد، باغ، رستوران</div>
                                </div>
                                
                                <div class="category-card" onclick="selectCategory('entertainment')">
                                    <span class="category-icon bounce">🎭</span>
                                    <div class="category-title">سرگرمی</div>
                                    <div class="category-desc">شعبده‌باز، نقاش چهره، بازی</div>
                                </div>
                                
                                <div class="category-card" onclick="selectCategory('other')">
                                    <span class="category-icon pulse">🎁</span>
                                    <div class="category-title">سایر خدمات</div>
                                    <div class="category-desc">خدمات دیگر</div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Details -->
                        <div class="form-step" id="step2">
                            <div class="step-header">
                                <h2>
                                    <span class="step-icon wiggle">📋</span>
                                    جزئیات نیازمندی
                                </h2>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="title" placeholder="عنوان نیازمندی" required>
                                        <label for="title">
                                            <i class="bi bi-pencil bounce"></i>
                                            عنوان نیازمندی *
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="description" placeholder="توضیحات کامل" style="height: 120px" required></textarea>
                                        <label for="description">
                                            <i class="bi bi-file-text pulse"></i>
                                            توضیحات کامل *
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Dynamic Fields Container -->
                                <div id="dynamicFields" class="col-12"></div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="city" required>
                                            <option value="">انتخاب شهر</option>
                                            <option value="tehran">تهران</option>
                                            <option value="isfahan">اصفهان</option>
                                            <option value="mashhad">مشهد</option>
                                            <option value="shiraz">شیراز</option>
                                            <option value="tabriz">تبریز</option>
                                            <option value="karaj">کرج</option>
                                            <option value="qom">قم</option>
                                            <option value="other">سایر شهرها</option>
                                        </select>
                                        <label for="city">
                                            <i class="bi bi-geo-alt wiggle"></i>
                                            شهر *
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="area" placeholder="منطقه/محله">
                                        <label for="area">
                                            <i class="bi bi-house float"></i>
                                            منطقه/محله
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="guestCount">
                                            <option value="">انتخاب کنید</option>
                                            <option value="1-10">۱ تا ۱۰ نفر</option>
                                            <option value="10-25">۱۰ تا ۲۵ نفر</option>
                                            <option value="25-50">۲۵ تا ۵۰ نفر</option>
                                            <option value="50-100">۵۰ تا ۱۰۰ نفر</option>
                                            <option value="100-200">۱۰۰ تا ۲۰۰ نفر</option>
                                            <option value="200+">بیش از ۲۰۰ نفر</option>
                                        </select>
                                        <label for="guestCount">
                                            <i class="bi bi-people sparkle"></i>
                                            تعداد مهمانان (تقریبی)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Budget and Timeline -->
                        <div class="form-step" id="step3">
                            <div class="step-header">
                                <h2>
                                    <span class="step-icon pulse">💰</span>
                                    بودجه و زمان‌بندی
                                </h2>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-currency-exchange wiggle"></i>
                                    بودجه مورد نظر (تومان) *
                                </label>
                                <div class="budget-grid">
                                    <div class="budget-option" onclick="selectBudget('under-500k')">
                                        <div class="budget-amount">زیر ۵۰۰ هزار</div>
                                    </div>
                                    <div class="budget-option" onclick="selectBudget('500k-1m')">
                                        <div class="budget-amount">۵۰۰ هزار - ۱ میلیون</div>
                                    </div>
                                    <div class="budget-option" onclick="selectBudget('1m-3m')">
                                        <div class="budget-amount">۱ - ۳ میلیون</div>
                                    </div>
                                    <div class="budget-option" onclick="selectBudget('3m-5m')">
                                        <div class="budget-amount">۳ - ۵ میلیون</div>
                                    </div>
                                    <div class="budget-option" onclick="selectBudget('5m-10m')">
                                        <div class="budget-amount">۵ - ۱۰ میلیون</div>
                                    </div>
                                    <div class="budget-option" onclick="selectBudget('over-10m')">
                                        <div class="budget-amount">بالای ۱۰ میلیون</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="eventDate" name="event_date" required>
                                        <label for="eventDate">
                                            <i class="bi bi-calendar-event bounce"></i>
                                            تاریخ مراسم *
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="deadline" name="deadline" required>
                                        <label for="deadline">
                                            <i class="bi bi-hourglass-bottom pulse"></i>
                                            مهلت دریافت پیشنهاد *
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="time" class="form-control" id="eventTime" name="event_time">
                                        <label for="eventTime">
                                            <i class="bi bi-clock float"></i>
                                            ساعت برگزاری
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-lightning sparkle"></i>
                                    اولویت درخواست *
                                </label>
                                <div class="priority-grid">
                                    <div class="priority-option normal" onclick="selectPriority('normal')">
                                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🟢</div>
                                        <div style="font-weight: bold;">عادی</div>
                                        <div style="font-size: 0.9rem; color: #6C757D;">زمان کافی دارم</div>
                                    </div>
                                    <div class="priority-option urgent" onclick="selectPriority('urgent')">
                                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🟡</div>
                                        <div style="font-weight: bold;">فوری</div>
                                        <div style="font-size: 0.9rem; color: #6C757D;">کمتر از یک هفته</div>
                                    </div>
                                    <div class="priority-option very-urgent" onclick="selectPriority('very-urgent')">
                                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔴</div>
                                        <div style="font-weight: bold;">خیلی فوری</div>
                                        <div style="font-size: 0.9rem; color: #6C757D;">کمتر از ۳ روز</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Images -->
                        <div class="form-step" id="step4">
                            <div class="step-header">
                                <h2>
                                    <span class="step-icon float">📸</span>
                                    تصاویر و فایل‌های مرجع
                                </h2>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-image sparkle"></i>
                                    آپلود تصاویر (اختیاری)
                                </label>
                                <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                                    <div class="file-upload-icon">
                                        <i class="bi bi-cloud-upload"></i>
                                    </div>
                                    <div style="font-weight: bold; margin-bottom: 1rem;">فایل‌های خود را اینجا بکشید یا کلیک کنید</div>
                                    <div style="color: #6C757D; font-size: 0.9rem;">
                                        فرمت‌های مجاز: JPG, PNG, PDF (حداکثر ۵ مگابایت)
                                    </div>
                                </div>
                                <input type="file" id="fileInput" name="images[]" multiple accept="image/*,.pdf" style="display: none;" onchange="handleFileUpload(event)">
                                
                                <div class="mt-3" id="uploadedFiles" style="display: none;">
                                    <h5 class="mb-3">
                                        <i class="bi bi-files"></i>
                                        فایل‌های آپلود شده:
                                    </h5>
                                </div>
                            </div>
                            
                            <div class="form-floating">
                                <textarea class="form-control" id="imageDescription" placeholder="توضیحات اضافی" style="height: 100px"></textarea>
                                <label for="imageDescription">
                                    <i class="bi bi-chat-text wiggle"></i>
                                    توضیحات اضافی درباره تصاویر
                                </label>
                            </div>
                        </div>

                        <!-- Step 5: Confirmation -->
                        <div class="form-step" id="step5">
                            <div class="step-header">
                                <h2>
                                    <span class="step-icon heartbeat">✅</span>
                                    بررسی و تأیید نهایی
                                </h2>
                            </div>
                            
                            <div class="summary-box mb-4">
                                <h4 class="mb-3">
                                    <i class="bi bi-list-check bounce"></i>
                                    خلاصه نیازمندی شما:
                                </h4>
                                
                                <div id="summaryContent">
                                    <!-- Summary will be populated by JavaScript -->
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="phone" placeholder="شماره تماس" required>
                                        <label for="phone">
                                            <i class="bi bi-telephone pulse"></i>
                                            شماره تماس *
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="fullName" placeholder="نام و نام خانوادگی" required>
                                        <label for="fullName">
                                            <i class="bi bi-person wiggle"></i>
                                            نام و نام خانوادگی *
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" placeholder="ایمیل">
                                        <label for="email">
                                            <i class="bi bi-envelope sparkle"></i>
                                            ایمیل (اختیاری)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label fw-bold" for="terms">
                                        قوانین و مقررات شادینو را مطالعه کرده و می‌پذیرم *
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Success Step -->
                        <div class="form-step" id="successStep">
                            <div class="success-container">
                                <div class="success-icon">🎉</div>
                                <div class="success-title">نیازمندی شما با موفقیت ثبت شد!</div>
                                <div class="success-message">
                                    کد پیگیری شما: <strong style="color: var(--primary-color);">#SH۱۲۳۴۵۶</strong><br><br>
                                    نیازمندی شما در حال بررسی است و به زودی منتشر خواهد شد.<br>
                                    ارائه‌دهندگان خدمات می‌توانند پیشنهادات خود را ارسال کنند.<br><br>
                                    <strong>مراحل بعدی:</strong><br>
                                    • بررسی و تأیید نیازمندی (حداکثر ۲ ساعت)<br>
                                    • انتشار در سایت و اطلاع‌رسانی به ارائه‌دهندگان<br>
                                    • دریافت پیشنهادات از متخصصان<br>
                                    • انتخاب بهترین پیشنهاد
                                </div>
                                
                                <div class="d-flex gap-3 justify-content-center flex-wrap">
                                    <button class="btn btn-primary-custom" onclick="window.location.href='/ads'">
                                        <i class="bi bi-eye bounce"></i>
                                        مشاهده همه نیازمندی‌ها
                                    </button>
                                    <button class="btn btn-secondary-custom" onclick="resetForm()">
                                        <i class="bi bi-plus-circle pulse"></i>
                                        ثبت نیاز جدید
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Form Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4" id="formButtons">
                            <button type="button" class="btn btn-secondary-custom" id="prevBtn" style="display: none;">
                                <i class="bi bi-arrow-right wiggle"></i>
                                مرحله قبل
                            </button>
                            
                            <button type="button" class="btn btn-primary-custom pulse" id="nextBtn" disabled>
                                مرحله بعد
                                <i class="bi bi-arrow-left bounce"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <h3>
                        <span class="pulse">💖</span> شادینو
                    </h3>
                    <p class="text-muted">پلتفرم آنلاین برای برگزاری بهترین جشن‌ها و مراسم‌های شما</p>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>لینک‌های سریع</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">درباره ما</a></li>
                        <li><a href="#">تماس با ما</a></li>
                        <li><a href="#">قوانین و مقررات</a></li>
                        <li><a href="#">حریم خصوصی</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>خدمات</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">ثبت نیاز</a></li>
                        <li><a href="#">جستجوی خدمات</a></li>
                        <li><a href="#">پیک و حمل</a></li>
                        <li><a href="#">مشاوره رایگان</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>تماس با ما</h5>
                    <div class="text-muted">
                        <p><i class="bi bi-telephone"></i> ۰۲۱-۱۲۳۴۵۶۷۸</p>
                        <p><i class="bi bi-envelope"></i> info@shadino.ir</p>
                        <p><i class="bi bi-geo-alt"></i> تهران، خیابان ولیعصر</p>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="text-center">
                <p class="text-muted mb-0">© ۱۴۰۳ شادینو. تمامی حقوق محفوظ است.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let currentStep = 1;
        const totalSteps = 5;
        let selectedCategory = '';
        let selectedBudget = '';
        let selectedPriority = '';
        let uploadedFiles = [];
        // Cache important field values to avoid missing elements when steps are hidden
        let cachedTitle = '';
        let cachedDescription = '';
        let cachedCity = '';
        let cachedEventDate = '';
        let cachedDeadline = '';

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing form...');
            
            try {
                updateStepIndicator();
                
                // Set minimum date to today
                const eventDateEl = document.getElementById('eventDate');
                if (eventDateEl) {
                    const today = new Date().toISOString().split('T')[0];
                    eventDateEl.min = today;
                }
                
                // File upload drag and drop
                const fileUpload = document.querySelector('.file-upload-area');
                if (fileUpload) {
                    fileUpload.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.classList.add('dragover');
                    });
                    
                    fileUpload.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        this.classList.remove('dragover');
                    });
                    
                    fileUpload.addEventListener('drop', function(e) {
                        e.preventDefault();
                        this.classList.remove('dragover');
                        const files = e.dataTransfer.files;
                        handleFiles(files);
                    });
                }
                
                // Add click event listeners to buttons
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                
                if (prevBtn) {
                    prevBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        previousStep();
                    });
                }
                
                if (nextBtn) {
                    nextBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        nextStep();
                    });
                }
                
                console.log('Form initialized successfully');
                
            } catch (error) {
                console.error('Error initializing form:', error);
            }
        });

        // Update step 2 fields based on selected category
        function updateStep2Fields(category) {
            const dynamicFields = document.getElementById('dynamicFields');
            const titleInput = document.getElementById('title');
            const descriptionInput = document.getElementById('description');
            
            // Clear previous dynamic fields
            dynamicFields.innerHTML = '';
            
            // Update placeholders and add specific fields based on category
            switch(category) {
                case 'cake':
                    titleInput.placeholder = 'مثال: نیاز به کیک تولد سفارشی برای ۲۰ نفر';
                    descriptionInput.placeholder = 'جزئیات کیک مورد نظر: طعم، طرح، اندازه، تزیینات خاص، آیا آلرژی خاصی دارید؟ تاریخ تحویل دقیق...';
                    
                    dynamicFields.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="cakeType" required>
                                        <option value="">انتخاب کنید</option>
                                        <option value="birthday">تولد</option>
                                        <option value="wedding">عروسی</option>
                                        <option value="anniversary">سالگرد</option>
                                        <option value="celebration">جشن</option>
                                        <option value="custom">سفارشی</option>
                                    </select>
                                    <label for="cakeType">
                                        <i class="bi bi-cake bounce"></i>
                                        نوع کیک *
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="cakeWeight">
                                        <option value="">انتخاب کنید</option>
                                        <option value="0.5">نیم کیلو</option>
                                        <option value="1">۱ کیلو</option>
                                        <option value="1.5">۱.۵ کیلو</option>
                                        <option value="2">۲ کیلو</option>
                                        <option value="3">۳ کیلو</option>
                                        <option value="custom">سایر اوزان</option>
                                    </select>
                                    <label for="cakeWeight">
                                        <i class="bi bi-speedometer pulse"></i>
                                        وزن کیک (کیلوگرم)
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="cakeFlavor" placeholder="طعم مورد علاقه">
                                    <label for="cakeFlavor">
                                        <i class="bi bi-heart wiggle"></i>
                                        طعم مورد علاقه
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="cakeDesign" placeholder="طرح و تزیینات خاص" style="height: 80px"></textarea>
                                    <label for="cakeDesign">
                                        <i class="bi bi-palette sparkle"></i>
                                        طرح و تزیینات خاص
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'decoration':
                    titleInput.placeholder = 'مثال: تزیین سالن تولد با تم پرنسسی';
                    descriptionInput.placeholder = 'جزئیات تزیینات: تم مورد نظر، رنگ‌بندی، نوع مکان، ابعاد فضا، تعداد میز و صندلی...';
                    
                    dynamicFields.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="decorationTheme" required>
                                        <option value="">انتخاب کنید</option>
                                        <option value="princess">پرنسسی</option>
                                        <option value="superhero">سوپرهیرو</option>
                                        <option value="classic">کلاسیک</option>
                                        <option value="modern">مدرن</option>
                                        <option value="vintage">وینتیج</option>
                                        <option value="custom">سفارشی</option>
                                    </select>
                                    <label for="decorationTheme">
                                        <i class="bi bi-palette bounce"></i>
                                        تم تزیینات *
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="colorScheme" placeholder="رنگ‌بندی اصلی">
                                    <label for="colorScheme">
                                        <i class="bi bi-rainbow pulse"></i>
                                        رنگ‌بندی اصلی
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="venueType">
                                        <option value="">انتخاب کنید</option>
                                        <option value="home">منزل</option>
                                        <option value="hall">سالن</option>
                                        <option value="garden">باغ</option>
                                        <option value="restaurant">رستوران</option>
                                        <option value="outdoor">فضای باز</option>
                                    </select>
                                    <label for="venueType">
                                        <i class="bi bi-building wiggle"></i>
                                        نوع مکان
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="spaceSize" placeholder="ابعاد فضا">
                                    <label for="spaceSize">
                                        <i class="bi bi-rulers sparkle"></i>
                                        ابعاد فضا (متر مربع)
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                // Add other cases for music, photo, catering, venue, entertainment, other...
                // Similar structure with Bootstrap form-floating components
                
                default:
                    // Default case for other categories
                    break;
            }
        }

        // Category selection
        function selectCategory(category) {
            // Remove previous selection
            document.querySelectorAll('.category-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selection to clicked card
            const clickedCard = document.querySelector(`[onclick="selectCategory('${category}')"]`);
            if (clickedCard) {
                clickedCard.classList.add('selected');
            }
            
            selectedCategory = category;
            
            // Update step 2 fields based on category
            updateStep2Fields(category);
            
            // Enable next button
            const nextBtn = document.getElementById('nextBtn');
            nextBtn.disabled = false;
        }

        // Budget selection
        function selectBudget(budget) {
            // Remove previous selection
            document.querySelectorAll('.budget-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selection to clicked option
            const clickedOption = document.querySelector(`[onclick="selectBudget('${budget}')"]`);
            if (clickedOption) {
                clickedOption.classList.add('selected');
            }
            selectedBudget = budget;
        }

        // Priority selection
        function selectPriority(priority) {
            // Remove previous selection
            document.querySelectorAll('.priority-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selection to clicked option
            const clickedOption = document.querySelector(`[onclick="selectPriority('${priority}')"]`);
            if (clickedOption) {
                clickedOption.classList.add('selected');
            }
            selectedPriority = priority;
        }

        // File upload handling
        function handleFileUpload(event) {
            const files = event.target.files;
            handleFiles(files);
        }

        function handleFiles(files) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            
            for (let file of files) {
                if (file.size > maxSize) {
                    alert(`فایل ${file.name} بیش از ۵ مگابایت است.`);
                    continue;
                }
                
                if (!allowedTypes.includes(file.type)) {
                    alert(`فرمت فایل ${file.name} مجاز نیست.`);
                    continue;
                }
                
                uploadedFiles.push(file);
                displayUploadedFile(file);
            }
            
            if (uploadedFiles.length > 0) {
                document.getElementById('uploadedFiles').style.display = 'block';
            }
        }

        function displayUploadedFile(file) {
            const container = document.getElementById('uploadedFiles');
            const fileDiv = document.createElement('div');
            fileDiv.className = 'uploaded-file';
            fileDiv.innerHTML = `
                <div class="file-info">
                    <i class="bi ${file.type.includes('image') ? 'bi-image' : 'bi-file-earmark-pdf'}"></i>
                    <span>${file.name}</span>
                    <small class="text-muted">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                </div>
                <span class="remove-file" onclick="removeFile('${file.name}', this)">
                    <i class="bi bi-x-circle"></i>
                </span>
            `;
            container.appendChild(fileDiv);
        }

        function removeFile(fileName, element) {
            uploadedFiles = uploadedFiles.filter(file => file.name !== fileName);
            element.parentElement.remove();
            
            if (uploadedFiles.length === 0) {
                document.getElementById('uploadedFiles').style.display = 'none';
            }
        }

        // Step navigation
        function nextStep() {
            console.log('Next step clicked, current step:', currentStep);
            
            if (!validateCurrentStep()) {
                console.log('Validation failed');
                return;
            }
            
            if (currentStep < totalSteps) {
                // Hide current step
                const currentStepEl = document.getElementById(`step${currentStep}`);
                if (currentStepEl) {
                    currentStepEl.classList.remove('active');
                }
                
                // Show next step
                currentStep++;
                const nextStepEl = document.getElementById(`step${currentStep}`);
                if (nextStepEl) {
                    nextStepEl.classList.add('active');
                }
                
                // Update step indicator
                updateStepIndicator();
                
                // Update buttons
                updateButtons();
                
                // Generate summary if on step 5
                if (currentStep === 5) {
                    generateSummary();
                }
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                // Submit form
                submitForm();
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                // Hide current step
                document.getElementById(`step${currentStep}`).classList.remove('active');
                
                // Show previous step
                currentStep--;
                document.getElementById(`step${currentStep}`).classList.add('active');
                
                // Update step indicator
                updateStepIndicator();
                
                // Update buttons
                updateButtons();
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function updateStepIndicator() {
            // Update progress bar
            const progressWidth = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progressBar').style.width = progressWidth + '%';
            
            // Update step indicators
            for (let i = 1; i <= totalSteps; i++) {
                const indicator = document.getElementById(`step${i}Indicator`);
                
                if (i < currentStep) {
                    indicator.className = 'step-circle completed';
                    indicator.innerHTML = '<i class="bi bi-check"></i>';
                } else if (i === currentStep) {
                    indicator.className = 'step-circle active';
                    indicator.innerHTML = i;
                } else {
                    indicator.className = 'step-circle pending';
                    indicator.innerHTML = i;
                }
            }
        }

        function updateButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            if (!prevBtn || !nextBtn) {
                console.error('Button elements not found');
                return;
            }
            
            // Show/hide previous button
            if (currentStep > 1) {
                prevBtn.style.display = 'flex';
            } else {
                prevBtn.style.display = 'none';
            }
            
            // Update next button text
            if (currentStep === totalSteps) {
                nextBtn.innerHTML = '<i class="bi bi-check-circle sparkle"></i> ثبت نیازمندی';
            } else {
                nextBtn.innerHTML = 'مرحله بعد <i class="bi bi-arrow-left bounce"></i>';
            }
        }

        function validateCurrentStep() {
            console.log('Validating step:', currentStep);
            
            try {
                switch (currentStep) {
                    case 1:
                        if (!selectedCategory) {
                            alert('لطفاً دسته‌بندی خدمات مورد نیاز را انتخاب کنید.');
                            return false;
                        }
                        break;
                        
                    case 2:
                        const titleEl = document.getElementById('title');
                        const descriptionEl = document.getElementById('description');
                        const cityEl = document.getElementById('city');
                        
                        if (!titleEl || !descriptionEl || !cityEl) {
                            console.error('Required elements not found');
                            return false;
                        }
                        
                        const title = titleEl.value.trim();
                        const description = descriptionEl.value.trim();
                        const city = cityEl.value;
                        // Cache for later when steps are hidden
                        cachedTitle = title;
                        cachedDescription = description;
                        cachedCity = city;
                        
                        // Clear previous validation styles
                        titleEl.classList.remove('is-invalid');
                        descriptionEl.classList.remove('is-invalid');
                        cityEl.classList.remove('is-invalid');
                        
                        if (!title) {
                            titleEl.classList.add('is-invalid');
                            alert('لطفاً عنوان نیازمندی را وارد کنید.');
                            titleEl.focus();
                            return false;
                        }
                        
                        if (title.length < 5) {
                            titleEl.classList.add('is-invalid');
                            alert('عنوان نیازمندی باید حداقل ۵ کاراکتر باشد.');
                            titleEl.focus();
                            return false;
                        }
                        
                        if (!description) {
                            descriptionEl.classList.add('is-invalid');
                            alert('لطفاً توضیحات کامل نیاز خود را وارد کنید.');
                            descriptionEl.focus();
                            return false;
                        }
                        
                        if (description.length < 10) {
                            descriptionEl.classList.add('is-invalid');
                            alert('توضیحات باید حداقل ۱۰ کاراکتر باشد.');
                            descriptionEl.focus();
                            return false;
                        }
                        
                        if (!city) {
                            cityEl.classList.add('is-invalid');
                            alert('لطفاً شهر را انتخاب کنید.');
                            cityEl.focus();
                            return false;
                        }
                        break;
                        
                    case 3:
                        const eventDateEl = document.getElementById('eventDate');
                        const deadlineEl = document.getElementById('deadline');
                        
                        if (!selectedBudget) {
                            alert('لطفاً بودجه مورد نظر را انتخاب کنید.');
                            return false;
                        }
                        // Clear previous validation styles
                        if (eventDateEl) eventDateEl.classList.remove('is-invalid');
                        if (deadlineEl) deadlineEl.classList.remove('is-invalid');
                        
                        if (!eventDateEl || !eventDateEl.value) {
                            if (eventDateEl) eventDateEl.classList.add('is-invalid');
                            alert('لطفاً تاریخ مراسم را انتخاب کنید.');
                            if (eventDateEl) eventDateEl.focus();
                            return false;
                        }
                        if (!deadlineEl || !deadlineEl.value) {
                            if (deadlineEl) deadlineEl.classList.add('is-invalid');
                            alert('لطفاً مهلت دریافت پیشنهاد را انتخاب کنید.');
                            if (deadlineEl) deadlineEl.focus();
                            return false;
                        }
                        // Check if dates are not in the past and deadline before event
                        // Cache values first
                        cachedEventDate = eventDateEl.value;
                        cachedDeadline = deadlineEl.value;
                        const selectedEventDate = new Date(cachedEventDate);
                        const selectedDeadline = new Date(cachedDeadline);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (selectedEventDate < today) {
                            eventDateEl.classList.add('is-invalid');
                            alert('تاریخ مراسم نمی‌تواند در گذشته باشد.');
                            eventDateEl.focus();
                            return false;
                        }
                        if (selectedDeadline < today) {
                            deadlineEl.classList.add('is-invalid');
                            alert('مهلت دریافت پیشنهاد نمی‌تواند در گذشته باشد.');
                            deadlineEl.focus();
                            return false;
                        }
                        if (selectedDeadline > selectedEventDate) {
                            deadlineEl.classList.add('is-invalid');
                            alert('مهلت دریافت پیشنهاد باید قبل از تاریخ مراسم باشد.');
                            deadlineEl.focus();
                            return false;
                        }
                        if (!selectedPriority) {
                            alert('لطفاً اولویت درخواست را انتخاب کنید.');
                            return false;
                        }
                        break;
                        
                    case 4:
                        // Step 4 is optional, always valid
                        break;
                        
                    case 5:
                        const phoneEl = document.getElementById('phone');
                        const fullNameEl = document.getElementById('fullName');
                        const termsEl = document.getElementById('terms');
                        
                        if (!phoneEl || !fullNameEl || !termsEl) {
                            console.error('Required elements not found in step 5');
                            return false;
                        }
                        
                        // Clear previous validation styles
                        phoneEl.classList.remove('is-invalid');
                        fullNameEl.classList.remove('is-invalid');
                        
                        const phone = phoneEl.value.trim();
                        const fullName = fullNameEl.value.trim();
                        const terms = termsEl.checked;
                        
                        if (!phone) {
                            phoneEl.classList.add('is-invalid');
                            alert('لطفاً شماره تماس را وارد کنید.');
                            phoneEl.focus();
                            return false;
                        }
                        
                        // Simple phone validation
                        const phoneRegex = /^09\d{9}$/;
                        if (!phoneRegex.test(phone)) {
                            phoneEl.classList.add('is-invalid');
                            alert('شماره تماس وارد شده معتبر نیست. (مثال: ۰۹۱۲۳۴۵۶۷۸۹)');
                            phoneEl.focus();
                            return false;
                        }
                        
                        if (!fullName) {
                            fullNameEl.classList.add('is-invalid');
                            alert('لطفاً نام و نام خانوادگی را وارد کنید.');
                            fullNameEl.focus();
                            return false;
                        }
                        
                        if (fullName.length < 3) {
                            fullNameEl.classList.add('is-invalid');
                            alert('نام و نام خانوادگی باید حداقل ۳ کاراکتر باشد.');
                            fullNameEl.focus();
                            return false;
                        }
                        
                        if (!terms) {
                            alert('لطفاً قوانین و مقررات را مطالعه کرده و تأیید کنید.');
                            return false;
                        }
                        break;
                }
                
                console.log('Validation passed for step:', currentStep);
                return true;
                
            } catch (error) {
                console.error('Validation error:', error);
                return false;
            }
        }

        function generateSummary() {
            const categoryNames = {
                'cake': 'کیک و شیرینی',
                'decoration': 'تزیینات',
                'music': 'موسیقی و DJ',
                'photo': 'عکاسی و فیلم',
                'catering': 'پذیرایی',
                'venue': 'سالن و مکان',
                'entertainment': 'سرگرمی',
                'other': 'سایر خدمات'
            };
            
            const budgetNames = {
                'under-500k': 'زیر ۵۰۰ هزار تومان',
                '500k-1m': '۵۰۰ هزار - ۱ میلیون تومان',
                '1m-3m': '۱ - ۳ میلیون تومان',
                '3m-5m': '۳ - ۵ میلیون تومان',
                '5m-10m': '۵ - ۱۰ میلیون تومان',
                'over-10m': 'بالای ۱۰ میلیون تومان'
            };
            
            const priorityNames = {
                'normal': 'عادی',
                'urgent': 'فوری',
                'very-urgent': 'خیلی فوری'
            };
            
            const cityNames = {
                'tehran': 'تهران',
                'isfahan': 'اصفهان',
                'mashhad': 'مشهد',
                'shiraz': 'شیراز',
                'tabriz': 'تبریز',
                'karaj': 'کرج',
                'qom': 'قم',
                'other': 'سایر شهرها'
            };
            
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const city = document.getElementById('city').value;
            const area = document.getElementById('area').value;
            const guestCount = document.getElementById('guestCount').value;
            const eventDate = document.getElementById('eventDate').value;
            const eventTime = document.getElementById('eventTime').value;
            
            let summary = `
                <div class="row g-3">
                    <div class="col-12">
                        <div class="summary-item">
                            <span class="summary-label">دسته‌بندی:</span>
                            <span class="summary-value">${categoryNames[selectedCategory]}</span>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="summary-item">
                            <span class="summary-label">عنوان:</span>
                            <span class="summary-value">${title}</span>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="summary-item">
                            <span class="summary-label">توضیحات:</span>
                            <span class="summary-value">${description.substring(0, 150)}${description.length > 150 ? '...' : ''}</span>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="summary-item">
                            <span class="summary-label">مکان:</span>
                            <span class="summary-value">${cityNames[city]}${area ? ` - ${area}` : ''}</span>
                        </div>
                    </div>
                    
                    ${guestCount ? `
                    <div class="col-12">
                        <div class="summary-item">
                            <span class="summary-label">تعداد مهمانان:</span>
                            <span class="summary-value">${guestCount}</span>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="col-12">
                        <div class="summary-item">
                            <span class="summary-label">بودجه:</span>
                            <span class="summary-value">${budgetNames[selectedBudget]}</span>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="summary-item">
                            <span class="summary-label">تاریخ:</span>
                            <span class="summary-value">${new Date(eventDate).toLocaleDateString('fa-IR')}${eventTime ? ` - ساعت ${eventTime}` : ''}</span>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="summary-item">
                            <span class="summary-label">اولویت:</span>
                            <span class="summary-value">${priorityNames[selectedPriority]}</span>
                        </div>
                    </div>
                    
                    ${uploadedFiles.length > 0 ? `
                    <div class="col-12">
                        <div class="summary-item">
                            <span class="summary-label">فایل‌های آپلود شده:</span>
                            <span class="summary-value">${uploadedFiles.length} فایل</span>
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('summaryContent').innerHTML = summary;
        }

        function submitForm() {
            const nextBtn = document.getElementById('nextBtn');
            const originalText = nextBtn.innerHTML;
            const currentUrl = window.location.href;
            try {
                // Show loading state
            nextBtn.innerHTML = '<i class="bi bi-hourglass-split pulse"></i> در حال ثبت...';
            nextBtn.disabled = true;
            
            // Create hidden inputs for form data
            const form = document.getElementById('needForm');
            
                // Remove only previously added hidden inputs for these field names (do not remove real form fields)
                const fieldNamesToReplace = [
                    'category','title','description','city','event_date','deadline','priority','budget_min','budget_max'
                ];
                fieldNamesToReplace.forEach(name => {
                    form.querySelectorAll(`input[type="hidden"][name="${name}"]`).forEach(input => input.remove());
                });

                // Add hidden inputs for all form data (map to backend expectations)
                const categoryMap = {
                    'cake': 'cake',
                    'decoration': 'decoration',
                    'photo': 'photography',
                    'music': 'music',
                    'venue': 'ceremony',
                    'catering': 'catering',
                    'entertainment': 'other',
                    'other': 'other'
                };

                const normalizeBudget = (budgetKey) => {
                    switch (budgetKey) {
                        case 'under-500k':
                            return { min: 1, max: 500000 };
                        case '500k-1m':
                            return { min: 500000, max: 1000000 };
                        case '1m-3m':
                            return { min: 1000000, max: 3000000 };
                        case '3m-5m':
                            return { min: 3000000, max: 5000000 };
                        case '5m-10m':
                            return { min: 5000000, max: 10000000 };
                        case 'over-10m':
                            return { min: 10000000, max: 10000000 };
                        default:
                            return { min: 0, max: 0 };
                    }
                };

                const mappedCategory = categoryMap[selectedCategory] || selectedCategory;
                const budgetRange = normalizeBudget(selectedBudget);

                // Safely read values from DOM
                const getEl = (id) => document.getElementById(id);
                const titleEl = getEl('title');
                const descriptionEl = getEl('description');
                const cityEl = getEl('city');
                // Prefer name-based lookup for date fields as they always submit
                const eventDateEl = form.querySelector('input[name="event_date"]') || getEl('eventDate');
                const deadlineEl = form.querySelector('input[name="deadline"]') || getEl('deadline');

                if (!titleEl || !descriptionEl || !cityEl || !eventDateEl || !deadlineEl) {
                    console.error('Missing elements:', {
                        title: !!titleEl,
                        description: !!descriptionEl,
                        city: !!cityEl,
                        eventDate: !!eventDateEl,
                        deadline: !!deadlineEl
                    });
                    // Navigate user to the correct step to re-render fields
                    currentStep = (!titleEl || !descriptionEl || !cityEl) ? 2 : 3;
                    document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
                    document.getElementById(`step${currentStep}`).classList.add('active');
                    updateStepIndicator();
                    updateButtons();
                    // Mark missing fields as invalid
                    if (!titleEl) {} else if (!titleEl.value) titleEl.classList.add('is-invalid');
                    if (!descriptionEl) {} else if (!descriptionEl.value) descriptionEl.classList.add('is-invalid');
                    if (!cityEl) {} else if (!cityEl.value) cityEl.classList.add('is-invalid');
                    if (!eventDateEl || !eventDateEl.value) {
                        if (eventDateEl) eventDateEl.classList.add('is-invalid');
                    }
                    if (!deadlineEl || !deadlineEl.value) {
                        if (deadlineEl) deadlineEl.classList.add('is-invalid');
                    }
                    // Scroll into view of the current step
                    document.getElementById(`step${currentStep}`).scrollIntoView({ behavior: 'smooth', block: 'start' });
                    nextBtn.innerHTML = originalText;
                    nextBtn.disabled = false;
                    return;
                }

                const formData = {
                    'category': mappedCategory,
                    'title': cachedTitle || titleEl.value,
                    'description': cachedDescription || descriptionEl.value,
                    'city': cachedCity || cityEl.value,
                    'event_date': cachedEventDate || eventDateEl.value,
                    'deadline': cachedDeadline || deadlineEl.value,
                    'priority': selectedPriority,
                    'budget_min': budgetRange.min,
                    'budget_max': budgetRange.max
                };

            console.log('Sending form data:', formData);
            
                // Add new hidden inputs only when there isn't an existing non-hidden field with the same name
            Object.keys(formData).forEach(key => {
                    if (formData[key] === undefined || formData[key] === '') return;
                    const existingControl = form.querySelector(`[name="${key}"]`);
                    const shouldAddHidden = !existingControl || (existingControl && existingControl.getAttribute('type') === 'hidden');
                    if (shouldAddHidden) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = formData[key];
                    form.appendChild(input);
                }
            });
            
            console.log('Submitting form...');
            form.submit();

                // Fallback: if navigation didn't happen within 10s, restore button
                setTimeout(() => {
                    if (window.location.href === currentUrl) {
                        nextBtn.innerHTML = originalText;
                        nextBtn.disabled = false;
                        alert('خطا در ثبت آگهی. لطفاً دوباره تلاش کنید یا اطلاعات وارد شده را بررسی کنید.');
                    }
                }, 10000);
            } catch (err) {
                console.error('Submit error:', err);
                nextBtn.innerHTML = originalText;
                nextBtn.disabled = false;
                alert('خطای غیرمنتظره هنگام ثبت. لطفاً دوباره تلاش کنید.');
            }
        }

        function resetForm() {
            // Reset all form data
            currentStep = 1;
            selectedCategory = '';
            selectedBudget = '';
            selectedPriority = '';
            uploadedFiles = [];
            
            // Reset form elements
            document.getElementById('needForm').reset();
            
            // Clear selections
            document.querySelectorAll('.selected').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Hide uploaded files
            document.getElementById('uploadedFiles').style.display = 'none';
            document.getElementById('uploadedFiles').innerHTML = '<h5 class="mb-3"><i class="bi bi-files"></i> فایل‌های آپلود شده:</h5>';
            
            // Show step 1
            document.querySelectorAll('.form-step').forEach(step => {
                step.classList.remove('active');
            });
            document.getElementById('step1').classList.add('active');
            
            // Show form buttons
            document.getElementById('formButtons').style.display = 'flex';
            
            // Update indicators
            updateStepIndicator();
            updateButtons();
            
            // Disable next button initially
            const nextBtn = document.getElementById('nextBtn');
            nextBtn.disabled = true;
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
