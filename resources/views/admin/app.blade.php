<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($siteTitle) ? setting('site_name').':'.ucfirst($siteTitle) : setting('site_name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ themeSetting('fav_icon') ? themeSetting('fav_icon')->favicon : asset('images/seeder/settings/favicon.png') }}">
    @include('admin.layouts.head')
</head>

<body>

    <main class="db-main">
        @include('admin.layouts.navigation')
        @include('admin.layouts.profileSidebar')
        @include('admin.layouts.sidebar')
        @yield('content')
    </main>
    @include('admin.layouts.script')
</body>
</html>
