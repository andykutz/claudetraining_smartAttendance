<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Smart Attendance &mdash; User Guide</title>
    <style>
        body { font-family: 'Segoe UI', Arial, Helvetica, sans-serif; color: #1e293b; margin: 0; }
        .cover { background: linear-gradient(135deg, #1e2b47 0%, #1e40af 100%); color: #ffffff; padding: 28px 26px; border-radius: 10px; margin-bottom: 22px; }
        .cover h1 { margin: 0 0 6px; font-size: 24px; }
        .cover p { margin: 0; font-size: 12px; color: #c7d2fe; }
    </style>
</head>
<body>
    <div class="cover">
        <h1>Smart Attendance &mdash; User Guide</h1>
        <p>A guide for administrators and managers &middot; generated {{ $generatedAt }}</p>
    </div>

    @include('admin.settings.partials.user-guide-content')
</body>
</html>
