<?php

function send_telegram_message($message)
{
  $api_token = "6022010632:AAHFPIkELltxnWRaOhjluaj7WzikKjykEw8";

  $data = [
    'chat_id' => '@user_dgos',
    'text' => $message
  ];

  file_get_contents("https://api.telegram.org/bot$api_token/sendMessage?" . http_build_query($data));
}
