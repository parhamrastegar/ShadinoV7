<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Ensure user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$other_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

include 'header.php';
?>

<div class="chat-container">
    <!-- Chat list sidebar -->
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <h3>گفتگوها</h3>
        </div>
        <div class="chat-list" id="chatList">
            <!-- Chat list will be populated by JavaScript -->
        </div>
    </div>

    <!-- Chat main area -->
    <div class="chat-main" id="chatMain">
        <?php if (!$other_user_id): ?>
            <div class="chat-welcome">
                <h2>به بخش گفتگو خوش آمدید</h2>
                <p>لطفاً یک گفتگو را از سمت راست انتخاب کنید</p>
            </div>
        <?php else: ?>
            <div class="chat-header" id="chatHeader">
                <!-- Chat header will be populated by JavaScript -->
            </div>
            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be populated by JavaScript -->
            </div>
            <div class="chat-input">
                <form id="messageForm" class="message-form">
                    <input type="text" id="messageInput" placeholder="پیام خود را بنویسید..." required>
                    <button type="submit">ارسال</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.chat-container {
    display: flex;
    height: calc(100vh - 60px);
    margin-top: 60px;
    direction: rtl;
}

.chat-sidebar {
    width: 300px;
    border-left: 1px solid #ddd;
    display: flex;
    flex-direction: column;
}

.chat-sidebar-header {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    background: #f8f9fa;
}

.chat-list {
    flex: 1;
    overflow-y: auto;
}

.chat-item {
    padding: 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.chat-item:hover {
    background: #f8f9fa;
}

.chat-item.active {
    background: #e9ecef;
}

.chat-item-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-left: 10px;
}

.chat-item-content {
    flex: 1;
}

.chat-item-name {
    font-weight: bold;
    margin-bottom: 5px;
}

.chat-item-message {
    color: #666;
    font-size: 0.9em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chat-welcome {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #666;
}

.chat-header {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    background: #f8f9fa;
    display: flex;
    align-items: center;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
}

.message {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

.message.sent {
    align-items: flex-start;
}

.message.received {
    align-items: flex-end;
}

.message-content {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 15px;
    margin-bottom: 5px;
}

.message.sent .message-content {
    background: #007bff;
    color: white;
}

.message.received .message-content {
    background: #e9ecef;
}

.message-time {
    font-size: 0.8em;
    color: #666;
}

.chat-input {
    padding: 15px;
    border-top: 1px solid #ddd;
}

.message-form {
    display: flex;
    gap: 10px;
}

.message-form input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.message-form button {
    padding: 10px 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.message-form button:hover {
    background: #0056b3;
}

.unread-count {
    background: #007bff;
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 0.8em;
    margin-right: 5px;
}
</style>

<script>
let currentConversationId = null;
let lastMessageTime = null;
const userId = <?php echo $user_id; ?>;
const initialOtherUserId = <?php echo $other_user_id ? $other_user_id : 'null'; ?>;

// Initialize chat
document.addEventListener('DOMContentLoaded', () => {
    loadConversations();
    if (initialOtherUserId) {
        startConversation(initialOtherUserId);
    }
    
    // Set up message form
    const messageForm = document.getElementById('messageForm');
    if (messageForm) {
        messageForm.addEventListener('submit', sendMessage);
    }
});

// Load conversations list
function loadConversations() {
    fetch('/api/chat.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const chatList = document.getElementById('chatList');
                chatList.innerHTML = data.conversations.map(conv => `
                    <div class="chat-item ${conv.id === currentConversationId ? 'active' : ''}" 
                         onclick="loadConversation(${conv.id})">
                        <img src="${conv.other_profile_image || 'default-avatar.jpg'}" 
                             class="chat-item-avatar" alt="Avatar">
                        <div class="chat-item-content">
                            <div class="chat-item-name">
                                ${conv.other_username}
                                ${conv.unread_count > 0 ? 
                                  `<span class="unread-count">${conv.unread_count}</span>` : ''}
                            </div>
                            <div class="chat-item-message">${conv.last_message || 'No messages yet'}</div>
                        </div>
                    </div>
                `).join('');
            }
        });
}

// Start new conversation
function startConversation(otherUserId) {
    fetch(`/api/chat.php?with_user=${otherUserId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadConversation(data.conversation_id);
            }
        });
}

// Load conversation messages
function loadConversation(conversationId) {
    currentConversationId = conversationId;
    document.querySelectorAll('.chat-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`.chat-item[onclick="loadConversation(${conversationId})"]`)?.classList.add('active');
    
    fetch(`/api/chat.php?conversation_id=${conversationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const messages = data.messages;
                const chatMessages = document.getElementById('chatMessages');
                
                chatMessages.innerHTML = messages.map(msg => `
                    <div class="message ${msg.sender_id == userId ? 'sent' : 'received'}">
                        <div class="message-content">${msg.message}</div>
                        <div class="message-time">
                            ${new Date(msg.created_at).toLocaleTimeString()}
                        </div>
                    </div>
                `).join('');
                
                chatMessages.scrollTop = chatMessages.scrollHeight;
                lastMessageTime = messages.length ? messages[messages.length - 1].created_at : null;
                
                // Start polling for new messages
                startPolling();
            }
        });
}

// Send new message
function sendMessage(event) {
    event.preventDefault();
    
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message || !currentConversationId) return;
    
    fetch('/api/chat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            conversation_id: currentConversationId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            loadConversation(currentConversationId);
        }
    });
}

// Polling for new messages
let pollingInterval;
function startPolling() {
    // Clear any existing polling
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
    
    // Start new polling
    pollingInterval = setInterval(() => {
        if (currentConversationId) {
            loadConversation(currentConversationId);
        }
    }, 3000); // Poll every 3 seconds
}

// Clean up when leaving page
window.addEventListener('beforeunload', () => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>

<?php include 'footer.php'; ?>
