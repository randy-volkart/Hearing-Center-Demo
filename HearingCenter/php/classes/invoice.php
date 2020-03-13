<?php

class Invoice
{
    //invoice headers
    private $invoiceID;
    private $name;
    private $address1;
    private $address2;
    private $city;
    private $provinceState;
    private $postalZip;
    private $country;
    private $phone;
    private $email;
    private $invoiceNumber;
    private $invoiceDate;
    private $invoiceDueDate;
    private $tax1_id;
    private $tax2_id ;   
    private $note;

    //inventory line items, arrays
    private $items;
    private $quantities;
    private $prices;
    private $tax1Checks;
    private $tax2Checks;

    //payment items, currently single but option for multiple can be implemented
    private $paymentIDs;
    private $paymentAmounts;

    //invoice totals
    private $tax1_total;
    private $tax2_total;
    private $sub_total;
    private $net_total;
    
    function __construct(
        $invoiceID, $name, $address1, $address2, 
        $city, $provinceState, $postalZip, $country, 
        $phone, $email, $invoiceNumber, $invoiceDate, 
        $invoiceDueDate, $tax1_id, $tax2_id, $note,
        
        $items, $quantities, $prices, $tax1Checks, $tax2Checks,
        
        $paymentIDs, $paymentAmounts,
        
        $tax1_total, $tax2_total, $sub_total, $net_total
    )       
    {
        $this->invoiceID = $invoiceID;
        $this->name = $name;
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->city = $city;
        $this->provinceState = $provinceState;
        $this->postalZip = $postalZip;
        $this->country = $country;
        $this->phone = $phone;
        $this->email = $email;
        $this->invoiceNumber = $invoiceNumber;
        $this->invoiceDate = $invoiceDate;
        $this->invoiceDueDate = $invoiceDueDate;
        $this->tax1_id = $tax1_id;
        $this->tax2_id = $tax2_id;
        $this->note = $note;        

        $this->items = $items;
        $this->quantities = $quantities;
        $this->prices = $prices;
        $this->tax1Checks = $tax1Checks;
        $this->tax2Checks = $tax2Checks;
        
        $this->paymentIDs = $paymentIDs;
        $this->paymentAmounts = $paymentAmounts;
        
        $this->tax1_total = $tax1_total;
        $this->tax2_total = $tax2_total;
        $this->sub_total = $sub_total;
        $this->net_total = $net_total;
    }

    //Dumps the createinvoice.html page into, simplifying it to be read only 
    //for viewing purposes and substiting fields with value=$this
    public function printReadOnlyInvoiceScreen($headerTitle, $footer)
    {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset='UTF-8'>
        <meta name='description' content='Invoicing Application for Hearing Center Canada'>
        <meta name='author' content='Randy Volkart'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
        
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
        
        <style>
        </style>
        
        <title>Hearing Center - $headerTitle</title>
        
        </head>
        
        <body>
            
            <div class='container' style='background-color:lightblue;'>
                <br>
                <hr>
                <h2 style='width: 100%;text-align:center;'>$headerTitle</h2>
                <hr>
                <form>                    
                    <!-- Customer Information -->
                    <div class='form-row' >
                        <div class='form-group col-md-2'>
                            <label for='invoiceid'>Invoice ID:</label>
                            <input type='text' class='form-control' id='invoiceid' value='$this->invoiceID' readonly />
                        </div>                      
                        <div class='form-group col-md-10'>
                            <label for='name'>Name:</label>
                            <input type='text' class='form-control' id='name' value='$this->name' readonly />
                        </div>  
                    </div>
        
                    <div class='form-row' >
                        <div class='form-group col-md-12'>        
                            <label class='control-label' for='address1'>Address:</label>
                            <input type='text' class='form-control' id='address1' value='$this->address1'  readonly/>                        
                        </div>
                    </div>
        
                    <div class='form-row' >
                        <div class='form-group col-md-12'>
                            <input type='text' class='form-control' value='$this->address2' readonly/>
                        </div>
                    </div>  
        
                    <div class='form-row' >
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='city'>City:</label>
                            <input type='text' class='form-control' id='city' value='$this->city' readonly/>
                        </div>
                        <div class='form-group col-md-2'>
                            <label class='control-label' for='province'>Province/State:</label>
                            <input type='text' class='form-control' id='province' value='$this->provinceState' readonly/>
                        </div>                
                        <div class='form-group col-md-2'>
                            <label class='control-label' for='postal'>Postal/Zip:</label>
                            <input type='text' class='form-control' id='postal' value='$this->postalZip' readonly/>
                        </div>         
                        <div class='form-group col-md-2'>
                            <label class='control-label for='country'>Country:</label>
                            <input type='text' class='form-control' id='country' value='$this->country' readonly/>
                        </div>                            
                    </div>  
        
                    <div class='form-row' >
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='phone'>Phone:</label>
                            <input type='text' class='form-control' id='phone' value='$this->phone' readonly/>
                        </div>
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='email'>Email:</label>
                            <input type='text' class='form-control'  id='email' value='$this->email' readonly/>
                        </div>                
                    </div>
                    
                    <!-- Invoice Information -->
                    <div class='form-row' >
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='invoicenumber'>Invoice Number:</label>
                            <input type='text'  class='form-control' id='invoicenumber' value='$this->invoiceNumber'  readonly/>                    
                        </div>
                        <div class='form-group col-md-3'>
                            <label class='control-label' for='tax1'>Tax 1 Pct:</label>
                            <input type='text' class='form-control' id='tax1' value='$this->tax1_id' readonly>
                        </div>   
                        <div class='form-group col-md-3'>
                            <label class='control-label' for='tax2'>Tax 2 Pct:</label>
                            <input type='text' class='form-control' id='tax2' value='$this->tax2_id' readonly>
                        </div>         
                    </div>
        
                    <div class='form-row' >
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='invoicedate'>Invoice Date :</label>
                            <input type='text' class='form-control' id='invoicedate' value='$this->invoiceDate' readonly>
                        </div>
                        <div class='form-group col-md-6'>
                            <label class='control-label ' for='invoiceduedate'>Invocie Due Date:</label>
                            <input type='text' class='form-control' id='invoiceduedate' value='$this->invoiceDueDate' readonly>
                         </div>
                    </div>
        
                    <div class='form-row' >
                        <div class='form-group col-md-12'>
                            <label class='control-label' for='note'>Note:</label>
                            <textarea  class='form-control' rows='3' id='note' readonly>$this->note</textarea>
                        </div>
                    </div>
                    
                    <!-- Line Items -->
                    <hr>
                    <h4 style='width: 100%;text-align:center;'>Line Items</h4>
                    <hr>         
        
                    <div class='form-row'>
                         <div class='form-group col-md-5'>
                            <label class='control-label' >Item:</label>
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='control-label' >Quantity:</label>
                        </div>
                        <div class='form-group col-md-2'>
                            <label class='control-label' >Price:</label>
                        </div>
                        <div class='col-md-1'>
                            <label class='form-check-label' >Tax 1:</label>
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='form-check-label' >Tax 2:</label>
                        </div>
                        <div class='form-group col-md-2'>
                            <label class='control-label' >Ext. Price:</label>
                        </div>
                    </div>
                    ";

        $recordCount = count($this->items );
        for ($i = 0; $i < $recordCount; $i++)
        {
            echo "
                    <div class='form-row' >
                        <div class='form-group col-md-5'>
                            <input type='text' class='form-control' value='" . $this->items[$i] . "' readonly>
                        </div>
                        <div class='form-group col-md-1'>
                            <input type='text' class='form-control' value='" . $this->quantities[$i] . "' readonly/>
                        </div>
                        <div class='form-group col-md-2'>
                            <input type='text' class='form-control' value='" . $this->prices[$i]. "' readonly/>    
                        </div>
                        <div class='form-group col-md-1'>
            ";
            
            if ($this->tax1Checks[$i] == "on" )
                echo "<input class='form-check-input' type='checkbox' checked disabled />";
            else 
                echo "<input class='form-check-input' type='checkbox' disabled/>";
            
            echo "
                        </div>
                        <div class='form-group col-md-1'>
            ";
            
            if ($this->tax2Checks[$i] == "on" )
                echo "<input class='form-check-input' type='checkbox' checked disabled />";
            else 
                echo "<input class='form-check-input' type='checkbox' disabled />";
            
            echo "
                        </div>
            
                        <div class='form-group col-md-2'>
                            <input type='text' class='form-control' value='" . $this->quantities[$i] * $this->prices[$i]  . "' readonly />
                        </div>
                    </div>
            ";
        }
        
        echo"
                    <!-- Payment Line Items-->
                    <hr>
                    <h4 style='width: 100%;text-align:center;'>Payments</h4>
                    <hr>
        
                    <div class='form-row'>
                        <div class='form-group col-md-6'>
                            <label class='control-label' >Payment Type:</label>
                        </div>
                        <div class='form-group col-md-6'>
                            <label class='control-label' >Payment Amount:</label>
                        </div>    
                    </div>
        
                    <div class='payment-row' >
                        <div class='form-row' >
                            <div class='form-group col-md-6'>
                                <input type='text' class='form-control' id='payment' value='$this->paymentIDs' readonly>
                            </div>
                            <div class='form-group col-md-6'>
                                <input type='text' class='form-control' id='paymentAmount'  value='$this->paymentAmounts' readonly />  
                            </div>              
                        </div>  
                    </div>
        
                    <!-- Invoice Totals-->
                    <hr>
                    <h4 style='width: 100%;text-align:center;'>Invoice Totals</h4>
                    <hr>
                    <div class='form-row' >
                        <div class='form-group col-md-1'>
                            <label class='control-label' >Subtotal:</label>    
                        </div>
                        <div class='form-group col-md-2'>
                            <input type='text' class='form-control' id='subTotal'  value='$this->sub_total' readonly />  
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='control-label col-sm-1'>$this->tax1_id:</label>   
                        </div>
                        <div class='form-group col-md-2'>     
                            <input type='text' class='form-control' id='tax1Total' value='$this->tax1_total' readonly/>    
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='control-label col-sm-1' >$this->tax2_id:</label>      
                        </div>
                        <div class='form-group col-md-2'>
                            <input type='text' class='form-control' id='tax2Total' value='$this->tax2_total' readonly/>     
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='control-label col-sm-1' >Total:</label>  
                        </div>
                        <div class='form-group col-md-2'>
                            <input type='text' class='form-control' id='netTotal' value='$this->net_total' readonly/>     
                        </div>                
                    </div>
                    
                    
                    <div class='form-row' >
                        <div class='form-group col-md-12'>
                            $footer
                        </div>     
                    </div>  
                    
        
                </form>

            </div>
            
        </body>  
        </html>       
        ";
    }

    //Dumps the createinvoice.html page into, simplifying it to be read only 
    //for viewing purposes and substiting fields with value=$this
    //...doing it this way seemed like a good idea at the time
    public function printEditInvoiceScreen($headerTitle, $footer, 
        &$provinceStateIDs, &$countryIDs, &$tax1IDs, &$tax2IDs, &$inventoryIDs, &$paymentIDs)
    {
        echo"
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset='UTF-8'>
        <meta name='description' content='Invoicing Application for Hearing Center Canada'>
        <meta name='author' content='Randy Volkart'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
        
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
        
        <style>
            hr {
                margin-top:0px;
                margin-bottom:0px;
                border: 0;
                clear:both;
                display:block;
                width: 100%;               
                background-color:whitesmoke;
                height: 1px;
            }
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
            /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
        }    
        </style>
        
        <title>Hearing Center - Create Invoice</title>
        
        </head>
        
        <body>
            
            <div class='container' style='background-color:aquamarine;'>
                <br>
                <hr>
                <h2 style='width: 100%;text-align:center;'>$headerTitle</h2>
                <hr>
                <form action='process.php' method='POST'>
                    <input type='hidden' name='_process' value='$this->invoiceID' />
                    <?php include '../createinvoice.php'; ?>
                    
                    <!-- Customer Information -->
    
                    <div class='form-row' >
                        <div class='form-group col-md-2'>
                            <label for='invoiceid'>Invoice ID:</label>
                            <input type='text' class='form-control' id='invoiceid' value='$this->invoiceID' readonly />
                        </div>                      
                        <div class='form-group col-md-10'>
                            <label for='name'>Name:</label>
                            <input type='text' class='form-control' id='name' name='_name' value='$this->name' />
                        </div>  
                    </div>                    
        
                    <div class='form-row' >
                        <div class='form-group col-md-12'>        
                            <label class='control-label' for='address1'>Address:</label>
                            <input type='text' class='form-control' id='address1'name='_address1' value='$this->address1' />                        
                        </div>
                    </div>
        
                    <div class='form-row' >
                        <div class='form-group col-md-12'>
                            <input type='text' class='form-control' id='address2' name='_address2' value='$this->address2' />
                        </div>
                    </div>  
        
                    <div class='form-row' >
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='city'>City:</label>
                            <input type='text' class='form-control' id='city' name='_city' value='$this->city' />
                        </div>
                        <div class='form-group col-md-2'>
                            <label class='control-label' for='province'>Province/State:</label>
                            <select class='form-control' id='province' name='_provincestate' required>
                            ";
                                foreach($provinceStateIDs as $item)
                                {
                                    $id = $item['province_state_id'];
                                    if($id ==  $this->provinceState )
                                        echo '<option selected>' . $id . '</option>';
                                    else
                                        echo '<option>' . $id . '</option>';
                                }                                  
                            echo "
                            </select>                    
                        </div>                
                        <div class='form-group col-md-2'>
                            <label class='control-label' for='postal'>Postal/Zip:</label>
                            <input type='text' class='form-control' id='postal' name='_postalzip' value='$this->postalZip' />
                        </div>         
                        <div class='form-group col-md-2'>
                            <label class='control-label for='country'>Country:</label>
                            <select class='form-control' id='country' name='_country' required>
                            ";
                                foreach($countryIDs as $item)
                                {
                                    $id = $item['country_id'];
                                    if($id ==  $this->country )
                                        echo '<option selected>' . $id . '</option>';
                                    else
                                        echo '<option>' . $id . '</option>';
                                }                                  
                            echo "
                            </select>   
                        </div>                            
                    </div>  
        
                    <div class='form-row' >
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='phone'>Phone:</label>
                            <input type='text' class='form-control' id='phone' name='_Phone' value='$this->phone' />                    
                        </div>
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='email'>Email:</label>
                            <input type='email' class='form-control' id='email' name='_email' value='$this->email' />
                        </div>                
                    </div>
                    
                    <!-- Invoice Information -->
                    <div class='form-row' >
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='invoicenumber'>Invoice Number:</label>
                            <input type='text'  class='form-control' id='invoicenumber' name='_invoicenumber' value='$this->invoiceNumber'/>                    
                        </div>
                        <div class='form-group col-md-3'>
                            <label class='control-label' for='tax1'>Tax 1 Pct:</label>
                            <select class='form-control' id='tax1' name='_tax1' required>
                            ";
                            foreach($tax1IDs as $item)
                            {
                                $id = $item['tax_id'];
                                $name = $item['percent'];
                                if($id == $this->tax1_id )
                                    echo "<option selected value ='" . $id . "'>" . $name . "</option>";
                                else
                                    echo "<option value ='" . $id . "'>" . $name . "</option>";
                            }                                  
                            echo "                            
                            </select>                                        
                        </div>   
                        <div class='form-group col-md-3'>
                            <label class='control-label' for='tax2'>Tax 2 Pct:</label>
                            <select class='form-control' id='tax2' name='_tax2' required>
                            ";
                            foreach($tax2IDs as $item)
                            {
                                $id = $item['tax_id'];
                                $name = $item['percent'];
                                if($id == $this->tax2_id )
                                    echo "<option selected value ='" . $id . "'>" . $name . "</option>";
                                else
                                    echo "<option value ='" . $id . "'>" . $name . "</option>";
                            }                                  
                            echo "                            
                            </select>   
                        </div>         
                    </div>
        
                    <div class='form-row' >
                        <div class='form-group col-md-6'>
                            <label class='control-label' for='invoicedate'>Invoice Date :</label>
                            <input type='date' class='form-control' id='invoicedate' name='_invoicedate''  value='$this->invoiceDate'/>          
                        </div>
                        <div class='form-group col-md-6'>
                            <label class='control-label ' for='invoiceduedate'>Invocie Due Date:</label>
                            <input type='date' class='form-control' id='invoiceduedate' name='_invoiceduedate''  value='$this->invoiceDueDate'/>   
                         </div>
                    </div>
        
                    <div class='form-row' >
                        <div class='form-group col-md-12'>
                            <label class='control-label' for='note'>Note:</label>
                            <textarea  class='form-control' rows='3' id='note' name='_note' placeholder='Enter Notes'>$this->note</textarea>
                        </div>
                    </div>
                    
                    <!-- Line Items -->
                    <hr>
                    <h4 style='width: 100%;text-align:center;'>Line Items</h4>
                    <hr>         
        
                    <div class='form-row'>
                        <div class='form-group col-md-1'>
                            <label class='control-label' ></label>
                        </div>                           
                        <div class='form-group col-md-4'>
                            <label class='control-label' >Item:</label>
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='control-label' >Quantity:</label>
                        </div>
                        <div class='form-group col-md-2'>
                            <label class='control-label' >Price:</label>
                        </div>
                        <div class='col-md-1'>
                            <label class='form-check-label' >Tax 1:</label>
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='form-check-label' >Tax 2:</label>
                        </div>
                        <div class='form-group col-md-2'>
                            <label class='control-label' >Ext. Price:</label>
                        </div>
                    </div>
        ";

        //Break for For loop on line items
        $recordCount = count($this->items );
        for ($i = 0; $i < $recordCount; $i++) 
        {
            echo "
                    <span id='itemRow_" . $i . "'>
                        <div class='form-row' >
                            <div class='form-group col-md-1'>
                            ";

                            //don't add the delete button if it's the first row
                            if($i == 0)
                                echo "<!--<input type='button' class='form-control' id='item_delete_0' onclick='deleteItemLine(this)' value='-'/>-->";
                            else{

                                //Not deleting?
                                echo  "
                                    <input class='form-control' type='button'  id='itemDelete_" . $i . "' value='x'
                                        onclick=\"removeItemRow(" . $i . ", 'itemRow_0','itemRow_" . $i . "');\"
                                    />
                                "; //creating an "Uncaught SyntaxError: Unexpected end of input" error
                            }
                            echo"
                            </div>                           
                            <div class='form-group col-md-4'>
                                <select class='form-control' id='item_" . $i . "' name='_item[]' required>
                                
                            ";
                            foreach($inventoryIDs as $item)
                            {
                                $id = $item['part_id'];
                                $name = $item['name'];
                                if($id == $this->items[$i])
                                    echo "<option selected value ='" . $id . "'>" . $name . "</option>";
                                else
                                    echo "<option value ='" . $id . "'>" . $name . "</option>";
                            }   
                            echo "
                                </select>
                            </div>
                            <div class='form-group col-md-1'>
                                <input type='number' class='form-control' id='quantity_" . $i . "' name='_quantitiy[]' min='0' max='99999999' 
                                    value='" . $this->quantities[$i] . "'
                                    onblur='updateItemPrices(" . $i . ")'/>
                            </div>
                            <div class='form-group col-md-2'>
                                <input type='number' class='form-control' id='price_" . $i . "' name='_price[]' step='0.01'
                                    value='" . $this->prices[$i] . "'
                                    onblur='updateItemPrices(" . $i . ")'/>    
                            </div>
                            <div class='form-group col-md-1'>
                            ";

                            if ($this->tax1Checks[$i] == "1" )
                                echo "<input class='form-check-input' type='checkbox' id='tax1Check_" . $i . "' name='_tax1Check[]' checked
                                    onchange='updateItemPrices(" . $i . ")' />";
                            else
                                echo "<input class='form-check-input' type='checkbox' id='tax1Check_" . $i . "' name='_tax1Check[]'
                                    onchange='updateItemPrices(" . $i . ")' />";                            
  
                            echo "<input id='tax1CheckHidden_" . $i . "' type='hidden' value='off' name='_tax1Check[]'>
                                </div>
                                <div class='form-group col-md-1'>
                            ";

                            if ($this->tax2Checks[$i] == "1" )
                                echo "<input class='form-check-input' type='checkbox' id='tax2Check_" . $i . "' name='_tax2Check[]' checked
                                    onchange='updateItemPrices(" . $i . ")' />";
                            else
                                echo "<input class='form-check-input' type='checkbox' id='tax2Check_" . $i . "' name='_tax2Check[]'
                                    onchange='updateItemPrices(" . $i . ")' />";  
                            echo "<input id='tax2CheckHidden_" . $i . "' type='hidden' value='off' name='_tax2Check[]'>                            
                            </div>
                            <div class='form-group col-md-2'>
                                <input type='number' class='form-control' id='extPrice_" . $i . "' name='_extPrice[]' value=" . $this->quantities[$i] * $this->prices[$i]  . " readonly />
                            </div>
                        </div>
                    </span>      
        ";
        }   
        //End Break on For loop on line items

        echo "
                    <div class='form-row' id ='itemAdd'>
                        <div class='form-group col-md-10'></div>
                        <div class='form-group col-md-2'>
                            <input type='button' class='form-control' id='itemAdd' value='Add Item' onclick='createItemRow()'/>
                        </div>   
                    </div>               
        
                    <!-- Payment Line Items-->
                    <hr>
                    <h4 style='width: 100%;text-align:center;'>Payments</h4>
                    <hr>
        
                    <div class='form-row'>
                        <div class='form-group col-md-6'>
                            <label class='control-label' >Payment Type:</label>
                        </div>
                        <div class='form-group col-md-6'>
                            <label class='control-label' >Payment Amount:</label>
                        </div>    
                    </div>
        
                    <div class='payment-row' >
                        <div class='form-row' >
                            <div class='form-group col-md-6'>
                                <select class='form-control' id='payment' name='_payment' required>
                                ";

                                foreach($paymentIDs as $item)
                                {
                                    $id = $item['payment_id'];
                                    if($id ==  $this->paymentIDs)
                                        echo "<option selected>" . $id . "</option>";
                                    else
                                        echo "<option>" . $id . "</option>";
                                }         

                                echo "                              
                                </select>                    
                            </div>
                            <div class='form-group col-md-6'>
                                <input type='number' class='form-control' id='paymentAmount' name='_paymentAmount' step='0.01' value=$this->paymentAmounts />                    
                            </div>              
                        </div>  
                    </div>
        
                    <!-- Invoice Totals-->
                    <hr>
                    <h4 style='width: 100%;text-align:center;'>Invoice Totals</h4>
                    <hr>
                    <div class='form-row' >
                        <div class='form-group col-md-1'>
                            <label class='control-label' >Subtotal:</label>    
                        </div>
                        <div class='form-group col-md-2'>
                            <input type='number' class='form-control' id='subTotal' name='_subTotal'  value='$this->sub_total' step='0.01' readonly />      
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='control-label col-sm-1'>$this->tax1_id:</label>         
                        </div>
                        <div class='form-group col-md-2'>       
                            <input type='number' class='form-control' id='tax1Total' name='_tax1Total' value='$this->tax1_total' step='0.01' readonly/>     
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='control-label col-sm-1' >$this->tax2_id:</label>         
                        </div>
                        <div class='form-group col-md-2'>
                            <input type='number' class='form-control' id='tax2Total' name='_tax2Total' value='$this->tax2_total' step='0.01' readonly/>      
                        </div>
                        <div class='form-group col-md-1'>
                            <label class='control-label col-sm-1' >Total:</label>  
                        </div>
                        <div class='form-group col-md-2'>
                            <input type='number' class='form-control' id='netTotal' name='_netTotal' value='$this->net_total' step='0.01' readonly/>       
                        </div>                
                    </div>
                    
                    <div class='form-row' >
                        <div class='form-group col-md-12'>
                            <button type='submit' class='btn btn-primary' style='width: 100%;text-align:center;'
                                onclick='validateSubmission();'>Update</button>
                        </div>     
                    </div>     

                    <div class='form-row' >
                        <div class='form-group col-md-12'>
                            $footer
                        </div>     
                    </div>                      
                </form>
            </div>
        
            <script src='js/createinvoice.js'></script>
            
        </body>
        
        </html> 
        ";

    }
}

?>