// Function to append a new chat message to the chat box
function appendMessage(username, message) {
    var chatBox = document.getElementById('chat-box');
    var messageElement = document.createElement('div');
    messageElement.innerHTML = '<span class="username">' + username + ':</span><span class="message">' + message + '</span>';
    chatBox.appendChild(messageElement);
    // Scroll to the bottom of the chat box
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Handle form submission
document.getElementById('chat-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var messageInput = document.getElementById('message-input');
    var message = messageInput.value.trim();
    if (message !== '') {
        // For simplicity, let's assume the username is hardcoded for now
        var username = 'User';
        // Append the message to the chat box
        appendMessage(username, message);
        // Clear input field after sending
        messageInput.value = '';
    }
});
