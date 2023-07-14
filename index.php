<?php

date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/src/MagneticDoor.php';
require_once __DIR__ . '/src/SmokeDetector.php';
require_once __DIR__ . '/src/TemperatureHumidity.php';

while (true) {
  insert_magnetic_door(5);
  sleep(2);
  insert_smoke_detector(5);
  sleep(2);
  insert_temperature_humidity(6, 'upper back');
  sleep(2);
  insert_temperature_humidity(7, 'lower back');
  sleep(2);
  insert_temperature_humidity(8, 'lower front');
  sleep(2);
  insert_temperature_humidity(9, 'upper front');
  sleep(2);
  insert_temperature_humidity(10, 'front top');
  sleep(2);
  insert_temperature_humidity(11, 'back top');
  sleep(2);
}
