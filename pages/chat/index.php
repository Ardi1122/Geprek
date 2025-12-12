<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pemilik') {
    header("Location: ../../index.php");
    exit();
}
require_once '../../config/database.php';
$path_to_root = "../..";
?>
<?php include '../../includes/header.php'; ?>

<!-- Google Material Symbols -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<?php include '../../includes/sidebar.php'; ?>

<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="container-fluid p-3 p-md-4">
        <div class="mb-4">
            <h2 class="fw-bold mb-1" style="color: #212529;">Business Assistant</h2>
            <p class="text-muted mb-0">Tanyakan apa saja tentang bisnis Anda kepada AI assistant</p>
        </div>

        <div class="row">
            <div class="col-12 col-xl-8 mx-auto">
                <div class="chat-card">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <div class="d-flex align-items-center">
                            <div class="bot-avatar">
                                <i class="bi bi-robot"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold">Chatbot Assistant</h5>
                                <small class="text-muted">
                                    <span class="status-dot"></span>
                                    Online
                                </small>
                            </div>
                        </div>
                        <button class="btn-clear-chat" onclick="clearChat()" title="Clear Chat">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>

                    <!-- Chat Body -->
                    <div class="chat-body" id="chatBox">
                        <div class="chat-message bot">
                            <div class="message-avatar">
                                <i class="bi bi-robot"></i>
                            </div>
                            <div class="message-content">
                                <div class="message-bubble">
                                    <p class="mb-2"><span class="material-symbols-outlined" style="vertical-align: middle; font-size: 20px;">waving_hand</span> Halo! Saya asisten virtual Anda.</p>
                                    <p class="mb-2">Saya dapat membantu Anda dengan:</p>
                                    <ul class="mb-0 info-list">
                                        <li><span class="material-symbols-outlined">payments</span> Informasi penghasilan</li>
                                        <li><span class="material-symbols-outlined">emoji_events</span> Menu terlaris</li>
                                        <li><span class="material-symbols-outlined">inventory_2</span> Status stok bahan baku</li>
                                        <li><span class="material-symbols-outlined">analytics</span> Data transaksi</li>
                                    </ul>
                                </div>
                                <div class="message-time"><?php echo date('H:i'); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <div class="quick-actions-label">
                            <i class="bi bi-lightning-charge-fill me-1"></i>
                            Quick Ask
                        </div>
                        <div class="quick-buttons">
                            <button class="quick-btn" onclick="sendQuickMessage('Penghasilan hari ini')">
                                <span class="material-symbols-outlined">payments</span>
                                <span>Penghasilan Hari Ini</span>
                            </button>
                            <button class="quick-btn" onclick="sendQuickMessage('Menu terlaris')">
                                <span class="material-symbols-outlined">emoji_events</span>
                                <span>Menu Terlaris</span>
                            </button>
                            <button class="quick-btn" onclick="sendQuickMessage('Stok menipis')">
                                <span class="material-symbols-outlined">inventory_2</span>
                                <span>Stok Menipis</span>
                            </button>
                            <button class="quick-btn" onclick="sendQuickMessage('Total transaksi hari ini')">
                                <span class="material-symbols-outlined">analytics</span>
                                <span>Transaksi Hari Ini</span>
                            </button>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="chat-input-wrapper">
                        <div class="chat-input-container">
                            <input type="text" 
                                   id="userInput" 
                                   class="chat-input"
                                   placeholder="Ketik pertanyaan Anda disini..." 
                                   onkeypress="handleEnter(event)">
                            <button class="btn-send" onclick="sendMessage()">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/chat/index.css">

<script>
    function handleEnter(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    function sendQuickMessage(text) {
        document.getElementById('userInput').value = text;
        sendMessage();
    }

    function sendMessage() {
        const input = document.getElementById('userInput');
        const message = input.value.trim();

        if (message === '') return;

        // Add user message
        addMessage(message, 'user');
        input.value = '';

        // Show typing indicator
        showTypingIndicator();

        // Send to backend
        fetch('response.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'message=' + encodeURIComponent(message)
        })
        .then(response => response.text())
        .then(data => {
            removeTypingIndicator();
            addMessage(data, 'bot');
        })
        .catch(error => {
            console.error('Error:', error);
            removeTypingIndicator();
            addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
        });
    }

    function addMessage(text, sender) {
        const chatBox = document.getElementById('chatBox');
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${sender}`;
        
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="bi bi-${sender === 'user' ? 'person' : 'robot'}"></i>
            </div>
            <div class="message-content">
                <div class="message-bubble">${text}</div>
                <div class="message-time">${time}</div>
            </div>
        `;
        
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function showTypingIndicator() {
        const chatBox = document.getElementById('chatBox');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-message bot';
        typingDiv.id = 'typingIndicator';
        
        typingDiv.innerHTML = `
            <div class="message-avatar">
                <i class="bi bi-robot"></i>
            </div>
            <div class="message-content">
                <div class="message-bubble">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            </div>
        `;
        
        chatBox.appendChild(typingDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    function clearChat() {
        if (confirm('Apakah Anda yakin ingin menghapus semua chat?')) {
            const chatBox = document.getElementById('chatBox');
            chatBox.innerHTML = `
                <div class="chat-message bot">
                    <div class="message-avatar">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p class="mb-2"><span class="material-symbols-outlined" style="vertical-align: middle; font-size: 20px;">waving_hand</span> Halo! Saya asisten virtual Anda.</p>
                            <p class="mb-2">Saya dapat membantu Anda dengan:</p>
                            <ul class="mb-0 info-list">
                                <li><span class="material-symbols-outlined">payments</span> Informasi penghasilan</li>
                                <li><span class="material-symbols-outlined">emoji_events</span> Menu terlaris</li>
                                <li><span class="material-symbols-outlined">inventory_2</span> Status stok bahan baku</li>
                                <li><span class="material-symbols-outlined">analytics</span> Data transaksi</li>
                            </ul>
                        </div>
                        <div class="message-time">${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</div>
                    </div>
                </div>
            `;
        }
    }

    // Auto-focus input on load
    window.addEventListener('load', function() {
        document.getElementById('userInput').focus();
    });
</script>

<?php include '../../includes/footer.php'; ?>