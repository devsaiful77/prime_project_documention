<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>BRAC Bank Limited # CSRnCMS | {{ (!empty($title)) ? $title : "" }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('public/img/favicon.ico') }}">
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
            font-weight: 100;
            font-family: 'Lato', sans-serif;
            background: #0072bc;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: left;
            display: inline-block;
        }

        .h2 {
            font-size: 26px;
            margin-bottom: 40px;
        }
        hr {
            border: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="h2">
            Dear Customer,<br>
            <hr>

            Attachments exceeding 3 months after ticket initiation have been archived.<br>

            To get required attachment, please write email to "enquiry@bracbank.com"<br>
            <hr>

            Sincerely,<br>
            BRAC Bank PLC.
        </div>
        {{--                <div class="h2">Attachments exceeding 3 months have been archived. <br> To get required attachment, please write to "ays@bracbank.com"</div>--}}
    </div>
</div>
</body>
</html>
