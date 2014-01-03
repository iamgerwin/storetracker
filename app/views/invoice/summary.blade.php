<div class='span6'>
Branch Name: <strong>{{$branch->branch_name}}</strong> <br />
Contact Name: <strong>{{$contact->last_name}}, {{$contact->first_name}}</strong> <br />
 <br />
</div>
<?php 

?>

<input type="hidden" id="branchID" value="{{$branch->branch_id}}" readonly>
<input type="hidden" id="contactID" value="{{$contact->contact_id}}" readonly>

<input type="hidden" id="invoiceDate" value="{{$dateInput}}" readonly>
<input type="hidden" id="invoiceNumber" value="{{$invoiceNumber}}" readonly>

<div class='span6'>
Invoice Date: <strong>{{$dateInput}}</strong> <br/>
Invoice Number: <h4>{{$invoiceNumber}}</h4>

</div>

<i><!-- <i class="icon-list"></i> -->List of Item/s</i>
<table class="table table-condensed">
<?php $i = count($items); $g = 0; $GROSSITEMDISCOUNT=0;   $GROSSAMOUNT=0; ?>
<tr>
  <td>Item Name</td>
  <td>Price</td>
  <td>Quantity</td>
  <td>Member Discount</td>
  <td>Discount Type</td>
  <td>Item Discount</td>
  <td>Item Price</td>
</tr>
@while($g < $i)
<tr>
  <td>{{$items[$g]->item_name}}</td>
  <input type="hidden" name="itemIDs" value="{{$items[$g]->item_id}}" readonly >
  <td>{{number_format($items[$g]->selling_price,2)}}</td>
    <input type="hidden" name="quantities" value="{{$itemQuantities[$g]}}" readonly >
  <td>{{$itemQuantities[$g]}}</td>
  <td>{{$itemMemberDiscounts[$g]}}</td>
  <input type="hidden" name="memberdiscounts" value="{{$itemMemberDiscounts[$g]}}" readonly >
   <input type="hidden" name="discountypes" value="{{$itemDiscounttype[$g]}}" readonly >
  <td>
      @if ($itemDiscounttype[$g] != 1)
            Percentage
      @else
            Fixed
      @endif
  </td>
  <input type="hidden" name="discountitems" value="{{$itemDiscounts[$g]}}" readonly >
  <td>{{$itemDiscounts[$g]}}</td>
  <?php 
  $itemGross[$g] = $items[$g]->selling_price * $itemQuantities[$g];

  if($itemDiscounttype[$g] == 0) {
      $itemDiscount[$g] = $itemGross[$g] * ($itemDiscounts[$g] / 100);
      $itemPrice[$g] = $itemGross[$g] - $itemDiscount[$g] ; 
  } else {
      $itemDiscount[$g] = $itemDiscounts[$g];
      $itemPrice[$g] = $itemGross[$g] - $itemDiscount[$g] ; 
  }

  $GROSSITEMDISCOUNT +=  $itemDiscount[$g];
  $GROSSAMOUNT += $itemGross[$g];
  ?>
  <td>{{number_format($itemPrice[$g],2) }}<td>
</tr>
<?php $g++;?>
@endwhile

<tr>
  <td></td>
  <td></td>
  <td></td>
  <td><h6>Gross Discount:</h6></td>
  <td><h5>{{number_format($computeData->totalDiscount,2)}}</h5></td>
  <td></td>
  <td></td>
</tr>


<tr>
  <td></td>
  <td></td>
  <td></td>
  <td><h6>Gross Amount:</h6></td>
  <td><h5>{{number_format($computeData->totalPrice,2)}}</h5></td>
  <td></td>
  <td></td>
</tr>


<tr>
  <td></td>
  <td></td>
  <td></td>
  <td><h6>Invoice Amount:</h6></td>
  <td><h4>{{number_format($computeData->grossSales,2)}}</h4></td>
  <td></td>
  <td></td>
</tr>

<tr>
  <td></td>
  <td></td>
  <td></td>
  <td><h6>Output Tax:</h6></td>
  <td><h5>{{number_format($computeData->outputTax,2)}}</h5></td>
  <td></td>
  <td></td>
</tr>

<tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td><h5>Net Sales:</h5</td>
  <td><h4>{{number_format($computeData->netSales,2)}}</h4></td>
  <td></td>
</tr>

</table>

  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true">Back</button>
    <button id="addInvoiceButton" class="btn btn-primary">Add Invoice</button>
  </div>  


<script type="text/javascript">

$(document).ready( function () {

      $( "button#addInvoiceButton" ).click(function() {
                
                    var itemIDs = new Array();
                      $("input[name='itemIDs']").each(function() {
                         itemIDs.push($(this).val());
                      });
                    var itemQTYs = new Array();
                      $("input[name='quantities']").each(function() {
                         itemQTYs.push($(this).val());
                      });
                      var itemMDs = new Array();
                      $("input[name='memberdiscounts']").each(function() {
                         itemMDs.push($(this).val());
                      });
                    var itemDTs = new Array();
                      $("input[name='discountypes']").each(function() {
                         itemDTs.push($(this).val());
                      });
                    var itemDIs = new Array();
                      $("input[name='discountitems']").each(function() {
                         itemDIs.push($(this).val());
                      });

                var Create = {};
                    Create.branchID = $('#branchID').val();
                    Create.contactID = $('#contactID').val();
                    Create.memberDiscount = $('#memberDiscount').val();
                    Create.invoiceDate = $('#invoiceDate').val();
                    Create.invoiceNumber = $('#invoiceNumber').val();
                    Create.memberDiscount = $('#memberDiscount').val();
                                        Create.itemIDs =  itemIDs;
                                        Create.itemQTYs =  itemQTYs;
                                        Create.itemMDs = itemMDs;
                                        Create.itemDTs =  itemDTs;
                                        Create.itemDIs =  itemDIs;

                  $.ajax({
                    type: 'POST',
                        url: '{{URL::route('invoice.store')}}',
                        cache: false,
                    data: Create,
                    beforeSend: function (tableload) {
                        $("div#msgAddinvoice").html('<div id="loadTable"><img src="{{URL::to('/')}}/packages/main/images/preloader-w8-cycle-black.gif" /></div>');
                     }
                    })
                    .done(function( html ) {    
                        $("div#msgAddinvoice").html(html);
                    });
      });
});
</script>