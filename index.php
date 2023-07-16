<?php

date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/src/MagneticDoor.php';
require_once __DIR__ . '/src/SmokeDetector.php';
require_once __DIR__ . '/src/TemperatureHumidity.php';

$interval = 1;

while (true) {
  insert_magnetic_door(5);
  sleep($interval);
  insert_smoke_detector(5);
  sleep($interval);
  insert_temperature_humidity(6, 'upper back', 22, 34);
  sleep($interval);
  insert_temperature_humidity(7, 'lower back', 22, 34);
  sleep($interval);
  insert_temperature_humidity(8, 'lower front', 20, 29);
  sleep($interval);
  insert_temperature_humidity(9, 'upper front', 20, 29);
  sleep($interval);
  insert_temperature_humidity(10, 'front top', 23, 35);
  sleep($interval);
  // insert_temperature_humidity(11, 'back top');
  // sleep($interval);
}
