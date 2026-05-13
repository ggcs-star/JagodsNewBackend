@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('settings/language/add') }}
        </div>
    </div>
</div>
@endsection

@section('admin.setting.layout')
<div class="row">
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('language.language') }}</h3>
            </div>
            <div class="db-card-body">
                <form action="{{ route('admin.language.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-col-12 sm:form-col-6 md:form-col-4">
                            <label class="db-field-title required">{{ __('language.language_name') }}</label>
                            <input type="text" name="name" class="db-field-control @error('name') invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6 md:form-col-4">
                            <label class="db-field-title required">{{ __('language.language_code') }}</label>
                            <input type="text" name="code" class="db-field-control @error('code') invalid @enderror" value="{{ old('code') }}">
                            @error('code')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6 md:form-col-4">
                            <label class="db-field-title required">{{ __('levels.status') }}</label>
                            <div class="db-field-down-arrow">
                            <select name="status" class="db-field-control appearance-none @error('status') invalid @enderror">
                                <option>---</option>
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
