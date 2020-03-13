<?php
    /***
     * Setup Page to display an existing invoice for editing 
     * using the same page layout as createinvoice.html
     */

    include "system/connectionstring.php";
    include "classes/mysqlutilities.php";
    include "php/classes/invoice.php";    
    
    //Configure invoice ID to use
    if(isset($_POST['id']))
        $invoiceID = $_POST['id'];
    else
        $invoiceID = 0;  

    $serverName = MYSQL_SERVERNAME;
    $userName = MYSQL_USERNAME;
    $password = MYSQL_PASSWORD;
    $dbName = MYSQL_DBNAME;

    $mysql = new MySQLDeleteData($serverName, $userName, $password, $dbName, $invoiceID);   
    
    $mysql->DeleteInvoicePayments();
    $mysql->DeleteInvoiceDetails();
    $mysql->DeleteInvoiceHeader();

    echo "<h4>Deleted Invoice " . $invoiceID . "</h4>";
?>