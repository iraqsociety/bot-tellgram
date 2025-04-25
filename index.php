<?php
include("config.php");

function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $options = [
        'http' => [
            'method'=> 'POST',
            'content' => http_build_query($datas),
            'header'=> 'Content-Type: application/x-www-form-urlencoded\r\n',
        ],
    ];
    $context= stream_context_create($options);
    $res = @file_get_contents($url, false, $context);

    if ($res === FALSE) {
        return json_encode(['error' => 'Request failed']);
    } else {
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));

if ($update->message) {
    $message = $update->message;
    $text = $message->text;
    $chat_id = $message->chat->id;
    $from_id = $message->from->id;
    $name = $message->from->first_name;
    $username = $message->from->username;
}

if ($text == "/start") {
    bot("sendMessage", [
        'chat_id' => $chat_id,
        'text' => "هلا بيك $name، نورت البوت.
تگدر ترسل رسالتك هنانا، وتختار اذا تحب تطلع هويتك او تبقى مجهول.

اختار من الأزرار جوه شلون تريد ترسل:",
        'parse_mode' => "Markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "مجهول", 'callback_data' => "anonymous"], ['text' => "أظهر هويتي", 'callback_data' => "revealed"]],
                [['text' => "ولد", 'callback_data' => "male"], ['text' => "بنية", 'callback_data' => "female"]],
                [['text' => "الإحصائيات", 'callback_data' => "stats"]],
                [['text' => "قناتنا", 'url' => "https://t.me/RageAndRose"]],
            ]
        ])
    ]);
}
?>
