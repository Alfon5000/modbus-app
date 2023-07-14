<?php

function connect_to_database()
{
  // Database configurations
  $hostname = '127.0.0.1';
  $username = 'root';
  $password = '';
  $database = 'dcim';

  // Connect to database
  $db_connection = new mysqli($hostname, $username, $password, $database);

  // Check if database connection error
  if (!$db_connection) {
    die("Connection failed: $db_connection->connect_error");
  }

  // Return database connection
  return $db_connection;
}
