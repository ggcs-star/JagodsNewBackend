@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('collection/add') }}
            </div>
        </div>
        <div class="col-12 md:col-12 xl:col-12" id="form_container">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('collection.collections') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.collection.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-12 sm:col-4 field">
                                <label class="db-field-title required">{{ __('levels.name') }}</label>
                                <div class="db-field-down-arrow">
                                    <select id="user_id" name="user_id"
                                        class="db-field-control appearance-none @error('user_id') invalid @enderror"
                                        data-url="{{ route('admin.collection.get-delivery-boy') }}">
                                        <option value="">{{ __('levels.select') }}</option>
                                        <?php $selectUser = []; ?>
                                        @if (!blank($users))
                                            @foreach ($users as $user)
                                                @if ($user->id == old('user_id'))
                                                    <?php $selectUser = $user; ?>
                                                @endif
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                    {{ !blank($user->phone) ? ' (' . $user->phone . ')' : '' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('user_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 sm:col-4 field">
                                <label class="db-field-title required">{{ __('levels.date') }}</label>
                                <input type="date" name="date"
                                    class="db-field-control @error('date') invalid @enderror"
                                    value="{{ old('date') }}">
                                @error('date')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 sm:col-4 field">
                                <label class="db-field-title required">{{ __('levels.amount') }}</label>
                                <input type="number" step=".01" name="amount"
                                    class="db-field-control @error('amount') invalid @enderror"
                                    value="{{ old('amount') }}">
                                @error('amount')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
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
        
        <div id="userInfo" class="col-12 md:col-4 xl:col-4 hidden">

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
                $('#userInfo').addClass('hidden');
                $('.field').addClass('sm:col-4');
                $('.field').removeClass('sm:col-6');
            }
        });
    </script>
    <script src="{{ asset('js/collection/create.js') }}"></script>
@endpush
