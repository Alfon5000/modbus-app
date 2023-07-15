<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . './DbConnection.php';
require_once __DIR__ . './ModbusConnection.php';
require_once __DIR__ . './TelegramMessage.php';

use ModbusTcpClient\Packet\ResponseFactory;
use ModbusTcpClient\Packet\ModbusFunction\ReadInputRegistersRequest;

function insert_temperature_humidity($sensor_id, $location, $max_temp = 28, $min_temp = 22, $max_hum = 55, $min_hum = 40)
{
  // Connect to Modbus host
  $connection = connect_to_modbus();

  // Get database connection
  $db_connection = connect_to_database();

  try {
    // Request read input register
    $packet = new ReadInputRegistersRequest(1, 2, $sensor_id);

    // Parse sensor data from binary to integer 16
    $binaryData = $connection->connect()->sendAndReceive($packet);
    $response = ResponseFactory::parseResponseOrThrow($binaryData);
    $temperature = $response[0]->getInt16() / 10;
    $humidity = $response[1]->getInt16() / 10;

    // Insert data to database
    $insert_query = "INSERT INTO temperature_humidities (temperature, humidity, sensor_id, created_at, updated_at) VALUES ($temperature, $humidity, $sensor_id, NOW(), NOW())";
    $db_connection->query($insert_query);

    // Insert temperature notifications
    $temperature_message = "The $location of the server room has a temperature of $temperature degrees Celsius";

    if ($temperature > $max_temp || $temperature < $min_temp) {
      $insert_query = "INSERT INTO notifications (message, is_read, created_at, updated_at) VALUES ('$temperature_message' , 0, NOW(), NOW())";
      $db_connection->query($insert_query);
      // send_telegram_message($temperature_message);
    }

    // Insert humidity notifications
    $humidity_message = "The $location of the server room has a humidity of $humidity percent";

    if ($humidity > $max_hum || $humidity < $min_hum) {
      $insert_query = "INSERT INTO notifications (message, is_read, created_at, updated_at) VALUES ('$humidity_message' , 0, NOW(), NOW())";
      $db_connection->query($insert_query);
      // send_telegram_message($humidity_message);
    }
  } catch (Exception $exception) {
    // echo $exception->getMessage() . PHP_EOL;
    echo $exception->getTraceAsString() . PHP_EOL;
  } finally {
    // Close all connection
    $connection->close();
    $db_connection->close();
  }
}
