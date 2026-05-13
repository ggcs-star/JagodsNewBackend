@extends('admin.app')

@section('content')

	<section class="pt-16 pb-12 flex flex-col items-center justify-center text-center">
        <img class="mb-8 w-full max-w-[220px]" src="{{ asset('backend/images/gif/404.gif') }}" alt="gif">
        <h3 class="capitalize text-[26px] font-medium leading-[40px] mb-2">{{ __('404 Page Not Found!') }}</h3>
        <p class="text-lg font-normal leading-[34px] mb-8">{{ __("We can’t seem to find the page you're looking for.") }}</p>
        <a href="/" class="w-full max-w-[250px] py-3 rounded-3xl capitalize text-base font-medium leading-6 text-center bg-primary text-white">go home</a>
    </section>

@endsection
