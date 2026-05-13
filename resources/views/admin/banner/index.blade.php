@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('banners') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('banner.banners') }}</h3>
                    <div class="db-card-filter">
                        @can('banner_create')
                            <a href="{{ route('admin.banner.create') }}" class="db-btn h-[38px] text-white bg-primary">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>{{ __('banner.add_banner') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="db-table-responsive">
                    <table class="db-table stripe" id="sortable-table">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th"><i class="fas fa-th"></i></th>
                                <th class="db-table-head-th font-bold">{{ __('levels.image') }}</th>
                                <th class="db-table-head-th font-bold">{{ __('levels.restaurant') }}</th>
                                <th class="db-table-head-th font-bold">{{ __('levels.title') }}</th>
                                <th class="db-table-head-th font-bold">{{ __('levels.status') }}</th>
                                @if (auth()->user()->can('banner_edit') || auth()->user()->can('banner_delete'))
                                    <th class="db-table-head-th font-bold">{{ __('levels.actions') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="db-table-body" data-url="{{ route('admin.sort.banner') }}">
                            @if (!blank($banners))
                                @foreach ($banners as $banner)
                                    <tr class="db-table-body-tr" data-id="{{ $banner->id }}">
                                        <td class="db-table-body-td">
                                            <div class="sort-handler">
                                                <i class="fas fa-th"></i>
                                            </div>
                                        </td>
                                        <td class="db-table-body-td">
                                            <img src="{{ $banner->image }}" class="w-20">
                                        </td>
                                        <td class="db-table-body-td">{{ $banner->restuarant->name }} </td>
                                        <td class="db-table-body-td">{{ Str::limit($banner->title, 60, '...') }} </td>
                                        <td class="db-table-body-td">
                                            @if ($banner->status == 5)
                                                <span
                                                    class="db-table-badge text-green-600 bg-green-100">{{ 'Active' }}</span>
                                            @else
                                                <span
                                                    class="db-table-badge text-red-600 bg-red-100">{{ 'Inactive' }}</span>
                                            @endif
                                        </td>
                                        @if (auth()->user()->can('banner_edit') || auth()->user()->can('banner_delete'))
                                            <td class="db-table-body-td">
                                                @if (auth()->user()->can('banner_edit'))
                                                    <a href="{{ route('admin.banner.edit', $banner) }}"
                                                        class="db-table-action edit modal-btn" data-toggle="tooltip"
                                                        data-placement="top" title="Edit">
                                                        <i class="fa-solid fa-pencil"></i>
                                                        <span class="db-tooltip">Edit</span>
                                                    </a>
                                                @endif

                                                @if (auth()->user()->can('banner_delete'))
                                                    <form class="inline-block" action="{{ route('admin.banner.destroy', $banner) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="db-table-action delete modal-btn"
                                                            data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                            <span class="db-tooltip">delete</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{ asset('backend/lib/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/banner/table.js') }}"></script>
@endpush
