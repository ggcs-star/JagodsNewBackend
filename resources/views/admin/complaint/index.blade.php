@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('complaints') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                @can('request-withdraw_create')
                    <div class="db-card-header border-none">
                        <h3 class="db-card-title">{{ __('levels.complaints') }}</h3>
                        
                    </div>
                @endcan
                
                <div class="db-table-responsive">
                    <table class="db-table stripe" id="maintable" data-url="{{ route('admin.complaint.get-complaints') }}"
                        data-status="{{ \App\Enums\Status::ACTIVE }}">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('levels.order_code') }}</th>
                                <th class="db-table-head-th">{{ __('levels.customer_name') }}</th>
                                <th class="db-table-head-th">{{ __('levels.status') }}</th>
                                <th class="db-table-head-th">{{ __('levels.actions') }}</th>
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

    <style>
        .dropdown-group {
            @apply relative leading-[0px]
        }

        .dropdown-btn {
            @apply cursor-pointer
        }

        .dropdown-list {
            @apply hidden
        }

        .dropdown-list.active {
            @apply block
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('backend/lib/datatable/js/dataTables.js') }}"></script>
    <script src="{{ asset('backend/lib/datatable/js/dataTables.tailwindcss.js') }}"></script>
    <script src="{{ asset('backend/lib/datatable/js/tailwindcss.js') }}"></script>
    <script src="{{ asset('js/complaints/index.js') }}"></script>
    <script>
        const dropGroup = document?.querySelectorAll(".dropdown-group");
        const dropList = document?.querySelectorAll(".dropdown-list");
        const dropBtn = document?.querySelectorAll(".dropdown-btn");
        dropBtn?.forEach((btnItem) => {
            btnItem?.addEventListener("click", () => {
                const dropIcon = btnItem.querySelector('.dropdown-icon')
                dropIcon?.classList.add('transition-all', 'duration-300', 'ease-in-out')
                const currentGroup = btnItem?.closest(".dropdown-group");
                const currentBtn = currentGroup?.querySelector(".dropdown-btn");
                const currentList = currentGroup?.querySelector(".dropdown-list");
                const currentActive = currentGroup?.className.includes("active");

                if (currentActive) {
                    currentGroup?.classList?.remove("active");
                    currentList?.classList?.remove("active");
                    currentBtn?.classList?.remove("active");
                    dropIcon?.classList.remove('rotate-180')
                } else {
                    dropGroup?.forEach((groupItem) => {
                        if (groupItem?.className?.includes("active")) {
                            groupItem?.classList?.remove("active");
                            groupItem?.querySelector(".dropdown-btn")?.classList?.remove("active");
                            groupItem?.querySelector(".dropdown-list")?.classList?.remove("active");
                            groupItem?.querySelector(".dropdown-icon")?.classList.remove(
                                "rotate-180")
                        }
                    })
                    currentGroup?.classList?.add("active");
                    currentList?.classList?.add("active");
                    currentBtn?.classList?.add("active");
                    dropIcon?.classList.add('rotate-180')

                }
            })

        })
        document?.addEventListener("click", (event) => {
            dropGroup?.forEach((item) => {
                if (!item?.contains(event?.target)) {
                    item?.classList?.remove("active");
                    item?.querySelector(".dropdown-btn")?.classList?.remove("active");
                    item?.querySelector(".dropdown-list")?.classList?.remove("active");
                    item?.querySelector(".dropdown-icon")?.classList.remove('rotate-180');
                }
            })
        })
    </script>
@endpush
