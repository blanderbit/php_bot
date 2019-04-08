<?php namespace App\Core;

use GuzzleHttp\Client;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class TelegramBot extends BotApi {
    public $token = BOT_API_TOKEN;

    protected $updateId;

    public function __construct()
    {
        parent::__construct($this->token);
    }

    protected function query($method, $parms = []) 
    {
        $url = 'https://api.telegram.org/bot' . $this->token . '/' . $method;

        if (!empty($parms)) {
            $url .= '?' .  http_build_query($parms);
        }

        $client = new Client([
            'base_uri' => $url
        ]);

        $result = $client->request('GET');

        return json_decode($result->getBody());
    }

    public function sendDocument($chatId, $document, $caption = NULL, $replyToMessageId = NULL, $replyMarkup = NULL, $disableNotification = false)
    {
        $document = new \CURLFile($document);
        $file_name = basename($document->name);
        
        $this->sendMessage($chatId, "<em>Загружаю...({$file_name})</em>", 'HTML');
        if ($response = parent::sendDocument($chatId, $document)) {
            // $keyboard = new ReplyKeyboardMarkup([
            //     ["one", "two", "three"]
            // ], true);
            // $this->sendMessage($chatId, '<em>Загрузил...</em>', 'HTML', false, null, $keyboard);
            $this->sendMessage($chatId, '<em>Загрузил...</em>', 'HTML');
        }
        return $response;
    }
    public function sendMessageBot($chatId, $message, $keyboard)
    {
        $user_message = str_replace(BOT_NAME, '', $message->text);
        $username = !empty($message->chat->username) ? $message->chat->username : 'Guest';
        return $this->sendMessage($chatId, botAnswer($user_message, $username), null, false, null, $keyboard);
    }
    public function getUpdatesMy()
    {
        $response = $this->query('getUpdates', [
            'offset' => $this->updateId + 1
        ]);

        if (!empty($response->result)) {
            $this->updateId = $response->result[count($response->result) - 1]->update_id;
        }

        return $response->result;
    }
}