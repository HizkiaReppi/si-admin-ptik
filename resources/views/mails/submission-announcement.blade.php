<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengumuman</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        p {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .badge {
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 12px;
            display: inline-block;
        }

        .label-note {
            margin-bottom: 5px;
        }

        .note {
            background-color: #f9f9f9;
            border-left: 5px solid #ff9800;
            padding: 5px 10px;
            border-radius: 5px;
        }

        a.btn {
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }

        a.btn:hover {
            background-color: #45a049;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>{{ $announcement->title }}</h1>
        <p>Halo <strong>{{ $submission->student->fullname }}</strong>,</p>
        <p>Ada pengumuman baru:</p>
        <div>
            {!! $announcement->content !!}
        </div>
        <div class="footer">
            <p>Terima Kasih</p>
        </div>
    </div>
</body>

</html>
