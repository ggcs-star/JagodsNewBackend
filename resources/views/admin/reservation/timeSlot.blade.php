<label class="db-field-title required" for="timeSlot">{{ __('levels.timeSlot') }}</label>
<div class="db-field-down-arrow">
    <select id="timeSlot" name="time_slot"
        class="db-field-control appearance-none{{ $errors->has('timeSlot') ? ' invalid ' : '' }}">
        @if (!blank($timeSlots))
            @foreach ($timeSlots as $timeSlot)
                <option value="{{ $timeSlot['id'] }}">{{ date('h:i A', strtotime($timeSlot['start_time'])) }} -
                    {{ date('h:i A', strtotime($timeSlot['end_time'])) }}</option>
            @endforeach
        @endif
    </select>
</div>
@if ($errors->has('timeSlot'))
    <small class="db-field-alert">{{ $errors->first('timeSlot') }}</small>
@endif

