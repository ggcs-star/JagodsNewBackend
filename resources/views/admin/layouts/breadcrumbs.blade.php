@if (count($breadcrumbs))
    <ul class="db-breadcrumb-list">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <li class="db-breadcrumb-item"><a class="db-breadcrumb-link" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="db-breadcrumb-item">{{ $breadcrumb->title }}</li>
            @endif
        @endforeach
    </ul>
@endif
