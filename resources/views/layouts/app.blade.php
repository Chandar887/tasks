<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend._meta')
    <title>@yield('page-title') - {{env('APP_NAME',"Satta")}}</title>
    @include('backend._styles')

</head>

<body style="min-height: 100vh">
    @auth
    @include('backend._navbar_sm')
    @include('backend._sidebar')
    <main class="content">
        @include('backend._navbar')
        <div class="h-100">
            {{ $slot }}
        </div>
    </main>
    @endauth

    <!--------->

    @guest
    <main>

        {{ $slot }}
    </main>
    @endguest
    @include('backend._scripts')
    {{-- <script src="https:\\unpkg.com\turbolinks"></script> --}}
</body>

</html>