@extends('admin.app')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.min.css" integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')

<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('coupons/edit') }}
		</div>
	</div>


	<div class="col-12">
		<div class="db-card">
			<div class="db-card-header">
				<h3 class="db-card-title">{{ __('levels.coupon') }}</h3>
			</div>
			<div class="db-card-body">
				<form action="{{ route('admin.coupon.update', $coupon->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
					@csrf
					@method('PUT')
					<div class="row">
						@if(auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title">{{ __('levels.restaurants') }}</label>
							<select name="restaurant_id" id="area" class="db-field-control select2 appearance-none @error('restaurant_id') invalid @enderror">
								<option value="0">{{ __('levels.select_restaurant') }}</option>
								@if(!blank($restaurants))
								@foreach($restaurants as $restaurant)
								<option value="{{ $restaurant->id }}" {{ (old('restaurant_id',$coupon->restaurant_id) == $restaurant->id) ? 'selected' : '' }}>{{ $restaurant->name }}</option>
								@endforeach
								@endif
							</select>

							@error('restaurant_id')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>
						@else
							<input type="hidden" name="restaurant_id" value="{{auth()->user()->restaurant->id }}">
						@endif

						@if(auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.name') }}</label>
							<input type="text" name="name" class="db-field-control @error('name') invalid @enderror" value="{{ old('name', $coupon->name) }}">

							@error('name')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>
						@else
						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.name') }}</label>
							<input type="text" name="name" class="db-field-control @error('name') invalid @enderror" value="{{ old('name', $coupon->name) }}">

							@error('name')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>
						@endif

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.discount_type') }}</label>
							<div class="db-field-down-arrow">
							<select name="discount_type" class="db-field-control select2 appearance-none @error('discount_type') invalid @enderror">
								<option value="0">{{ __('levels.select_coupon_type') }}</option>
								@foreach(trans('discount_types') as $key => $discount_type)
								<option value="{{ $key }}" {{ (old('discount_type',$coupon->discount_type) == $key) ? 'selected' : '' }}>{{ $discount_type}}</option>
								@endforeach
							</select>
							</div>
							@error('discount_type')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.amount') }}</label>
							<input type="text" name="amount" class="db-field-control @error('amount') invalid @enderror" value="{{ old('amount', $coupon->amount) }}">

							@error('amount')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title">{{ __('levels.minimum_order_amount') }}</label>
							<input type="text" name="minimum_order_amount" class="db-field-control @error('minimum_order_amount') invalid @enderror" value="{{ old('minimum_order_amount',$coupon->minimum_order_amount) }}">

							@error('minimum_order_amount')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.limit') }}</label>
							<input type="number" name="limit" class="db-field-control @error('limit') invalid @enderror" value="{{ old('limit', $coupon->limit) }}">

							@error('limit')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.per_user_limit') }}</label>

							<input type="number" name="user_limit" class="db-field-control custom-file-input @error('user_limit') invalid @enderror" value="{{ old('user_limit', $coupon->user_limit) }}">

							@error('user_limit')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.starts_at') }}</label>
							<input type="datetime-local" name="from_date" class="db-field-control datepicker @error('from_date') invalid @enderror" value="{{ old('from_date', $coupon->from_date) }}">

							@error('from_date')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.ends_at') }}</label>
							<input type="datetime-local" name="to_date" class="db-field-control datepicker @error('to_date') invalid @enderror" value="{{ old('to_date', $coupon->to_date) }}">

							@error('to_date')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.full.min.js" integrity="sha512-hDFt+089A+EmzZS6n/urree+gmentY36d9flHQ5ChfiRjEJJKFSsl1HqyEOS5qz7jjbMZ0JU4u/x1qe211534g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/coupon/edit.js') }}"></script>
@endsection
