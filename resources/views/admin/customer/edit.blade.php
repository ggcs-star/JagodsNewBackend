@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('customers/edit') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('user.customers') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.customers.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.first_name') }}</label>
                                <input type="text" name="first_name"
                                    class="db-field-control @error('first_name') invalid @enderror"
                                    value="{{ old('first_name', $user->first_name) }}">
                                @error('first_name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.last_name') }}</label>
                                <input type="text" name="last_name"
                                    class="db-field-control @error('last_name') invalid @enderror"
                                    value="{{ old('last_name', $user->last_name) }}">
                                @error('last_name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.email') }}</label>
                                <input type="text" name="email"
                                    class="db-field-control @error('email') invalid @enderror"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('levels.phone') }}</label>
                                <input type="text" name="phone"
                                    class="db-field-control @error('phone') invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" onkeypress='validate(event)'>
                                @error('phone')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('levels.username') }}</label>
                                <input type="text" name="username"
                                    class="db-field-control @error('username') invalid @enderror"
                                    value="{{ old('username', $user->username) }}">
                                @error('username')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('levels.password') }}</label>
                                <input type="text" name="password"
                                    class="db-field-control @error('password') invalid @enderror"
                                    value="{{ old('password') }}">
                                @error('password')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title" for="customFile">{{ __('levels.image') }}</label>
                                <input name="image" type="file"
                                    class="db-field-control @error('image') invalid @enderror" id="customFile"
                                    onchange="readURL(this);">
                                @if ($errors->has('image'))
                                    <small class="db-field-alert">{{ $errors->first('image') }}</small>
                                @endif
                                @if ($user->getFirstMediaUrl('user'))
                                    <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage"
                                        src="{{ asset($user->getFirstMediaUrl('user')) }}" alt="your image" />
                                @else
                                    <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage"
                                        src="{{ asset('backend/images/default/user.png') }}" alt="your image" />
                                @endif
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('levels.address') }}</label>

                                <input type="text" name="address"
                                    class="db-field-control @error('address') invalid @enderror"
                                    value="{{ old('address', $user->address) }}">
                                @error('address')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.status') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="status"
                                        class="db-field-control appearance-none @error('status') invalid @enderror">
                                        @foreach (trans('user_statuses') as $key => $status)
                                            <option value="{{ $key }}"
                                                {{ old('status', $user->status) == $key ? 'selected' : '' }}>
                                                {{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('status')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary">
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
    <script src="{{ asset('js/customer/edit.js') }}"></script>
    <script src="{{ asset('js/phone_validation/index.js') }}"></script>
@endpush
