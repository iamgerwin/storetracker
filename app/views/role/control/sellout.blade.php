@if(Session::get('Role') == 1)
	<a href="{{URL::to('/')}}/invoice/create" class="btn btn-medium btn-success"><i class="icon-plus"></i></a>	
@endif