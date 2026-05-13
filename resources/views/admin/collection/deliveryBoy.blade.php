@if (!blank($deliveryboy))
    <div class="db-card">
        <div class="db-card-body">
            <img class="w-[120px] h-[120px] object-cover rounded-lg mb-8" src="{{ $deliveryboy->image ? $deliveryboy->image : asset('backend/images/default/user.png') }}" alt="avatar">
            <ul class="db-list single">
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.name') }}</span>
                    <span class="db-list-item-text">{{ @$deliveryboy->name }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.phone') }}</span>
                    <span class="db-list-item-text">{{ @$deliveryboy->phone }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.email') }}</span>
                    <span class="db-list-item-text">{{ @$deliveryboy->email }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.order_balance') }}</span>
                    <span class="db-list-item-text">{{ currencyFormat(@$deliveryboy->deliveryBoyAccount->balance) }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.credit') }}</span>
                    <span
                        class="db-list-item-text">{{ currencyFormat(@$deliveryboy->balance->balance > 0 ? @$deliveryboy->balance->balance : 0) }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.address') }}</span>
                    <span class="db-list-item-text">{{ @$deliveryboy->address }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.status') }}</span>
                    <span class="db-list-item-text">{!! @$deliveryboy->statusName !!}</span>
                </li>
            </ul>
        </div>
    </div>
@endif
