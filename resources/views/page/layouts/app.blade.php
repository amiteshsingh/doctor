<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'RogiSewa - Find Doctors & Hospitals Near You')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="RogiSewa">

    {{-- SEO Meta Tags --}}
    <meta name="description" content="@yield('meta_description', 'RogiSewa helps you find trusted doctors and hospitals nearby. Book appointments, check reviews, and get the best healthcare guidance online.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Rogi Sewa, Rogisewa, doctors, hospitals, health, online appointment, Bihar, India, healthcare, medical services')">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    
    <meta name="google-adsense-account" content="ca-pub-9062741479178096">


    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="@yield('title', 'RogiSewa - Find Doctors & Hospitals Near You')">
    <meta property="og:description" content="@yield('meta_description', 'RogiSewa helps you find trusted doctors and hospitals nearby.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('img/logo.png') }}">
    <meta property="og:site_name" content="RogiSewa">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'RogiSewa - Find Doctors & Hospitals Near You')">
    <meta name="twitter:description" content="@yield('meta_description', 'RogiSewa helps you find trusted doctors and hospitals nearby.')">
    <meta name="twitter:image" content="{{ asset('img/logo.png') }}">

    <!-- Favicon -->
    <link href="{{ asset('img/logo.png') }}" rel="icon"> 

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">  

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
   
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    {{-- Google AdSense --}}
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9062741479178096" crossorigin="anonymous"></script>
</head>

<body>
@include('page.layouts.header')



@yield('content')



@include('page.layouts.footer')


</body>

</html>