<!doctype html>
<html lang="en">

@include("front.layout.partials.head")

<body>

<div style="min-height:100vh;display:flex;flex-direction:column;justify-content:space-between;">
    <div>
        {{-- Navbar --}}
        @include("front.layout.partials.navbar")

        @yield('content')
    </div>

    {{-- Footer --}}
    @include("front.layout.partials.footer")
</div>

{{-- Right panels --}}
@include("front.layout.partials.panels.mobile-navbar")

{{-- Loader & tooltip --}}
<div class="_loading" style="display:none;">Loading&#8230;</div>

@yield('footer.js')
</body>
</html>
