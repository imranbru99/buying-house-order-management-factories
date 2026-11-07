@extends('website.layouts.app')
@section('content')
<div class="tf-wrap">
    <h1>{{ $title }}</h1>
    <ul style="margin-top:1rem;line-height:2;">
        <li><a href="{{ url('/docs/ERD.md') }}">Entity relationship overview</a> (see repository <code>docs/ERD.md</code>)</li>
        <li><a href="{{ url('/docs/DEPLOYMENT.md') }}">Deployment guide</a> (<code>docs/DEPLOYMENT.md</code>)</li>
        <li>API base: <code>{{ url('/api/v1') }}</code></li>
        <li>Admin workspace: <a href="{{ url('/app') }}">{{ url('/app') }}</a></li>
    </ul>
</div>
@endsection
