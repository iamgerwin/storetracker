<table cellpadding="0" cellspacing="0" border="0" class="table bordered-table table-hover" id="selloutInvoiceTable">
	<thead>
		<tr>
			<th>Date</th>
			<th>BranchCode</th>
			<th>Branch</th>
			<th>Invoice #</th>
			<th>GrossAmount</th>
			<th>TotalDiscount</th>
			<th>SalesAmount</th>
			<th>Status</th>
			<th>Remarks</th>
			@if(Session::get('Role') == 1)
			<th></th>
			@endif
		</tr>
	</thead>
	<tbody>
	@foreach($datas as $data)
		<tr>
			<?php	
				$invoiceRetailId = Gerwin::getUniqueInvoice($data->branch_id,$data->invoice_number);
				$itD = Compute::itemData($invoiceRetailId);
				$inD = Compute::invoiceData($itD->subTotal,$itD->itemDiscount);
				$bc = Branch::find($data->branch_id);
			?>
			<td>{{$data->invoice_date}}</td>
			<td>{{$bc->branch_code}}</td>
			<td>{{$data->branch_name}}</td>
			<td>{{$data->invoice_number}}</td>
			<td>{{number_format($inD->totalPrice,2)}}</td>
			<td>{{number_format($inD->totalDiscount,2)}}</td>
			<td>{{number_format($inD->netSales,2)}}</td>
			<td>{{$data->invoice_status}}</td>
			<td>{{$data->remarks}}</td>
			@if(Session::get('Role') == 1)
			<td>
				<a href="{{URL::to('/')}}/invoice/{{$data->retail_invoice_id}}/edit" class='btn btn-medium btn-success'><i class="icon-edit"></i></a>
			</td>
			@endif
		</tr>
	@endforeach
	</tbody>
</table>



<script type="text/javascript">
$(document).ready(function() {
    $('#selloutInvoiceTable').dataTable( {
    	"aaSorting": [[ 0, "desc" ]]
    } );
} );
</script>