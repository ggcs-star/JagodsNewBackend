@extends('admin.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/lib/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/lib/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/lib/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('restaurant/add') }}
        </div>
        </div>

        <div class="col-12">
            <form action="{{ route('admin.restaurant.restaurant-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="db-card">
                            <div class="db-card-header">
                                <h3 class="db-card-title">{{ __('restaurant.restaurant_information') }}</h3>
                            </div>
                            <div class="db-card-body">
                                <div class="row">
        
                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title required" for="name">{{ __('levels.name') }}</label>
                                        <input type="text" name="name" id="name" class="db-field-control @error('name') invalid @enderror" value="{{ old('name') }}">
            
                                        @error('name')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>
    
                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title" for="opening_time">{{ __('levels.opening_time') }}</label>
                                        <input type="time" name="opening_time" id="opening_time" class="db-field-control @error('opening_time') invalid @enderror" value="{{ old('opening_time') }}">
            
                                        @error('opening_time')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>
    
                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title" for="closing_time">{{ __('levels.closing_time') }}</label>
                                        <input type="time" name="closing_time" id="closing_time" class="db-field-control @error('closing_time') invalid @enderror" value="{{ old('closing_time') }}">
            
                                        @error('closing_time')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>
    
                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title" for="cuisines">{{ __('levels.cuisines') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="cuisines[]" id="cuisines" class="db-field-control select2 appearance-none @error('cuisines') invalid @enderror" multiple="multiple">
                                                <option value="">---</option>
                                                @if(!blank($cuisines))
                                                    @foreach($cuisines as $cuisine)
                                                        <option value="{{ $cuisine->id }}">{{ $cuisine->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
            
                                        @error('cuisines')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>
        
                                    <div class="form-col-12">
                                        <label class="db-field-title required" for="address">{{ __('levels.restaurant_address') }}</label>
                                        <input type="text" name="address"
                                            class="db-field-control @error('address') invalid @enderror"
                                            id="address" value="{{ old('address') }}"></input>
                                        @error('address')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>
        
                                    <div class="form-col-12">
                                        <label class="db-field-title" for="description">{{ __('levels.description') }}</label>
                                        <textarea name="description"
                                            class="db-field-control @error('description') invalid @enderror"
                                            style="height: 5rem"
                                            id="editor">{{ old('description') }}</textarea>
                                        @error('description')
                                            <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>
        
                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title" for="customFile">{{ __('restaurant.logo') }}</label>
            
                                        <input type="file" name="restaurant_logo" id="customFile" class="db-field-control @error('restaurant_logo') invalid @enderror">
            
                                        @if ($errors->has('restaurant_logo'))
                                        <small class="db-field-alert">{{ $errors->first('restaurant_logo') }}</small>
                                        @endif
                                    </div>
        
                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title" for="customFile">{{ __('restaurant.background_image') }}</label>
            
                                        <input type="file" name="image" id="customFile" class="db-field-control @error('image') invalid @enderror">
            
                                        @if ($errors->has('image'))
                                        <small class="db-field-alert">{{ $errors->first('image') }}</small>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        
                    </div>
                    <div class="col-6">
                        <div class="db-card">
                            <div class="db-card-header">
                                <h3 class="db-card-title">{{ __('restaurant.restaurant_location') }}</h3>
                            </div>
                            <div class="db-card-body">
                                <div class="row">
        
                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required" for="name">{{ __('levels.latitude') }}</label>
                                        <input type="text" name="lat" id="lat" class="db-field-control @error('lat') invalid @enderror" value="{{ old('lat') }}">
            
                                        @error('lat')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>
    
                                    <div class="form-col-6 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required" for="name">{{ __('levels.longitude') }}</label>
                                        <input type="text" name="long" id="long" class="db-field-control @error('long') invalid @enderror" value="{{ old('long') }}">
            
                                        @error('long')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>
    
                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <div id="googleMap"></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="db-card mt-5">
                            <div class="db-card-header">
                                <h3 class="db-card-title">{{ __('restaurant.restaurant_status') }}</h3>
                            </div>
                            <div class="db-card-body">
                                <div class="row">

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.delivery') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="delivery_status" class="db-field-control appearance-none @error('delivery_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach(trans('delivery_statuses') as $delivery_statusKey => $delivery_status)
                                                    <option value="{{ $delivery_statusKey }}"
                                                        {{ (old('delivery_status') == $delivery_statusKey) ? 'selected' : '' }}>
                                                        {{ $delivery_status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
            
                                        @error('delivery_status')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.pickup') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="pickup_status" class="db-field-control appearance-none @error('pickup_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach(trans('pickup_statuses') as $pickup_statusKey => $pickup_status)
                                                    <option value="{{ $pickup_statusKey }}"
                                                        {{ (old('pickup_status') == $pickup_statusKey) ? 'selected' : '' }}>
                                                        {{ $pickup_status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
            
                                        @error('pickup_status')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.table') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="table_status" class="db-field-control appearance-none @error('table_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach(trans('table_statuses') as $table_statusKey => $table_status)
                                                    <option value="{{ $table_statusKey }}"
                                                        {{ (old('table_status') == $table_statusKey) ? 'selected' : '' }}>
                                                        {{ $table_status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
            
                                        @error('table_status')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                                        <label class="db-field-title required">{{ __('levels.current_status') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="current_status" class="db-field-control appearance-none @error('current_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach(trans('current_statuses') as $current_statusKey => $current_status)
                                                    <option value="{{ $current_statusKey }}"
                                                        {{ (old('current_status') == $current_statusKey) ? 'selected' : '' }}>
                                                        {{ $current_status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
            
                                        @error('current_status')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                                        <label class="db-field-title required">{{ __('levels.waiter_status') }}</label>
                                        <div class="db-field-down-arrow">
                                            <select name="waiter_status" class="db-field-control appearance-none @error('waiter_status') invalid @enderror">
                                                <option value="">---</option>
                                                @foreach(trans('waiter_statuses') as $waiter_statusKey => $waiter_status)
                                                    <option value="{{ $waiter_statusKey }}"
                                                        {{ (old('waiter_status') == $waiter_statusKey) ? 'selected' : '' }}>{{ $waiter_status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
            
                                        @error('waiter_status')
                                        <small class="db-field-alert">{{ $message }}</small>
                                        @enderror
                                    </div>
            
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="db-btn text-white bg-primary">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>{{ __('levels.save') }}</span>
                                        </button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>



            </form>
        </div>

        
    </div>

@endsection

@push('js')
    <script src="{{ asset('backend/lib/select2/dist/js/select2.full.min.js') }}"></script>
    <script async src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_map_api_key') }}&libraries=places&callback=initMap"></script>
    <script src="{{ asset('js/restaurant/create.js') }}"></script>
@endpush
