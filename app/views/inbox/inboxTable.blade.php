<table cellpadding="0" cellspacing="0" border="0" class="table bordered-table table-hover" id="inboxTable">
	<thead>
		<tr>
			<th>Date</th>
			<th>Sender</th>
			<th>Text</th>
		</tr>
	</thead>
	<tbody>
	@foreach($datas as $data)
		<tr>
			<td>{{$data->date_sent}}</td>
			<td>{{$data->message_sender}}</td>
			<td>{{$data->message_text}}</td>
		</tr>
	@endforeach
	</tbody>
</table>


<script type="text/javascript">
$(document).ready(function() {
    $('table#inboxTable').dataTable( {
    	"aaSorting": [[ 0, "desc" ]]
    } );
} );
</script>