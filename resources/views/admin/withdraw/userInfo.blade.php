@if(!blank($user))

<div class="db-card">
    <div class="db-card-body">
        <img class="w-[120px] h-[120px] object-cover rounded-lg mb-8" src="{{ $user->image }}" alt="avatar">
        <ul class="db-list single">
            <li class="db-list-item">
                <span class="db-list-item-title">{{ __('levels.name') }}</span>
                <span class="db-list-item-text">{{ $user->name }}</span>
            </li>
            <li class="db-list-item">
                <span class="db-list-item-title">{{ __('levels.phone') }}</span>
                <span class="db-list-item-text">{{ $user->phone }}</span>
            </li>
            <li class="db-list-item">
                <span class="db-list-item-title">{{ __('levels.email') }}</span>
                <span class="db-list-item-text">{{ $user->email }}</span>
            </li>
            @if($user->myrole == 4)
            <li class="db-list-item">
                <span class="db-list-item-title">{{ __('levels.order_balance') }}</span>
                <span class="db-list-item-text">{{ currencyFormat($user->deliveryBoyAccount->balance) }}</span>
            </li>
            @endif
            <li class="db-list-item">
                <span class="db-list-item-title">{{ __('levels.credit') }}</span>
                <span
                    class="db-list-item-text">{{ currencyFormat($user->balance->balance > 0 ? $user->balance->balance : 0) }}</span>
            </li>
            <li class="db-list-item">
                <span class="db-list-item-title">{{ __('levels.address') }}</span>
                <span class="db-list-item-text">{{ $user->address }}</span>
            </li>
            <li class="db-list-item">
                <span class="db-list-item-title">{{ __('levels.role') }}</span>
                <span class="db-list-item-text">{{ trans('user_roles.'.$user->myrole) }}</span>
            </li>
            <li class="db-list-item">
                <span class="db-list-item-title">{{ __('levels.status') }}</span>
                <span class="db-list-item-text">{!! $user->statusName !!}</span>
            </li>
        </ul>
    </div>
</div>
@endif
