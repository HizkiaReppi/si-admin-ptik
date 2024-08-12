@php
    $title = null;

    $adminOrHeadOfDepartmentRole = ['admin', 'HoD', 'super-admin'];
    $isAdminOrHeadOfDepartment = in_array(auth()->user()->role, $adminOrHeadOfDepartmentRole) ? true : false;

    if ($isAdminOrHeadOfDepartment) {
        $title = 'Manajemen Pengumuman';
    } else {
        $title = 'Pengumuman';
    }

    $linkDetail = $isAdminOrHeadOfDepartment ? route('dashboard.submission.show', $submission->id) : route('dashboard.submission.student.detail', [$submission->category->slug, $submission->id]);

    $content = 'Pengajuan ' . $submission->student->fullname . ' ' . $submission->category->name;

    $baseUrl = config('app.url');
    $baseUrl = explode('://', $baseUrl)[1];

    if (request()->secure()) {
        $baseUrl = 'https://' . $baseUrl;
    } else {
        $baseUrl = 'http://' . $baseUrl;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-meta-data :title="$content" csrfToken="{{ csrf_token() }}" :baseurl="$baseUrl"/>
    <script src="https://kit.fontawesome.com/29057e6c17.js" crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            height: 100vh; /* Ensure body takes full viewport height */
        }
        header {
            background-color: #ff6600;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0;
            height: 40px; /* Adjust header height */
            flex-shrink: 0; /* Prevent header from shrinking */
        }
        header h1 {
            font-size: 1rem;
            margin: 0;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
            margin-left: 10px;
            text-align: center;
        }
        .header-actions {
            display: flex;
            align-items: center;
            margin: 0;
        }
        .header-actions a {
            display: inline-flex;
            align-items: center;
            color: #ff6600;
            background-color: white;
            text-decoration: none;
            height: 40px;
            font-size: 1rem;
            font-weight: 600;
            padding: 0 15px;
        }
        .galley_view {
            flex: 1; /* Allow the content area to take the remaining height */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .pdfCanvas {
            width: 100%;
            height: 100%;
            border: none;
        }
        .download-text {
            display: none;
        }
        @media screen and (min-width: 768px) {
            .download-text {
                display: inline;
                margin-left: 10px;
            }
            header h1 {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-actions">
            <a href="{{ $linkDetail }}">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="download-text">Kembali</span>
            </a>
        </div>
        <h1>{{ $content }}</h1>
        <div class="header-actions">
            <a href="{{ asset($submission_file->file_path) }}" download>
                <i class="fa-solid fa-download"></i>
                <span class="download-text">Download</span>
            </a>
        </div>
    </header>
    <div id="pdfCanvasContainer" class="galley_view">
        <iframe
            src="{{ asset('vendor/pdfJsViewer/pdf.js/web/viewer.html?file=' . urlencode($submission_file->file_path)) }}"
            title="{{ $content }}"
            class="pdfCanvas"
            allowfullscreen
            webkitallowfullscreen>
        </iframe>
    </div>
</body>
</html>
