<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <title>@lang('auth.signup') - {{ config('other.title') }}</title>
    @section('meta')
        <meta name="description"
            content="@lang('auth.login-now-on') {{ config('other.title') }} . @lang('auth.not-a-member')">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:title" content="@lang('auth.login')">
        <meta property="og:site_name" content="{{ config('other.title') }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ url('/img/og.png') }}">
        <meta property="og:description" content="{{ config('unit3d.powered-by') }}">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:locale" content="{{ config('app.locale') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @show
    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ mix('css/main/login.css') }}" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/arrive/2.4.1/arrive.min.js"></script>
    <script>
        (async function () {
            var backgroundList = ["/img/background-login/1.jpg", "/img/background-login/2.jpg", "/img/background-login/3.jpg", "/img/background-login/4.jpg", "/img/background-login/5.jpg", "/img/background-login/6.jpg", "/img/background-login/7.jpg", "/img/background-login/8.jpg", "/img/background-login/10.png", "/img/background-login/11.jpg", "/img/background-login/12.jpg", "/img/background-login/13.jpg", "/img/background-login/14.jpg", "/img/background-login/15.jpg", ];
            var backgroundChangeIntervalSeconds = 2;
            
            // promisify image loading
            var loadImage = function loadImage(source) {
                return new Promise(function (resolve) {
                var image = new Image();
                image.src = source;

                image.onload = function () {
                    return resolve();
                };
                });
            };

            var setBackground = function setBackground() {
                // create an array from the nodeList of loginPages
                var loginPages = Array.from(document.querySelectorAll(".loginPage")); // only proceed if there are login pages that are visible               

                if (loginPages.filter(function (page) {
                return page.classList.contains("hide");
                }).length < loginPages.length) {
                // go through each login page and apply the styling
                loginPages.forEach(function (page) {
                    return page.style = "\n                            background: url(".concat(backgroundList[Math.floor(Math.random() * backgroundList.length)], ") no-repeat center center fixed; \n                            -webkit-background-size: cover; \n                            -moz-background-size: cover; \n                            -o-background-size: cover; \n                            transition: background-image 0.5s ease-in-out; \n                            -webkit-transition: background-image 0.5s ease-in-out;\n                        ");
                });
                }
            };

            document.arrive(".loginPage", setBackground); // preload all images

            for (var _i = 0, _backgroundList = backgroundList; _i < _backgroundList.length; _i++) {
                var backgroundImg = _backgroundList[_i];
                await loadImage(backgroundImg);
            } // update via interval we set 


            setInterval(setBackground, backgroundChangeIntervalSeconds * 2500);
            })();
    </script>
    
</head>

<body class="loginPage">
    @if ($errors->any())
        <div id="ERROR_COPY" style="display: none;">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    <div class="wrapper fadeInDown">
        @if (config('other.invite-only') == true && !$code)
            <div class="alert alert-info">
                @lang('auth.need-invite')
            </div>
        @endif
        <svg viewBox="0 0 800 100" class="sitebanner">
            <symbol id="s-text">
                <text text-anchor="middle" x="50%" y="50%" dy=".35em">
                    {{ config('other.title') }}
                </text>
            </symbol>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text"></use>
        </svg>

        <div id="formContent">
            <a href="{{ route('login') }}">
                <h2 class="inactive underlineHover">@lang('auth.login') </h2>
            </a>
            <a href="{{ route('registrationForm', ['code' => $code]) }}">
                <h2 class="active">@lang('auth.signup') </h2>
            </a>

            <div class="fadeIn first">
                <img src="{{ url('/img/icon.svg') }}" id="icon" alt="@lang('auth.user-icon')" />
            </div>

            <form role="form" method="POST" action="{{ route('register', ['code' => $code]) }}">
                @csrf
                <label for="username"></label><input type="text" id="username" class="fadeIn second" name="username"
                    placeholder="@lang('auth.username')" required autofocus>
                <label for="email"></label><input type="email" id="email" class="fadeIn third" name="email"
                    placeholder="@lang('auth.email')" required>
                <label for="password"></label><input type="password" id="password" class="fadeIn third" name="password"
                    placeholder="@lang('auth.password')" required>
                @if (config('captcha.enabled') == true)
                    @hiddencaptcha
                @endif
                <button type="submit" class="fadeIn fourth">@lang('auth.signup')</button>
            </form>

            <div id="formFooter">
                <a href="{{ route('password.request') }}">
                    <h2 class="inactive underlineHover">@lang('auth.lost-password') </h2>
                </a>
                <a href="{{ route('username.request') }}">
                    <h2 class="inactive underlineHover">@lang('auth.lost-username') </h2>
                </a>
                @if (config('email-white-blacklist.enabled') == 'block')
                    <br>
                    <a href="{{ route('public.email') }}">
                        <h2 class="inactive underlineHover">@lang('common.email-blacklist') </h2>
                    </a>
                @elseif (config('email-white-blacklist.enabled') == 'allow')
                    <br>
                    <a href="{{ route('public.email') }}">
                        <h2 class="inactive underlineHover">@lang('common.email-whitelist') </h2>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}" crossorigin="anonymous"></script>
    @foreach (['warning', 'success', 'info'] as $key)
        @if (Session::has($key))
            <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                Toast.fire({
                    icon: '{{ $key }}',
                    title: '{{ Session::get($key) }}'
                })

            </script>
        @endif
    @endforeach

    @if (Session::has('errors'))
        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
            Swal.fire({
                title: '<strong style=" color: rgb(17,17,17);">Error</strong>',
                icon: 'error',
                html: jQuery("#ERROR_COPY").html(),
                showCloseButton: true,
            })

        </script>
    @endif

</body>

</html>
