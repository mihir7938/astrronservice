@if(Session::get('message')!='')
<div class="alert {{ Session::get('alert-type') }} alert-dismissible" >
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<span>{!! Session::get('message') !!}</span></div>
<?php Session::put('message','');
Session::put('alert-type',''); ?>
@endif