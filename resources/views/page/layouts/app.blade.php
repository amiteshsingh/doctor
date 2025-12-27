<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'RogiSewa - Find Doctors & Hospitals Near You')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO Meta Tags --}}
    <meta name="description" content="@yield('meta_description', 'RogiSewa helps you find trusted doctors and hospitals nearby. Book appointments, check reviews, and get the best healthcare guidance online.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Rogi Sewa, Rogisewa, doctors, hospitals, health, online appointment, Bihar, India, healthcare, medical services')">
    <meta name="robots" content="index, follow">
    
    <meta name="google-adsense-account" content="ca-pub-3979062395254203">


    <link rel="canonical" href="{{ url()->current() }}">

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
    
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3979062395254203"
     crossorigin="anonymous"></script>
     
</head>

<body>
@include('page.layouts.header')



@yield('content')



@include('page.layouts.footer')


</body>

</html>