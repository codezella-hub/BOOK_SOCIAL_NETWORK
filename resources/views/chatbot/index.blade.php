@extends('layouts.user-layout')

@section('title', 'Assistant AI - Livres')

@section('content')
<div class="chatbot-container">
    <!-- Background Books Decoration -->
    <div class="books-background">
        <div class="floating-book book-1">📚</div>
        <div class="floating-book book-2">📖</div>
        <div class="floating-book book-3">📓</div>
        <div class="floating-book book-4">📔</div>
        <div class="floating-book book-5">📕</div>
        <div class="floating-book book-6">📗</div>
        <div class="floating-book book-7">📘</div>
        <div class="floating-book book-8">📙</div>
    </div>

    <div class="chat-header">
        <div class="chat-header-content">
            <div class="bot-avatar">
                <div class="avatar-inner">
                    <i class="fas fa-robot"></i>
                    <div class="ai-pulse"></div>
                </div>
            </div>
            <div class="bot-info">
                <h1><i class="fas fa-brain"></i> Assistant AI <span class="ai-badge">Spécialiste Livres</span></h1>
                <p><i class="fas fa-sparkles"></i> Intelligence artificielle dédiée à la littérature et aux livres !</p>
                @if(isset($donation))
                    <div class="context-info">
                        <i class="fas fa-bookmark"></i>
                        Discussion à propos de : <strong>{{ $donation->book_title }}</strong> 
                        @if($donation->author) par {{ $donation->author }} @endif
                    </div>
                @endif
            </div>
            <div class="chat-actions">
                <button class="btn-clear-chat" onclick="clearChat()" title="Effacer la conversation">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <a href="{{ route('user.donations.index') }}" class="btn-back" title="Retour aux donations">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="chat-body" id="chatBody">
        <div class="welcome-message">
            <div class="message bot-message">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <div class="welcome-header">
                        <h3><i class="fas fa-sparkles"></i> Bienvenue dans Assistant AI</h3>
                        <p class="ai-tagline">🤖 Votre intelligence artificielle spécialisée en littérature</p>
                    </div>
                    
                    <div class="book-showcase">
                        <div class="book-item">
                            <div class="book-cover">📚</div>
                            <span>Fiction</span>
                        </div>
                        <div class="book-item">
                            <div class="book-cover">📖</div>
                            <span>Romans</span>
                        </div>
                        <div class="book-item">
                            <div class="book-cover">📓</div>
                            <span>Poésie</span>
                        </div>
                        <div class="book-item">
                            <div class="book-cover">📔</div>
                            <span>Biographies</span>
                        </div>
                        <div class="book-item">
                            <div class="book-cover">📕</div>
                            <span>Histoire</span>
                        </div>
                        <div class="book-item">
                            <div class="book-cover">�</div>
                            <span>Sciences</span>
                        </div>
                    </div>

                    <p><strong>🎯 Mes capacités spécialisées :</strong></p>
                    <div class="capabilities-grid">
                        <div class="capability-item">
                            <i class="fas fa-search"></i>
                            <span>Recherche de livres</span>
                        </div>
                        <div class="capability-item">
                            <i class="fas fa-star"></i>
                            <span>Recommandations personnalisées</span>
                        </div>
                        <div class="capability-item">
                            <i class="fas fa-user-graduate"></i>
                            <span>Analyses d'auteurs</span>
                        </div>
                        <div class="capability-item">
                            <i class="fas fa-theater-masks"></i>
                            <span>Exploration des genres</span>
                        </div>
                        <div class="capability-item">
                            <i class="fas fa-chart-line"></i>
                            <span>Critiques littéraires</span>
                        </div>
                        <div class="capability-item">
                            <i class="fas fa-lightbulb"></i>
                            <span>Conseils de lecture</span>
                        </div>
                    </div>
                    @if(isset($context) && !empty($context))
                        <div class="quick-suggestions">
                            <p><strong>Suggestions rapides :</strong></p>
                            <div class="suggestion-buttons">
                                @if(isset($context['book_title']))
                                    <button class="suggestion-btn" onclick="askQuestion('Parle-moi du livre {{ $context['book_title'] }}')">
                                        📖 À propos de ce livre
                                    </button>
                                    <button class="suggestion-btn" onclick="askQuestion('Recommande-moi des livres similaires à {{ $context['book_title'] }}')">
                                        🔗 Livres similaires
                                    </button>
                                @endif
                                @if(isset($context['author']))
                                    <button class="suggestion-btn" onclick="askQuestion('Parle-moi de l\'auteur {{ $context['author'] }}')">
                                        ✍️ À propos de l'auteur
                                    </button>
                                @endif
                                @if(isset($context['genre']))
                                    <button class="suggestion-btn" onclick="askQuestion('Recommande-moi d\'autres livres du genre {{ $context['genre'] }}')">
                                        🎭 Plus de {{ $context['genre'] }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="quick-suggestions">
                            <p><strong>Pour commencer :</strong></p>
                            <div class="suggestion-buttons">
                                <button class="suggestion-btn" onclick="askQuestion('Recommande-moi un bon livre de fiction')">
                                    📚 Fiction recommandée
                                </button>
                                <button class="suggestion-btn" onclick="askQuestion('Quels sont les meilleurs romans classiques ?')">
                                    🎭 Classiques incontournables
                                </button>
                                <button class="suggestion-btn" onclick="askQuestion('Suggère-moi un livre de science-fiction')">
                                    🚀 Science-fiction
                                </button>
                                <button class="suggestion-btn" onclick="askQuestion('Quel livre lire pour se détendre ?')">
                                    😌 Lecture détente
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="chat-input-container">
        <div class="chat-input-wrapper">
            <div class="input-group">
                <input type="text" 
                       id="messageInput" 
                       placeholder="💬 Demandez à Assistant AI tout sur les livres..." 
                       maxlength="1000"
                       onkeypress="handleKeyPress(event)">
                <button id="sendButton" onclick="sendMessage()" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <div class="input-footer">
                <span class="char-count">0/1000</span>
                <span class="tip">🤖 Assistant AI : Plus votre question est précise, plus ma réponse sera utile !</span>
            </div>
        </div>
        <div class="typing-indicator" id="typingIndicator" style="display: none;">
            <div class="message bot-message">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <div class="typing-animation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chatbot-container {
    max-width: 900px;
    margin: 2rem auto;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 80vh;
    position: relative;
}

/* Background Books Animation */
.books-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    overflow: hidden;
    z-index: 1;
}

.floating-book {
    position: absolute;
    font-size: 2rem;
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
}

.book-1 { top: 10%; left: 5%; animation-delay: 0s; }
.book-2 { top: 20%; right: 10%; animation-delay: 1s; }
.book-3 { top: 40%; left: 15%; animation-delay: 2s; }
.book-4 { top: 60%; right: 5%; animation-delay: 3s; }
.book-5 { top: 80%; left: 25%; animation-delay: 4s; }
.book-6 { top: 70%; right: 20%; animation-delay: 5s; }
.book-7 { top: 30%; left: 80%; animation-delay: 1.5s; }
.book-8 { top: 50%; left: 70%; animation-delay: 3.5s; }

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    position: relative;
    z-index: 10;
}

.chat-header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.bot-avatar {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    position: relative;
    backdrop-filter: blur(10px);
}

.avatar-inner {
    position: relative;
    z-index: 2;
}

.ai-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
    50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.3; }
    100% { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
}

.bot-info {
    flex: 1;
}

.bot-info h1 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ai-badge {
    background: rgba(255,255,255,0.2);
    padding: 0.2rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.bot-info p {
    margin: 0;
    opacity: 0.9;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.context-info {
    margin-top: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    font-size: 0.85rem;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chat-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-clear-chat, .btn-back {
    width: 45px;
    height: 45px;
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
    backdrop-filter: blur(10px);
}

.btn-clear-chat:hover, .btn-back:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.1);
}

.chat-body {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
    background: #f8f9fa;
    position: relative;
    z-index: 10;
}

/* Welcome Message Styles */
.welcome-header h3 {
    margin: 0 0 0.5rem 0;
    color: #667eea;
    font-size: 1.4rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ai-tagline {
    margin: 0 0 1rem 0;
    font-style: italic;
    color: #6c757d;
    font-size: 0.9rem;
}

.book-showcase {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    gap: 1rem;
    margin: 1.5rem 0;
    padding: 1rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border: 2px dashed #dee2e6;
}

.book-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    transition: transform 0.2s ease;
}

.book-item:hover {
    transform: translateY(-5px);
}

.book-cover {
    font-size: 2rem;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
}

.book-item span {
    font-size: 0.75rem;
    font-weight: 600;
    color: #495057;
    text-align: center;
}

.capabilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.8rem;
    margin: 1rem 0;
}

.capability-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.8rem;
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border-radius: 8px;
    border-left: 4px solid #667eea;
    transition: all 0.2s ease;
}

.capability-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.capability-item i {
    color: #667eea;
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
}

.capability-item span {
    font-weight: 500;
    color: #495057;
    font-size: 0.9rem;
}

.message {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    align-items: flex-start;
    position: relative;
    z-index: 10;
}

.user-message {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.bot-message .message-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.user-message .message-avatar {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.message-content {
    max-width: 70%;
    padding: 1rem 1.2rem;
    border-radius: 18px;
    line-height: 1.5;
}

.bot-message .message-content {
    background: white;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.user-message .message-content {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.message-content ul {
    margin: 0.5rem 0;
    padding-left: 1.2rem;
}

.message-content li {
    margin: 0.3rem 0;
}

.quick-suggestions {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.suggestion-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.suggestion-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 25px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

.suggestion-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.chat-input-container {
    padding: 1.5rem;
    background: white;
    border-top: 1px solid #e9ecef;
    position: relative;
    z-index: 10;
}

.input-group {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.input-group input {
    flex: 1;
    padding: 1rem 1.2rem;
    border: 2px solid #e9ecef;
    border-radius: 25px;
    font-size: 0.95rem;
    outline: none;
    transition: border-color 0.2s ease;
}

.input-group input:focus {
    border-color: #667eea;
}

.input-group button {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.input-group button:disabled {
    background: #dee2e6;
    cursor: not-allowed;
}

.input-group button:not(:disabled):hover {
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.input-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    color: #6c757d;
}

.char-count {
    font-weight: 500;
}

.tip {
    font-style: italic;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.typing-indicator {
    padding: 0 1.5rem;
}

.typing-animation {
    display: flex;
    gap: 4px;
}

.typing-animation span {
    width: 8px;
    height: 8px;
    background: #667eea;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-animation span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-animation span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

.error-message {
    color: #dc3545;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    padding: 0.75rem;
    border-radius: 8px;
    margin-top: 0.5rem;
}

@media (max-width: 768px) {
    .chatbot-container {
        margin: 1rem;
        height: calc(100vh - 2rem);
        border-radius: 15px;
    }
    
    .chat-header-content {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .book-showcase {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .capabilities-grid {
        grid-template-columns: 1fr;
    }
    
    .suggestion-buttons {
        flex-direction: column;
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .input-footer .tip {
        display: none;
    }
    
    .floating-book {
        font-size: 1.5rem;
    }
}
</style>

<script>
// Variables globales
let isTyping = false;
const context = @json($context ?? []);

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const charCount = document.querySelector('.char-count');
    
    // Gestion du compteur de caractères
    messageInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length}/1000`;
        sendButton.disabled = length === 0 || isTyping;
    });
    
    // Focus automatique sur l'input
    messageInput.focus();
});

// Gestion de l'envoi avec Enter
function handleKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
}

// Fonction pour poser une question suggérée
function askQuestion(question) {
    document.getElementById('messageInput').value = question;
    sendMessage();
}

// Fonction principale d'envoi de message
async function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const question = messageInput.value.trim();
    
    if (!question || isTyping) return;
    
    // Afficher le message utilisateur
    addMessage(question, 'user');
    
    // Vider l'input et désactiver le bouton
    messageInput.value = '';
    document.querySelector('.char-count').textContent = '0/1000';
    document.getElementById('sendButton').disabled = true;
    
    // Afficher l'indicateur de frappe
    showTypingIndicator();
    
    try {
        const response = await fetch('{{ route("chatbot.ask") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                question: question,
                context: context
            })
        });
        
        const data = await response.json();
        
        hideTypingIndicator();
        
        if (data.success) {
            addMessage(data.response, 'bot');
        } else {
            addMessage(`❌ ${data.error || 'Désolé, Assistant AI rencontre une difficulté technique.'}`, 'bot', true);
        }
        
    } catch (error) {
        hideTypingIndicator();
        addMessage('🔌 Erreur de connexion avec Assistant AI. Veuillez réessayer.', 'bot', true);
    }
    
    isTyping = false;
    messageInput.focus();
}

// Ajouter un message à la conversation
function addMessage(content, sender, isError = false) {
    const chatBody = document.getElementById('chatBody');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}-message`;
    
    const avatarIcon = sender === 'bot' ? 'fas fa-robot' : 'fas fa-user';
    
    messageDiv.innerHTML = `
        <div class="message-avatar">
            <i class="${avatarIcon}"></i>
        </div>
        <div class="message-content ${isError ? 'error-message' : ''}">
            ${formatMessage(content)}
        </div>
    `;
    
    chatBody.appendChild(messageDiv);
    chatBody.scrollTop = chatBody.scrollHeight;
}

// Formater le message (markdown simple)
function formatMessage(text) {
    return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/\n/g, '<br>');
}

// Afficher l'indicateur de frappe
function showTypingIndicator() {
    isTyping = true;
    document.getElementById('typingIndicator').style.display = 'block';
    document.getElementById('chatBody').scrollTop = document.getElementById('chatBody').scrollHeight;
}

// Masquer l'indicateur de frappe
function hideTypingIndicator() {
    document.getElementById('typingIndicator').style.display = 'none';
}

// Effacer la conversation
function clearChat() {
    if (confirm('Êtes-vous sûr de vouloir effacer la conversation ?')) {
        const chatBody = document.getElementById('chatBody');
        const welcomeMessage = chatBody.querySelector('.welcome-message');
        chatBody.innerHTML = '';
        chatBody.appendChild(welcomeMessage);
    }
}
</script>

<!-- CSRF Token pour AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection