
@extends('frontend.layouts.app')

@section('title', 'Terms of Service')

@section('main-content')

<section class="terms-of-service py-5">
    <div class="container">

        <div class="row">
            <div class="col-md-12">

                <h2 class="mb-4">
                    {{ $page->title ?? 'Terms of Service' }}
                </h2>

                <div class="terms-content">
                    {!! $page->description ?? '' !!}
                </div>

            </div>
        </div>

    </div>
</section>

@endsection

