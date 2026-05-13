@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('/address') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('levels.address') }}</h3>
                    <div class="db-card-filter">

                        <button data-modal="#addAddress" class="db-btn h-[38px] text-white bg-primary">
                            <i class="fa-solid fa-circle-plus"></i>
                            <span>{{ __('levels.add_address') }}</span>
                        </button>
                    </div>
                </div>
                <div class="db-table-responsive">
                    <table class="db-table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('levels.label') }}</th>
                                <th class="db-table-head-th">{{ __('levels.address') }}</th>
                                <th class="db-table-head-th">action</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body">
                            @forelse ($addresses as $address)
                                <tr class="db-table-body-tr">
                                    <td class="db-table-body-td">{{ $address->label_name }}</td>
                                    <td class="db-table-body-td">{{ $address->address }}</td>
                                    <td class="db-table-body-td">
                                        <button class="db-table-action edit modal-btn edit-address" data-modal="#editAddress"
                                            data-id="{{ $address->id }}" data-labelname="{{$address->label_name}}" data-label="{{ $address->label }}"
                                            data-address="{{ $address->address }}" data-apartment="{{ $address->apartment }}"
                                            data-latitude="{{ $address->latitude }}"
                                            data-longitude="{{ $address->longitude }}">
                                            <i class="fa-solid fa-pencil"></i>
                                            <span class="db-tooltip">edit</span>
                                        </button>

                                        <button class="db-table-action delete modal-btn"
                                            onclick="event.preventDefault();document.getElementById('address-delete-form{{ $address->id }}').submit();">
                                            <i class="fa-solid fa-trash-can"></i>
                                            <span class="db-tooltip">delete</span>
                                        </button>
                                        <form id="address-delete-form{{ $address->id }}"
                                            action="{{ route('admin.profile.delete-address', $address) }}" method="POST"
                                            class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr class="db-table-body-tr">
                                    <td colspan="3" class="db-table-body-td text-center text-gray-500">
                                        {{ __('levels.no_data_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">

                </div>

            </div>
        </div>
    </div>


    <div id="addAddress" class="modal">
        <div class="modal-dialog max-w-2xl rounded-none">
            <div class="modal-header">
                <h3 class="db-card-title mt-2">{{ __('levels.add_address') }}</h3>
                <button type="button" class="modal-close flex items-center justify-center gap-1.5 py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" id="close">
                        <path fill="none" d="M0 0h24v24H0Z"></path>
                        <path fill="#525863"
                            d="M15.741 17.61 12 13.87l-3.742 3.74a1.323 1.323 0 0 1-1.873-1.869L10.128 12 6.385 8.258a1.324 1.324 0 0 1 1.873-1.873L12 10.127l3.741-3.742a1.324 1.324 0 0 1 1.873 1.873L13.872 12l3.742 3.741a1.323 1.323 0 0 1-1.873 1.869Z">
                        </path>
                    </svg>

                </button>
            </div>
            <div class="modal-body">
                <div class="row px-5 pt-3 pb-5">
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title required" for="label">{{ __('levels.label') }}</label>
                        <div class="db-field-down-arrow">
                            <select name="label" id="label"
                                class="db-field-control select2 appearance-none @error('label') invalid @enderror"
                                onchange="select()">
                                <option value="">---</option>
                                @foreach (trans('address_types') as $addressTypeKey => $addressType)
                                    <option value="{{ $addressTypeKey }}">{{ $addressType }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('label')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6 hidden" id="other_label">
                        <label class="db-field-title required" for="label_name">{{ __('levels.label_name') }}</label>
                        <input type="text" name="label_name" id="label_name"
                            class="db-field-control @error('label_name') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_new_label') }}" autocomplete="off">
                        @error('label_name')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title required" for="new_address">{{ __('levels.new_address') }}</label>
                        <input type="text" name="new_address" id="new_address"
                            class="db-field-control @error('new_address') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_new_address') }}">
                        @error('new_address')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title" for="apartment">{{ __('levels.apartment') }}</label>
                        <input type="text" name="apartment" id="apartment"
                            class="db-field-control @error('apartment') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_apartment') }}">
                        @error('apartment')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title" for="latitude">{{ __('levels.latitude') }}</label>
                        <input type="text" name="latitude" id="latitude"
                            class="db-field-control @error('latitude') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_latitude') }}">
                        @error('latitude')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title" for="longitude">{{ __('levels.longitude') }}</label>
                        <input type="text" name="longitude" id="longitude"
                            class="db-field-control @error('longitude') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_longitude') }}">
                        @error('longitude')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                        <div id="googleMapAddress"></div>
                    </div>

                    <div class="form-col-12">
                        <button type="submit" class="db-btn text-white bg-primary" id="save-address">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ __('levels.save') }}</span>
                        </button>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div id="editAddress" class="modal">
        <div class="modal-dialog max-w-2xl rounded-none">
            <div class="modal-header">
                <h3 class="db-card-title mt-2">{{ __('levels.edit_address') }}</h3>
                <button type="button" class="modal-close flex items-center justify-center gap-1.5 py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" id="close">
                        <path fill="none" d="M0 0h24v24H0Z"></path>
                        <path fill="#525863"
                            d="M15.741 17.61 12 13.87l-3.742 3.74a1.323 1.323 0 0 1-1.873-1.869L10.128 12 6.385 8.258a1.324 1.324 0 0 1 1.873-1.873L12 10.127l3.741-3.742a1.324 1.324 0 0 1 1.873 1.873L13.872 12l3.742 3.741a1.323 1.323 0 0 1-1.873 1.869Z">
                        </path>
                    </svg>

                </button>
            </div>
            <input type="hidden" id="edit_id" name="id">
            <div class="modal-body">
                <div class="row px-5 pt-3 pb-5">
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title required" for="label">{{ __('levels.label') }}</label>
                        <div class="db-field-down-arrow">
                            <select name="label" id="edit_label"
                                class="db-field-control select2 appearance-none @error('label') invalid @enderror"
                                onchange="editselect()">
                                <option value="">---</option>
                                @foreach (trans('address_types') as $addressTypeKey => $addressType)
                                    <option value="{{ $addressTypeKey }}">{{ $addressType }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('label')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6 hidden" id="edit_other_label">
                        <label class="db-field-title required" for="label_name">{{ __('levels.label_name') }}</label>
                        <input type="text" name="label_name" id="edit_label_name"
                            class="db-field-control @error('label_name') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_new_label') }}" autocomplete="off">
                        @error('label_name')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title required" for="new_address">{{ __('levels.new_address') }}</label>
                        <input type="text" name="new_address" id="edit_new_address"
                            class="db-field-control @error('new_address') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_new_address') }}">
                        @error('new_address')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title" for="apartment">{{ __('levels.apartment') }}</label>
                        <input type="text" name="apartment" id="edit_apartment"
                            class="db-field-control @error('apartment') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_apartment') }}">
                        @error('apartment')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title" for="edit_latitude">{{ __('levels.latitude') }}</label>
                        <input type="text" name="latitude" id="edit_latitude"
                            class="db-field-control @error('latitude') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_latitude') }}">
                        @error('latitude')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6 md:form-col-6">
                        <label class="db-field-title" for="edit_longitude">{{ __('levels.longitude') }}</label>
                        <input type="text" name="longitude" id="edit_longitude"
                            class="db-field-control @error('longitude') invalid @enderror" value=""
                            placeholder="{{ __('levels.enter_longitude') }}">
                        @error('longitude')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-12 md:form-col-12">
                        <div id="googleMapAddressEdit" style="width: 100%; height: 200px;"></div>
                    </div>

                    <div class="form-col-12">
                        <button type="submit" class="db-btn text-white bg-primary" id="save-address" onclick="event.preventDefault();saveEditAddress();">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ __('levels.save') }}</span>
                        </button>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@push('js')
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_map_api_key') }}&libraries=places&callback=initMap">
    </script>

    <script type="text/javascript">
        let setUrl = "{{ route('admin.profile.save-address') }}";
    </script>

    <script src="{{ asset('js/profile/index.js') }}"></script>
@endpush
