@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('reservations/edit') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('levels.reservation') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.reservation.update', $reservation) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            @if (auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
                                <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                    <label class="db-field-title required"
                                        for="restaurant_id">{{ __('levels.restaurant') }}</label>
                                    <div class="db-field-down-arrow">

                                        <select name="restaurant_id" id="restaurant_id"
                                            class="db-field-control appearance-none @error('restaurant_id') invalid red-border @enderror">
                                            <option value="">{{ __('levels.restaurant') }}</option>
                                            @if (!blank($restaurants))
                                                @foreach ($restaurants as $restaurant)
                                                    <option value="{{ $restaurant->id }}"
                                                        {{ old('restaurant_id', $reservation->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
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
                            @else
                                <input type="hidden" id="restaurant_id" name="restaurant_id"
                                    value="{{ auth()->user()->restaurant->id ?? 0 }}">
                            @endif
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required" for="user_id">{{ __('levels.customer') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="user_id" id="user_id"
                                        class="db-field-control appearance-none @error('user_id') invalid @enderror">
                                        <option value="">{{ __('levels.select_customer') }}</option>
                                        @if (!blank($users))
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id', $reservation->user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('user_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.first_name') }}</label>
                                <input type="text" id="first_name" name="first_name"
                                    class="db-field-control @error('first_name') invalid @enderror"
                                    value="{{ old('first_name', $reservation->first_name) }}">
                                @error('first_name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.last_name') }}</label>
                                <input type="text" id="last_name" name="last_name"
                                    class="db-field-control @error('last_name') invalid @enderror"
                                    value="{{ old('last_name', $reservation->last_name) }}">
                                @error('last_name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.email') }}</label>
                                <input type="text" id="email" name="email"
                                    class="db-field-control @error('email') invalid @enderror"
                                    value="{{ old('email', $reservation->email) }}">
                                @error('email')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.phone') }}</label>
                                <input type="text" id="phone" name="phone"
                                    class="db-field-control @error('phone') invalid @enderror"
                                    value="{{ old('phone', $reservation->phone) }}" onkeypress='validate(event)'>
                                @error('phone')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.date') }}</label>
                                <input type="date" autocomplete="off" id="reservation_date" name="reservation_date"
                                    class="db-field-control @error('reservation_date') invalid @enderror"
                                    value="{{ $reservation->reservation_date }}">
                                @error('reservation_date')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('levels.guest') }}</label>
                                <input type="number" step=".01" name="guest" id="guest"
                                    class="db-field-control @error('guest') invalid @enderror"
                                    value="{{ old('guest', $reservation->guest_number) }}">
                                @error('guest')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6 md:form-col-4" id="timeSlotList">
                                <label class="db-field-title required" for="timeSlot">{{ __('levels.timeSlot') }}</label>
                                <div class="db-field-down-arrow">
                                    <select id="timeSlot" name="time_slot"
                                        class="db-field-control appearance-none {{ $errors->has('timeSlot') ? ' is-invalid ' : '' }}">
                                        @if (!blank($timeSlots))
                                            <option value="{{ $timeSlots->id }}" selected>
                                                {{ date('h:i A', strtotime($timeSlots->start_time)) }} -
                                                {{ date('h:i A', strtotime($timeSlots->end_time)) }}</option>
                                        @endif
                                    </select>
                                    @error('time_slot')
                                        <small class="db-field-alert">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary" type="submit">
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

@push('js')
    <script src="{{ asset('backend/lib/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        var Url = '{{ route('admin.reservation.timeSlot') }}'
        var UserUrl = '{{ route('admin.reservation.user') }}'
    </script>
    <script src="{{ asset('js/reservation/edit.js') }}"></script>
    <script src="{{ asset('js/phone_validation/index.js') }}"></script>
@endpush
