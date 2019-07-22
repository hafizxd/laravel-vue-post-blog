<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">

        <title>Laravel</title>

        <style>
          body {
            font-family: 'Roboto';
            background-color: #f2f2f2;
          }
        </style>

    </head>
    <body>
        <div id="app">
          <index></index>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
