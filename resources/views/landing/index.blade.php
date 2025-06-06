<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abdulbaqi Mayi</title>
    <meta name="description"
        content="Abdulbaqi Mayi (Abdulbaghi Ahmad) is a renowned child and adolescent psychiatrist working to improve mental health care in Sweden and Kurdistan.">
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
                <span class="cs-topper">{{ __('Hello World') }}</span>
                <h1> {{ __('Hello guys, how are you doing?') }}</h1>
                <h1 class="cs-title">
                    {{ __('A Lifetime of Struggle and Innovation') }}</h1>
                <p class="cs-text">
                    {{ __('Abdulbaghi Ahmad (Mayi) has his expertise in child mental health and child and adolescent psychiatry with focus on childhood trauma and posttraumatic health and wellbeing. His open and contextual creative concept based on a bio-psycho-social model of trauma and posttraumatic growth creating new pathways for improving the immunity and child mental health. He has developed several innovations and constructions for investigation, prevention and treatment of child mental health problems after years of clinical experience, research, evaluation, teaching and administration both in hospital and education institutions to provide suitable environment for the child for building up a healthy personality and prosperous society.') }}'
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
                <img aria-hidden="true" decoding="async" src="{{ URL::asset('images/abdul.jpg') }}"
                    alt="Image of Abdulbaqi Mayi" width="570" height="701">
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
