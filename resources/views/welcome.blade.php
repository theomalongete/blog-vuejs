<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('/favicon.ico')}}">
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
        <title>Blog</title>
        <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    </head>
    <body class="container-fluid">
        <div id="app">
            <app></app>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>

