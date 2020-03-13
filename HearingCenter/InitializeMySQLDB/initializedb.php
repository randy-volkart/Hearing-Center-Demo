<?php

include "../system/connectionstring.php";

$serverName = MYSQL_SERVERNAME;
$userName = MYSQL_USERNAME;
$password = MYSQL_PASSWORD;
$dbName = MYSQL_DBNAME;

/*** FUNCTIONS ***/

function dropDataBase($serverName, $userName, $password, $dbName)
{
    try{
        $connCreateDB = new PDO("mysql:host=$serverName", $userName, $password);
        $connCreateDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DROP DATABASE " . $dbName;

        $connCreateDB->exec($sql);
        echo "Database " . $dbName . " dropped<br>";
    }
    catch(PDOException $e)
    {
        echo "DB " .  $dbName . "Drop Error: " . $e->getMessage();
    }
    finally
    {
        $connCreateDB = null;
    }
}

function createDataBase($serverName, $userName, $password, $dbName)
{
    try{
        $connCreateDB = new PDO("mysql:host=$serverName", $userName, $password);
        $connCreateDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "CREATE DATABASE IF NOT EXISTS " . $dbName;

        $connCreateDB->exec($sql);
        echo "Database " . $dbName . " created successfully<br>";
    }
    catch(PDOException $e)
    {
        echo "DB " .  $dbName . " Creation Error: " . $e->getMessage();
    }
    finally
    {
        $connCreateDB = null;
    }
}

function createTable($serverName, $dbName, $userName, $password, $query, $tableName)
{
    try{
        $connCreate = new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $password);
        $connCreate->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $connCreate->exec($query);
        echo "Created Table " . $tableName . " <br>";
    }
    catch(PDOException $e){
        echo "Create Table " . $tableName . " Error: " . $e->getMessage() . "<br>";
    }
    finally{
        $conn = null;
    }        
}

function insertToTable($serverName, $dbName, $userName, $password, $insertQueryArray, $tableName)
{
    try{
        $connCreate = new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $password);
        $connCreate->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        for($i = 0; $i < count($insertQueryArray); $i++) {
            $connCreate->exec($insertQueryArray[$i]);
            echo "Added line " . $i . " to Table " . $tableName . " <br>";
        }
    }
    catch(PDOException $e){
        echo "Adding to Table " . $tableName . " Error on line " . $i . ": ". $e->getMessage() . "<br>";
    }
    finally{
        $conn = null;
    }        
}

/*** QUERIES ***/

$sqlTaxType = "
    CREATE TABLE IF NOT EXISTS tax_type(
        tax_id      VARCHAR(10) PRIMARY KEY,
        name        VARCHAR(60) NOT NULL DEFAULT '',
        percent     TINYINT(2) UNSIGNED NOT NULL DEFAULT 0,
        last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
";

$sqlPaymentType = "
    CREATE TABLE IF NOT EXISTS payment_type(
        payment_id  VARCHAR(20) PRIMARY KEY,
        name        VARCHAR(60) NOT NULL DEFAULT '',
        last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
";

$sqlCountry = "
    CREATE TABLE IF NOT EXISTS country(
        country_id  VARCHAR(10) PRIMARY KEY,
        name        VARCHAR(128) NOT NULL DEFAULT '',
        last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
";

$sqlProvinceState = "
    CREATE TABLE IF NOT EXISTS province_state(
        province_state_id   VARCHAR(20) PRIMARY KEY,
        name                VARCHAR(128) NOT NULL DEFAULT '',
        country_id          VARCHAR(10),
        tax1_id            VARCHAR(10),
        tax2_id            VARCHAR(10),
        last_update         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (country_id ) REFERENCES country(country_id ),
        FOREIGN KEY (tax1_id) REFERENCES tax_type(tax_id),
        FOREIGN KEY (tax2_id) REFERENCES tax_type(tax_id)        
    )
";

$sqlInventory = "
    CREATE TABLE IF NOT EXISTS inventory(
        part_id     VARCHAR(20) PRIMARY KEY,
        name        VARCHAR(60) NOT NULL,
        upc         VARCHAR(40),        
        price       DECIMAL(10,2) DEFAULT 0.00,
        tax1_flag  TINYINT(1) NOT NULL DEFAULT 0,
        tax2_flag  TINYINT(1) NOT NULL DEFAULT 0,
        last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
";

$sqlInvoiceHeader = "
    CREATE TABLE IF NOT EXISTS invoice_header(
        invoice_id      INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name            VARCHAR(60) NOT NULL DEFAULT '',
        address1        VARCHAR(64) NOT NULL DEFAULT '', 
        address2        VARCHAR(64) NOT NULL DEFAULT '', 
        city            VARCHAR(60) NOT NULL DEFAULT '', 
        province_state  VARCHAR(20), 
        postal_zip      VARCHAR(20) NOT NULL DEFAULT '', 
        country         VARCHAR(20),
        phone           VARCHAR(30) NOT NULL DEFAULT '', 
        email           VARCHAR(80) NOT NULL DEFAULT '', 
        invoice_number  VARCHAR(30) NOT NULL DEFAULT '',
        invoice_date    DATE NOT NULL DEFAULT '1900-01-01',
        due_date        DATE,
        note            VARCHAR(1024) NOT NULL DEFAULT '',
        tax1_id        VARCHAR(10),
        tax2_id        VARCHAR(10),
        tax1_total     Decimal(8,2) NOT NULL DEFAULT 0,
        tax2_total     Decimal(8,2) NOT NULL DEFAULT 0,
        sub_total       Decimal(10,2) NOT NULL DEFAULT 0,
        net_total       Decimal(10,2) NOT NULL DEFAULT 0,
        last_update     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (country) REFERENCES Country(country_id),
        FOREIGN KEY (province_state) REFERENCES province_state(province_state_id),
        FOREIGN KEY (tax1_id) REFERENCES tax_type(tax_id),
        FOREIGN KEY (tax2_id) REFERENCES tax_type(tax_id)
    )
";

$sqlInvoiceDetail = "
    CREATE TABLE IF NOT EXISTS invoice_detail(
        invoice_id  INT(10),
        record_no   INT(3),
        part_id     VARCHAR(20),
        quantity    INT(5) NOT NULL DEFAULT 0,
        price       DECIMAL(8,2) UNSIGNED NOT NULL DEFAULT 0,
        tax1_flag  TINYINT(1) NOT NULL DEFAULT 0,
        tax2_flag  TINYINT(1) NOT NULL DEFAULT 0,
        last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (invoice_id, record_no),
        FOREIGN KEY (part_id) REFERENCES inventory(part_id)
    )
";

$sqlInvoicePayment = "
    CREATE TABLE IF NOT EXISTS invoice_payment(
        invoice_id      INT(10),
        record_no       INT(3),
        payment_id      VARCHAR(20),
        payment_amount  Decimal(10,2) NOT NULL DEFAULT 0,
        last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (invoice_id, record_no),
        FOREIGN KEY (payment_id) REFERENCES payment_type(payment_id)
    )    
";

$sqlPopulateTax00 = "INSERT INTO tax_type (tax_id, name, percent) VALUES ('0', '', 00)";
$sqlPopulatetax1 = "INSERT INTO tax_type (tax_id, name, percent) VALUES ('GST', 'GST', 05)";
$sqlPopulatetax2 = "INSERT INTO tax_type (tax_id, name, percent) VALUES ('PST-BC', 'PST', 07)";
$sqlPopulateTax03 = "INSERT INTO tax_type (tax_id, name, percent) VALUES ('HST-ON', 'HST', 13)";
$sqlPopulateTaxes = array($sqlPopulateTax00, $sqlPopulatetax1, $sqlPopulatetax2, $sqlPopulateTax03);

$sqlPopulateCountry01 = "INSERT INTO country (country_id, name) VALUES ('CAN', 'Canada')";
$sqlPopulateCountries = array($sqlPopulateCountry01);

$sqlPopulateProvState01 = "INSERT INTO province_state (province_state_id, name, country_id, tax1_id, tax2_id ) VALUES 
                        ('BC', 'British Columbia', 'CAN', 'GST', 'PST-BC')";
$sqlPopulateProvState02 = "INSERT INTO province_state (province_state_id, name, country_id, tax1_id ) VALUES 
                        ('AB', 'Alberta', 'CAN', 'GST')";
$sqlPopulateProvState03 = "INSERT INTO province_state (province_state_id, name, country_id, tax1_id ) VALUES 
                        ('ON', 'Ontario', 'CAN', 'HST-ON')";
$sqlPopulateProvinceStates = array($sqlPopulateProvState01, $sqlPopulateProvState02, $sqlPopulateProvState03);

$sqlPopulatePayment01 = "INSERT INTO payment_type (payment_id, Name) VALUES ('CASH', 'Cash')";
$sqlPopulatePayment02 = "INSERT INTO payment_type (payment_id, Name) VALUES ('DEBIT', 'Debit')";
$sqlPopulatePayment03 = "INSERT INTO payment_type (payment_id, Name) VALUES ('CREDIT-VISA', 'Visa')";
$sqlPopulatePayment04 = "INSERT INTO payment_type (payment_id, Name) VALUES ('CREDIT-MC', 'Mastercard')";
$sqlPopulatePayment05 = "INSERT INTO payment_type (payment_id, Name) VALUES ('CREDIT-AMEX', 'American Express')";
$sqlPopulatePayment06 = "INSERT INTO payment_type (payment_id, Name) VALUES ('ON ACCOUNT', 'Accounts Receivable')";
$sqlPopulatePayments = array($sqlPopulatePayment01, $sqlPopulatePayment02, $sqlPopulatePayment03,
                                    $sqlPopulatePayment04, $sqlPopulatePayment05, $sqlPopulatePayment06);
    
$sqlPopulateInventory01 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400257', 'Bone conduction hearing aid, left', 575.00, 0, 0 )";
$sqlPopulateInventory02 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400258', 'Bone conduction hearing aid, right', 575.00, 0, 0 )";
$sqlPopulateInventory03 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400266', 'BTE ear mold (new aid), left', 45.00, 0, 0 )";
$sqlPopulateInventory04 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400267', 'BTE ear mold (new aid), right', 45.00, 0, 0 )";
$sqlPopulateInventory05 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400270', 'Repairs by manufacturer, left', 125.00, 0, 0 )";
$sqlPopulateInventory06 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400271', 'Repairs by manufacturer, right', 125.00, 0, 0 )";
$sqlPopulateInventory07 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99401122', 'Remake by manufacturer, left', 176.00, 0, 0 )";
$sqlPopulateInventory08 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99401123', 'Remake by manufacturer, right', 176.00, 0, 0 )";
$sqlPopulateInventory09 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400259', 'Batteries, hearing aid left', .00, 0, 0 )";
$sqlPopulateInventory10 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400643', 'Batteries, hearing aid right', .00, 0, 0 )";
$sqlPopulateInventory11 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400866', 'Tubes/Domes OTE left', 20.00, 0, 0 )";
$sqlPopulateInventory12 = "INSERT INTO inventory (part_id, name, price, tax1_flag, tax2_flag) VALUES 
                        ('99400900', 'Tubes/Domes OTE right', 20.00, 0, 0 )";
$sqlPopulateInventories = array($sqlPopulateInventory01, $sqlPopulateInventory02, $sqlPopulateInventory03,
                                $sqlPopulateInventory04, $sqlPopulateInventory05, $sqlPopulateInventory06,
                                $sqlPopulateInventory07, $sqlPopulateInventory08, $sqlPopulateInventory09,
                                $sqlPopulateInventory10, $sqlPopulateInventory11, $sqlPopulateInventory12,
);

/*** RUNTIME ***/

dropDataBase($serverName, $userName, $password, $dbName);
echo "<br>";
createDataBase($serverName, $userName, $password, $dbName);
echo "<br>";

createTable($serverName, $dbName, $userName, $password, $sqlTaxType, "tax_type");
createTable($serverName, $dbName, $userName, $password, $sqlCountry, "country");
createTable($serverName, $dbName, $userName, $password, $sqlProvinceState, "province_state");
createTable($serverName, $dbName, $userName, $password, $sqlPaymentType, "payment_type");
createTable($serverName, $dbName, $userName, $password, $sqlInventory, "inventory");
createTable($serverName, $dbName, $userName, $password, $sqlInvoiceHeader, "invoice_header");
createTable($serverName, $dbName, $userName, $password, $sqlInvoiceDetail, "invoice_detail");
createTable($serverName, $dbName, $userName, $password, $sqlInvoicePayment, "invoice_payment");
echo "<br>";

insertToTable($serverName, $dbName, $userName, $password, $sqlPopulateTaxes, "tax_type");
echo "<br>";
insertToTable($serverName, $dbName, $userName, $password, $sqlPopulateCountries, "country");
echo "<br>";
insertToTable($serverName, $dbName, $userName, $password, $sqlPopulateProvinceStates, "province_state");
echo "<br>";
insertToTable($serverName, $dbName, $userName, $password, $sqlPopulatePayments, "payment_type");
echo "<br>";
insertToTable($serverName, $dbName, $userName, $password, $sqlPopulateInventories, "inventory");
echo "<br>";

?>