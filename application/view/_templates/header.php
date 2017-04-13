<!doctype html>
<html class="no-js">
<head lang="en">
    <title>FPL Analysis</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="FPL Analysis">
    <meta name="description" content="FPL Analysis for Fantasy Premier League Managers">
    <meta name="keywords" content="English Premier League, Fantasy Premier League, EPL, BPL, Barclays Premier League, Premier League">

    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/nprogress.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/style.css" />
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-91316667-2', 'auto');
        ga('send', 'pageview');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '827358514068521'); // Insert your pixel ID here.
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=827358514068521&ev=PageView&noscript=1"
    /></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
</head>
<body>
    <!-- Return to Top -->
    <a href="javascript:" id="return-to-top"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
    <div class="se-pre-con"></div>
    <!-- wrapper, to center website -->
    <div class="wrapper">

        <!-- logo -->
        <div class="logo"></div>
        <?php
        if($filename != 'cactus/index' && $filename != 'cactus/upload') {
        ?>
        <div class="container">
            <div class="row">
                <!-- navigation -->
                <ul class="navigation">
                    <li <?php if (View::checkForActiveController($filename, "index")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>index/index">Gameweek</a>
                    </li>
                    <li <?php if (View::checkForActiveController($filename, "players")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>players/index">Players</a>
                    </li>
                    <li <?php if (View::checkForActiveController($filename, "teams")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>teams/index">Teams</a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
        }
        ?>
