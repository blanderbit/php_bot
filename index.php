<?php
require_once('init.php');

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use App\Core\TelegramBot;
use App\Model\Message;

$messagesSend = [
    'Привет, если хочешь заработать я могу помочь',
    'Вот ссылка, регайся тут http://google.com/',
];
$bot = new TelegramBot;

while (true) {
    sleep(1);

    $updates = $bot->getUpdatesMy();

    foreach ($updates as $msg) {
        $message = $msg->message;

        switch ($message->text) {
            case '/delete_all' : 
            $bot->sendMessage($message->chat->id, 'Очистил =)');
            Message::truncate();
            $clear = true;
            break;
            case '/status' : $bot->sendMessage($message->chat->id, 'Мне написали уже ' . Message::count() . ' сообщений');
            break;
        }
        if (isset($clear)) {
            unset($clear);
            continue;
        }
        Message::create([
            'user_from' => $message->from->id,
            'user_to' => 1,
            'message' => $message->text,
        ]);
        $messages = Message::whereUserFromAndUserTo(1, $message->from->id)->get();

        if (!$send = $messagesSend[$messages->count()] ?? '') {
            continue;
        }
        if ($bot->sendMessage($message->chat->id, $send)) {
            Message::create([
                'user_from' => 1,
                'user_to' => $message->from->id,
                'message' => $send,
            ]);
        }
    }
}