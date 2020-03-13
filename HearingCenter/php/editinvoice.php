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
    
    $mysql = new MySQLGetSelectData($serverName, $userName, $password, $dbName);    
    
    //Setup Invoice Header values
    $invoiceHeader = $mysql->getInvoiceHeader($invoiceID);


    if(!($invoiceHeader)){
        //Invoice not found
        echo "Invoive ID $invoiceID not found";
        echo "<br><a href='dashboard.html'>Back to Main Page</a>";        
    }else{
        //Invoice Found, begin creating page
        $invoiceDetails = $mysql->getInvoiceDetails($invoiceID);
        $invoicePayments = $mysql->getInvoicePayments($invoiceID);

        $name = $invoiceHeader['name'];
        $address1 = $invoiceHeader['address1'];
        $address2 = $invoiceHeader['address2'];
        $city = $invoiceHeader['city'];
        $provinceState = $invoiceHeader['province_state'];
        $postalZip = $invoiceHeader['postal_zip'];
        $country = $invoiceHeader['country'];
        $phone = $invoiceHeader['phone'];
        $email = $invoiceHeader['email'];
        $invoiceNumber = $invoiceHeader['invoice_number'];
        
        $rawInvoiceDate = $invoiceHeader['invoice_date'];
        $invoiceDate = date("Y-m-d",strtotime($rawInvoiceDate));

        $rawInvoiceDueDate = $invoiceHeader['due_date'];
        $invoiceDueDate = date("Y-m-d",strtotime($rawInvoiceDueDate));

        $note = $invoiceHeader['note'];
        $tax1_id = $invoiceHeader['tax1_id'];
        $tax2_id= $invoiceHeader['tax2_id'];

        //Initialize Arrays for Invoice Details/Item Lines
        $items = array();
        $quantities = array();
        $prices = array();
        $tax1Checks = array();
        $tax2Checks = array();

        //Setup Invoice Details/Item Lines values
        $detailsLineCount = count($invoiceDetails );
        for ($i = 0; $i < $detailsLineCount; $i++)
        {
            array_push($items, $invoiceDetails[$i]['part_id']);
            array_push($quantities,$invoiceDetails[$i]['quantity']);
            array_push($prices,$invoiceDetails[$i]['price']);
            array_push($tax1Checks, $invoiceDetails[$i]['tax1_flag']);
            array_push($tax2Checks, $invoiceDetails[$i]['tax2_flag']);
        }


        //Setup Invoice Payments values
        $paymentsLineCount = count($invoicePayments);
        if( $paymentsLineCount > 1){
        /* implementation for multiple payment types */
        }else{
            //single line implementation
            $paymentIDs = $invoicePayments[0]['payment_id'];
            $paymentAmounts = $invoicePayments[0]["payment_amount"];
            
        }    

        //Setup Invoice Totals values
        $tax1_total = $invoiceHeader['tax1_total']; 
        $tax2_total = $invoiceHeader['tax2_total'];
        $sub_total = $invoiceHeader['sub_total'];
        $net_total = $invoiceHeader['net_total'];

        //Create Invoice View Page
        $invoice = new Invoice(
            $invoiceID, $name, $address1, $address2, 
            $city, $provinceState, $postalZip, $country, 
            $phone, $email, $invoiceNumber, $invoiceDate, 
            $invoiceDueDate, $tax1_id, $tax2_id, $note,
            
            $items, $quantities, $prices, $tax1Checks, $tax2Checks,
            
            $paymentIDs, $paymentAmounts,
            
            $tax1_total, $tax2_total, $sub_total, $net_total
        );

        //prepping values needed for form creation
        $provinceStateIDs = $mysql->getProvinceStateIDs();
        $countryIDs = $mysql->getCountryIDs();
        $tax1IDs = $mysql->getTaxIDsWithPercent();
        $tax2IDs = $mysql->getTaxIDsWithPercent();
        $inventoryIDs = $mysql->getInventoryIDs();
        $paymentIDs = $mysql->getPaymentIDs();   

        $title = "Edit Invoice";
        $footer = "<br><a href='dashboard.html'>Back to Main Page</a>";
        $invoice->printEditInvoiceScreen($title, $footer, 
            $provinceStateIDs, $countryIDs, $tax1IDs, $tax2IDs, $inventoryIDs, $paymentIDs);    
    }

?>