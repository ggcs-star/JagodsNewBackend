@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('profile/edit') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('levels.profile') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.profile.update', $user) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="first_name">{{ __('levels.first_name') }}</label>
                                <input id="first_name" type="text" name="first_name" class="db-field-control @error('first_name') invalid @enderror"
                                    value="{{ old('first_name', $user->first_name) }}">
                                    @error('first_name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                    @enderror
                            </div>


                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="last_name">{{ __('levels.last_name') }}</label>
                                <input id="last_name" type="text" name="last_name" class="db-field-control @error('first_name') invalid @enderror"
                                    value="{{ old('last_name', $user->last_name) }}">
                                    @error('last_name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                    @enderror
                            </div>

                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title" for="username">{{ __('levels.username') }}</label>
                                <input type="text" id="username" name="username" class="db-field-control @error('username') invalid @enderror" value="{{ old('username', $user->username) }}" autocomplete="off">
                                @error('username')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="email">{{ __('levels.email') }}</label>
                                <input type="text" name="email" id="email" class="db-field-control @error('email') invalid @enderror" value="{{ old('email', $user->email) }}">
                                @error('email')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="phone">{{ __('levels.phone') }}</label>
                                <input type="text" name="phone" id="phone" class="db-field-control @error('phone') invalid @enderror" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>

                           

                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title" for="address">{{ __('levels.address') }}</label>
                                <input type="text" id="address" name="address" class="db-field-control "
                                    value="{{ old('address', $user->address) }}">
                                    @error('address')
                                        <small class="db-field-alert">{{ $message }}</small>
                                    @enderror
                            </div>

                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title" for="customFile">{{ __('levels.image') }}</label>
                                <input name="image" type="file" class="db-field-control @error('image') invalid @enderror" id="customFile">
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
