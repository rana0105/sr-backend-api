<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Shuttle | Rental</title>
    <link rel="icon" href="{{asset('/frontend/assets/favicon/shuttle.svg')}}" type="image/gif">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{asset('frontend/assets/css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Facebook Pixel Code -->
<script>
   !function(f,b,e,v,n,t,s)
   {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
   n.callMethod.apply(n,arguments):n.queue.push(arguments)};
   if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
   n.queue=[];t=b.createElement(e);t.async=!0;
   t.src=v;s=b.getElementsByTagName(e)[0];
   s.parentNode.insertBefore(t,s)}(window, document,'script',
   'https://connect.facebook.net/en_US/fbevents.js');
   fbq('init', '572824090396819');
   fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
   src="https://www.facebook.com/tr?id=572824090396819&ev=PageView&noscript=1"
   /></noscript>
<!-- End Facebook Pixel Code -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z7VR6GCRL3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Z7VR6GCRL3');
</script>
    @yield('styles')
</head>

<body>
<div id="app">
    <div id="topbar">
        <div class="container-fluid">
            <div class="row justfy-content-center">
                <div class="col-md-4"></div>
                <div class="col-md-8 text-right">
                    <p>CONTACT: +8809638888868 | info@shuttlebd.com</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <nav class="navbar navbar-expand-md navbar-light shadow-none">
                <div class="container-fluid">
                    <img style="cursor: pointer;" class="navbar-brand" src="{{asset('frontend/assets/favicon/shuttle.svg')}}">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span><i class="fas fa-bars"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto" id="menubar">
                            <li class="nav-item">
                                <a class="nav-link" href="http://shuttlebd.com/">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="http://shuttlebd.com/business">Business</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="http://shuttlebd.com/women">Women</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link active" href="http://rental.shuttlebd.com/">Rental</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="http://shuttlebd.com/partner">Partner</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
<div class="container">
    @include('layouts.flash')
</div>

@yield('content')

<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 col-sm-12" id="shuttle-info">
                <ul>
                    <li><a href="javascript:void()">Careers</a></li>
                    <li><a href="javascript:void()">FAQ</a></li>
                    <li><a href="javascript:void()">Subscribe</a></li>
                    <li><a href="javascript:void()">Meet the founders</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-12" id="shuttle-contact">
                <p>Contact us:</p>
                <p id="ftrNubmer">+8809638888868</p>
                <p>(Saturday - Thursday: 6:00AM - 9:00PM)</p>
                <p>Email: info@shuttlebd.com</p>
            </div>
            <div class="col-md-4 col-lg-4 mx-auto text-center" id="icon-social">
              <ul style="display: flex; list-style-type: none;justify-content: center;padding-inline-start: 0 !important;">
                <li><a target="_blank" href="https://web.facebook.com/Shuttleforbusiness"><i class="fab fa-facebook-f mr-4"></i></a></li>
                <li><a target="_blank" href="https://twitter.com/shuttle_bd"><i class="fab fa-twitter mr-4"></i></a></li>
                <li><a target="_blank" href="https://www.linkedin.com/company/shuttlebd"><i class="fab fa-linkedin mr-4"></i></a> </li>
                <li><a target="_blank" href="https://www.instagram.com/shuttleforwomen/?igshid=1uyjftxnbl4qw"><i class="fab fa-instagram"></i></a></li>
              </ul>
              <p>&copy; 2020 Shuttle Technologies Limited</p>
            </div>
        </div>
    </div>
</footer>
@yield('js')
</body>
</html>
