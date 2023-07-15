<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ModbusTcpClient\Network\BinaryStreamConnection;

function connect_to_modbus()
{
  $host = '192.168.101.149';
  $port = 502;

  $connection = BinaryStreamConnection::getBuilder()
    ->setHost($host)
    ->setPort($port)
    ->build();

  return $connection;
}
