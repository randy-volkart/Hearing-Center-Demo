<?php

class MySQLGetSelectData
{
    private $serverName;
    private $userName;
    private $password;
    private $dbName; 
    
    function __construct($serverName, $userName, $password, $dbName)
    {
        $this->serverName = $serverName;
        $this->userName = $userName;
        $this->password = $password;
        $this->dbName = $dbName;
    }

    //Gets the data to be used on the dashboard.html
    public function getDashBoardData()
    {
        $dashboardData = array();
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                SELECT invoice_id, name, city, invoice_date, due_date, net_total, last_update
                FROM invoice_header"
            );
            $stmt->execute([]);
            $dashboardData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }            

        return $dashboardData;        
    }    

    private function getIDs($query)
    {
        {   
            $ids = array();
    
            try {
                $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $ids = $stmt->fetchAll();
            }
            catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            finally{
                $conn = null;
            }
    
            return $ids;
        }           
    }

    public function getProvinceStateIDs()
    {   
        $provinceStateIDs = array();
        $provinceStateIDs = $this->getIDs("SELECT province_state_id FROM province_state");
        return $provinceStateIDs;
    }    

    public function getCountryIDs()
    {
        $countryIDs = array();
        $countryIDs = $this->getIDs("SELECT country_id FROM country");
        return $countryIDs;
    }

    public function getTaxIDs()
    {
        $taxIDs = array();
        $taxIDs = $this->getIDs("SELECT tax_id FROM tax_type");
        return $taxIDs;
    }

    public function getTaxIDsWithPercent()
    {
        $taxIDs = array();
        $taxIDs = $this->getIDs("SELECT tax_id, percent FROM tax_type ORDER BY percent");
        return $taxIDs;
    }    

    public function getInventoryIDs()
    {
        $inventoryIDs = array();
        $inventoryIDs = $this->getIDs("SELECT part_id, name FROM inventory ORDER BY name");
        
        return $inventoryIDs;        
    }

    public function getPaymentIDs()
    {
        $paymentIDs = array();
        $paymentIDs = $this->getIDs("SELECT payment_id, name FROM payment_type ORDER BY payment_id");
        
        return $paymentIDs;              
    }

    public function getTaxIDsByProvinceStateIDs($provinecStateID)
    {
        $countryIDs = array();
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT tax1_id, tax2_id FROM province_state WHERE province_state_id = :id");
            $stmt->execute(['id' => $provinecStateID]);
            $countryIDs = $stmt->fetch(PDO::FETCH_NUM);
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }            

        return $countryIDs;
    }

    /** Used with API to update line rows, which is currently incomplete */
    public function getInventoryItem($itemID)
    {
        $item = array();
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM inventory WHERE part_id = :id");
            $stmt->execute(['id' => $itemID]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $item = null;
        }            

        return $countryIDs;
    }

    public function getInvoiceHeader($invoiceID)
    {
        $invoiceHeader = array();
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM invoice_header WHERE invoice_id = :id");
            $stmt->execute(['id' => $invoiceID]);
            $invoiceHeader = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }            

        return $invoiceHeader;        
    }

    public function getInvoiceDetails($invoiceID)
    {
        $invoiceDetails = array();
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM invoice_detail WHERE invoice_id = :id");
            $stmt->execute(['id' => $invoiceID]);
            $invoiceDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }            

        return $invoiceDetails;        
    }    

    public function getInvoicePayments($invoiceID)
    {
        $invoicePayments = array();
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM invoice_payment WHERE invoice_id = :id");
            $stmt->execute(['id' => $invoiceID]);
            $invoicePayments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }            

        return $invoicePayments;        
    }
}

class MySQLInsertData
{
    private $serverName;
    private $userName;
    private $password;
    private $dbName; 

    private $lastInvoiceNumber;
    
    function __construct($serverName, $userName, $password, $dbName)
    {
        $this->serverName = $serverName;
        $this->userName = $userName;
        $this->password = $password;
        $this->dbName = $dbName;
    }

    public function insertInvoiceHeader(
        $name, $address1, $address2, $city, $provinceState, $postalZip, 
        $country, $phone, $email, $invoiceNumber, $invoiceDate, $invoiceDueDate, 
        $note, $tax1_id, $tax2_id, $tax1_total, $tax2_total, $sub_total, 
        $net_total)
    {
        $lastID = 0; 
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                INSERT INTO invoice_header (
                    name, address1, address2, city, province_state, postal_zip,
                    country, phone, email, invoice_number, invoice_date,  due_date,
                    note, tax1_id, tax2_id, tax1_total, tax2_total, sub_total, 
                    net_total
                ) VALUES (
                    ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?,
                    ?
                );
            ");
            $stmt->execute([ 
                $name, $address1, $address2, $city, $provinceState, $postalZip, 
                $country, $phone, $email, $invoiceNumber, $invoiceDate, $invoiceDueDate, 
                $note, $tax1_id, $tax2_id, $tax1_total, $tax2_total, $sub_total, 
                $net_total
            ]);

            $lastID = $conn->lastInsertId();
            $this->lastInvoiceNumber = $lastID;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }
        
        return $lastID;
    }

    public function InsertInvoiceDetail(
        $invoiceID, $recordNoItem, $partID, $quantity, 
        $price, $tax1Check, $tax2Check)
    {
        $result = false;

        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                INSERT INTO invoice_detail (
                    invoice_id, record_no, part_id, quantity, 
                    price, tax1_flag, tax2_flag
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?
                );
            ");
            $stmt->execute([ 
                $invoiceID, $recordNoItem, $partID, $quantity,
                $price, $tax1Check, $tax2Check
            ]);

            //$lastID = $conn->lastInsertId();
            //$this->lastInvoiceNumber = $lastID;
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }

        return $result;
    }

    public function InsertInvoicePayment($invoiceID, $recordNoPmt, $paymentID, $paymentAmount)
    {
        $result = false;

        try{
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                INSERT INTO invoice_payment (
                    invoice_id, record_no, payment_id, payment_amount
                ) VALUES (
                    ?, ?, ?, ?
                );
            ");
            $stmt->execute([ 
                $invoiceID, $recordNoPmt, $paymentID, $paymentAmount
            ]);

            $lastID = $conn->lastInsertId();
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }

        return $result;
    }
}

class MySQLUpdateData
{
    private $serverName;
    private $userName;
    private $password;
    private $dbName; 

    private $currentInvoiceNumber;
    
    function __construct($serverName, $userName, $password, $dbName, $currentInvoiceNumber)
    {
        $this->serverName = $serverName;
        $this->userName = $userName;
        $this->password = $password;
        $this->dbName = $dbName;

        $this->currentInvoiceNumber = $currentInvoiceNumber;
    }

    public function UpdateInvoiceHeader(
        $name, $address1, $address2, $city, $provinceState, $postalZip, 
        $country, $phone, $email, $invoiceNumber, $invoiceDate, $invoiceDueDate, 
        $note, $tax1_id, $tax2_id, $tax1_total, $tax2_total, $sub_total, 
        $net_total)
    {
        $result = false;
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                UPDATE invoice_header SET
                    name = ?, address1 = ?, address2 = ?, city = ?, province_state = ?, postal_zip = ?,
                    country = ?, phone = ?, email = ?, invoice_number = ?, invoice_date = ?,  due_date = ?,
                    note = ?, tax1_id = ?, tax2_id = ?, tax1_total = ?, tax2_total = ?, sub_total = ?, 
                    net_total = ?
                WHERE invoice_id = " . $this->currentInvoiceNumber . ";
            ");
            $stmt->execute([ 
                $name, $address1, $address2, $city, $provinceState, $postalZip, 
                $country, $phone, $email, $invoiceNumber, $invoiceDate, $invoiceDueDate, 
                $note, $tax1_id, $tax2_id, $tax1_total, $tax2_total, $sub_total, 
                $net_total
            ]);
            
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }
        
        return $result;
    }    

    //Primary key invoice_id+record_no will be an issue so for now simplfying by
    //removing old detail lines first then recreating
    public function InsertInvoiceDetail(
        $invoiceID, $recordNoItem, $partID, $quantity, 
        $price, $tax1Check, $tax2Check)
    {

        $result = false;

        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                INSERT INTO invoice_detail (
                    invoice_id, record_no, part_id, quantity, 
                    price, tax1_flag, tax2_flag
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?
                );
            ");
            $stmt->execute([ 
                $invoiceID, $recordNoItem, $partID, $quantity,
                $price, $tax1Check, $tax2Check
            ]);

            //$lastID = $conn->lastInsertId();
            //$this->lastInvoiceNumber = $lastID;
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }

        return $result;
    }

    //Primary key invoice_id+record_no will be an issue so for now simplfying by
    //removing old payment lines first then recreating
    public function UpdateInvoicePayment($invoiceID, $recordNoPmt, $paymentID, $paymentAmount)
    {
        $result = false;

        try{
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                UPDATE invoice_payment SET
                    invoice_id = ?, record_no = ?, payment_id = ?, payment_amount = ?
                WHERE invoice_id = " . $this->currentInvoiceNumber . ";;
            ");
            $stmt->execute([ 
                $invoiceID, $recordNoPmt, $paymentID, $paymentAmount
            ]);

            $lastID = $conn->lastInsertId();
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }

        return $result;
    }        

    public function DeleteInvoiceDetails()
    {
        $result = false;
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                DELETE FROM invoice_detail
                WHERE invoice_id = " . $this->currentInvoiceNumber . ";
            ");
            $stmt->execute([ ]);
            
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }
        
        return $result;
    }       

    public function DeleteInvoicePayment()
    {
        $result = false;
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                DELETE FROM invoice_payment
                WHERE invoice_id = " . $this->currentInvoiceNumber . ";
            ");
            $stmt->execute([ ]);
            
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }
        
        return $result;
    }       

}  

class MySQLDeleteData
{
    private $serverName;
    private $userName;
    private $password;
    private $dbName; 

    private $currentInvoiceNumber;
    
    function __construct($serverName, $userName, $password, $dbName, $currentInvoiceNumber)
    {
        $this->serverName = $serverName;
        $this->userName = $userName;
        $this->password = $password;
        $this->dbName = $dbName;

        $this->currentInvoiceNumber = $currentInvoiceNumber;
    }

    public function DeleteInvoicePayments()
    {
        $result = false;
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                DELETE FROM invoice_payment
                WHERE invoice_id = " . $this->currentInvoiceNumber . ";
            ");
            $stmt->execute([ ]);
            
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }
        
        return $result;
    }
    
    public function DeleteInvoiceDetails()
    {
        $result = false;
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                DELETE FROM invoice_detail
                WHERE invoice_id = " . $this->currentInvoiceNumber . ";
            ");
            $stmt->execute([ ]);
            
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }
        
        return $result;
    }
    
    public function DeleteInvoiceHeader()
    {
        $result = false;
        try {
            $conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("
                DELETE FROM invoice_header
                WHERE invoice_id = " . $this->currentInvoiceNumber . ";
            ");
            $stmt->execute([ ]);
            
            $result = true;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn = null;
        }
        
        return $result;
    }  
}      
?>