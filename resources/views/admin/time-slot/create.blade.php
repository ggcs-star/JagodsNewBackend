@extends('admin.app')

@section('content')

	<div class="row">
        <div class="col-12">
			<div class="custome-breadcrumb">
            {{ Breadcrumbs::render('time-slots/add') }}
			</div>
        </div>

		<div class="col-12">
			<div class="db-card">
				<div class="db-card-header">
					<h3 class="db-card-title">{{ __('time_slot.time_slot') }}</h3>
				</div>
				<div class="db-card-body">
					<form action="{{ route('admin.time-slots.store') }}" method="POST">
						@csrf
						<div class="row">

							@if(auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
							<div class="form-col-12 sm:form-col-6 md:form-col-4 xl:form-col-3">
								<label class="db-field-title required" for="restaurant_id">{{ __('levels.restaurant') }}</label>
								<div class="db-field-down-arrow">
									<select name="restaurant_id" id="restaurant_id" class="db-field-control select2 appearance-none @error('restaurant_id') invalid @enderror">
										<option value="">---</option>
										@if(!blank($restaurants))
										@foreach($restaurants as $restaurant)
											<option value="{{ $restaurant->id }}" {{ (old('restaurant_id') == $restaurant->id) ? 'selected' : '' }}>{{ $restaurant->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
	
								@error('restaurant_id')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>
							@else
								<input type="hidden" name="restaurant_id" value="{{auth()->user()->restaurant->id ?? 0}}">
							@endif

							<div class="form-col-12 sm:form-col-6 md:form-col-4">
								<label class="db-field-title required" for="start_time">{{ __('levels.start_time') }}</label>
								<input type="time" name="start_time" id="start_time" class="db-field-control @error('start_time') invalid @enderror" value="{{ old('start_time') }}">
	
								@error('start_time')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>

							<div class="form-col-12 sm:form-col-6 md:form-col-4">
								<label class="db-field-title required" for="end_time">{{ __('levels.end_time') }}</label>
								<input type="time" name="end_time" id="end_time" class="db-field-control @error('end_time') invalid @enderror" value="{{ old('end_time') }}">
	
								@error('end_time')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>
							
							<div class="form-col-12 sm:form-col-6 md:form-col-4">
								<label class="db-field-title required">{{ __('levels.status') }}</label>
								<div class="db-field-down-arrow">
									<select name="status" class="db-field-control appearance-none @error('status') invalid @enderror">
										<option value="">---</option>
										@foreach(trans('statuses') as $key => $status)
											<option value="{{ $key }}" {{ (old('status') == $key) ? 'selected' : '' }}>{{ $status }}</option>
										@endforeach
									</select>
								</div>
	
								@error('status')
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


@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('backend/lib/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('js/time-slot/create.js') }}"></script>
@endpush
