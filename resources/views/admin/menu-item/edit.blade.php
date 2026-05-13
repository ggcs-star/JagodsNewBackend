@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('menu-items/edit') }}
            </div>
        </div>

        <div class="col-12">
			<div class="db-card">
				<div class="db-card-header">
					<h3 class="db-card-title">{{ __('restaurant.menu_item') }}</h3>
				</div>
				<div class="db-card-body">
					<form action="{{ route('admin.menu-items.update', $menuItem) }}" method="POST" enctype="multipart/form-data">
						@csrf
                        @method('PUT')
						<div class="row">
                            @if(auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
								<label class="db-field-title required" for="area">{{ __('levels.restaurant') }}</label>
								<div class="db-field-down-arrow">
									<select name="restaurant_id" id="area" class="db-field-control !appearance-none select2 custom-select2 @error('restaurant_id') invalid @enderror">
										<option value="">---</option>
										@if(!blank($restaurants))
                                        @foreach($restaurants as $restaurant)
											<option value="{{ $restaurant->id }}" {{ (old('restaurant_id', $menuItem->restaurant_id) == $restaurant->id) ? 'selected' : '' }}>{{ $restaurant->name }}</option>
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

							<div class="col-12 sm:col-6 md:col-4 xl:col-3">
								<label class="db-field-title required" for="name">{{ __('levels.name') }}</label>
								<input type="text" name="name" id="name" class="db-field-control @error('name') invalid @enderror" value="{{ old('name', $menuItem->name) }}">
	
								@error('name')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>

                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
								<label class="db-field-title" for="categories">{{ __('restaurant.categories') }}</label>
								<div class="db-field-down-arrow">
									<select name="categories[]" id="categories" class="db-field-control appearance-none select2 custom-select2 @error('categories') invalid @enderror" multiple="multiple">
										@if(!blank($categories))
                                        @foreach($categories as $category)
                                            @if(in_array($category->id, $menuItem_categories))
                                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                            @else
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
										@endforeach
                                        @endif
									</select>
								</div>
	
								@error('categories')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>

                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
								<label class="db-field-title required" for="unit_price">{{ __('levels.unit_price') }}</label>
								<input type="text" name="unit_price" id="unit_price" class="db-field-control @error('unit_price') invalid @enderror" value="{{ old('unit_price', $menuItem->unit_price) }}">
	
								@error('unit_price')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>

                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
								<label class="db-field-title" for="discount_price">{{ __('levels.discount_price') }}</label>
								<input type="text" name="discount_price" id="discount_price" class="db-field-control @error('discount_price') invalid @enderror" value="{{ old('discount_price', $menuItem->discount_price) }}">
	
								@error('discount_price')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>
							
							<div class="col-12 sm:col-6 md:col-4 xl:col-3">
								<label class="db-field-title required">{{ __('levels.status') }}</label>
								<div class="db-field-down-arrow">
									<select name="status" class="db-field-control appearance-none @error('status') invalid @enderror">
										<option value="">---</option>
										@foreach(trans('statuses') as $key => $status)
											<option value="{{ $key }}" {{ (old('status', $menuItem->status) == $key) ? 'selected' : '' }}>{{ $status }}</option>
										@endforeach
									</select>
								</div>
	
								@error('status')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>

							<div class="col-12 sm:col-6 md:col-4 xl:col-3">
								<label class="db-field-title" for="customFile">{{ __('levels.image') }}</label>
	
								<input type="file" name="image" id="customFile" class="db-field-control @error('image') invalid @enderror">
	
								@if ($errors->has('image'))
								<small class="db-field-alert">{{ $errors->first('image') }}</small>
								@endif
							</div>

                            <div class="form-col-12">
								<label class="db-field-title required" for="description">{{ __('levels.description') }}</label>
								<textarea name="description"
									class="db-field-control @error('description') invalid @enderror"
									id="editor" cols="30" rows="10">{{ old('description', $menuItem->description) }}</textarea>
								@error('description')
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
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script src="{{ asset('backend/lib/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/menu-item/edit.js') }}"></script>
@endpush
