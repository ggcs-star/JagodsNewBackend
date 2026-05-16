@extends('frontend.layouts.app')

@section('title', 'Privacy Policy')

@section('main-content')

<section class="privacy-policy py-5">
    <div class="container">

        <div class="row">
            <div class="col-md-12">

                <h2 class="mb-4">
                    {{ $page->title ?? 'Privacy Policy' }}
                </h2>

                <div class="privacy-content">
                    {!! $page->description ?? '' !!}
                </div>

            </div>
        </div>

    </div>
</section>

@endsection

