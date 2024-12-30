<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    @vite('resources/css/app.css')
    @vite('resources/css/landing.css')
</head>

<body class="">
    @component('components.header')
    @endcomponent
<!-- ============================================ -->
<!--                    Hero                      -->
<!-- ============================================ -->

<section id="hero-856">
    <div class="cs-container">
        <div class="cs-content">
            <span class="cs-topper">Modern and Professional</span>
            <h1 class="cs-title">Physical Therapy & Health and Wellness</h1>
            <p class="cs-text">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Temporibus unde id saepe quo. Est, ea! Lorem, ipsum dolor sit amet consectetur adipisicing elit. Id, provident? Lorem ipsum dolor sit amet consectetur adipisicing.
            </p>
            <a href="" class="cs-button-solid">Schedule An Appointment</a>
        </div>
        <!--Hero Image-->
        <picture class="cs-picture">
            <!--Mobile-->
            <source media="(max-width: 600px)" srcset="https://scontent.farn1-2.fna.fbcdn.net/v/t1.6435-9/159321465_10225197193262866_3274209467435046150_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=h42lqUOgFdkQ7kNvgGC2OMM&_nc_zt=23&_nc_ht=scontent.farn1-2.fna&_nc_gid=AlBW-d2nuodQlUo4D7t3YRi&oh=00_AYAfrPfQNrQMIFV7Q-tOhvtbrfSE7R7kHmv3woJVbOVp9g&oe=679A4F26">
            <!--Tablet-->
            <source media="(min-width: 601px)" srcset="https://scontent.farn1-2.fna.fbcdn.net/v/t1.6435-9/159321465_10225197193262866_3274209467435046150_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=h42lqUOgFdkQ7kNvgGC2OMM&_nc_zt=23&_nc_ht=scontent.farn1-2.fna&_nc_gid=AlBW-d2nuodQlUo4D7t3YRi&oh=00_AYAfrPfQNrQMIFV7Q-tOhvtbrfSE7R7kHmv3woJVbOVp9g&oe=679A4F26">
            <!--Desktop-->
            <source media="(min-width: 1024px)" srcset="https://scontent.farn1-2.fna.fbcdn.net/v/t1.6435-9/159321465_10225197193262866_3274209467435046150_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=h42lqUOgFdkQ7kNvgGC2OMM&_nc_zt=23&_nc_ht=scontent.farn1-2.fna&_nc_gid=AlBW-d2nuodQlUo4D7t3YRi&oh=00_AYAfrPfQNrQMIFV7Q-tOhvtbrfSE7R7kHmv3woJVbOVp9g&oe=679A4F26">
            <img aria-hidden="true" decoding="async" src="https://scontent.farn1-2.fna.fbcdn.net/v/t1.6435-9/159321465_10225197193262866_3274209467435046150_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=h42lqUOgFdkQ7kNvgGC2OMM&_nc_zt=23&_nc_ht=scontent.farn1-2.fna&_nc_gid=AlBW-d2nuodQlUo4D7t3YRi&oh=00_AYAfrPfQNrQMIFV7Q-tOhvtbrfSE7R7kHmv3woJVbOVp9g&oe=679A4F26" alt="therapy" width="570" height="701">
            </picture>
    </div>
    <!--Change the svg path fill color to whatever color the section below is so you can match it and create the illusion it is all one piece-->
    <svg class="cs-wave" width="1920" height="179" viewBox="0 0 1920 179" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M1920 179V91.3463C1835.33 91.3463 1715.47 76.615 1549.2 32.9521C1299.48 -32.3214 1132.77 12.1006 947.32 61.5167C810.762 97.9044 664.042 137 466.533 137C331.607 137 256.468 123.447 188.082 111.113C130.974 100.812 78.5746 91.3609 0 91.3609V179H1920Z" fill="white"/>
    </svg>
</section>
                                
</body>

</html>
