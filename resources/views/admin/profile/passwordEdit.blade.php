@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('password/edit') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('user.change_password') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.profile.change') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row">

                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="old_password">{{ __('user.old_password') }}</label>
                                <input type="password" id="old_password" name="old_password" class="db-field-control @error('old_password') invalid @enderror"
                                    value="{{ old('old_password') }}">
                                @error('old_password')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror

                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="password">{{ __('levels.password') }}</label>
                                <input type="password" id="password" name="password" class="db-field-control @error('password') invalid @enderror" value="{{ old('password') }}">
                                @error('password')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="password_confirmation">{{ __('user.password_confirmation') }}</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="db-field-control @error('password_confirmation') invalid @enderror"
                                    value="{{ old('password_confirmation') }}">
                                @error('password_confirmation')
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
