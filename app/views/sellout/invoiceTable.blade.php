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
	@for($i=0;$i<count($invoiceData);$i++)
		<tr>
			<td>{{$data[$i]->invoice_date}}</td>
			<td>{{$data[$i]->brancher->branch_code}}</td>
			<td>{{$data[$i]->brancher->branch_name}}</td>
			<td>{{$data[$i]->invoice_number}}</td>
			<td>{{number_format($invoiceData[$i]->totalPrice,2)}}</td>
			<td>{{number_format($invoiceData[$i]->totalDiscount,2)}}</td>
			<td>{{number_format($invoiceData[$i]->netSales,2)}}</td>
			<td>{{$data[$i]->invoice_status}}</td>
			<td>{{$data[$i]->remarks}}</td>
			@if(Session::get('Role') == 1)
			<td>
				<a href="{{URL::to('/')}}/invoice/{{$data[$i]->retail_invoice_id}}/edit" class='btn btn-medium btn-success'><i class="icon-edit"></i></a>
			</td>
			@endif
		</tr>
	@endfor
	
	</tbody>
</table>



<script type="text/javascript">
$(document).ready(function() {
    $('#selloutInvoiceTable').dataTable( {
    	"aaSorting": [[ 0, "desc" ]]
    } );
} );
</script>