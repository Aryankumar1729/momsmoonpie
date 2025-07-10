<!-- resources/views/layouts/front.blade.php -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Moms Moonpie')</title>
    <link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/splide.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/marqueefy.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    @yield('style')
</head>

<body>
    @include('layouts.front.partials.header')

    <!-- Main Content -->
    @yield('content')

    @include('layouts.front.partials.footer')

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/splide.min.js') }}"></script>
    <script src="{{ asset('js/marqueefy.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    <script>
        var loggedin = {{ auth()->guard('customer')->check() ? 'true' : 'false' }};
    </script>

    <script>
        $('.wishliat-btn, .cart-wishlist-btn').on('click', function() {
            if (!loggedin) {
                window.location.href = "{{ route('front.customer-login') }}";
            }
            const productId = $(this).data('product-id');
            const $icon = $(this).find('i');
            const isAdding = $icon.hasClass('fa-regular');
            const url = isAdding ? "{{ route('front.wishlists.add') }}" :
                "{{ route('front.wishlists.remove') }}";

            $.ajax({
                url: url,
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify({
                    product_id: productId
                }),
                success: function(data) {
                    if (data.status === 'success') {
                        $icon.toggleClass('fa-regular fa-solid');
                        const $wishlistImg = document.getElementById('wishlist-icon-img');
                        $wishlistImg.classList.add('wishlist-pop');

                        // remove class after animation ends
                        setTimeout(() => {
                            $wishlistImg.classList.remove('wishlist-pop');
                        }, 400);
                        $icon.addClass('pop');
                        setTimeout(() => $icon.removeClass('pop'), 300);
                        // alert(data.message);
                        console.log(data.message);
                        // toastr.success(data.message);

                        document.getElementById('wishlist-badge').style.display = 'inline-block';
                        localStorage.setItem('wishlistNotification', 'true');
                    }
                }
            });
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Show red dot if stored
        if (localStorage.getItem('wishlistNotification') === 'true') {
            const badge = document.getElementById('wishlist-badge');
            if (badge) badge.style.display = 'inline-block';
        }

        // Remove red dot if on wishlist page
        const path = window.location.pathname;
        if (path.includes('/wishlist') || path.includes('/wishlists')) {
            localStorage.removeItem('wishlistNotification');
            const badge = document.getElementById('wishlist-badge');
            if (badge) badge.style.display = 'none';
        }
    });
    </script>
    @yield('script')
</body>

</html>
