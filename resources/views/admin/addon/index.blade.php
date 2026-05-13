@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('addons') }}
            </div>
        </div>
    </div>
    <div class="db-card mb-5">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ __('addon.addons') }}</h3>
            @can('addons_create')
                <a href="{{ route('admin.addons.create') }}" class="db-btn h-[37px] text-white bg-primary">
                    <i class="fa-solid fa-circle-plus"></i>
                    <span>{{ __('addon.add_addon') }}</span>
                </a>
            @endcan
        </div>
    </div>
    <div class="grid gap-3 sm:gap-[18px] grid-cols-5 sm:grid-cols-5 mb-8 md:mb-0">
        @if (!blank($addons))
            @foreach ($addons as $addon)
                <div class="rounded-2xl border transition border-[#EFF0F6] bg-white hover:shadow-xs">
                    <img class="w-full rounded-t-2xl" src="{{ $addon->image }}" alt="{{ $addon->title }}">
                    <div class="py-3 px-3 rounded-b-2xl">
                        <h3
                            class="text-sm mb-3 font-medium font-client capitalize text-ellipsis whitespace-nowrap overflow-hidden">
                            {{ \Illuminate\Support\Str::limit($addon->title, 45) }}
                            <span
                                class="text-xs capitalize h-5 leading-5 px-2 rounded-3xl text-[#1AB759] bg-[#E1FFED]">{{ $addon->version }}</span>
                        </h3>
                        <h4 class="font-client mb-2">{{ \Illuminate\Support\Str::limit($addon->description, 100) }}</h4>
                        @can('addons_delete')
                            <a href="#" id="DeleteModalClick" data-attr="{{ route('admin.addons.destroy', $addon->id) }}"
                                data-title="{{ $addon->title }}" title="Delete Addon" class="db-btn-fill sm red"><i
                                    class="fa-solid fa-trash-can"></i><span>{{ __('levels.remove') }}</a>
                        @endcan
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <form id="deleteForm" method="post">
                            <div class="modal-body">
                                @csrf
                                @method('DELETE')
                                <h5 class="text-center" id="fromTitle"></h5>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ __('levels.cancel') }}</button>
                                <button type="submit" class="btn btn-danger">{{ __('addon.delete_addon') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{ asset('js/addon/index.js') }}"></script>
@endpush
