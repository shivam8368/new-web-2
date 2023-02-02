<head>
    {{-- Required meta tags  --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Free Porn Videos') - {{ env('APP_NAME') }}</title>

    {{-- Faviocn --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/front/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/front/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/front/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/front/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/assets/front/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/assets/front/img/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#b91d47">
    <meta name="msapplication-config" content="/assets/front/img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    {{-- SEO --}}
    <meta name="description" content="{{ env('APP_NAME') }} Free Porn Videos">
    <meta name="keywords" content="porn,xxx,porn videos,adult,adult videos">
    <meta name="application-name" content="{{ env('APP_NAME') }}">
    <meta name="author" content="Adult Videos API (www.adultvideosapi.com)">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700&display=swap" rel="stylesheet">

    <script>
        window['isDesktopDevice'] = {{ (new \Jenssegers\Agent\Agent())->isDesktop() ? 'true' : 'false' }};
        window['currentUri'] = '{{ Request::getRequestUri()  }}';
    </script>

    <link href="/assets/front/front.css?1664283972336" rel="stylesheet" type="text/css" media="all" />
    <script type="text/javascript" src="/assets/front/front.js?1664283972336" ></script>
</head>
