@component('mail::message')
# Hey {{$guestName}}, you are invited!

A gift exchange party is happening, and <span style="font-weight: 800;">{{$hostName}}</span> has invited you! Just head on to Giftroom and to join the party with there following code.<br>
<br>
<a href="https://giftroom.party" style="
    display: block;
    background-color: #f48fb1;
    padding: 15px;
    font-size: 1.5rem;
    text-align: center;
    color: #fff;
    cursor: pointer;
    text-decoration: none;">
{{$roomId}}
</a>

<br>
Enjoy the best party!<br>
<a style="text-decoration: none;" href="https://giftroom.party">{{ config('app.name') }}<br>
@endcomponent
