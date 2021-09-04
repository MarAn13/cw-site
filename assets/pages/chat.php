<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<!-- onload="setInterval('chat.update()', 1000)" -->

<body>
    <div id="page-wrap">

        <h2>jQuery/PHP Chat</h2>

        <p id="name-area"></p>

        <div id="chat-wrap">
            <div id="chat-area">
                <?php
                require_once("php/sql.php");

                //set_chat(1);
                ?>
            </div>
        </div>

        <form id="send-message-area">
            <p>Your message: </p>
            <textarea id="sendie" maxlength='100'></textarea>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        //gets the state of the chat
        function checkChat(message_num) {
            $.ajax({
                type: "POST",
                url: "chat.inc.php",
                data: {
                    'function': 'checkChat',
                    'message_num': message_num
                },
                success: function(data) {
                    if (data !== "no_new_messages") {
                        data = $.parseJSON(data);
                        data[0].forEach(function(arr) {
                            $('#chat-area').append(arr);
                        })
                    }
                }
            });
        }

        //send the message
        function sendMessage(message, message_sender) {
            $.ajax({
                type: "POST",
                url: "chat.inc.php",
                data: {
                    'function': 'updateChat',
                    'message': message,
                    'message_sender': message_sender
                },
                success: function(data) {
                    console.log(data);
                }
            });
        }
    </script>
</body>

</html>