<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . './DbConnection.php';
require_once __DIR__ . './ModbusConnection.php';
require_once __DIR__ . './TelegramMessage.php';

use ModbusTcpClient\Packet\ResponseFactory;
use ModbusTcpClient\Packet\ModbusFunction\ReadInputDiscretesRequest;

function insert_magnetic_door($sensor_id)
{
  // Modbus connection
  $connection = connect_to_modbus();

  // Get database connection
  $db_connection = connect_to_database();

  try {
    // Request read input register
    $packet = new ReadInputDiscretesRequest(0, 1, $sensor_id);
    $binaryData = $connection->connect()->sendAndReceive($packet);
    $response = ResponseFactory::parseResponseOrThrow($binaryData);
    $status = $response[0];

    if ($status == true) {
      $status = 1;
      $description = 'Open';
    } else {
      $status = 0;
      $description = 'Closed';
    }

    // Read data from database
    $select_query = "SELECT status FROM magnetic_doors ORDER BY created_at DESC LIMIT 1";
    $result = $db_connection->query($select_query);

    // Check number of results
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      // Check magnetic door status
      if ($row['status'] != $status) {
        $insert_query = "INSERT INTO magnetic_doors (sensor_id, status, description, created_at, updated_at) VALUES ($sensor_id, $status, '$description', NOW(), NOW())";
        $db_connection->query($insert_query);
      }
    } else {
      $insert_query = "INSERT INTO magnetic_doors (sensor_id, status, description, created_at, updated_at) VALUES ($sensor_id, $status, '$description', NOW(), NOW())";
      $db_connection->query($insert_query);
    }

    // Insert magnetic door notifications
    $magnetic_door_message = 'The server room door is open';
    $telegram_message = 'Informasi: pintu ruang server terbuka pada tanggal ' . date('Y-m-d') . ' pukul ' . date('H:i:s');

    if ($status === 1) {
      $insert_query = "INSERT INTO notifications (message, is_read, created_at, updated_at) VALUES ('$magnetic_door_message' , 0, NOW(), NOW())";
      $db_connection->query($insert_query);
      send_telegram_message($telegram_message);
    }
  } catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
    // echo $exception->getTraceAsString() . PHP_EOL;
  } finally {
    // Close all connection
    $connection->close();
    $db_connection->close();
  }
}
