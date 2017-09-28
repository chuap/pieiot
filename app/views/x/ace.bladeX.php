@if(Session::get('cat.position_s')!='ลจ.')
@include('edoc.ace_inc')
@else
@include('edoc.ace_inc_os')
@endif
