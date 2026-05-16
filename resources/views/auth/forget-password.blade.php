<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Astrron Syystems | Forgot Password</title>
	<link rel="icon" href="{{asset('img/favicon.ico')}}" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="{{asset('adminlte/css/all.min.css')}}">
	<link rel="stylesheet" href="{{asset('adminlte/css/adminlte.min.css')}}">
	<link rel="stylesheet" href="{{asset('adminlte/css/custom.css')}}">
</head>
<body class="hold-transition login-page">
	<div class="login-box">
	  	<div class="card card-outline card-primary">
		    <div class="card-header text-center">
		      	<a href="{{route('login')}}" class="h1"><img src="{{asset('img/logo.png')}}" alt="Logo"></a>
		    </div>
	    	<div class="card-body">
	      		<p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
	      		<form id="forget-password-form" name="forget-password-form" action="{{route('check_password_reset')}}" method="POST">
	      			@csrf
					@include('shared.alert')
					@if (count($errors) > 0)
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif
	        		<div class="input-group mb-3">
	          			<input type="email" id="email" name="email" placeholder="Email" class="form-control">
	          			<div class="input-group-append">
	            			<div class="input-group-text">
	              				<span class="fas fa-envelope"></span>
	            			</div>
	          			</div>
	        		</div>
	        		<div class="row">
	          			<div class="col-12">
	            			<button type="submit" name="submitBtn" class="btn btn-primary btn-block">Request new password</button>
	          			</div>
        			</div>
	      		</form>
	      		<p class="mt-3 mb-1">
	        		<a href="{{route('login')}}">Login</a>
	      		</p>
	    	</div>
	  	</div>
	</div>
	<script src="{{asset('adminlte/js/jquery.min.js')}}"></script>
	<script src="{{asset('adminlte/js/bootstrap.bundle.min.js')}}"></script>
	<script src="{{asset('adminlte/js/adminlte.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js"></script>
	<script src="{{asset('js/validation-additional.js')}}"></script>
	<script>
		(function() {
			$('#forget-password-form').validate({
				rules: {
					email: {
						required: true,
						alphanumeric: true
					},
				},
				messages:{
				 	email:{
				 		required: "Please enter your email.",
				 		email: "Please provide a valid email."
				 	}
				},
				errorPlacement: function(error, element) {
					$(element.parent()).find("div.input-group-append").after(error);
	            },
				submitHandler: function (form) {
					form.submit();
				},
			});
		})();
	</script>
</body>
</html>