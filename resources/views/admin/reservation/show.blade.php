@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('reservations/view') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header justify-start gap-6 items-center">
                    <h3 class="db-card-title">{{ $reservation->restaurant->name }}</h3>
                    <div class="flex items-center">
                        <span class="text-xs" title="{{ __('levels.reservation_status') }}">{!!  $reservation->statusName !!}</span>
                    </div>
                </div>
                <div class="db-card-body">
                    <ul class="db-list multiple">
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{ __('levels.name') }}</span>
                            <span
                                class="db-list-item-text">{{ $reservation->first_name . ' ' . $reservation->last_name }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{ __('levels.email') }}</span>
                            <span class="db-list-item-text">{{ $reservation->email }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{ __('levels.phone') }}</span>
                            <span class="db-list-item-text">{{ $reservation->phone }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{ __('levels.date&time') }}</span>
                            <span
                                class="db-list-item-text">{{ \Carbon\Carbon::parse($reservation->created_at)->format('d M Y, h:i A') }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{ __('levels.timeSlot') }}</span>
                            <span
                                class="db-list-item-text">{{ date('h:i A', strtotime($reservation->timeSlot->start_time)) . '-' . date('h:i A', strtotime($reservation->timeSlot->end_time)) }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{ __('levels.table') }}</span>
                            <span class="db-list-item-text">{{ $reservation->table->name }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{ __('levels.guest') }}</span>
                            <span class="db-list-item-text">{{ $reservation->guest_number }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{ __('Status') }}</span>
                            <span class="db-list-item-text">
                                    <div class="box w-fit">
                                        <div class="relative cursor-pointer">
                                            <select data-reservation-id="{{ $reservation->id }}" id="status" class="text-sm cursor-pointer capitalize appearance-none pl-4 pr-10 h-[38px] rounded border border-primary bg-white text-primary">
                                                @foreach ((new ReflectionClass(\App\Enums\ReservationStatus::class))->getConstants() as $key => $status)
                                                <option value="{{ $status }}" {{ $reservation->status == $status ? 'selected' : '' }}>
                                                        {{ __('reservation_status.'. $status ) }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <i class="fa-solid fa-chevron-down cursor-pointer absolute top-1/2 right-3.5 -translate-y-1/2 text-xs text-primary"></i>
                                        </div>
                                    </div>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>

        $('#status').on('change', function() {
            let reservationId = $(this).data('reservation-id');
            let status = $(this).val();
            
            $.ajax({
                url: "status/update/"+ reservationId + "/" + status,
                type: 'GET',
                success: function(response) {
                    location.reload();
                }
            });
            
        });
        
    </script>
@endpush
