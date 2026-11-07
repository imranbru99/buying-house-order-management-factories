@extends('website.layouts.app')
@section('content')
<div class="tf-wrap">
    <h1>{{ $module['name'] }}</h1>
    <p>{{ $module['description'] }}</p>
    <div class="tf-card" style="margin-top:1.5rem;">
        <p style="margin:0;"><strong>Key:</strong> <code>{{ $module['key'] }}</code></p>
        <p style="margin:.5rem 0 0;"><strong>Dependencies:</strong>
            {{ count($module['dependencies']) ? implode(', ', $module['dependencies']) : 'None' }}
        </p>
        <p style="margin:.5rem 0 0;"><strong>Core module:</strong> {{ $module['core'] ? 'Yes' : 'No' }}</p>
    </div>
    <p style="margin-top:1.5rem;"><a href="{{ url('/modules') }}">&larr; All modules</a></p>
</div>
@endsection
