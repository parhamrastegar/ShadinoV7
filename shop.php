<?php
include 'header.php';
?>
<!DOCTYPE html> 
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه شادینو - لوازم جشن و مراسم</title>
    
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
            --purple-gradient: linear-gradient(135deg, #9C27B0, #E91E63);
            --blue-gradient: linear-gradient(135deg, #2196F3, #03DAC6);
            --green-gradient: linear-gradient(135deg, #4CAF50, #8BC34A);
        }
        
        body {
            background: linear-gradient(135deg, #FFE4E1 0%, #FFF0F5 50%, #F8F9FA 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Floating Elements */
        .floating-balloons {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        
        .floating-balloon {
            position: absolute;
            font-size: 2.5rem;
            animation: floatBalloon 12s linear infinite;
        }
        
        @keyframes floatBalloon {
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
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        @keyframes cartBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Live Elements */
        .live-stat-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            color: white;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease-out;
        }
        
        .live-stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(255, 105, 180, 0.2);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
            animation: float 3s ease-in-out infinite;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .stat-label {
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.9;
        }
        
        .live-dot {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 12px;
            height: 12px;
            background: #00FF00;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
            box-shadow: 0 0 10px #00FF00;
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
        
        .floating-cart-icon {
            position: fixed;
            top: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            cursor: pointer;
            z-index: 1000;
            box-shadow: var(--shadow-primary);
            transition: all 0.3s ease;
            animation: float 3s ease-in-out infinite;
        }
        
        .floating-cart-icon:hover {
            transform: scale(1.1);
            animation: cartBounce 0.6s ease-in-out;
        }
        
        .cart-pulse {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--primary-color);
            opacity: 0.3;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: heartbeat 2s ease-in-out infinite;
        }
        
        /* Hero Section */
        .hero-section {
            background: var(--gradient-hero);
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="party" x="0" y="0" width="25" height="25" patternUnits="userSpaceOnUse"><text x="12.5" y="18" text-anchor="middle" font-size="14" fill="rgba(255,255,255,0.1)">🎉</text></pattern></defs><rect width="100" height="100" fill="url(%23party)"/></svg>');
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
        
        /* Search Bar */
        .search-container {
            max-width: 600px;
            margin: 2rem auto;
            position: relative;
        }
        
        .search-bar {
            width: 100%;
            padding: 1rem 3rem 1rem 1.5rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: var(--shadow-primary);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .search-bar:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.3), var(--shadow-primary);
            transform: translateY(-2px);
        }
        
        .search-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gradient-primary);
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            transform: translateY(-50%) scale(1.1);
            animation: pulse 1s ease-in-out;
        }
        
        /* Categories Section */
        .categories-section {
            padding: 80px 0;
            background: white;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 3rem;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .category-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }
        
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 105, 180, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }
        
        .category-card:hover::before {
            left: 100%;
        }
        
        .category-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
            transition: all 0.3s ease;
        }
        
        .category-card:hover .category-icon {
            animation: bounce 0.6s ease-in-out;
        }
        
        .category-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.5rem;
        }
        
        .category-count {
            color: #6C757D;
            font-size: 0.9rem;
        }
        
        /* Products Section */
        .products-section {
            padding: 80px 0;
            background: var(--gradient-secondary);
        }
        
        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }
        
        .filter-tab {
            padding: 1rem 2rem;
            border: none;
            border-radius: 25px;
            background: white;
            color: #6C757D;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--card-shadow);
        }
        
        .filter-tab:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .filter-tab.active {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-2px);
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        
        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            height: 200px;
            background: var(--gradient-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            position: relative;
            overflow: hidden;
        }
        
        .product-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .product-card:hover .product-image::before {
            left: 100%;
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .product-badge.new {
            background: var(--success-color);
        }
        
        .product-badge.sale {
            background: var(--warning-color);
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.5rem;
        }
        
        .product-description {
            color: #6C757D;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .product-price {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .price-current {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .price-original {
            font-size: 1rem;
            color: #6C757D;
            text-decoration: line-through;
        }
        
        .product-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .stars {
            color: #FFD700;
        }
        
        .rating-count {
            color: #6C757D;
            font-size: 0.9rem;
        }
        
        .product-actions {
            display: flex;
            gap: 1rem;
        }
        
        .add-to-cart-btn {
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: 15px;
            background: var(--gradient-primary);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .add-to-cart-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.4);
        }
        
        .add-to-cart-btn:hover::before {
            left: 100%;
        }
        
        .wishlist-btn {
            width: 45px;
            height: 45px;
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            background: white;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .wishlist-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }
        
        .wishlist-btn.active {
            background: var(--primary-color);
            color: white;
            animation: heartbeat 1s ease-in-out;
        }
        
        /* Special Offers Section */
        .offers-section {
            padding: 80px 0;
            background: white;
            position: relative;
            overflow: hidden;
        }
        
        .offers-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="confetti" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><text x="10" y="15" text-anchor="middle" font-size="12" fill="rgba(255,105,180,0.1)">🎊</text></pattern></defs><rect width="100" height="100" fill="url(%23confetti)"/></svg>');
            animation: float 30s ease-in-out infinite;
        }
        
        .offer-banner {
            background: var(--gradient-primary);
            border-radius: 30px;
            padding: 3rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 3rem;
        }
        
        .offer-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: rotate 10s linear infinite;
        }
        
        .offer-content {
            position: relative;
            z-index: 2;
        }
        
        .offer-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .offer-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .countdown-timer {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }
        
        .countdown-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 1rem;
            min-width: 80px;
            backdrop-filter: blur(10px);
        }
        
        .countdown-number {
            font-size: 2rem;
            font-weight: 800;
            display: block;
        }
        
        .countdown-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .offer-btn {
            padding: 1rem 2rem;
            border: 2px solid white;
            border-radius: 25px;
            background: transparent;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .offer-btn:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.3);
        }
        
        /* Cart Sidebar */
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.2);
            z-index: 1050;
            transition: right 0.3s ease;
            overflow-y: auto;
        }
        
        .cart-sidebar.open {
            right: 0;
        }
        
        .cart-header {
            padding: 2rem;
            border-bottom: 2px solid #F8F9FA;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .cart-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #212529;
        }
        
        .close-cart {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6C757D;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close-cart:hover {
            color: var(--primary-color);
            transform: scale(1.2);
        }
        
        .cart-items {
            padding: 1rem;
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #F8F9FA;
        }
        
        .cart-item-image {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: var(--gradient-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .cart-item-info {
            flex: 1;
        }
        
        .cart-item-name {
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.5rem;
        }
        
        .cart-item-price {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #DEE2E6;
            border-radius: 5px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .quantity-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #DEE2E6;
            border-radius: 5px;
            padding: 0.3rem;
        }
        
        .cart-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 2rem;
            background: white;
            border-top: 2px solid #F8F9FA;
        }
        
        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        .checkout-btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 15px;
            background: var(--gradient-primary);
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.4);
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
            
            .categories-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
            
            .filter-tabs {
                gap: 0.5rem;
            }
            
            .filter-tab {
                padding: 0.8rem 1.5rem;
                font-size: 0.9rem;
            }
            
            .countdown-timer {
                gap: 1rem;
            }
            
            .cart-sidebar {
                width: 100%;
                right: -100%;
            }
            
            .offer-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-section {
                padding: 100px 0 60px;
            }
            
            .categories-section,
            .products-section,
            .offers-section {
                padding: 60px 0;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .category-card {
                padding: 1.5rem;
            }
            
            .category-icon {
                font-size: 3rem;
            }
            
            .offer-banner {
                padding: 2rem;
            }
            
            .countdown-timer {
                flex-direction: column;
                align-items: center;
            }
            
            .live-stats {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            .live-stat-card {
                padding: 1.5rem 1rem;
            }
            
            .stat-icon {
                font-size: 2rem;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Balloons Background -->
    <div class="floating-balloons" id="floatingBalloons"></div>

    <!-- Floating Cart Icon -->
    <div class="floating-cart-icon" onclick="toggleCart()">
        <i class="bi bi-bag-heart"></i>
        <span class="cart-badge" id="cartBadge">3</span>
        <div class="cart-pulse"></div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    فروشگاه <span style="color: #FFD700;">لوازم جشن</span> شادینو
                </h1>
                <p class="typewriter-text">
                    بهترین لوازم جشن و مراسم برای خاطره‌سازی‌های شما
                </p>
                
                <div class="search-container">
                    <input type="text" class="search-bar" placeholder="دنبال چه چیزی می‌گردید؟ (بادکنک، تزئینات، کیک و...)" id="searchInput">
                    <button class="search-btn" onclick="performSearch()">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                
                <div class="live-stats" style="margin-top: 3rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; max-width: 800px; margin-left: auto; margin-right: auto;">
                    <div class="live-stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <div class="stat-number" id="liveViewers">۴۷</div>
                            <div class="stat-label">کاربر آنلاین</div>
                        </div>
                        <div class="live-dot"></div>
                    </div>
                    <div class="live-stat-card">
                        <div class="stat-icon">⚡</div>
                        <div class="stat-content">
                            <div class="stat-number">۲۴ ساعته</div>
                            <div class="stat-label">ارسال سریع</div>
                        </div>
                    </div>
                    <div class="live-stat-card">
                        <div class="stat-icon">🎁</div>
                        <div class="stat-content">
                            <div class="stat-number">۵۰۰+</div>
                            <div class="stat-label">محصول متنوع</div>
                        </div>
                    </div>
                </div>
                

            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <h2 class="section-title">دسته‌بندی محصولات</h2>
            
            <div class="categories-grid" id="categoriesGrid">
                <!-- Categories will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">محصولات پرفروش</h2>
            
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">همه محصولات</button>
                <button class="filter-tab" data-filter="balloons">بادکنک‌ها</button>
                <button class="filter-tab" data-filter="decorations">تزئینات</button>
                <button class="filter-tab" data-filter="party-supplies">لوازم جشن</button>
                <button class="filter-tab" data-filter="cakes">کیک و شیرینی</button>
                <button class="filter-tab" data-filter="gifts">هدایا</button>
            </div>
            
            <div class="products-grid" id="productsGrid">
                <!-- Products will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Special Offers Section -->
    <section class="offers-section">
        <div class="container">
            <div class="offer-banner">
                <div class="offer-content">
                    <h2 class="offer-title">🎊 فروش ویژه هفته 🎊</h2>
                    <p class="offer-subtitle">تا ۵۰٪ تخفیف روی تمام لوازم جشن تولد</p>
                    
                    <div class="countdown-timer">
                        <div class="countdown-item">
                            <span class="countdown-number" id="days">03</span>
                            <span class="countdown-label">روز</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="hours">14</span>
                            <span class="countdown-label">ساعت</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="minutes">27</span>
                            <span class="countdown-label">دقیقه</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="seconds">45</span>
                            <span class="countdown-label">ثانیه</span>
                        </div>
                    </div>
                    
                    <button class="offer-btn" onclick="showOfferProducts()">
                        مشاهده محصولات تخفیف‌دار
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3 class="cart-title">سبد خرید شما</h3>
            <button class="close-cart" onclick="toggleCart()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="cart-items" id="cartItems">
            <!-- Cart items will be populated by JavaScript -->
        </div>
        
        <div class="cart-footer">
            <div class="cart-total">
                <span>مجموع:</span>
                <span id="cartTotal">۲۴۵,۰۰۰ تومان</span>
            </div>
            <button class="checkout-btn" onclick="proceedToCheckout()">
                ادامه خرید
            </button>
        </div>
    </div>

    <!-- Cart Overlay -->
    <div class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" 
         style="z-index: 1040; display: none;" id="cartOverlay" onclick="toggleCart()"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Categories data
        const categories = [
            { name: 'بادکنک‌ها', icon: '🎈', count: '۱۲۰+ محصول', id: 'balloons' },
            { name: 'تزئینات', icon: '🎀', count: '۸۵+ محصول', id: 'decorations' },
            { name: 'لوازم جشن', icon: '🎉', count: '۹۵+ محصول', id: 'party-supplies' },
            { name: 'کیک و شیرینی', icon: '🎂', count: '۶۰+ محصول', id: 'cakes' },
            { name: 'هدایا', icon: '🎁', count: '۷۵+ محصول', id: 'gifts' },
            { name: 'لباس‌های جشن', icon: '👗', count: '۴۰+ محصول', id: 'costumes' },
            { name: 'ظروف یکبار مصرف', icon: '🥤', count: '۵۵+ محصول', id: 'disposables' },
            { name: 'بازی و سرگرمی', icon: '🎮', count: '۳۰+ محصول', id: 'games' }
        ];

        // Products data
        const products = [
            {
                id: 1,
                name: 'بادکنک‌های رنگی مخصوص تولد',
                description: 'بسته ۵۰ عددی بادکنک‌های رنگارنگ با کیفیت بالا',
                price: '۴۵,۰۰۰',
                originalPrice: '۶۰,۰۰۰',
                image: '🎈',
                category: 'balloons',
                badge: 'sale',
                rating: 4.8,
                reviews: 156
            },
            {
                id: 2,
                name: 'ست تزئینات جشن تولد طلایی',
                description: 'شامل بنر، ریسه، و تزئینات دیواری',
                price: '۱۲۰,۰۰۰',
                originalPrice: null,
                image: '✨',
                category: 'decorations',
                badge: 'new',
                rating: 4.9,
                reviews: 89
            },
            {
                id: 3,
                name: 'کیک تولد سفارشی ۲ کیلویی',
                description: 'کیک خامه‌ای با طعم وانیل و تزئین دلخواه',
                price: '۱۸۰,۰۰۰',
                originalPrice: '۲۲۰,۰۰۰',
                image: '🎂',
                category: 'cakes',
                badge: 'popular',
                rating: 5.0,
                reviews: 234
            },
            {
                id: 4,
                name: 'بادکنک‌های فویلی قلبی',
                description: 'بسته ۱۰ عددی بادکنک فویلی قرمز و صورتی',
                price: '۳۵,۰۰۰',
                originalPrice: null,
                image: '💖',
                category: 'balloons',
                badge: null,
                rating: 4.7,
                reviews: 67
            },
            {
                id: 5,
                name: 'ست هدیه تولد کودک',
                description: 'شامل اسباب‌بازی، کتاب رنگ‌آمیزی و شیرینی',
                price: '۹۵,۰۰۰',
                originalPrice: '۱۲۰,۰۰۰',
                image: '🎁',
                category: 'gifts',
                badge: 'sale',
                rating: 4.6,
                reviews: 123
            },
            {
                id: 6,
                name: 'ظروف یکبار مصرف طلایی',
                description: 'بشقاب، لیوان و قاشق چنگال برای ۲۰ نفر',
                price: '۷۵,۰۰۰',
                originalPrice: null,
                image: '🍽️',
                category: 'disposables',
                badge: null,
                rating: 4.4,
                reviews: 45
            },
            {
                id: 7,
                name: 'بنر تولدت مبارک شخصی‌سازی شده',
                description: 'بنر پارچه‌ای با نام و عکس دلخواه',
                price: '۶۵,۰۰۰',
                originalPrice: '۸۰,۰۰۰',
                image: '🎊',
                category: 'decorations',
                badge: 'custom',
                rating: 4.8,
                reviews: 78
            },
            {
                id: 8,
                name: 'بازی جشن خانوادگی',
                description: 'مجموعه بازی‌های گروهی برای مهمانی',
                price: '۱۱۰,۰۰۰',
                originalPrice: null,
                image: '🎯',
                category: 'games',
                badge: 'new',
                rating: 4.5,
                reviews: 34
            }
        ];

        // Cart data
        let cart = [
            { id: 1, name: 'بادکنک‌های رنگی', price: 45000, quantity: 2, image: '🎈' },
            { id: 3, name: 'کیک تولد ۲ کیلویی', price: 180000, quantity: 1, image: '🎂' },
            { id: 5, name: 'ست هدیه تولد', price: 95000, quantity: 1, image: '🎁' }
        ];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            initializeFloatingBalloons();
            initializeCategories();
            initializeProducts();
            initializeFilterTabs();
            initializeCart();
            startCountdown();
            startLiveUpdates();
            initializeScrollAnimations();
        });

        // Floating balloons animation
        function initializeFloatingBalloons() {
            const balloonsContainer = document.getElementById('floatingBalloons');
            const balloons = ['🎈', '🎀', '🎉', '🎊', '💖', '✨', '🌟', '💫'];
            
            setInterval(() => {
                const balloon = document.createElement('div');
                balloon.className = 'floating-balloon';
                balloon.textContent = balloons[Math.floor(Math.random() * balloons.length)];
                balloon.style.left = Math.random() * 100 + '%';
                balloon.style.animationDuration = (Math.random() * 4 + 8) + 's';
                balloon.style.fontSize = (Math.random() * 1 + 2) + 'rem';
                
                // Random colors for balloons
                const colors = ['#FF69B4', '#FFB6C1', '#FFC0CB', '#FF1493', '#FFD700', '#FF6347'];
                balloon.style.color = colors[Math.floor(Math.random() * colors.length)];
                
                balloonsContainer.appendChild(balloon);
                
                setTimeout(() => {
                    balloon.remove();
                }, 12000);
            }, 1500);
        }

        // Initialize categories
        function initializeCategories() {
            const categoriesGrid = document.getElementById('categoriesGrid');
            
            categories.forEach((category, index) => {
                const categoryCard = document.createElement('div');
                categoryCard.className = 'category-card';
                categoryCard.style.animationDelay = (index * 0.1) + 's';
                categoryCard.onclick = () => filterByCategory(category.id);
                
                categoryCard.innerHTML = `
                    <span class="category-icon">${category.icon}</span>
                    <h4 class="category-name">${category.name}</h4>
                    <p class="category-count">${category.count}</p>
                `;
                
                categoriesGrid.appendChild(categoryCard);
                
                // Add entrance animation
                setTimeout(() => {
                    categoryCard.style.animation = 'fadeInUp 0.6s ease-out';
                }, index * 100);
            });
        }

        // Initialize products
        function initializeProducts() {
            const productsGrid = document.getElementById('productsGrid');
            
            products.forEach((product, index) => {
                const productCard = createProductCard(product, index);
                productsGrid.appendChild(productCard);
            });
        }

        // Create product card
        function createProductCard(product, index) {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            productCard.style.animationDelay = (index * 0.1) + 's';
            productCard.dataset.category = product.category;
            
            const badgeHtml = product.badge ? `<div class="product-badge ${product.badge}">${getBadgeText(product.badge)}</div>` : '';
            const originalPriceHtml = product.originalPrice ? `<span class="price-original">${product.originalPrice} تومان</span>` : '';
            
            productCard.innerHTML = `
                <div class="product-image">
                    ${product.image}
                    ${badgeHtml}
                </div>
                <div class="product-info">
                    <h5 class="product-name">${product.name}</h5>
                    <p class="product-description">${product.description}</p>
                    <div class="product-rating">
                        <div class="stars">${generateStars(product.rating)}</div>
                        <span class="rating-count">(${product.reviews} نظر)</span>
                    </div>
                    <div class="product-price">
                        <div>
                            <span class="price-current">${product.price} تومان</span>
                            ${originalPriceHtml}
                        </div>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart-btn" onclick="addToCart(${product.id})">
                            <i class="bi bi-bag-plus"></i> افزودن به سبد
                        </button>
                        <button class="wishlist-btn" onclick="toggleWishlist(this, ${product.id})">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Add entrance animation
            setTimeout(() => {
                productCard.style.animation = 'fadeInUp 0.6s ease-out';
            }, index * 100);
            
            return productCard;
        }

        // Generate stars for rating
        function generateStars(rating) {
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 !== 0;
            let starsHtml = '';
            
            for (let i = 0; i < fullStars; i++) {
                starsHtml += '<i class="bi bi-star-fill"></i>';
            }
            
            if (hasHalfStar) {
                starsHtml += '<i class="bi bi-star-half"></i>';
            }
            
            const emptyStars = 5 - Math.ceil(rating);
            for (let i = 0; i < emptyStars; i++) {
                starsHtml += '<i class="bi bi-star"></i>';
            }
            
            return starsHtml;
        }

        // Get badge text
        function getBadgeText(badge) {
            const badges = {
                'sale': 'تخفیف',
                'new': 'جدید',
                'popular': 'پرفروش',
                'custom': 'سفارشی'
            };
            return badges[badge] || badge;
        }

        // Initialize filter tabs
        function initializeFilterTabs() {
            const filterTabs = document.querySelectorAll('.filter-tab');
            
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    const filter = this.dataset.filter;
                    filterProducts(filter);
                    
                    // Add click animation
                    this.style.animation = 'bounce 0.6s ease-in-out';
                    setTimeout(() => {
                        this.style.animation = '';
                    }, 600);
                });
            });
        }

        // Filter products
        function filterProducts(filter) {
            const productCards = document.querySelectorAll('.product-card');
            
            productCards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.6s ease-out';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Filter by category
        function filterByCategory(categoryId) {
            const filterTabs = document.querySelectorAll('.filter-tab');
            filterTabs.forEach(tab => tab.classList.remove('active'));
            
            const targetTab = document.querySelector(`[data-filter="${categoryId}"]`);
            if (targetTab) {
                targetTab.classList.add('active');
                filterProducts(categoryId);
            }
            
            // Scroll to products section
            document.querySelector('.products-section').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }

        // Add to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;
            
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: productId,
                    name: product.name,
                    price: parseInt(product.price.replace(/,/g, '')),
                    quantity: 1,
                    image: product.image
                });
            }
            
            updateCartUI();
            showToast(`${product.name} به سبد خرید اضافه شد!`, 'success');
            
            // Add sparkle effect
            const button = event.target;
            addSparkleEffect(button);
        }

        // Toggle wishlist
        function toggleWishlist(button, productId) {
            button.classList.toggle('active');
            
            if (button.classList.contains('active')) {
                button.innerHTML = '<i class="bi bi-heart-fill"></i>';
                showToast('به لیست علاقه‌مندی‌ها اضافه شد!', 'success');
            } else {
                button.innerHTML = '<i class="bi bi-heart"></i>';
                showToast('از لیست علاقه‌مندی‌ها حذف شد!', 'info');
            }
            
            // Add animation
            button.style.animation = 'heartbeat 0.6s ease-in-out';
            setTimeout(() => {
                button.style.animation = '';
            }, 600);
        }

        // Initialize cart
        function initializeCart() {
            updateCartUI();
        }

        // Update cart UI
        function updateCartUI() {
            const cartBadge = document.getElementById('cartBadge');
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            
            // Update badge
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartBadge.textContent = totalItems;
            
            // Update cart items
            cartItems.innerHTML = '';
            let total = 0;
            
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                
                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <div class="cart-item-image">${item.image}</div>
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">${item.price.toLocaleString()} تومان</div>
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                            <input type="number" class="quantity-input" value="${item.quantity}" readonly>
                            <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                        </div>
                    </div>
                `;
                
                cartItems.appendChild(cartItem);
            });
            
            // Update total
            cartTotal.textContent = total.toLocaleString() + ' تومان';
        }

        // Update quantity
        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (!item) return;
            
            item.quantity += change;
            
            if (item.quantity <= 0) {
                cart = cart.filter(item => item.id !== productId);
                showToast('محصول از سبد خرید حذف شد!', 'info');
            }
            
            updateCartUI();
        }

        // Toggle cart
        function toggleCart() {
            const cartSidebar = document.getElementById('cartSidebar');
            const cartOverlay = document.getElementById('cartOverlay');
            
            if (cartSidebar.classList.contains('open')) {
                cartSidebar.classList.remove('open');
                cartOverlay.style.display = 'none';
            } else {
                cartSidebar.classList.add('open');
                cartOverlay.style.display = 'block';
            }
        }

        // Proceed to checkout
        function proceedToCheckout() {
            if (cart.length === 0) {
                showToast('سبد خرید شما خالی است!', 'error');
                return;
            }
            
            showToast('در حال انتقال به صفحه پرداخت...', 'info');
            
            setTimeout(() => {
                showToast('پرداخت با موفقیت انجام شد!', 'success');
                cart = [];
                updateCartUI();
                toggleCart();
            }, 2000);
        }

        // Start countdown timer
        function startCountdown() {
            const targetDate = new Date();
            targetDate.setDate(targetDate.getDate() + 3);
            targetDate.setHours(targetDate.getHours() + 14);
            targetDate.setMinutes(targetDate.getMinutes() + 27);
            targetDate.setSeconds(targetDate.getSeconds() + 45);
            
            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetDate.getTime() - now;
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('days').textContent = days.toString().padStart(2, '0');
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
                
                if (distance < 0) {
                    clearInterval(countdownTimer);
                    document.querySelector('.countdown-timer').innerHTML = '<h3>فروش ویژه به پایان رسید!</h3>';
                }
            }
            
            const countdownTimer = setInterval(updateCountdown, 1000);
            updateCountdown();
        }

        // Show offer products
        function showOfferProducts() {
            const filterTabs = document.querySelectorAll('.filter-tab');
            filterTabs.forEach(tab => tab.classList.remove('active'));
            filterTabs[0].classList.add('active');
            
            filterProducts('all');
            
            // Scroll to products
            document.querySelector('.products-section').scrollIntoView({ 
                behavior: 'smooth' 
            });
            
            showToast('محصولات تخفیف‌دار نمایش داده شد!', 'success');
        }

        // Perform search
        function performSearch() {
            const searchInput = document.getElementById('searchInput');
            const query = searchInput.value.trim();
            
            if (!query) {
                showToast('لطفاً کلمه‌ای برای جستجو وارد کنید!', 'error');
                return;
            }
            
            showToast(`در حال جستجو برای "${query}"...`, 'info');
            
            // Simulate search
            setTimeout(() => {
                showToast(`${Math.floor(Math.random() * 20) + 5} محصول یافت شد!`, 'success');
                document.querySelector('.products-section').scrollIntoView({ 
                    behavior: 'smooth' 
                });
            }, 1000);
        }

        // Start live updates
        function startLiveUpdates() {
            // Update live viewer count
            setInterval(() => {
                const viewerCount = Math.floor(Math.random() * 25) + 35;
                const liveViewers = document.getElementById('liveViewers');
                liveViewers.textContent = viewerCount;
            }, 3000);
            
            // Update cart badge randomly
            setInterval(() => {
                const currentCount = parseInt(document.getElementById('cartBadge').textContent);
                const change = Math.random() > 0.7 ? 1 : 0;
                if (change && currentCount < 10) {
                    updateCartBadge(currentCount + 1);
                }
            }, 8000);
            
            // Animate product cards randomly
            setInterval(() => {
                const productCards = document.querySelectorAll('.product-card');
                const randomCard = productCards[Math.floor(Math.random() * productCards.length)];
                if (randomCard) {
                    randomCard.style.animation = 'pulse 1s ease-in-out';
                    setTimeout(() => {
                        randomCard.style.animation = '';
                    }, 1000);
                }
            }, 10000);
        }
        

        
        // Update cart badge
        function updateCartBadge(count) {
            const badge = document.getElementById('cartBadge');
            badge.textContent = count;
            badge.style.animation = 'heartbeat 0.6s ease-in-out';
            setTimeout(() => {
                badge.style.animation = 'heartbeat 2s ease-in-out infinite';
            }, 600);
        }

        // Add sparkle effect
        function addSparkleEffect(element) {
            const sparkles = ['✨', '⭐', '💫', '🌟', '💖', '🎉'];
            
            for (let i = 0; i < 6; i++) {
                const sparkle = document.createElement('div');
                sparkle.textContent = sparkles[Math.floor(Math.random() * sparkles.length)];
                sparkle.style.cssText = `
                    position: absolute;
                    font-size: ${Math.random() * 1 + 1.2}rem;
                    pointer-events: none;
                    animation: sparkle 1.5s ease-out forwards;
                    top: ${Math.random() * 100}%;
                    left: ${Math.random() * 100}%;
                    z-index: 1000;
                `;
                
                element.style.position = 'relative';
                element.appendChild(sparkle);
                
                setTimeout(() => {
                    sparkle.remove();
                }, 1500);
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
            }, 3000);
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
            document.querySelectorAll('.category-card, .product-card').forEach(el => {
                observer.observe(el);
            });
        }



        // Search on Enter key
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });

        // Page load animation
        window.addEventListener('load', function() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.8s ease-in-out';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9727ecc6d766dcc0',t:'MTc1NTc1NjE4MS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
