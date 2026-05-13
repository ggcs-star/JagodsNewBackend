@extends('admin.app')

@section('content')

	<div class="row">
        <div class="col-12">
			<div class="custome-breadcrumb">
            {{ Breadcrumbs::render('tables/add') }}
			</div>
        </div>

		<div class="col-12">
			<div class="db-card">
				<div class="db-card-header">
					<h3 class="db-card-title">{{ __('table.add_tables') }}</h3>
				</div>
				<div class="db-card-body">
					<form action="{{ route('admin.tables.store') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row">
							@if(auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
							<div class="form-col-12 sm:form-col-6 md:form-col-4">
								<label class="db-field-title required" for="restaurant_id">{{ __('levels.restaurant') }}</label>
								<div class="db-field-down-arrow">
								<select name="restaurant_id" id="restaurant_id" class="db-field-control select2 appearance-none @error('restaurant_id') invalid @enderror" value="{{ old('restaurant_id') }}">
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
								<label class="db-field-title required" for="name">{{ __('levels.name') }}</label>
								<input type="text" name="name" id="name" class="db-field-control @error('name') invalid @enderror" value="{{ old('name') }}">
	
								@error('name')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>
							
							<div class="form-col-12 sm:form-col-6 md:form-col-4">
								<label class="db-field-title required" for="capacity">{{ __('levels.capacity') }}</label>
								<input type="number" step=".01" name="capacity" id="capacity" class="db-field-control @error('capacity') invalid @enderror" value="{{ old('capacity') }}">
	
								@error('capacity')
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
	<script>
		$(document).ready(function() {
			$('.select2').select2();
		});
	</script>
@endpush
