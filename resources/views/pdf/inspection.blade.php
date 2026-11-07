<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inspection {{ $inspection->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    </style>
</head>
<body>
    <h1>Inspection {{ $inspection->number }}</h1>
    <p><strong>Type:</strong> {{ $inspection->type }}</p>
    <p><strong>Status:</strong> {{ $inspection->status }}</p>

    <h2>Defects</h2>
    <ul>
        @forelse ($inspection->defects as $defect)
            <li>{{ $defect->defect_code }} — qty {{ $defect->quantity }} ({{ $defect->severity }})</li>
        @empty
            <li>No defects recorded.</li>
        @endforelse
    </ul>
</body>
</html>
