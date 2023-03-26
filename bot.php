<?php

require 'vendor/autoload.php';


use skrtdev\NovaGram\Bot;
use skrtdev\Telegram\{Update, User, Message, CallbackQuery, Chat};

$MC = new Bot("token bot", [
    'debug' => 1111, //vostro id telegram
    "restart_on_changes" => true, // ogni volta che cambiate una cosa nel file si auto restarta il bot
]);

$MC->onCommand("start", function(Message $message){
    if($message->chat->type == "private"){
        $user = $message->from;
        $bottone[] = [ ["text" => "Developer", "url" => "https://t.me/ChillatoDevIsBack"] ];
        $message->reply([
            "text" => "👋 | Ciao {$user->first_name}! Grazie per avermi avviato.

🤖 | Questo bot ha la funzione di dare ogni singola informazione del server desiderato!

ℹ️ | Comandi:
/java [ServerIp]",
            'reply_markup' => ["inline_keyboard" => $bottone]
        ]);
    }
});

$MC->onCommand("java", function (Message $message) use ($MC){
    $split = explode(" ", $message->text);
    $minecraft = json_decode(file_get_contents("https://api.mcsrvstat.us/2/{$split[1]}"));
    if($minecraft->online == true) {
        $ip = $minecraft->ip;
        $port = $minecraft->port;
        $host = $minecraft->hostname;
        $versione = $minecraft->version;
        $protocol = $minecraft->protocol;
        $online = $minecraft->players->online;
        $playermassimi = $minecraft->players->max;
        $bottone[] = [ ["text" => "❌ | Chiudi", "callback_data" => "close"] ];
        $message->reply([
            "text" => "ℹ️ | STATUS {$host}\n\n👉🏻 IP: {$ip}\n👉🏻 PORT: {$port}\n👉🏻 VERSION: {$versione}\n👉🏻 PROTOCOL: {$protocol}\n👉🏻 PLAYER: {$online} / {$playermassimi}",
            "reply_markup" => ["inline_keyboard" => $bottone]
        ]);
    } else {
        $message->reply("Errore server non esistente o offline");
    }
});

$MC->onCallbackQuery(function(CallbackQuery $query) use ($MC){
    if($query->data == "close"){
        $query->message->delete();
    }
});

$MC->start();
?>

