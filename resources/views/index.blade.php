<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  @include('_layouts.meta')
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
<div id="app">
  <giftroom-navbar logo="false"></giftroom-navbar>

  <router-view></router-view>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>