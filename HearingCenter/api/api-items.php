<?php

include "system/connectionstring.php";
include "classes/mysqlutilities.php";

//Connection values
$serverName = MYSQL_SERVERNAME;
$userName = MYSQL_USERNAME;
$password = MYSQL_PASSWORD;
$dbName = MYSQL_DBNAME;

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);

//retrieve the key from the path
$key = ""; //incomplete

$mysql = new MySQLGetSelectData($serverName, $userName, $password, $dbName);

$mysql->getInventoryItem($key);

echo json_encode($mysql, JSON_PRETTY_PRINT);

?>