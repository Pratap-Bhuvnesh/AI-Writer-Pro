<!DOCTYPE html>
<html>
<head>
    <title>AI Chatbot</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial; }
        #chat-box {
            width: 400px;
            height: 500px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            padding: 10px;
        }       
        .user {
            color: blue; 
    text-align: right;
    background: #e1f5fe;
    padding: 5px;
    border-radius: 5px;
}
.bot {
    color: green; 
    text-align: left;
    background: #e8f5e9;
    padding: 5px;
    border-radius: 5px;
}
    </style>
</head>
<body>

<h2>E-commerce Chatbot</h2>

<div id="chat-box"></div>

<input type="text" id="message" placeholder="Type your message">
<button onclick="sendMessage()">Send</button>

<script>
function sendMessage() {
    
    let msgInput = document.getElementById('message');
    let msg = msgInput.value;

    if (!msg) return;
    let typingId = "typing-" + Date.now();
    // Show user message
    let chatBox = document.getElementById('chat-box');    
    chatBox.innerHTML += `<p class="user"><b>Youu:</b> ${msg}</p>`;
    chatBox.innerHTML += `<p class="bot" id="${typingId}">Typing...</p>`;
    msgInput.value = "";

    fetch('/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ message: msg })
    })
    .then(res => res.json())
    .then(data => {
        // console.log(data);
       // let reply = data.choices[0].message.content;
        let reply =  data.data;
        // Find typing element
        let typingElement = document.getElementById(typingId);

        if (typingElement) {
            typingElement.innerHTML = `<b>Bot:</b> ${reply}`;
        }

        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(err => {
        console.error(err);
    });
}
document.getElementById("message").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        sendMessage();
    }
});
</script>

</body>
</html>