@extends('admin.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('live-orders') }}
        </div>
    </div>
    <div class="col-12">
        <div class="section-body"></div>
    </div>
</div>

@endsection


@push('js')
<script>
    const liveOrderRoute = "{{ route('admin.orders.get-live-Order') }}";
</script>

<script src="{{ asset('js/live-orders/index.js') }}"></script>
@endpush