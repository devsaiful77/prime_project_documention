<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>
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
                font-size: 50px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{{ (!empty($exception->getMessage())) ? $exception->getMessage() : 'Be right back.' }}</div>
            </div>
        </div>
    </body>
</html>
