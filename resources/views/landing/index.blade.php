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
                <span class="cs-topper">A SWEDISH PHYSICIAN FROM KURDISTAN</span>
                <h1 class="cs-title">
                    A Lifetime of Struggle and Success</h1>
                <p class="cs-text">
                    Abdulbaqi Mayi (Ahmad) is a child mental health expert, specialist in child and adolescent
                    psychiatry and posttraumatic growth...etc.
                </p>
            </div>
            <!--Hero Image-->
            <picture class="cs-picture">
                <!--Mobile-->
                <source media="(max-width: 600px)" srcset="{{ URL::asset('images/abdul.jpg') }}">
                <!--Tablet-->
                <source media="(min-width: 601px)" srcset="{{ URL::asset('images/abdul.jpg') }}">
                <!--Desktop-->
                <source media="(min-width: 1024px)" srcset="{{ URL::asset('images/abdul.jpg') }}">
                <img aria-hidden="true" decoding="async" src="{{ URL::asset('images/abdul.jpg') }}" alt="therapy"
                    width="570" height="701">
            </picture>
        </div>
        <!--Change the svg path fill color to whatever color the section below is so you can match it and create the illusion it is all one piece-->
        <svg class="cs-wave" width="1920" height="179" viewBox="0 0 1920 179" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M1920 179V91.3463C1835.33 91.3463 1715.47 76.615 1549.2 32.9521C1299.48 -32.3214 1132.77 12.1006 947.32 61.5167C810.762 97.9044 664.042 137 466.533 137C331.607 137 256.468 123.447 188.082 111.113C130.974 100.812 78.5746 91.3609 0 91.3609V179H1920Z"
                fill="white" />
        </svg>
    </section>

</body>

</html>
