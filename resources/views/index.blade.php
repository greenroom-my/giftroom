<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('_layouts.meta')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
<div id="app">
    @include('_layouts.navbar-top')
    <div class="container">

    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>