<?php
    include "system/connectionstring.php";
    include "classes/mysqlutilities.php";
    include "php/classes/invoice.php";    
    //include "classes/htmlutilities.php";
    
    $serverName = MYSQL_SERVERNAME;
    $userName = MYSQL_USERNAME;
    $password = MYSQL_PASSWORD;
    $dbName = MYSQL_DBNAME;
    
    $mysql = new MySQLGetSelectData($serverName, $userName, $password, $dbName);   
    
    $dashboardData = $mysql->getDashBoardData();

?>