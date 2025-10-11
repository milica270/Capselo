<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/brite/bootstrap.min.css">
    @vite('resources/js/app.js')
    @inertiaHead
    @routes
    <style>
        :root {
        --yelloworange: #FFAE42;
        --paleyellow: #fff3d7ff;
        --lightGrey: #9c9c9c;
        --darkGrey: #4a4a4a;
        --darkBlue: #0033a0;
        --lightBlue: #90D5FF;
        --lightGreen: #CDEBC5;
        --darkGreen: #14452f;
        --gradient: linear-gradient(210.41deg,rgb(3, 39, 60) 1.14%,rgb(110, 191, 198) 100.75%);
        }


    </style>
</head>
<body class="bg-light">
    @inertia
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>