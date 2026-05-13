@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('request-withdraw/add') }}
            </div>
        </div>
        <div class="col-12 md:col-8 xl:col-8" id="form_container">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('levels.request_withdraw') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.request-withdraw.store') }}" method="POST" autocomplete="on">
                        @csrf
                        <div class="row">
                            @if (auth()->user()->myrole == 1)
                                <div class="col-12 sm:col-6 field">
                                    <label class="db-field-title required">{{ __('levels.user') }}</label>
                                    <div class="db-field-down-arrow">
                                        <select name="user_id" id="user_id"
                                            class="db-field-control appearance-none @error('user_id') invalid @enderror"
                                            data-url="{{ route('admin.request-withdraw.get-user-info') }}">
                                            <option value="">{{ __('levels.select_user') }}</option>
                                            <?php $selectUser = []; ?>
                                            @if (!blank($users))
                                                @foreach ($users as $user)
                                                    @if ($user->id == old('user_id'))
                                                        <?php $selectUser = $user; ?>
                                                    @endif
                                                    <option value="{{ $user->id }}"
                                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ trans('user_roles.' . $user->myrole) }}
                                                        {{ !blank($user->phone) ? ' ' . $user->phone : '' }})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @error('user_id')
                                        <small class="db-field-alert">{{ $message }}</small>
                                    @enderror
                                </div>
                            @else
                                <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}"
                                    data-url="{{ route('admin.request-withdraw.get-user-info') }}">
                            @endif
                            <div class="col-12 sm:col-6 field">
                                <label class="db-field-title required">{{ __('levels.amount') }}</label>
                                <input type="number" step=".01" name="amount"
                                    class="db-field-control @error('amount') invalid @enderror" value="{{ old('amount') }}"
                                    autocomplete="off">
                                @error('amount')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 sm:col-6 field">
                                <label class="db-field-title required">{{ __('levels.date') }}</label>
                                <input type="date" autocomplete="off" name="date"
                                    class="db-field-control @error('date') invalid @enderror" value="{{ old('date') }}">
                                @error('date')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
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
        <div id="userInfo" class="col-12 md:col-4 xl:col-4">

        </div>
    </div>

@endsection

@push('js')
<script>
    $('#user_id').on('change', function () {
        let selectedUser = $(this).val();
        if (selectedUser) {
            $('#form_container').addClass('md:col-8 xl:col-8');
            $('#form_container').removeClass('md:col-12 xl:col-12');
            $('.field').removeClass('sm:col-4');
            $('.field').addClass('sm:col-6');
            $('#userInfo').removeClass('hidden');
        } else {
            $('#form_container').removeClass('md:col-8 xl:col-8');
            $('#form_container').addClass('md:col-12 xl:col-12');
            $('.field').addClass('sm:col-4');
            $('.field').removeClass('sm:col-6');
            $('#userInfo').addClass('hidden');
        }
    });
</script>
    <script src="{{ asset('js/requestwithdraw/create.js') }}"></script>
@endpush
