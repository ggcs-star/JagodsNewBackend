@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('banners/edit') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('banner.banners') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.banner.update', $banner) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="area">{{ __('levels.restaurant') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="restaurant_id" id="area"
                                        class="db-field-control appearance-none @error('restaurant_id') invalid @enderror">
                                        <option value="">{{ __('levels.select_restaurant') }}</option>
                                        @if (!blank($restaurants))
                                            @foreach ($restaurants as $restaurant)
                                                <option value="{{ $restaurant->id }}"
                                                    {{ old('restaurant_id', $banner->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                                    {{ $restaurant->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('restaurant_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('levels.title') }}</label>
                                <input type="text" name="name"
                                    class="db-field-control @error('name') invalid @enderror"
                                    value="{{ old('name', $banner->title) }}">
                                @error('name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('levels.url') }}</label>
                                <input type="text" name="url"
                                    class="db-field-control @error('url') invalid @enderror"
                                    value="{{ old('url', $banner->link) }}">
                                @error('url')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.status') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="status"
                                        class="db-field-control appearance-none @error('status') invalid @enderror">
                                        @foreach (trans('statuses') as $key => $status)
                                            <option value="{{ $key }}"
                                                {{ old('status', $banner->status) == $key ? 'selected' : '' }}>
                                                {{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('status')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('levels.description') }}</label>
                                <input type="text" name="description"
                                    class="db-field-control @error('description') invalid @enderror"
                                    value="{{ old('description', $banner->short_description) }}">
                                @error('description')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="customFile">{{ __('levels.image') }}</label>
                                <input name="image" type="file"
                                    class="db-field-control @error('image') invalid @enderror" id="customFile"
                                    onchange="readURL(this);">
                                @if ($errors->has('image'))
                                    <small class="db-field-alert">{{ $errors->first('image') }}</small>
                                @endif
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary" type="submit">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('levels.submit') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/banner/edit.js') }}"></script>
@endpush
