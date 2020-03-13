<?php

include "system/connectionstring.php";
include "php/classes/mysqlutilities.php";
include "php/classes/invoice.php";
//include "classes/htmlutilities.php";

$serverName = MYSQL_SERVERNAME;
$userName = MYSQL_USERNAME;
$password = MYSQL_PASSWORD;
$dbName = MYSQL_DBNAME;



function convertCheckBoxToBit2($check)
{
    if($check =="on")
      $result = 1;
    else if($check == "off")
      $result = 0;
    else  
      $result = -1;

    return $result;
}

function processNewInvoice($serverName, $userName, $password, $dbName)
{
   //var_dump($_POST);
   $mysql = new MySQLInsertData($serverName, $userName, $password, $dbName);

   //setup invoice header
   $name = $_POST['_name'];
   $address1 = $_POST['_address1'];
   $address2 = $_POST['_address2'];
   $city = $_POST['_city'];
   $provinceState = $_POST['_provincestate'];
   $postalZip = $_POST['_postalzip'];
   $country = $_POST['_country'];
   $phone = $_POST['_Phone'];
   $email = $_POST['_email'];
   $invoiceNumber = $_POST['_invoicenumber'];

   $rawInvoiceDate = $_POST['_invoicedate'];
   $invoiceDate = date("Y-m-d",strtotime($rawInvoiceDate));

   $rawInvoiceDueDate = $_POST['_invoiceduedate'];
   $invoiceDueDate = date("Y-m-d",strtotime($rawInvoiceDueDate));

   $note = $_POST['_note'];
   $tax1_id = $_POST['_tax1'];
   $tax2_id= $_POST['_tax2'];
   $tax1_total = $_POST['_tax1Total']; 
   $tax2_total = $_POST['_tax2Total'];
   $sub_total = $_POST['_subTotal'];
   $net_total = $_POST['_netTotal'];

   $invoiceID = $mysql->insertInvoiceHeader(
       $name, $address1, $address2, $city, $provinceState, $postalZip, $country, 
       $phone, $email, $invoiceNumber, $invoiceDate, $invoiceDueDate, $note,
       $tax1_id, $tax2_id, $tax1_total, $tax2_total, $sub_total, $net_total
   );

   $recordCount = count($_POST['_item'] );

   $printReadOnlyDisplay = false;

   if($invoiceID != 0){
       $printReadOnlyDisplay = true;
       for ($i = 0; $i < $recordCount; $i++)
       {
           $recordNoItem = $i + 1;
           $partID =  $_POST['_item'][$i];
           $quantity = $_POST['_quantitiy'][$i];
           $price = $_POST['_price'][$i];

           $rawTax1Check = $_POST['_tax1Check'][$i];
           $tax1Check = convertCheckBoxToBit2($rawTax1Check);

           $rawTax2Check = $_POST['_tax2Check'][$i];
           $tax2Check = convertCheckBoxToBit2($rawTax2Check);

           $printReadOnlyDisplay = $mysql->InsertInvoiceDetail(
               $invoiceID, $recordNoItem, $partID, $quantity, 
               $price, $tax1Check, $tax2Check
           );           
       }

       if(!(is_array($_POST['_payment']))){
         $recordNoPmt = 1;
         $paymentID = $_POST['_payment'];
         $paymentAmount = $_POST['_paymentAmount'];

         $printReadOnlyDisplay = $mysql->InsertInvoicePayment(
             $invoiceID, $recordNoPmt, $paymentID, $paymentAmount
         );

       }else{
         /* implementation for multiple payment types */
       }
   }

   if($printReadOnlyDisplay){

       
       $items = $_POST['_item'];
       $quantities = $_POST['_quantitiy'];
       $prices = $_POST['_price'];
       $tax1Checks = $_POST['_tax1Check'];
       $tax2Checks = $_POST['_tax2Check'];
       $paymentIDs = $_POST['_payment'];
       $paymentAmounts = $_POST['_paymentAmount'];

       $invoice = new Invoice(
         $invoiceID, $name, $address1, $address2, 
         $city, $provinceState, $postalZip, $country, 
         $phone, $email, $invoiceNumber, $invoiceDate, 
         $invoiceDueDate, $tax1_id, $tax2_id, $note,
         
         $items, $quantities, $prices, $tax1Checks, $tax2Checks,
         
         $paymentIDs, $paymentAmounts,
         
         $tax1_total, $tax2_total, $sub_total, $net_total
       );

       $title = "Created New Invoice";
       $footer = "
           <br><a href='createinvoice.html'>Create Another Invoice</a>
           <br><a href='dashboard.html'>Back to Main Page</a>
       ";
       $invoice->printReadOnlyInvoiceScreen($title, $footer);
   }
   else{
       echo "Invoive failed to save";
       echo "<br><a href='createinvoice.html'>Create Another Invoice</a>";
       echo "<br><a href='dashboard.html'>Back to Main Page</a>";
   }

}

function processEditInvoice($serverName, $userName, $password, $dbName,$invoiceID)
{
    //var_dump($_POST);
    //
    $mysql = new MySQLUpdateData($serverName, $userName, $password, $dbName, $invoiceID);
    
    //setup invoice header variables
    $name = $_POST['_name'];
    $address1 = $_POST['_address1'];
    $address2 = $_POST['_address2'];
    $city = $_POST['_city'];
    $provinceState = $_POST['_provincestate'];
    $postalZip = $_POST['_postalzip'];
    $country = $_POST['_country'];
    $phone = $_POST['_Phone'];
    $email = $_POST['_email'];
    $invoiceNumber = $_POST['_invoicenumber'];
 
    $rawInvoiceDate = $_POST['_invoicedate'];
    $invoiceDate = date("Y-m-d",strtotime($rawInvoiceDate));
 
    $rawInvoiceDueDate = $_POST['_invoiceduedate'];
    $invoiceDueDate = date("Y-m-d",strtotime($rawInvoiceDueDate));
 
    $note = $_POST['_note'];
    $tax1_id = $_POST['_tax1'];
    $tax2_id= $_POST['_tax2'];
    $tax1_total = $_POST['_tax1Total']; 
    $tax2_total = $_POST['_tax2Total'];
    $sub_total = $_POST['_subTotal'];
    $net_total = $_POST['_netTotal'];

    $mysql->updateInvoiceHeader(
        $name, $address1, $address2, $city, $provinceState, $postalZip, $country, 
        $phone, $email, $invoiceNumber, $invoiceDate, $invoiceDueDate, $note,
        $tax1_id, $tax2_id, $tax1_total, $tax2_total, $sub_total, $net_total
    );

    $recordCount = count($_POST['_item'] );

    $printReadOnlyDisplay = false;
 
    if($invoiceID != 0){

        //clear old lines first
        $mysql->DeleteInvoiceDetails();

        $printReadOnlyDisplay = true;
        
        for ($i = 0; $i < $recordCount; $i++)
        {
            $recordNoItem = $i + 1;
            $partID =  $_POST['_item'][$i];
            $quantity = $_POST['_quantitiy'][$i];
            $price = $_POST['_price'][$i];
 
            $rawTax1Check = $_POST['_tax1Check'][$i];
            $tax1Check = convertCheckBoxToBit2($rawTax1Check);
 
            $rawTax2Check = $_POST['_tax2Check'][$i];
            $tax2Check = convertCheckBoxToBit2($rawTax2Check);
 
            $printReadOnlyDisplay = $mysql->InsertInvoiceDetail(
                $invoiceID, $recordNoItem, $partID, $quantity, 
                $price, $tax1Check, $tax2Check
            ); 
        }
 
        if(!(is_array($_POST['_payment']))){
          $recordNoPmt = 1;
          $paymentID = $_POST['_payment'];
          $paymentAmount = $_POST['_paymentAmount'];
 
          $printReadOnlyDisplay = $mysql->UpdateInvoicePayment(
              $invoiceID, $recordNoPmt, $paymentID, $paymentAmount
          );
 
        }else{
          /* implementation for multiple payment types */
        }
    }
 
    if($printReadOnlyDisplay){ 
        
        $items = $_POST['_item'];
        $quantities = $_POST['_quantitiy'];
        $prices = $_POST['_price'];
        $tax1Checks = $_POST['_tax1Check'];
        $tax2Checks = $_POST['_tax2Check'];
        $paymentIDs = $_POST['_payment'];
        $paymentAmounts = $_POST['_paymentAmount'];
 
        $invoice = new Invoice(
          $invoiceID, $name, $address1, $address2, 
          $city, $provinceState, $postalZip, $country, 
          $phone, $email, $invoiceNumber, $invoiceDate, 
          $invoiceDueDate, $tax1_id, $tax2_id, $note,
          
          $items, $quantities, $prices, $tax1Checks, $tax2Checks,
          
          $paymentIDs, $paymentAmounts,
          
          $tax1_total, $tax2_total, $sub_total, $net_total
        );
 
        $title = "Invoice Updated";
        $footer = "
            <br><a href='dashboard.html'>Back to Main Page</a>
        ";
        $invoice->printReadOnlyInvoiceScreen($title, $footer);
    }
    else{
        echo "Invoive failed to save";
        echo "<br><a href='createinvoice.html'>Create Another Invoice</a>";
        echo "<br><a href='dashboard.html'>Back to Main Page</a>";
    }    


}

//Main directional split
if (isset($_POST['_process']))
{
    if($_POST['_process'] == "new"){
        processNewInvoice($serverName, $userName, $password, $dbName);
    }else{
        $id = $_POST['_process'];
        processEditInvoice($serverName, $userName, $password, $dbName,$id);
    }
}
else{
    echo "No invoice Loaded";
    echo "<br><a href='dashboard.html'>Back to Main Page</a>";    
}

?>