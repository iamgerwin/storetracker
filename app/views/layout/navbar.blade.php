
<div id="mainnavbar" class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="{{URL::to('/')}}/hello"><img src="{{URL::to('/ger/storetracker-logo.png')}}" alt="StoreTracker" width="100px"  /></a>
    <ul class="nav">
      <li class="@yield('Mstate')"><a id="Nmain" ><i class="icon-home"></i>Main</a></li>
      <li class="@yield('Sstate')"><a id="Nsellout" ><i class="icon-tag"></i>Sellout</a></li>
      <li class="@yield('Istate')"><a id="Ninbox" ><i class="icon-inbox"></i>Inbox</a></li>
      <li class="@yield('Cstate')"><a id="Ncontacts" ><i class="icon-list-alt"></i>Contacts</a></li>
      <li class="@yield('Sestate')"><a id="Nsettings" ><i class="icon-wrench"></i>Settings</a></li>
      
      
    </ul>
    <div id='out'><button type="button" onclick="location.href='{{URL::to('/')}}/out'"; class = "btn btn-warning" ><i class="icon-fire"></i>Logout</button></div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

  $('#Nmain').on('click',function() {
    location = "{{URL::to('/')}}/hello"
  }); 
  $('#Nsellout').on('click',function() {
    location = "{{URL::to('/')}}/sellout"
  });
  
  $('#Ninbox').on('click',function() {
    location = "{{URL::to('/')}}/inbox"
  }); 
  $('#Ncontacts').on('click',function() {
    location = "{{URL::to('/')}}/contacts"
  }); 
  $('#Nsettings').on('click',function() {
    location = "{{URL::to('/')}}/settings"
  }); 
});
</script>