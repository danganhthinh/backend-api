@extends('auth.layout.default')
@section('content')
@section('pageTitle', 'Reset Password')

<div id="content-reset-password">
	<div class="inside-block">
		<div class="title reset-password">
			@if(session('error'))
				<div class="alert alert-danger text-center" style="margin: 10px">
					{{session('error')}}
				</div>
			@endif
			@if(session('success'))
				<div class="alert alert-success text-center" style="margin: 10px" >
					{{session('success')}}
				</div>
			@endif
			<div class="tile-header">
				<div class="x_title text-center">
                    <button class="back btn btn-primary">
                       <a href="/login" style="position: absolute;
                       margin-left: -18%;">Back</a>
                    </button>
					<h2>Reset Password </h2>
					<ul class="nav navbar-right panel_toolbox"></ul>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="text-center description-reset-password">
				<span>Please enter your email address and we'll send you instructions on how to reset your password</span>
			</div>
			<?= Form::open(['url' => 'resetPassword','method' =>'POST']) ?>
			<div class="form-group">
				<input name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
				<p class="help-block" style="color:red;">{!! $errors->first('email')!!}</p>
			</div>
			<div class="form-group">
				<div class="text-center">
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
			</div>
			<?= Form::close() ?>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

@endsection
