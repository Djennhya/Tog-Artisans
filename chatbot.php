<?php include("header.php"); ?>
<!-- Assistance intelligente par ChatGPT -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot d'Assistance</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .chat-container {
            width: 800px;
            margin: 50px auto;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            background: #06792eea;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .chat-messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            border-bottom: 1px solid #ddd;
        }
        .chat-input {
            display: flex;
            border-top: 1px solid #ddd;
        }
        .chat-input input {
            flex: 1;
            padding: 10px;
            border: none;
            border-bottom-left-radius: 5px;
        }
        .chat-input button {
            padding: 10px;
            background: #ff9500ff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-bottom-right-radius: 5px;
        }
        .message {
            margin-bottom: 10px;
        }
        .message.user {
            text-align: right;
        }
        .message.bot {
            text-align: left;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2>Tog'Artisans Chatbot</h2>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message bot">Bonjour et bienvenu(e) sur Tog'Artisans! Comment puis-je vous aider aujourd'hui?</div>
        </div>
        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Tapez votre message ici...">
            <button id="sendBtn">Envoyer</button>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#sendBtn').on('click', function() {
                var userMessage = $('#userInput').val().trim();
                if (userMessage) {
                    $('#chatMessages').append('<div class="message user">' + userMessage + '</div>');
                    $('#userInput').val('');
                    // Simulate bot response
                    setTimeout(function() {
                        var botResponse = getBotResponse(userMessage);
                        $('#chatMessages').append('<div class="message bot">' + botResponse + '</div>');
                        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
                    }, 1000);
                }
            });

            function getBotResponse(message) {
                // Simple keyword-based responses
                message = message.toLowerCase();
                if (message.includes('bonjour') || message.includes('salut')) {
                    return 'Bonjour! Comment puis-je vous aider?';
                } else if (message.includes('aide')) {
                    return 'Bien sûr! Dites-moi ce dont vous avez besoin.';
                } else if (message.includes('merci')) {
                    return 'De rien! N\'hésitez pas à poser d\'autres questions.';
                } else {
                    return 'Je suis désolé, je ne comprends pas. Pouvez-vous reformuler?';
                }
            }
        });
    </script>
</body>
</html>
    <script>
        toastr.success('<?php echo $message; ?>');
    </script>