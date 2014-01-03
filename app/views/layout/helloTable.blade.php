		<table id="helloTable" class="table table-hover centered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Logs</th>
					<th>DateTime</th>
				</tr>
			</thead>

			<tbody>	
				@foreach($datas as $data)
				<tr>
					<td>{{$data->message_id}}</td>
					<td>{{$data->message_text}}</td>
					<td>{{$data->date_sent}}</td>
				</tr>
				@endforeach
			</tbody>

		</table>
