@include('elements.header')
@include('elements.navigation')
@include('elements.footer')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @yield('head')
    </head>
    <body>
        <main>
            <header>
                <div class="px-3 py-2">
                    <div class="container">
                        @yield('navigation')
                    </div>
                </div>
            </header>
            <section>
                <div class="container">
                    <div class="min-vh-100">
                        @yield('content')
                    </div>
                </div>
            </section>
            @yield('footer')
        </main>
    </body>
</html>