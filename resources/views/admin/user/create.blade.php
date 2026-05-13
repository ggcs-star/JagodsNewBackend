@extends('admin.app')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('user/add') }}
		</div>
	</div>


	<div class="col-12">
		<div class="db-card">
			<div class="db-card-header">
				<h3 class="db-card-title">{{ __('users.user') }}</h3>
			</div>
			<div class="db-card-body">
				<form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.first_name') }}</label>
							<input type="text" name="first_name" class="db-field-control @error('first_name') invalid @enderror" value="{{ old('first_name') }}">

							@error('first_name')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.last_name') }}</label>
							<input type="text" name="last_name" class="db-field-control @error('last_name') invalid @enderror" value="{{ old('last_name') }}">

							@error('last_name')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.email') }}</label>
							<input type="email" name="email" class="db-field-control @error('email') invalid @enderror" value="{{ old('email') }}">

							@error('email')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.phone') }}</label>
							<input type="text" name="phone" class="db-field-control @error('phone') invalid @enderror" value="{{ old('phone') }}" onkeypress='validate(event)'>

							@error('phone')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title">{{ __('levels.username') }}</label>
							<input type="text" name="username" class="db-field-control @error('username') invalid @enderror" value="{{ old('username') }}">

							@error('username')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.password') }}</label>
							<input type="password" name="password" class="db-field-control @error('password') invalid @enderror" value="{{ old('password') }}">

							@error('password')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.role') }}</label>

							<div class="db-field-down-arrow">
								<select name="role" class="db-field-control appearance-none @error('role') invalid @enderror">
									<option>---</option>
									@if(!blank($roles))
									@foreach($roles as $key => $role)
										<option value="{{ $role->id }}" {{ (old('role') == $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
									@endforeach
									@endif
								</select>
							</div>

							@error('role')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.status') }}</label>

							<div class="db-field-down-arrow">
								<select name="status" class="db-field-control appearance-none @error('status') invalid @enderror">
									<option>---</option>
									@foreach(trans('user_statuses') as $key => $status)
										<option value="{{ $key }}" {{ (old('status') == $key) ? 'selected' : '' }}>{{ $status }}</option>
									@endforeach
								</select>
							</div>

							@error('status')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title" for="address">{{ __('levels.address') }}</label>
							<input type="text" name="address" id="address" class="db-field-control @error('address') invalid @enderror" value="{{ old('address') }}">

							@error('address')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title" for="customFile">{{ __('levels.image') }}</label>

							<input type="file" name="image" id="customFile" class="db-field-control @error('image') invalid @enderror" onchange="readURL(this);">

							@if ($errors->has('image'))
							<small class="db-field-alert">{{ $errors->first('image') }}</small>
							@endif
						</div>

						<div class="col-12">
							<button type="submit" class="db-btn text-white bg-primary">
								<i class="fa-solid fa-circle-check"></i>
								<span>{{ __('levels.save') }}</span>
							</button>
						</div>


						
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
	
@endsection

@section('scripts')
	<script src="{{ asset('js/delivery-boy/create.js') }}"></script>
	<script src="{{ asset('js/phone_validation/index.js') }}"></script>

@endsection
