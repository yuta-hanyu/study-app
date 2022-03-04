<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>{{ config('app.name', 'Vue Laravel SPA') }}</title> --}}
    <title>muscle</title>
    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    {{-- axios --}}
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

</head>
  <head>
    <title>MyStudyApp</title>
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
  </head>
<body>
  <div id="app">
  </div>
</body>
</html>
