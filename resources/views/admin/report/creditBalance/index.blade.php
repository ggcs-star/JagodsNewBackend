@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('credit-balance-report') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('reports.credit_balance_report') }}</h3>
                    <div class="db-card-filter">
                        <button class="db-card-filter-btn table-filter-btn">
                            <i class="fa-solid fa-filter"></i>
                            <span>{{ __('levels.filter') }}</span>
                        </button>
                        <div class="dropdown-group">
                            <button class="db-card-filter-btn dropdown-btn">
                              <i class="fa-solid fa-file-export"></i>
                              <span>{{ __('levels.export') }}</span>
                            </button>
                            <div class="dropdown-list db-card-filter-dropdown-list">
                              <button onclick="printDiv('printablediv')" class="db-card-filter-dropdown-menu">
                                <i class="fa-solid fa-print"></i>
                                {{ __('levels.print') }}
                              </button>
                            </div>
                          </div>
                    </div>
                </div>
                <div class="table-filter-div input-group input-daterange" id="date-picker" style="">
                    <div class="p-5 mb-8">
                        <div class="row">
                            <div class="col-4 sm:col-4">
                                <label class="db-field-title">{{ __('levels.user_role') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="role_id" id="userRoleID"
                                        class="db-field-control appearance-none @error('role_id') invalid @enderror">
                                        <option value="">--</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4 sm:col-4">
                                <label class="db-field-title">{{ __('levels.users') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="user_id" id="users"
                                        class="db-field-control appearance-none @error('user_id') invalid @enderror">
                                        <option value="">--</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }} @if($user->phone) ({{ $user->phone }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="flex flex-wrap gap-3 input-group-append h-full items-center">
                                    <button type="button" class="db-btn py-2 text-white bg-primary h-fit" id="date-search">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <span>{{ __('levels.search') }}</span>
                                    </button>
                                    <button class="db-btn py-2 text-white bg-gray-600 h-fit" id="clear">
                                        <i class="fa-solid fa-xmark"></i>
                                        <span>{{ __('levels.clear') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="db-table-responsive" id="printablediv">
                    <table class="db-table table stripe" id="maintable" data-url="{{ route('admin.credit-balance-report.index') }}">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('Name') }}</th>
                                <th class="db-table-head-th">{{ __('User Role') }}</th>
                                <th class="db-table-head-th">{{ __('Phone') }}</th>
                                <th class="db-table-head-th">{{ __('Credit') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/datatable/css/dataTables.tailwindcss.css') }}">
@endpush
@push('js')
    <script src="{{ asset('backend/lib/datatable/js/dataTables.js') }}"></script>
    <script src="{{ asset('backend/lib/datatable/js/dataTables.tailwindcss.js') }}"></script>
    <script src="{{ asset('backend/lib/datatable/js/tailwindcss.js') }}"></script>
    <script>
        const indexUrl = "{{ route('admin.get-role-user') }}";
   </script>
    <script src="{{ asset('js/creditBalance/index.js') }}"></script>
@endpush
