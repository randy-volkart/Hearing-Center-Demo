/***
* Javascript functions for page createinvoice.html
***/

//Global variable used for the number of line items added
var itemCount = 0;

//Updates the itemCount for when an invoice was loaded
/** itemCount is correct but still not adding to the right place
 * when editing invoice and adding a new line
 */
function refreshItemCount()
{
    itemCount = $("span").length - 1;
}

//If doing update re-append spans to parent node so they can be deleted
function attachSpanNodesToParent()
{
    var parent = document.getElementById('itemRow_0');
    for (var i = 1; i <= itemCount; i++)
    {
        var id = "itemRow_" + i
        var child = document.getElementById(id);
        parent.append(child);

    }
}

refreshItemCount();
attachSpanNodesToParent();

function getTaxAmount(amt, tax, taxcheck )
{
    var tax1Index = document.getElementById(tax).selectedIndex;
    var taxPct = 0;
    if (document.getElementById(taxcheck).checked)
        taxPct = document.getElementById("tax1").options[tax1Index].text;      
    
    return taxPct * 0.01 * amt;
}

//Clears the invoice total fields then recalculates them based on each item line.
//Done in this manner to simplify modifying an existing item line
function calculateInvoiceTotals()
{
    //reset totals
    document.getElementById("subTotal").value = 0;
    document.getElementById("tax1Total").value = 0;
    document.getElementById("tax2Total").value = 0;
    document.getElementById("netTotal").value = 0;    
    //iterates through each item line to calculate the invoice totals
    for (var i = 0; i <= itemCount; i++ )
    {
        //elements can be missing if deleted, so check one element for null
        var element =  document.getElementById("price_" + i);

        if (typeof(element) != 'undefined' && element != null){        
            var extAmt = document.getElementById("price_" + i).value * document.getElementById("quantity_" + i).value;
            var tax1Amt = getTaxAmount(extAmt, "tax1", "tax1Check_" + i);
            var tax2Amt = getTaxAmount(extAmt, "tax2", "tax2Check_" + i);

            AddToInvoiceTotals ("subTotal", extAmt );
            AddToInvoiceTotals ("tax1Total", tax1Amt);
            AddToInvoiceTotals ("tax2Total", tax2Amt);
            
            var totAmt = parseFloat(extAmt)+ parseFloat(tax1Amt) + parseFloat(tax2Amt);
            AddToInvoiceTotals ("netTotal", totAmt);  
        }        
    }

    //Update the amount in the payment field for user convenience
    document.getElementById("paymentAmount").value = document.getElementById("netTotal").value
}

//function used when a change in an item price or quantity occurs, 
//then triggers the calculateInvoiceTotals function
function updateItemPrices( idNo )
{
    //Get Extended Amounts
    var extPrice = document.getElementById("price_" + idNo).value * document.getElementById("quantity_" + idNo).value;
    document.getElementById("extPrice_" + idNo).value = extPrice;

    //Calculate taxes
    var tax1Amt = getTaxAmount(extPrice, "tax1", "tax1Check_" + idNo);
    var tax2Amt = getTaxAmount(extPrice, "tax2", "tax2Check_" + idNo);  

    //Calculate New Invoice totals
    calculateInvoiceTotals()
}

function AddToInvoiceTotals( id, val)
{
    document.getElementById(id).stepUp(val * 100);
}

function SubtractFromInvoiceTotals( id, val)
{
    document.getElementById(id).stepDown(val * 100);
}

//initial list is built from PHP selection, need to get the options and insert them into 
//element item_# in function createItemRow()
function buildItemOptionListFromInitialPHP()
{
    var opt = document.getElementById("item_0");
    var createList = "";
    for(var i = 0; i < opt.length; i++)
    {
        var value = opt.options[i].value;
        var name = opt.options[i].text;
        createList = createList + " <option value='" + value + "'>" + name + "</option> " ;
    }
    return createList;
}


function validateSubmission()
{
    //only validation needed for the moment is converting unchecked checkboxes to a POST value
    //using a hidden input
    for (var i = 0; i <= itemCount; i++ )
    {
        //elements can be missing if deleted, so check one element for null
        var elTax1Check =  document.getElementById("tax1Check_" + i);
        
        if (typeof(elTax1Check) != 'undefined' && elTax1Check != null){        
            if(document.getElementById("tax1Check_" + i).checked) {
                document.getElementById("tax1CheckHidden_" + i).disabled = true;
            } 
        }
        
        var elTax2Check =  document.getElementById("tax2Check_" + i);

        if (typeof(elTax2Check) != 'undefined' && elTax2Check != null){        
            if(document.getElementById("tax2Check_" + i).checked) {
                document.getElementById("tax2CheckHidden_" + i).disabled = true;
            } 
        } 
    }    


}
//The first item row is hardcoded into the html, this function will create new rows below it
function createItemRow()
{
    itemCount++;
    
    var newSpan = document.createElement("SPAN")
    newSpan.setAttribute("id", "itemRow_" + itemCount.toString());

    var newDiv =document.createElement("DIV");
    newDiv.setAttribute("class", "form-row");
    newDiv.innerHTML = `
        <div class="form-group col-md-1">
            <input class="form-control" type="button"  id="itemDelete_` + itemCount + `"
                onclick="removeItemRow(` + itemCount + `, 'itemRow_0','itemRow_` + itemCount + `');"
            value="x"/>
        </div>                           
        <div class="form-group col-md-4">
            <select class="form-control" id="item_` + itemCount + `" name="_item[]" required>
                ` + buildItemOptionListFromInitialPHP() + `
            </select>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="quantity_` + itemCount + `" name="_quantitiy[]" min="0" max="99999999" value="1"
                onblur="updateItemPrices(` + itemCount + `)"/>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="price_` + itemCount + `" name="_price[]" value="0.00" step="0.01"
                onblur="updateItemPrices(` + itemCount + `)" style"background-color:red;"/>    
        </div>
        <div class="form-group col-md-1">
            <input class="form-check-input" type="checkbox" id="tax1Check_` + itemCount + `" name="_tax1Check[]" checked    
                onchange="updateItemPrices(` + itemCount + `)"/>
            <input id='tax1CheckHidden_` + itemCount + `' type='hidden' value='off' name='_tax1Check[]'>     
        </div>
        <div class="form-group col-md-1">
            <input class="form-check-input" type="checkbox" id="tax2Check_` + itemCount + `" name="_tax2Check[]" checked
                onchange="updateItemPrices(` + itemCount + `)"/>
            <input id='tax2CheckHidden_` + itemCount + `' type='hidden' value='off' name='_tax2Check[]'>      
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="extPrice_` + itemCount + `" name="_extPrice[]" readonly />
        </div>            
    `;

    newSpan.append(newDiv);

    document.getElementById("itemRow_0").appendChild(newSpan);
}

//Removes one of the line item row that triggered the event, and updates invoice totals
//with reduced amount.
function removeItemRow(id, parentDiv, childDiv)
{
    if (childDiv == parentDiv){
        //The parent div cannot be removed
        return false;
    }
    else if (document.getElementById(childDiv)){

        //setup amounts from row #id to subtract from totals
        var extAmt = document.getElementById("extPrice_" + id).value;
        var tax1Amt = getTaxAmount(extAmt, "tax1", "tax1Check_" + id);
        var tax2Amt = getTaxAmount(extAmt, "tax2", "tax2Check_" + id);
        
        //remove row
        var child = document.getElementById(childDiv);
        var parent = document.getElementById(parentDiv);
        parent.removeChild(child);

        //Adjust net total amounts
        SubtractFromInvoiceTotals ("subTotal", extAmt );
        SubtractFromInvoiceTotals ("tax1Total", tax1Amt);
        SubtractFromInvoiceTotals ("tax2Total", tax2Amt);

        var totAmt = parseFloat(extAmt)+ parseFloat(tax1Amt) + parseFloat(tax2Amt);
        SubtractFromInvoiceTotals ("netTotal", totAmt);          

        //Update the amount in the payment field for user convenience
        document.getElementById("paymentAmount").value = document.getElementById("netTotal").value
        return true;
    }
    else{
        //Child div has already been removed or does not exist
        return false;
    }		
}

function UpdateLine( val )
{
    /* Unfinished, need to use API request to update price and taxes using item code */
}

//API Request
function createRequest() {
    var result = null;
    if (window.XMLHttpRequest) {
    // FireFox, Safari, etc.
        result = new XMLHttpRequest();
    }else if (window.ActiveXObject) {
        // MSIE
        result = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
    // No known mechanism -- consider aborting the application
    }
    return result;
}



//API to get inventory item details
function loadJSONitemDetail(itemId) {
    
    var req = createRequest(); // defined above
    
    // Create the callback:
    req.onreadystatechange = function() {
        if (req.readyState != 4) return;// Not there yet
        if (req.status != 200) {		// Handle request failure here...
            return;
        }
        // Request successful, read the response
        var resp = req.responseText;
        var details = JSON.parse(resp);

        /* Unfinished */
    }
    req.open("GET", "api-detail.php/transfer_detail/" + transferId, true);
    req.send();		
}	
