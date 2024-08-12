@php
    $badgeColor = match ($submission->status) {
        'submitted' => '#007BFF', // Blue (Default)
        'pending' => '#FFC107', // Amber
        'proses_kajur' => '#17A2B8', // Cyan
        'proses_dekan' => '#6F42C1', // Purple
        'done' => '#28A745', // Green
        'rejected' => '#DC3545', // Red
        'canceled' => '#FF5722', // Deep Orange
        'expired' => '#9E9E9E', // Grey
        default => '#343A40', // Dark Grey (for any undefined status)
    };

    $badgeTextColor = match ($submission->status) {
        'submitted' => '#FFFFFF', // White (suitable for blue background)
        'pending' => '#000000', // Black (suitable for amber background)
        'proses_kajur' => '#FFFFFF', // White (suitable for cyan background)
        'proses_dekan' => '#FFFFFF', // White (suitable for purple background)
        'done' => '#FFFFFF', // White (suitable for green background)
        'rejected' => '#FFFFFF', // White (suitable for red background)
        'canceled' => '#FFFFFF', // White (suitable for deep orange background)
        'expired' => '#FFFFFF', // White (suitable for grey background)
        default => '#FFFFFF', // White (default for any undefined status)
    };

@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Status Pengajuan Anda Telah Diperbarui</title>
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
        <p>Halo <strong>{{ $submission->student->fullname }}</strong>,</p>
        <p>
            Semoga Anda dalam keadaan sehat dan baik. <br>
            Pengajuan <strong>{{ $submission->category->name }}</strong> telah diperbarui oleh <strong>Admin</strong>
            menjadi:</p>
        <div>
            <span class="badge" style="background-color: {{ $badgeColor }}; color: {{ $badgeTextColor }};">
                {{ $submission->parseSubmissionStatus }}
            </span>
        </div>
        @php
            $statuses = ['rejected', 'canceled', 'expired'];
            $isStatusInArray = in_array($submission->status, $statuses);
        @endphp
        @if ($isStatusInArray && $submission->note || $submission->note)
            <p class="label-note">Admin menuliskan catatan untuk Anda sebagai berikut:</p>
            <div class="note">
                <p>{{ $submission->note }}</p>
            </div>
        @endif
        <p>Silahkan lihat detail pengajuan di Sistem Informasi Admin PTIK atau melalui link berikut:</p>
        <div>
            <a class="btn"
                href="{{ route('dashboard.submission.student.detail', [$submission->category->slug, $submission->id]) }}">
                Lihat Pengajuan
            </a>
        </div>
        <div class="footer">
            <p>Terima Kasih</p>
        </div>
    </div>
</body>

</html>
