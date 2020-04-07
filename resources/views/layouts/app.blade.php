<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {!! SEO::generate(true) !!}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('kulara/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('kulara/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Nunito:regular,bold">
    <link rel="stylesheet" href="{{ asset('kulara/css/kulara-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kulara/css/kulara-custom.css') }}">

    <title>@yield('title') | {{ config('app.name') }}</title>
    @stack('styles')
</head>
<body class="@yield('body-class')"{!! session('flash') ? ' data-flash-class="'.session('flash.0').'" data-flash-message="'.session('flash.1').'"' : '' !!}>

@yield('parent-content')

<div class="overlay"></div>
<script type="text/javascript" src="{{ asset('kulara/js/kulara-all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('kulara/js/kulara-components.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('kulara/js/kulara-custom.js') }}"></script>

@routes
<script type="text/javascript">
$(function() {
    @if (env('BROADCAST_DRIVER') == 'pusher')
    let pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
      cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
      forceTLS: true
    });
    let pusher_callback = function(data) {
        let icon = '{{ asset('kulara/logo.png') }}';
        if (data.icon != '') {
            icon = data.icon;
        }
        let link = '';
        if (data.link != '') {
            link = data.link;
        }
        let timeout = 5000;
        if (data.timeout != '') {
            timeout = data.timeout;
        }
        Push.create(data.title, {
            body: data.message,
            icon: icon,
            link: link,
            timeout: timeout,
            onClick: function () {
                window.focus();
                this.close();
            }
        });
    }
    let channel = pusher.subscribe('{{ sha1(env("APP_NAME")) }}');
    channel.bind('{{ sha1('general') }}', pusher_callback);

    @if (auth()->check())
    @php
        $groups = array_unique(auth()->user()->flatPermissions()->pluck('group')->toArray());
    @endphp
    @foreach ($groups as $group)
    channel.bind('{{ sha1($group) }}', pusher_callback);
    @endforeach
    @endif

    @endif
});
</script>
@stack('scripts')
</body>
</html>
