<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-SVZMS2JP37"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-SVZMS2JP37');
        </script>
        
        <!-- LinkedIn Insight Tag -->
        <script type="text/javascript">
        _linkedin_partner_id = "7398036";
        window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
        window._linkedin_data_partner_ids.push(_linkedin_partner_id);
        </script><script type="text/javascript">
        (function(l) {
        if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
        window.lintrk.q=[]}
        var s = document.getElementsByTagName("script")[0];
        var b = document.createElement("script");
        b.type = "text/javascript";b.async = true;
        b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
        s.parentNode.insertBefore(b, s);})(window.lintrk);
        </script>
        <noscript>
        <img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=7398036&fmt=gif" />
        </noscript>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        @php
            // Kiểm tra nếu đang ở trang try-writing hoặc các trang cần layout rộng
            $isWidePage = request()->is('try-writing*');
        @endphp

        @if($isWidePage)
            <div class="min-h-screen bg-gray-100">
                <main>
                    @yield('content')
                </main>
            </div>
        @else
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <div>
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>

                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </div>
        @endif
    </body>
</html>
