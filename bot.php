<?php
require_once('init.php');

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use App\Core\TelegramBot;

$bot = new TelegramBot;
$keyboard = new ReplyKeyboardMarkup(
    [
        ['Лысый привет', 'Лысый как дела', 'Лысый мудак'],
        ['Лысый что делаешь', 'Лысый иди гулять', 'Лысый ты пидр'],
['/1', '/2', '/3'],
    ], true);
$keyboard2 = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
    [ 
	[
	     ['text' => '33', 'callback_data' => '24124']
	]
    ]
);
//$keyboard = new ReplyKeyboardMarkup([], true);

$keyboardCubex = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
    [
        [
            ['text' => 'Cubex', 'url' => 'http://qbex.io/']
        ]
    ]
);
//$keyboard = [];
while (true) {
    sleep(1);

    $updates = $bot->getUpdatesMy();
	
    foreach ($updates as $msg) {
       $message = $msg->message;
        if ($message && (strpos($message->text, BOT_NAME) !== false)) {
            
            $bot->sendMessageBot($message->chat->id, $message, $keyboard);
            //$bot->sendMessageBot($message->chat->id, $message, $keyboard);
        }
        if($message){switch ($message->text) {
            case '/start':
                $bot->sendMessage($message->chat->id, 'Start', null, false, null, $keyboard);
                break;
            case '/cubex':
                $bot->sendMessage($message->chat->id, 'CUBEX2', null, false, null, $keyboardCubex);
		break;
	    case '/1':
                $bot->sendDocument($message->chat->id, 'database/2.bin');
		break;
            case '/2':
                $bot->sendContact($message->chat->id, '380957341494', 'Sanek');
		break;
	    case '/3':
                $bot->sendMessage($message->chat->id, '/3', null, false, null, $keyboard2);
                break;
            default:
			break;
        }
}
var_dump('####################', $msg);
if($msg ){
switch ($msg->callback_query->data) {
		case '24124':
if($msg->callback_query->message&&$msg->callback_query->message->chat&&$msg->callback_query->message->chat->id){
                $bot->sendMessage($message->callback_query->message->chat->id, $msg->callback_query->data, null, false);
}
                break;
}
  }  
        //$bot->sendDocument($message->chat->id, 'database/2.bin');
        // $bot->sendContact($message->chat->id, '380957341494', 'Sanek');
    }
}
/*
[update_id] => 602397138
[message] => stdClass Object
    (
        [message_id] => 1422
        [from] => stdClass Object
            (
                [id] => 366567962
                [is_bot] => 
                [first_name] => Alexandr
                [last_name] => Shapoval
                [username] => Sanek_OS9
                [language_code] => en
            )

        [chat] => stdClass Object
            (
                [id] => 366567962
                [first_name] => Alexandr
                [last_name] => Shapoval
                [username] => Sanek_OS9
                [type] => private
            )

        [date] => 1511554652
        [text] => ff
*/
