document.addEventListener("DOMContentLoaded", function () {
    console.log("hi there");

    let chatBox = document.getElementById("chat-box");
    let messageForm = document.getElementById("message-form");
    let messageInput = document.getElementById("message-input");
    let typingIndicator = document.getElementById("typing-indicator");

    fetch("/online", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ||
                CSRFToken,
        },
    });

    // subscribe to the private channel for sending message
    window.Echo.private("chat." + senderId).listen("MessageSent", (e) => {
        // show message
        const messageDiv = document.createElement("div");
        messageDiv.className = "mb-2 text-start";
        messageDiv.innerHTML =
            '<span class="badge bg-secondary p-2">' +
            e.message.message +
            "</span>";
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    });

    // subscribe to the private channel for user typing
    window.Echo.private("typing." + receiverId).listen("UserTyping", (e) => {
        // console.log(e);
        if (e.typerId == receiverId) {
            typingIndicator.style.display = "block";
        }
    });

    messageForm.addEventListener("submit", function (e) {
        e.preventDefault(); // to NOT make a refresh to the page
        const message = messageInput.value;

        if (message) {
            fetch(`/chat/${receiverId}/send`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": CSRFToken,
                },
                body: JSON.stringify({ message }),
            });
        }
        const messageDiv = document.createElement("div");
        messageDiv.className = "mb-2 text-end";
        messageDiv.innerHTML =
            '<span class="badge bg-primary p-2">' + message + "</span>";
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
        messageInput.value = "";
    });

    messageInput.addEventListener("input", function () {
        fetch("/chat/typing", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": CSRFToken,
            },
        });
    });
});
