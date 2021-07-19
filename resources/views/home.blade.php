<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
        </style>
    </head>

    <body>
        <div id="app">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1 class="mt-3">Developer test</h1>
                        <p>
                            From the table below you will need to add the following functionality
                        </p>
                        <ul>
                            <li>Add a new column to the table</li>
                            <li>Add a new row to the table</li>
                            <li>Remove a row from the table</li>
                            <li>Complete the logic in the /api/csv-export route, which will generate a csv file into the browser.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <csv-generator/>
        </div>

        <script src="{{ asset('/js/app.js') }}" type="text/javascript"></script>
    </body>
</html>
