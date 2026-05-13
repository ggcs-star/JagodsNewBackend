@extends('admin.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
        {{ Breadcrumbs::render('file-import-export') }}
    </div>
    </div>


    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('restaurant.import_restaurant') }}</h3>
                <div class="db-card-filter">
                    @can('restaurants_create')
                    <a href="{{ route('admin.restaurants.create') }}" class="db-btn h-[38px] text-white bg-primary">
                        <i class="fa-solid fa-circle-plus"></i>
                        <span>{{ __('restaurant.add_restaurant') }}</span>
                    </a>
                    @endcan
                </div>
            </div>
            <div class="db-card-body">
                <div class="row">
                    <div class="col-8 md:col-8 sm:col-8" style="padding: 25px;">
                        <form action="{{ route('admin.file-import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
        
                                <div class="form-col-12 sm:form-col-6 md:form-col-6">
        
                                    <input type="file" name="file" id="customFile" class="db-field-control @error('file') invalid @enderror">
        
                                    @error ('file')
                                    <small class="db-field-alert">{{ $message }}</small>
                                    @enderror
                                </div>
        
                                <div class="col-12 sm:form-col-12 md:form-col-12 mt-2 px-4">
                                    <div class="row">
        
                                        <button type="submit" class="db-btn text-white bg-primary" style="margin-inline-end: 16px;">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>{{ __('restaurant.import') }}</span>
                                        </button>
                                        
                                        <a href="{{asset('backend/sample/restaurantImportSample.xlsx')}}" download class="db-card-filter-btn pseudo-none">
                                            <i class="fa-solid fa-file-import"></i>
                                            <span>{{ __('restaurant.sample') }}</span>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                    
                        </form>
                    </div>

                    <div class="col-4 md:col-4 sm:col-4">
                        @if(session()->has('importErrors'))
                        <h2 class="border-bottom">{{__('restaurant.validation_log')}}</h2>
                        @foreach(session()->get('importErrors') as $key => $values)
                        <div class="text-primary ">{{__('restaurant.in_row_number')}} : {{ $key }}</div>
                        @foreach($values as $value)
                        <div class="text-danger ">{{ $value }}</div>
                        @endforeach
                        @endforeach
    
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection