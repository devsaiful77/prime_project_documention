<!DOCTYPE html>
<html>
    <head>
        <title>Unauthorized action.</title>

        <link href="{{asset('public/font/google-font.css')}}" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #ffff;
                display: table;
                font-weight: 400;
                background: #0072bc;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-weight: normal;
                font-size: 50px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{{ (!empty($exception->getMessage())) ? $exception->getMessage() : 'Unauthorized Action.' }}</div>
            </div>
        </div>
    </body>
</html>
