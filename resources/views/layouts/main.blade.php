<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="publishable-key" content="{{ Config('services.stripe.pub_key') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        
 
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/offcanvas-navbar.css', 'resources/css/adapt.css'])
        <link href="https://cdn.bootcdn.net/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{asset('fontawesome/css/fontawesome.css')}}" rel="stylesheet">
        <link href="{{asset('fontawesome/css/brands.css')}}" rel="stylesheet">
        <link href="{{asset('fontawesome/css/solid.css')}}" rel="stylesheet">

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 container col-md-6 col-md-offset-3">
            @include('partials.nav')
            @if(Session::has('flash_message'))
                <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_message') !!}</em></div>
            @endif
            {{-- @include('layouts.navigation') --}}
            @include('partials.message')
            @yield('content')
        </div>
    </body> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
   
    {{-- stripe js --}}
    <script src="https://js.stripe.com/v2/"></script>
    {{-- <script src="{{asset('js/app.js')}}"></script> --}}

    @yield('scripts')
  
</html>
