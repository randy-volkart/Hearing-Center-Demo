<?php
/*
*   Embedded Form for Creating New Invoices in Hearing Center application
*/
include "system/connectionstring.php";
include "classes/mysqlutilities.php";
//include "classes/htmlutilities.php";

define("DEFAULT_COUNTRY", "CAN");
define("DEFAULT_PROVINCE", "BC");

$serverName = MYSQL_SERVERNAME;
$userName = MYSQL_USERNAME;
$password = MYSQL_PASSWORD;
$dbName = MYSQL_DBNAME;

$mysql = new MySQLGetSelectData($serverName, $userName, $password, $dbName);
//$html = new SelectRows();

$defaultTaxes = $mysql->getTaxIDsByProvinceStateIDs(DEFAULT_PROVINCE);
$defaultTax01 = (is_null($defaultTaxes[0])) ? "0" : $defaultTaxes[0];
$defaultTax02 = (is_null($defaultTaxes[1])) ? "0" : $defaultTaxes[1];
$defaultDate = date("Y-m-d");
$defaultDueDate = date("Y-m-d", strtotime("+30 day"));

$provinceStateIDs = $mysql->getProvinceStateIDs();
$countryIDs = $mysql->getCountryIDs();
$tax1IDs = $mysql->getTaxIDsWithPercent();
$tax2IDs = $mysql->getTaxIDsWithPercent();
$inventoryIDs = $mysql->getInventoryIDs();
$paymentIDs = $mysql->getPaymentIDs();


function iterrate1KeyOptions($array, $key,  $selected)
{
    foreach($array as $item)
    {
        $id = $item[$key];
        if($id == $selected)
            echo "<option selected>" . $id . "</option>";
        else
            echo "<option>" . $id . "</option>";        
    }       
}

function iterrate2KeyOptions($array, $key1, $key2, $selected)
{
    foreach($array as $item)
    {
        $id = $item[$key1];
        $name = $item[$key2];
        if($id == $selected)
            echo "<option selected value ='" . $id . "'>" . $name . "</option>";
        else
            echo "<option value ='" . $id . "'>" . $name . "</option>";
    }        
}

function populateProvinceStateSelect($provinceStateIDs)
{
    iterrate1KeyOptions($provinceStateIDs, 'province_state_id', DEFAULT_PROVINCE);
}

function populateCountrySelect($countryIDs)
{
    iterrate1KeyOptions($countryIDs, 'country_id',DEFAULT_COUNTRY);
}

function populateTaxSelect($taxIDs, $defaultTax)
{
    iterrate2KeyOptions($taxIDs, 'tax_id', 'percent', $defaultTax);
    //iterrate1KeyOptions($taxIDs, 'tax_id', $defaultTax);    
}

function populateInventoryItemSelect($inventoryIDs)
{
    iterrate2KeyOptions($inventoryIDs, 'part_id', 'name', '');
}

function populatePaymentsSelect($paymentIDs)
{
    iterrate1KeyOptions($paymentIDs, 'payment_id', '');
}

function createInput($type, $class, $id, $name, $defaultDate)
{
    echo "<input type='$type' class='$class' id='$id' name='$name'  value='$defaultDate'/>";
}

function createInvoiceDueDateSelect($class, $id, $name, $defaultDueDate)
{
    echo "<input type='date' class='$class' id='$id' name='$name' value='$defaultDueDate'/>";
}

?>