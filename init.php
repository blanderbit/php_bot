<?php
define('BOT_NAME', 'Лысый');
define('SEARCH_MESSAGE_PERCENT', 82);
define('TAG_USERNAME', '%USERNAME%');
define('BOT_API_TOKEN', '738480901:AAFeLJtSXn2s7oigZrMKfZVXDmbHNftM01Q');


function dd($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    // exit;
}
function botAnswer($message, $username)
{
    $files_answer = glob('database/*');
    $handle = fopen($files_answer[mt_rand(0, count($files_answer) - 1)], 'r');
    $bot_messages = [];
    while (!feof($handle)) {
        $buffer = fgets($handle);
        $text = explode('\\', $buffer);
    
        if (empty($text[1])) {
            continue;
        }
        similar_text($message, $text[0], $percent);
        if ($percent >= SEARCH_MESSAGE_PERCENT) {
            $text[1] = str_replace([TAG_USERNAME], ['@' . $username], $text[1]);
            $bot_messages[] = ['message' => $text[1], 'key' => $text[0], 'ver' => $percent];
        }
    }
    fclose($handle);
    if (empty($bot_messages)) {
        return 'Я не знаю что ответить =(';
    }
    $i = getRandomIndex($bot_messages);
    return !empty($bot_messages[$i]['message']) ? $bot_messages[$i]['message'] : 'Я не знаю что ответить =(';
}
/**
 * Случайная выборка с учетом веса каждого элемента.
 * @param array $data Массив, в котором ищется случайный элемент
 * @param string $column Параметр массива, содержащий «вес» вероятности
 * @return int Индекс найденного элемента в массиве $data 
 */
function getRandomIndex($data, $column = 'ver')
{
    $rand = mt_rand(1, array_sum(array_column($data, $column)));
    $cur = $prev = 0;
    for ($i = 0, $count = count($data); $i < $count; ++$i) {
        $prev += $i != 0 ? $data[$i-1][$column] : 0;
        $cur += $data[$i][$column];
        if ($rand > $prev && $rand <= $cur) {
            return $i;
        }
    }
    return -1;
}
require_once('vendor/autoload.php');

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'telegrambot',
    'username'  => 'root',
    'password'  => '100500',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
