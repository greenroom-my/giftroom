<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('_layouts.meta')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
<div id="app">
    <Slideout menu="#menu" panel="#panel" :toggleSelectors="['.toggle-button']">
        <nav id="menu">
            <ul>
                <li @click="closeMenu('/room-home')" class="btn btn-primary btn-block"><i class="fa fa-home"></i> &nbsp; Home</li>
                <li @click="closeMenu('/wish-list')" class="btn btn-primary btn-block"><i class="fa fa-tasks"></i> &nbsp; My Wish List</li>
                <li @click="closeMenu('/match')" class="btn btn-primary btn-block"><i class="fa fa-user"></i> &nbsp; My Match</li>
            </ul>

            <div class="room-switch">You are in <strong>LIMFAMILY</strong></div>
        </nav>
        <main id="panel">
            <giftroom-navbar :menu="navbar.menu" :logo="navbar.logo"></giftroom-navbar>

            <router-view></router-view>
            <vue-up></vue-up>
        </main>
    </Slideout>




</div>
<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>