<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چت شادینو</title>
    
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
            --gradient-primary: linear-gradient(135deg, #FF69B4, #FFB6C1);
            --gradient-secondary: linear-gradient(135deg, #FFF0F5, #FFE4E1);
            --shadow-primary: 0 15px 35px rgba(255, 105, 180, 0.3);
            --shadow-secondary: 0 8px 25px rgba(0, 0, 0, 0.1);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background: linear-gradient(135deg, #FFE4E1 0%, #FFF0F5 50%, #F8F9FA 100%);
            min-height: 100vh;
            overflow: hidden;
        }
        
        /* Chat Container */
        .chat-container {
            height: 100vh;
            display: flex;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        /* Sidebar */
        .chat-sidebar {
            width: 320px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-left: 2px solid rgba(255, 105, 180, 0.1);
            box-shadow: var(--shadow-secondary);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            background: var(--gradient-primary);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: rotate 10s linear infinite;
        }
        
        .sidebar-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
            position: relative;
            z-index: 2;
        }
        
        .online-count {
            font-size: 0.9rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }
        
        .users-list {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }
        
        .user-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .user-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 105, 180, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .user-item:hover {
            background: rgba(255, 105, 180, 0.1);
            transform: translateX(-5px);
        }
        
        .user-item:hover::before {
            left: 100%;
        }
        
        .user-item.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.4);
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--gradient-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-left: 1rem;
            position: relative;
        }
        
        .user-status {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        .user-status.online {
            background: var(--success-color);
            animation: pulse 2s ease-in-out infinite;
        }
        
        .user-status.offline {
            background: #6C757D;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .user-message {
            font-size: 0.85rem;
            opacity: 0.7;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .message-time {
            font-size: 0.75rem;
            opacity: 0.6;
        }
        
        /* Chat Main */
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        
        .chat-header {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 2px solid rgba(255, 105, 180, 0.1);
            box-shadow: var(--shadow-secondary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .chat-user-info {
            display: flex;
            align-items: center;
        }
        
        .chat-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin-left: 1rem;
            position: relative;
        }
        
        .chat-user-details h4 {
            margin: 0;
            font-weight: 700;
            color: #212529;
        }
        
        .chat-user-status {
            font-size: 0.9rem;
            color: var(--success-color);
            font-weight: 600;
        }
        
        .chat-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .chat-action-btn {
            width: 40px;
            height: 40px;
            border: 2px solid var(--primary-color);
            border-radius: 50%;
            background: white;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .chat-action-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Messages Area */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="hearts" x="0" y="0" width="25" height="25" patternUnits="userSpaceOnUse"><text x="12.5" y="18" text-anchor="middle" font-size="12" fill="rgba(255,105,180,0.05)">💖</text></pattern></defs><rect width="100" height="100" fill="url(%23hearts)"/></svg>');
        }
        
        .message {
            display: flex;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.5s ease-out;
        }
        
        .message.own {
            justify-content: flex-end;
        }
        
        .message-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--gradient-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin: 0 0.75rem;
            flex-shrink: 0;
        }
        
        .message.own .message-avatar {
            background: var(--gradient-primary);
            color: white;
        }
        
        .message-content {
            max-width: 70%;
            position: relative;
        }
        
        .message-bubble {
            background: white;
            padding: 1rem 1.25rem;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            position: relative;
            word-wrap: break-word;
        }
        
        .message.own .message-bubble {
            background: var(--gradient-primary);
            color: white;
        }
        
        .message-bubble::before {
            content: '';
            position: absolute;
            top: 15px;
            width: 0;
            height: 0;
            border: 8px solid transparent;
        }
        
        .message:not(.own) .message-bubble::before {
            right: -16px;
            border-right-color: white;
        }
        
        .message.own .message-bubble::before {
            left: -16px;
            border-left-color: var(--primary-color);
        }
        
        .message-text {
            margin: 0;
            line-height: 1.5;
        }
        
        .message-time {
            font-size: 0.75rem;
            opacity: 0.7;
            margin-top: 0.5rem;
            text-align: left;
        }
        
        .message.own .message-time {
            text-align: right;
        }
        
        /* Message Input */
        .message-input-container {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-top: 2px solid rgba(255, 105, 180, 0.1);
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .message-input-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: white;
            border-radius: 25px;
            padding: 0.75rem 1.25rem;
            box-shadow: var(--card-shadow);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .message-input-wrapper:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.2);
        }
        
        .emoji-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .emoji-btn:hover {
            transform: scale(1.2);
        }
        
        .message-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 1rem;
            padding: 0.5rem 0;
            background: transparent;
        }
        
        .message-input::placeholder {
            color: #6C757D;
        }
        
        .send-btn {
            background: var(--gradient-primary);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .send-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.4);
        }
        
        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
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
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .chat-container {
                flex-direction: column;
            }
            
            .chat-sidebar {
                width: 100%;
                height: auto;
                position: fixed;
                top: 0;
                left: -100%;
                z-index: 1000;
                transition: left 0.3s ease;
            }
            
            .chat-sidebar.open {
                left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .chat-main {
                height: 100vh;
            }
            
            .message-content {
                max-width: 85%;
            }
            
            .users-list {
                max-height: 60vh;
            }
            
            .sidebar-header {
                padding: 1rem;
            }
            
            .sidebar-title {
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 576px) {
            .chat-header {
                padding: 1rem;
            }
            
            .chat-avatar {
                width: 40px;
                height: 40px;
                font-size: 1.5rem;
            }
            
            .messages-container {
                padding: 1rem;
            }
            
            .message-input-container {
                padding: 1rem;
            }
            
            .message-content {
                max-width: 90%;
            }
            
            .user-item {
                padding: 0.75rem;
            }
            
            .user-avatar {
                width: 40px;
                height: 40px;
                font-size: 1.3rem;
            }
        }
        
        /* Scrollbar Styling */
        .users-list::-webkit-scrollbar,
        .messages-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .users-list::-webkit-scrollbar-track,
        .messages-container::-webkit-scrollbar-track {
            background: rgba(255, 105, 180, 0.1);
            border-radius: 3px;
        }
        
        .users-list::-webkit-scrollbar-thumb,
        .messages-container::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }
        
        .users-list::-webkit-scrollbar-thumb:hover,
        .messages-container::-webkit-scrollbar-thumb:hover {
            background: var(--accent-color);
        }
        
        /* Overlay for mobile */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        
        .mobile-overlay.show {
            display: block;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="chat-container">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileMenu()"></div>
        
        <!-- Sidebar -->
        <div class="chat-sidebar" id="chatSidebar">
            <div class="sidebar-header">
                <h3 class="sidebar-title">💬 چت شادینو</h3>
                <p class="online-count">🟢 <span id="onlineCount">12</span> نفر آنلاین</p>
            </div>
            
            <div class="users-list" id="usersList">
                <!-- Users will be populated by JavaScript -->
            </div>
        </div>
        
        <!-- Main Chat Area -->
        <div class="chat-main">
            <!-- Chat Header -->
            <div class="chat-header">
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="chat-user-info">
                    <div class="chat-avatar" id="currentChatAvatar">👩</div>
                    <div class="chat-user-details">
                        <h4 id="currentChatName">سارا احمدی</h4>
                        <div class="chat-user-status" id="currentChatStatus">آنلاین</div>
                    </div>
                </div>
                
                <div class="chat-actions">
                    <button class="chat-action-btn" onclick="makeCall()" title="تماس صوتی">
                        <i class="bi bi-telephone"></i>
                    </button>
                    <button class="chat-action-btn" onclick="videoCall()" title="تماس تصویری">
                        <i class="bi bi-camera-video"></i>
                    </button>
                    <button class="chat-action-btn" onclick="showInfo()" title="اطلاعات">
                        <i class="bi bi-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <!-- Messages Container -->
            <div class="messages-container" id="messagesContainer">
                <!-- Messages will be populated by JavaScript -->
            </div>
            
            <!-- Message Input -->
            <div class="message-input-container">
                <div class="message-input-wrapper">
                    <button class="emoji-btn" onclick="toggleEmojiPicker()">😊</button>
                    <input type="text" class="message-input" id="messageInput" placeholder="پیام خود را بنویسید...">
                    <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sample data
        const users = [
            { id: 1, name: 'سارا احمدی', avatar: '👩', status: 'online', lastMessage: 'سلام! چطوری؟', time: '۱۰:۳۰' },
            { id: 2, name: 'علی رضایی', avatar: '👨', status: 'online', lastMessage: 'فردا میبینمت', time: '۱۰:۱۵' },
            { id: 3, name: 'مریم کریمی', avatar: '👩‍🦰', status: 'offline', lastMessage: 'ممنون از همه چی', time: '۹:۴۵' },
            { id: 4, name: 'حسین محمدی', avatar: '👨‍💼', status: 'online', lastMessage: 'کار تموم شد', time: '۹:۳۰' },
            { id: 5, name: 'فاطمه نوری', avatar: '👩‍💻', status: 'online', lastMessage: 'عالی بود!', time: '۹:۱۵' },
            { id: 6, name: 'محمد صادقی', avatar: '👨‍🎓', status: 'offline', lastMessage: 'تا فردا', time: '۸:۵۰' }
        ];
        
        const userChats = {
            1: [
                { id: 1, sender: 'سارا احمدی', text: 'سلام! چطوری؟', time: '۱۰:۲۵', own: false },
                { id: 2, sender: 'شما', text: 'سلام سارا! خوبم ممنون، تو چطوری؟', time: '۱۰:۲۶', own: true },
                { id: 3, sender: 'سارا احمدی', text: 'منم خوبم! امروز برنامه‌ای داری؟', time: '۱۰:۲۷', own: false },
                { id: 4, sender: 'شما', text: 'آره، قراره با دوستا بریم پارک. تو هم میای؟', time: '۱۰:۲۸', own: true },
                { id: 5, sender: 'سارا احمدی', text: 'عالیه! حتماً میام 🎉', time: '۱۰:۳۰', own: false }
            ],
            2: [
                { id: 1, sender: 'علی رضایی', text: 'سلام داداش! چه خبر؟', time: '۱۰:۱۰', own: false },
                { id: 2, sender: 'شما', text: 'سلام علی! همه چی خوبه، تو چطوری؟', time: '۱۰:۱۱', own: true },
                { id: 3, sender: 'علی رضایی', text: 'عالی! فردا میبینمت', time: '۱۰:۱۵', own: false }
            ],
            3: [
                { id: 1, sender: 'مریم کریمی', text: 'سلام عزیزم! کارت چطور پیش رفت؟', time: '۹:۴۰', own: false },
                { id: 2, sender: 'شما', text: 'سلام مریم! خیلی خوب پیش رفت، ممنون', time: '۹:۴۲', own: true },
                { id: 3, sender: 'مریم کریمی', text: 'ممنون از همه چی ❤️', time: '۹:۴۵', own: false }
            ],
            4: [
                { id: 1, sender: 'حسین محمدی', text: 'سلام! پروژه آماده شد؟', time: '۹:۲۵', own: false },
                { id: 2, sender: 'شما', text: 'سلام حسین! آره، همین الان تموم شد', time: '۹:۲۸', own: true },
                { id: 3, sender: 'حسین محمدی', text: 'کار تموم شد 👍', time: '۹:۳۰', own: false }
            ],
            5: [
                { id: 1, sender: 'فاطمه نوری', text: 'سلام! نظرت راجع به پروپوزال چیه؟', time: '۹:۱۰', own: false },
                { id: 2, sender: 'شما', text: 'سلام فاطمه! خیلی عالی بود، تبریک میگم', time: '۹:۱۲', own: true },
                { id: 3, sender: 'فاطمه نوری', text: 'عالی بود! 🎉', time: '۹:۱۵', own: false }
            ],
            6: [
                { id: 1, sender: 'محمد صادقی', text: 'سلام! جلسه فردا ساعت چنده؟', time: '۸:۴۵', own: false },
                { id: 2, sender: 'شما', text: 'سلام محمد! ساعت ۱۰ صبح', time: '۸:۴۷', own: true },
                { id: 3, sender: 'محمد صادقی', text: 'باشه، تا فردا 👋', time: '۸:۵۰', own: false }
            ]
        };
        
        let currentUser = users[0];
        let isTyping = false;
        
        // Initialize chat
        document.addEventListener('DOMContentLoaded', function() {
            initializeUsers();
            initializeMessages();
            initializeEventListeners();
            startLiveUpdates();
        });
        
        // Initialize users list
        function initializeUsers() {
            const usersList = document.getElementById('usersList');
            usersList.innerHTML = '';
            
            users.forEach((user, index) => {
                const userItem = document.createElement('div');
                userItem.className = `user-item ${index === 0 ? 'active' : ''}`;
                userItem.onclick = () => selectUser(user);
                
                userItem.innerHTML = `
                    <div class="user-avatar">
                        ${user.avatar}
                        <div class="user-status ${user.status}"></div>
                    </div>
                    <div class="user-info">
                        <div class="user-name">${user.name}</div>
                        <div class="user-message">${user.lastMessage}</div>
                    </div>
                    <div class="message-time">${user.time}</div>
                `;
                
                usersList.appendChild(userItem);
            });
        }
        
        // Initialize messages
        function initializeMessages() {
            const messagesContainer = document.getElementById('messagesContainer');
            messagesContainer.innerHTML = '';
            
            const currentMessages = userChats[currentUser.id] || [];
            currentMessages.forEach(message => {
                addMessageToChat(message);
            });
            
            scrollToBottom();
        }
        
        // Add message to chat
        function addMessageToChat(message) {
            const messagesContainer = document.getElementById('messagesContainer');
            const messageElement = document.createElement('div');
            messageElement.className = `message ${message.own ? 'own' : ''}`;
            
            messageElement.innerHTML = `
                <div class="message-avatar">${message.own ? '🙂' : currentUser.avatar}</div>
                <div class="message-content">
                    <div class="message-bubble">
                        <p class="message-text">${message.text}</p>
                        <div class="message-time">${message.time}</div>
                    </div>
                </div>
            `;
            
            messagesContainer.appendChild(messageElement);
            scrollToBottom();
        }
        
        // Select user
        function selectUser(user) {
            currentUser = user;
            
            // Update active user in sidebar
            document.querySelectorAll('.user-item').forEach(item => {
                item.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Update chat header
            document.getElementById('currentChatAvatar').textContent = user.avatar;
            document.getElementById('currentChatName').textContent = user.name;
            document.getElementById('currentChatStatus').textContent = user.status === 'online' ? 'آنلاین' : 'آفلاین';
            
            // Load messages for selected user
            initializeMessages();
            
            // Close mobile menu if open
            closeMobileMenu();
            
            showToast(`چت با ${user.name} باز شد`, 'info');
        }
        
        // Replace static data with API calls
async function fetchMessages(conversationId) {
    try {
        const response = await fetch(`/api/chat.php?conversation_id=${conversationId}`);
        const data = await response.json();
        if (data.success) {
            const messagesContainer = document.getElementById('messagesContainer');
            messagesContainer.innerHTML = '';
            data.messages.forEach(message => {
                addMessageToChat({
                    text: message.message_text,
                    time: new Date(message.sent_at).toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit' }),
                    own: message.sender_username === 'شما'
                });
            });
        }
    } catch (error) {
        console.error('Error fetching messages:', error);
    }
}

async function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const text = messageInput.value.trim();

    if (!text) return;

    const payload = {
        conversation_id: currentUser.id, // Replace with actual conversation ID
        sender_id: 1, // Replace with actual sender ID
        message_text: text
    };

    try {
        const response = await fetch('/api/chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        if (data.success) {
            addMessageToChat({
                text: text,
                time: new Date().toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit' }),
                own: true
            });
            messageInput.value = '';
        }
    } catch (error) {
        console.error('Error sending message:', error);
    }
}

// Call fetchMessages when a user is selected
function selectUser(user) {
    currentUser = user;
    fetchMessages(user.id);
    // ...existing code...
}
    </script>
<script>
// Shadino DM integration: fetch proposals and render in the sidebar under a special section
(function(){
    let CURRENT_USER = null;
    async function fetchShadino() {
        try {
            const res = await fetch('/api/shadino.php', { credentials: 'same-origin' });
            const data = await res.json();
            if (!data.success) return;
            const payload = data.data;
            CURRENT_USER = payload.current_user || null;
            renderShadino(payload.proposals || []);
        } catch (err) {
            console.error('Failed to load Shadino data', err);
        }
    }

    function renderShadino(proposals) {
        if (!proposals || !proposals.length) return;

        const usersList = document.getElementById('usersList');
        if (!usersList) return;

        // Create section container
        const section = document.createElement('div');
        section.className = 'mb-3';
        section.style.padding = '0 1rem 0 1rem';

        const header = document.createElement('div');
        header.className = 'sidebar-title p-2';
        header.style.background = 'linear-gradient(135deg,#FF69B4,#FFB6C1)';
        header.style.color = '#fff';
        header.style.borderRadius = '12px';
        header.style.marginBottom = '8px';
        header.textContent = 'شادینو — پیشنهادها';
        section.appendChild(header);

        proposals.forEach(p => {
            const item = document.createElement('div');
            item.className = 'user-item';
            item.style.display = 'flex';
            item.style.justifyContent = 'space-between';
            item.style.alignItems = 'center';

            const left = document.createElement('div');
            left.style.display = 'flex';
            left.style.alignItems = 'center';

            const avatar = document.createElement('div');
            avatar.className = 'user-avatar';
            avatar.textContent = '💼';

            const info = document.createElement('div');
            info.className = 'user-info';
            info.style.maxWidth = '160px';

            const name = document.createElement('div');
            name.className = 'user-name';
            name.textContent = escapeHtml(p.business_name || p.ad_owner_name || 'کسب‌وکار');

            const msg = document.createElement('div');
            msg.className = 'user-message';
            msg.textContent = 'آگهی: ' + (p.ad_title ? escapeHtml(p.ad_title) : '') + (p.price ? (' — ' + p.price + ' تومان') : '');

            info.appendChild(name);
            info.appendChild(msg);
            left.appendChild(avatar);
            left.appendChild(info);

            const actions = document.createElement('div');
            actions.style.display = 'flex';
            actions.style.flexDirection = 'column';
            actions.style.gap = '6px';
            actions.style.alignItems = 'flex-start';

            const accept = document.createElement('a');
            accept.href = '#';
            accept.className = 'btn btn-sm btn-outline-primary';
            accept.textContent = 'پذیرش / باز کردن چت';

            const details = document.createElement('a');
            details.href = '/api/proposals.php?ad_id=' + encodeURIComponent(p.ad_id);
            details.className = 'btn btn-sm btn-link';
            details.textContent = 'جزئیات پیشنهاد';

            actions.appendChild(accept);
            actions.appendChild(details);

            item.appendChild(left);
            item.appendChild(actions);

            // Determine partner id: prefer ad_owner_id if present, otherwise business_id
            const partnerId = p.ad_owner_id || p.business_id || p.business_id;
            item.dataset.partnerId = partnerId;
            item.dataset.adId = p.ad_id;
            item.dataset.proposalId = p.id;

            accept.addEventListener('click', async function(e){
                e.preventDefault();
                if (!partnerId) { alert('شناسه مخاطب نامشخص است'); return; }
                // If current user is ad owner, call accept API first
                const isAdOwner = CURRENT_USER && CURRENT_USER.id && (CURRENT_USER.id === p.ad_owner_id || CURRENT_USER.id === p.ad_owner_id);
                if (isAdOwner) {
                    try {
                        const resp = await fetch('/api/shadino.php?action=accept', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ proposal_id: p.id })
                        });
                        const rj = await resp.json();
                        if (!rj.success) {
                            alert(rj.message || 'خطا در پذیرش پیشنهاد');
                            return;
                        }
                    } catch (err) {
                        console.error(err);
                        alert('خطا در ارتباط با سرور');
                        return;
                    }
                }

                // Redirect to chat page with conversation_id and ad_id so server can load messages for this ad
                window.location.href = '/chat.php?conversation_id=' + encodeURIComponent(partnerId) + '&ad_id=' + encodeURIComponent(p.ad_id);
            });

            section.appendChild(item);
        });

        // Prepend the Shadino section at the top of users list
        usersList.prepend(section);
    }

    function escapeHtml(str){
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(s){
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"})[s];
        });
    }

    document.addEventListener('DOMContentLoaded', fetchShadino);
})();
</script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9727b28e00b8dcc0',t:'MTc1NTc1Mzc5Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>

<script>
// Close chat functionality
async function closeChat(conversationId) {
    try {
        const response = await fetch(`/api/chat.php?action=close&conversation_id=${conversationId}`, {
            method: 'POST'
        });
        const data = await response.json();
        if (data.success) {
            alert('چت بسته شد');
            location.reload();
        } else {
            alert('خطا در بستن چت');
        }
    } catch (error) {
        console.error('Error closing chat:', error);
    }
}

// Fetch and display open and closed chats
async function fetchChats() {
    try {
        const response = await fetch('/api/chat.php?action=list');
        const data = await response.json();
        if (data.success) {
            const openChats = data.chats.filter(chat => !chat.closed);
            const closedChats = data.chats.filter(chat => chat.closed);

            // Render open chats
            const openChatsContainer = document.getElementById('openChats');
            openChatsContainer.innerHTML = '';
            openChats.forEach(chat => {
                const chatItem = document.createElement('div');
                chatItem.textContent = `چت با ${chat.partner_name}`;
                const closeButton = document.createElement('button');
                closeButton.textContent = 'بستن چت';
                closeButton.onclick = () => closeChat(chat.id);
                chatItem.appendChild(closeButton);
                openChatsContainer.appendChild(chatItem);
            });

            // Render closed chats
            const closedChatsContainer = document.getElementById('closedChats');
            closedChatsContainer.innerHTML = '';
            closedChats.forEach(chat => {
                const chatItem = document.createElement('div');
                chatItem.textContent = `چت بسته شده با ${chat.partner_name}`;
                closedChatsContainer.appendChild(chatItem);
            });
        }
    } catch (error) {
        console.error('Error fetching chats:', error);
    }
}

// Call fetchChats on page load
document.addEventListener('DOMContentLoaded', fetchChats);
</script>

<div id="openChats">
    <h3>چت‌های باز</h3>
    <!-- Open chats will be rendered here -->
</div>

<div id="closedChats">
    <h3>چت‌های بسته</h3>
    <!-- Closed chats will be rendered here -->
</div>
</body>
</html>
