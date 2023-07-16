<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . './DbConnection.php';
require_once __DIR__ . './ModbusConnection.php';
require_once __DIR__ . './TelegramMessage.php';

use ModbusTcpClient\Packet\ResponseFactory;
use ModbusTcpClient\Packet\ModbusFunction\ReadInputDiscretesRequest;

function insert_smoke_detector($sensor_id)
{
  // Modbus connection
  $connection = connect_to_modbus();

  // Get database connection
  $db_connection = connect_to_database();

  try {
    // Request read input register
    $packet = new ReadInputDiscretesRequest(1, 1, $sensor_id);
    $binaryData = $connection->connect()->sendAndReceive($packet);
    $response = ResponseFactory::parseResponseOrThrow($binaryData);
    $status = $response[0];

    if ($status == true) {
      $status = 1;
      $description = 'Smoky';
    } else {
      $status = 0;
      $description = 'No Smoke';
    }

    // Read data from database
    $select_query = "SELECT status FROM smoke_detectors ORDER BY created_at DESC LIMIT 1";
    $result = $db_connection->query($select_query);

    // Check number of results
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      // Check magnetic door status
      if ($row['status'] != $status) {
        $insert_query = "INSERT INTO smoke_detectors (sensor_id, status, description, created_at, updated_at) VALUES ($sensor_id, $status, '$description', NOW(), NOW())";
        $db_connection->query($insert_query);
      }
    } else {
      $insert_query = "INSERT INTO smoke_detectors (sensor_id, status, description, created_at, updated_at) VALUES ($sensor_id, $status, '$description', NOW(), NOW())";
      $db_connection->query($insert_query);
    }

    // Insert smoke detector notifications
    $smoke_detector_message = 'There is smoke in the server room';
    $telegram_message = 'Peringatan! ruang server mendeteksi asap pada tanggal ' . date('Y-m-d') . ' pukul ' . date('H:i:s');

    if ($status === 1) {
      $insert_query = "INSERT INTO notifications (message, is_read, created_at, updated_at) VALUES ('$smoke_detector_message' , 0, NOW(), NOW())";
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
