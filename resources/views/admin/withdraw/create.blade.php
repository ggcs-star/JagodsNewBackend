@extends('admin.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
        {{ Breadcrumbs::render('withdraw/add') }}
        </div>
    </div>

    <div class="col-12 md:col-8 xl:col-8">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('withdraw.withdraw') }}</h3>
            </div>
            <div class="db-card-body">
                <form action="{{ route('admin.withdraw.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 sm:col-6">
                            <label class="db-field-title required">{{ __('levels.user') }}</label>
                            <div class="db-field-down-arrow">
                                <select name="user_id" id="user_id" class="db-field-control appearance-none @error('user_id') invalid @enderror" data-url="{{ route('admin.withdraw.get-user-info') }}">
                                    <option value="">---</option>
                                    <?php $selectUser = []; ?>
                                    @if(!blank($users))
                                    @foreach($users as $user)
                                    @if($user->id == old('user_id'))
                                    <?php  $selectUser = $user; ?>
                                    @endif
                                    <option value="{{ $user->id }}" {{ (old('user_id', $requestWithdraw->user_id) == $user->id || auth()->user()->id == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{  trans('user_roles.'.$user->myrole) }} {{ !blank($user->phone)  ? ' '.$user->phone : '' }})
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('user_id')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 sm:col-6">
                            <label class="db-field-title required">{{ __('levels.amount') }}</label>
                            <input type="number" step=".01" name="amount" class="db-field-control @error('amount') invalid @enderror" value="{{ old('amount', $requestWithdraw->amount) }}">
                            @error('amount')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 sm:col-6">
                            <label class="db-field-title required">{{ __('withdraw.payment_type') }}</label>
                            <div class="db-field-down-arrow">
                            <select id="payment_type" name="payment_type" class="db-field-control appearance-none @error('payment_type') invalid @enderror">
                                <option value="">---</option>
                                @if(trans('payment_type'))
                                @foreach(trans('payment_type') as $key=> $value)
                                <option value="{{ $key }}" {{ old('payment_type') == $key ? 'selected':'' }}>{{ $value }}</option>
                                @endforeach
                                @endif
                            </select>
                            </div>
                            @error('payment_type')
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


    <div id="userInfo" class="col-4 md:col-4 xl:col-4">
        
    </div>

</div>

@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('backend/lib/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('backend/lib/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/withdraw/create.js') }}"></script>
@endpush
